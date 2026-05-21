<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Services\CryptoSignatureService;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;

class ReleveNotesController extends Controller
{
    /**
     * Generate and download a cryptographically signed PDF transcript.
     */
    public function download(Request $request, CryptoSignatureService $cryptoService)
    {
        $user = $request->user();
        $etudiant = $user->etudiant;

        if (!$etudiant) {
            return back()->with('error', 'Profil étudiant introuvable.');
        }

        // Load related data
        $etudiant->load(['groupe.niveau.filiere', 'notes.module']);

        // Build the data payload for the transcript
        $notes = [];
        $totalCoeff = 0;
        $totalWeighted = 0;

        foreach ($etudiant->notes as $note) {
            $moduleName = $note->module->nom ?? 'Module inconnu';
            $coeff = $note->module->coefficient ?? 1;
            $finale = (float) ($note->note_finale ?? 0);
            $notes[] = [
                'module'      => $moduleName,
                'cc1'         => $note->cc1 !== null ? number_format((float) $note->cc1, 2) : '—',
                'cc2'         => $note->cc2 !== null ? number_format((float) $note->cc2, 2) : '—',
                'examen'      => $note->examen !== null ? number_format((float) $note->examen, 2) : '—',
                'note'        => $note->note_finale !== null ? number_format($finale, 2) : '—',
                'coefficient' => $coeff,
                'statut'      => $note->note_finale === null ? 'En attente' : ($finale >= 10 ? 'Validé' : 'Ajourné'),
            ];
            if ($note->note_finale !== null) {
                $totalWeighted += $finale * $coeff;
                $totalCoeff += $coeff;
            }
        }

        $moyenne = $totalCoeff > 0 ? round($totalWeighted / $totalCoeff, 2) : 0;

        // The data that will be cryptographically sealed
        $sealedData = [
            'student_name' => trim(($user->prenom ?? '') . ' ' . $user->name),
            'cne' => $etudiant->cne ?? 'N/A',
            'filiere' => $etudiant->groupe->niveau->filiere->nom ?? 'N/A',
            'groupe' => $etudiant->groupe->nom ?? 'N/A',
            'document_type' => 'Relevé de notes officiel',
            'issue_date' => now()->format('Y-m-d'),
            'moyenne' => $moyenne . '/20',
            'notes' => $notes,
        ];

        // Sign the document cryptographically
        $docSignature = $cryptoService->signDocument($user, 'releve_notes', $sealedData);

        // Generate the verification URL (points to React frontend)
        $frontendUrl = config('app.verification_frontend_url', 'http://localhost:5173');
        $verificationUrl = $frontendUrl . '/verify/' . $docSignature->document_id;

        // Generate QR Code as SVG
        $qrCode = QrCode::format('svg')
            ->size(150)
            ->errorCorrection('H')
            ->generate($verificationUrl);

        $qrCodeBase64 = base64_encode($qrCode);

        // Generate the PDF
        $pdf = Pdf::loadView('pdf.releve-notes', [
            'user' => $user,
            'etudiant' => $etudiant,
            'sealedData' => $sealedData,
            'docSignature' => $docSignature,
            'qrCodeBase64' => $qrCodeBase64,
            'verificationUrl' => $verificationUrl,
        ]);

        $filename = 'Releve_Notes_' . str_replace(' ', '_', $user->name) . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
