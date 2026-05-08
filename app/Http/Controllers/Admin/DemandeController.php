<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemandeAdministrative;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\DemandeApprouvee;
use App\Mail\DemandeRefusee;

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

        // Generate the PDF
        $pdf = Pdf::loadView('pdf.demande', compact('demande'));
        $filename = 'demande_' . $demande->id . '_' . $demande->type . '.pdf';
        Storage::disk('public')->put('demandes/' . $filename, $pdf->output());
        
        $demande->update(['fichier_pdf' => 'demandes/' . $filename]);
        
        // Envoi de l'email de notification
        Mail::to($demande->user->email)->send(new DemandeApprouvee($demande));
        
        return back()->with('success', 'Demande validée, PDF généré et email envoyé à l\'étudiant.');
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
        Mail::to($demande->user->email)->send(new DemandeRefusee($demande));
        
        return back()->with('warning', 'Demande refusée et email de notification envoyé.');
    }

    /**
     * Download or view the generated PDF.
     */
    public function genererPdf(DemandeAdministrative $demande)
    {
        $demande->load('user');
        $pdf = Pdf::loadView('pdf.demande', compact('demande'));
        
        $typeLabels = [
            'attestation_scolarite' => 'Attestation_Scolarite',
            'releve_notes' => 'Releve_Notes',
            'certificat_inscription' => 'Certificat_Inscription',
            'attestation_travail' => 'Attestation_Travail',
            'ordre_mission' => 'Ordre_Mission',
        ];
        
        $label = $typeLabels[$demande->type] ?? 'Document_Administratif';
        
        return $pdf->download($label . '_' . $demande->user->name . '.pdf');
    }
}
