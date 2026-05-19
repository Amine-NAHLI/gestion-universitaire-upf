<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Absence;
use App\Models\Seance;
use App\Models\SupportCours;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $etudiant = auth()->user()->etudiant;
        
        if (!$etudiant) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Profil étudiant introuvable. Veuillez contacter l\'administration.');
        }

        // 1. Absences
        $absencesCount = Absence::where('etudiant_id', $etudiant->id)->count();

        // 2. Notes & Moyenne
        $notes = Note::with('module')->where('etudiant_id', $etudiant->id)->get();
        $totalSomes = 0;
        $creditsValides = 0;
        $totalModules = ($etudiant->groupe ? $etudiant->groupe->modules()->count() : 0) ?: 1; // Éviter div par 0
        
        foreach ($notes as $note) {
            $ccAvg = (($note->cc1 ?? 0) + ($note->cc2 ?? 0)) / 2;
            $moyenneModule = ($ccAvg * 0.4) + (($note->examen ?? 0) * 0.6);
            $totalSomes += $moyenneModule;
            if ($moyenneModule >= 10) {
                $creditsValides++;
            }
        }
        $moyenneGenerale = $notes->count() > 0 ? ($totalSomes / $notes->count()) : 0;

        // 3. Supports de cours
        $moduleIds = ($etudiant->groupe && $etudiant->groupe->niveau)
            ? $etudiant->groupe->modules()->pluck('modules.id')
            : collect();
        $supportsCount = $moduleIds->isNotEmpty() ? SupportCours::whereIn('module_id', $moduleIds)->count() : 0;

        // 4. Planning du jour
        $planningJour = $etudiant->groupe_id
            ? Seance::with(['module', 'salle', 'professeur.user'])
                ->where('groupe_id', $etudiant->groupe_id)
                ->whereDate('date', Carbon::today())
                ->orderBy('heure_debut')
                ->get()
            : collect();

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
