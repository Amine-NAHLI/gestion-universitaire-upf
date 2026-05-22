<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemandeAdministrative;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemandeApprouvee;
use App\Mail\DemandeRefusee;
use App\Models\NotificationApp;

class DemandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statut = $request->query('statut', 'en_attente');
        
        $demandes = DemandeAdministrative::with(['user'])
            ->when($statut !== 'tous', fn($q) => $q->where('statut', $statut))
            ->latest()
            ->paginate(15)
            ->withQueryString();
            
        $stats = [
            'en_attente' => DemandeAdministrative::where('statut', 'en_attente')->count(),
            'validees' => DemandeAdministrative::where('statut', 'validee')->count(),
            'refusees' => DemandeAdministrative::where('statut', 'refusee')->count(),
            'total' => DemandeAdministrative::count(),
        ];
        
        return view('admin.demandes.index', compact('demandes', 'stats', 'statut'));
    }

    /**
     * Display the specified resource.
     */
    public function show(DemandeAdministrative $demande)
    {
        $demande->load('user');
        return view('admin.demandes.show', compact('demande'));
    }

    /**
     * Validate the administrative request and generate PDF.
     */
    public function valider(DemandeAdministrative $demande)
    {
        $demande->update([
            'statut' => 'validee',
            'validateur_id' => auth()->id(),
            'date_validation' => now(),
        ]);
        
        // Ensure directory exists
        if (!Storage::disk('public')->exists('demandes')) {
            Storage::disk('public')->makeDirectory('demandes');
        }

        // Generate the certified PDF with QR code
        $user = $demande->user;
        $user->load('etudiant.notes.module', 'etudiant.groupe.niveau.filiere');
        $langue = $demande->langue_document ?? 'fr';

        $sealedData = [
            'student_name'     => trim(($user->prenom ?? '') . ' ' . $user->name),
            'cne'              => $user->etudiant?->cne ?? 'N/A',
            'filiere'          => $user->etudiant?->groupe?->niveau?->filiere?->nom ?? 'N/A',
            'groupe'           => $user->etudiant?->groupe?->nom ?? 'N/A',
            'document_type'    => $this->getTypeLabel($demande->type),
            'issue_date'       => now()->format('Y-m-d'),
            'moyenne'          => $this->getMoyenneGenerale($user),
            'validateur'       => trim((auth()->user()->prenom ?? '') . ' ' . auth()->user()->name),
            'langue'           => $langue,
        ];

        $cryptoService = app(\App\Services\CryptoSignatureService::class);
        $docSignature = $cryptoService->signDocument($user, $demande->type, $sealedData);

        $frontendUrl = config('app.verification_frontend_url', 'http://localhost:5173');
        $verificationUrl = rtrim($frontendUrl, '/') . '/verify/' . $docSignature->document_id;
        $qrCode = QrCode::format('svg')->size(120)->errorCorrection('H')->generate($verificationUrl);
        $qrCodeBase64 = base64_encode($qrCode);

        $pdf = Pdf::loadView('pdf.demande', compact(
            'demande', 'langue', 'docSignature', 'qrCodeBase64', 'verificationUrl'
        ));
        $filename = 'demande_' . $demande->id . '_' . $demande->type . '_certified.pdf';
        Storage::disk('public')->put('demandes/' . $filename, $pdf->output());

        $demande->update(['fichier_pdf' => 'demandes/' . $filename]);
        
        // Envoi de l'email de notification (try-catch pour ne pas bloquer la notification interne)
        try {
            Mail::to($demande->user->email)->send(new DemandeApprouvee($demande));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur envoi email validation : " . $e->getMessage());
        }
        
        // Création de la notification interne
        $demande->load('user');
        NotificationApp::create([
            'user_id' => $demande->user_id,
            'type' => 'DEMANDE',
            'titre' => 'Demande approuvée !',
            'message' => 'Votre demande de ' . str_replace('_', ' ', $demande->type) . ' a été validée.',
            'lien' => $demande->user->isEtudiant() ? route('etudiant.demandes.index') : route('professeur.demandes.index'),
        ]);
        
        return back()->with('success', 'Demande validée, PDF généré et notifications envoyées.');
    }

    /**
     * Reject the administrative request.
     */
    public function refuser(DemandeAdministrative $demande, Request $request)
    {
        $request->validate([
            'motif_refus' => 'required|string|min:10'
        ]);
        
        $demande->update([
            'statut' => 'refusee',
            'validateur_id' => auth()->id(),
            'motif_refus' => $request->motif_refus,
        ]);

        // Envoi de l'email de notification
        try {
            Mail::to($demande->user->email)->send(new DemandeRefusee($demande));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur envoi email refus : " . $e->getMessage());
        }
        
        // Création de la notification interne
        $demande->load('user');
        NotificationApp::create([
            'user_id' => $demande->user_id,
            'type' => 'DEMANDE',
            'titre' => 'Demande refusée',
            'message' => 'Votre demande de ' . str_replace('_', ' ', $demande->type) . ' a été refusée.',
            'lien' => $demande->user->isEtudiant() ? route('etudiant.demandes.index') : route('professeur.demandes.index'),
        ]);
        
        return back()->with('warning', 'Demande refusée et notifications envoyées.');
    }

    public function genererPdf(DemandeAdministrative $demande)
    {
        $demande->load('user');
        $user = $demande->user;
        $user->load('etudiant.notes.module', 'etudiant.groupe.niveau.filiere');
        $langue = $demande->langue_document ?? 'fr';

        // Prepare sealed data — same keys as ReleveNotesController for PKI frontend compatibility
        $sealedData = [
            'student_name'     => trim(($user->prenom ?? '') . ' ' . $user->name),
            'cne'              => $user->etudiant?->cne ?? 'N/A',
            'filiere'          => $user->etudiant?->groupe?->niveau?->filiere?->nom ?? 'N/A',
            'groupe'           => $user->etudiant?->groupe?->nom ?? 'N/A',
            'document_type'    => $this->getTypeLabel($demande->type),
            'issue_date'       => now()->format('Y-m-d'),
            'moyenne'          => $this->getMoyenneGenerale($user),
            'validateur'       => trim((auth()->user()->prenom ?? '') . ' ' . auth()->user()->name),
            'langue'           => $langue,
        ];

        // Generate Crypto Signature
        $cryptoService = app(\App\Services\CryptoSignatureService::class);
        $docSignature = $cryptoService->signDocument($user, $demande->type, $sealedData);

        // Generate QR Code
        $frontendUrl = config('app.verification_frontend_url', 'http://localhost:5173');
        $verificationUrl = rtrim($frontendUrl, '/') . '/verify/' . $docSignature->document_id;
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(120)
            ->errorCorrection('H')
            ->generate($verificationUrl);
        $qrCodeBase64 = base64_encode($qrCode);

        // Load View with new data
        $pdf = Pdf::loadView('pdf.demande', compact('demande', 'langue', 'docSignature', 'qrCodeBase64', 'verificationUrl'));
        
        $label = str_replace(' ', '_', $sealedData['document_type']);
        
        return $pdf->download($label . '_' . $user->name . '.pdf');
    }

    /**
     * Get the human-readable label for a demande type.
     */
    private function getTypeLabel(string $type): string
    {
        return match($type) {
            'attestation_scolarite'  => 'Attestation de Scolarité',
            'releve_notes'           => 'Relevé de Notes Officiel',
            'certificat_inscription' => "Certificat d'Inscription",
            'attestation_travail'    => 'Attestation de Travail',
            'ordre_mission'          => 'Ordre de Mission',
            default                  => ucfirst(str_replace('_', ' ', $type)),
        };
    }

    /**
     * Calculate the student's general average from their notes.
     */
    private function getMoyenneGenerale($user): string
    {
        if (!$user->etudiant) return 'N/A';

        $moyenne = $user->etudiant->notes()
            ->whereNotNull('note_finale')
            ->avg('note_finale');

        return $moyenne
            ? number_format((float) $moyenne, 2) . '/20'
            : '0/20';
    }
}
