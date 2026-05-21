<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Justificatif;
use App\Models\Groupe;
use App\Models\Etudiant;
use App\Models\NotificationApp;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsencesExport;
use Illuminate\Support\Facades\Mail;
use App\Mail\JustificatifApprouve;
use App\Mail\JustificatifRefuse;

class AbsenceController extends Controller
{
    public function export(Request $request)
    {
        $groupe_id = $request->query('groupe_id');
        return Excel::download(new AbsencesExport($groupe_id), 'absences_etudiants.xlsx');
    }

    /**
     * Display a listing of the absences.
     */
    public function index(Request $request)
    {
        $groupe_id = $request->query('groupe_id');
        $groupes = Groupe::all();
        
        $absences = Absence::with(['etudiant.user', 'seance.module', 'justificatif'])
            ->when($groupe_id, fn($q) => $q->whereHas('etudiant', fn($q2) => $q2->where('groupe_id', $groupe_id)))
            ->latest()
            ->paginate(20)
            ->withQueryString();
        
        // Stats
        $stats = [
            'total' => Absence::count(),
            'justifiees' => Absence::where('justifiee', true)->count(),
            'non_justifiees' => Absence::where('justifiee', false)->count(),
            'en_attente' => Justificatif::where('statut', 'en_attente')->count(),
        ];
        
        return view('admin.absences.index', compact('absences', 'groupes', 'groupe_id', 'stats'));
    }

    /**
     * Toggle the justification status of an absence.
     */
    public function toggleJustifiee(Absence $absence)
    {
        $absence->update(['justifiee' => !$absence->justifiee]);
        return back()->with('success', 'Statut de justification mis à jour avec succès.');
    }

    /**
     * Validate a submitted justification.
     */
    public function validerJustificatif(Justificatif $justificatif)
    {
        $justificatif->update([
            'statut' => 'accepte',
            'validateur_id' => auth()->id()
        ]);
        
        $justificatif->absence->update(['justifiee' => true]);
        
        NotificationApp::create([
            'user_id' => $justificatif->absence->etudiant->user_id,
            'type' => 'ABSENCE',
            'titre' => 'Justificatif accepté',
            'message' => 'Votre justificatif pour l\'absence du ' . $justificatif->absence->seance->date->format('d/m/Y') . ' a été validé.',
            'lien' => route('etudiant.absences.index'),
        ]);

        try {
            Mail::to($justificatif->absence->etudiant->user->email)->send(new JustificatifApprouve($justificatif));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur email justificatif approuvé : " . $e->getMessage());
        }
        
        return back()->with('success', 'Justificatif accepté, notification et email envoyés.');
    }

    /**
     * Reject a submitted justification.
     */
    public function refuserJustificatif(Justificatif $justificatif, Request $request)
    {
        $request->validate([
            'motif_refus' => 'required|string|min:3|max:500',
        ]);

        $justificatif->update([
            'statut' => 'refuse',
            'validateur_id' => auth()->id(),
            'motif_refus' => $request->input('motif_refus'),
        ]);
        
        NotificationApp::create([
            'user_id' => $justificatif->absence->etudiant->user_id,
            'type' => 'ABSENCE',
            'titre' => 'Justificatif refusé',
            'message' => 'Votre justificatif pour l\'absence du ' . $justificatif->absence->seance->date->format('d/m/Y') . ' a été refusé.',
            'lien' => route('etudiant.absences.index'),
        ]);

        try {
            Mail::to($justificatif->absence->etudiant->user->email)->send(new JustificatifRefuse($justificatif));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur email justificatif refusé : " . $e->getMessage());
        }
        
        return back()->with('warning', 'Justificatif refusé, notification et email envoyés.');
    }
}
