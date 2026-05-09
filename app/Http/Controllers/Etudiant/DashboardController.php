<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Absence;
use App\Models\Seance;
use App\Models\Support;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $etudiant = auth()->user()->etudiant;
        
        if (!$etudiant) {
            return view('etudiant.dashboard')->with('error', 'Profil étudiant introuvable.');
        }

        // 1. Absences
        $absencesCount = Absence::where('etudiant_id', $etudiant->id)->count();

        // 2. Notes & Moyenne
        $notes = Note::with('module')->where('etudiant_id', $etudiant->id)->get();
        $totalSomes = 0;
        $creditsValides = 0;
        $totalModules = $etudiant->groupe->modules()->count() ?: 1; // Éviter div par 0
        
        foreach ($notes as $note) {
            $ccAvg = (($note->cc1 ?? 0) + ($note->cc2 ?? 0)) / 2;
            $moyenneModule = ($ccAvg * 0.4) + (($note->examen ?? 0) * 0.6);
            $totalSomes += $moyenneModule;
            if ($moyenneModule >= 10) {
                $creditsValides++;
            }
        }
        $moyenneGenerale = $notes->count() > 0 ? ($totalSomes / $notes->count()) : 0;

        // 3. Supports de cours (On simule ou compte selon la db si Support existe)
        // Si vous avez un modèle Support, sinon on met 0
        $supportsCount = 0;
        if (class_exists('App\Models\Support')) {
            $supportsCount = Support::whereIn('module_id', $etudiant->groupe->modules()->pluck('modules.id'))->count();
        }

        // 4. Planning du jour
        $planningJour = Seance::with(['module', 'salle', 'professeur.user'])
            ->where('groupe_id', $etudiant->groupe_id)
            ->whereDate('date', Carbon::today())
            ->orderBy('heure_debut')
            ->get();

        return view('etudiant.dashboard', compact(
            'etudiant',
            'absencesCount',
            'notes',
            'moyenneGenerale',
            'creditsValides',
            'totalModules',
            'supportsCount',
            'planningJour'
        ));
    }
}
