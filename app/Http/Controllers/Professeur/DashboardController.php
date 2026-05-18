<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Etudiant;
use App\Models\Note;
use App\Models\Seance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $professeur = auth()->user()->professeur;
        
        if (!$professeur) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Profil professeur introuvable. Veuillez contacter l\'administration.');
        }

        // 1. Heures assurées (Somme des durées des séances effectuées)
        // Dans une vraie application, il faut calculer la durée entre heure_debut et heure_fin
        // Pour faire simple ici on compte les séances * 2h
        $seancesEffectuees = Seance::where('professeur_id', $professeur->id)
            ->where('statut', 'effectuee')
            ->count();
        $heuresAssurees = $seancesEffectuees * 2;

        // 2. Étudiants suivis (Étudiants dans les groupes des modules du prof)
        $groupesIds = DB::table('module_professeur')
            ->join('module_groupe', 'module_professeur.module_id', '=', 'module_groupe.module_id')
            ->where('module_professeur.professeur_id', $professeur->id)
            ->pluck('module_groupe.groupe_id')
            ->unique();
        $etudiantsSuivis = Etudiant::whereIn('groupe_id', $groupesIds)->count();

        // 3. Modules actifs
        $modulesActifs = $professeur->modules()->count();

        // 4. Prochaines Séances
        $prochainesSeances = Seance::with(['module', 'groupe', 'salle'])
            ->where('professeur_id', $professeur->id)
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->limit(3)
            ->get();

        // 5. Progression des notes (Optimisé avec withCount)
        $modules = $professeur->modules()->withCount('groupes')->get();
        $modulesProgress = [];
        $moduleIds = $modules->pluck('id')->all();

        // Batch compute totals to avoid N+1 queries (keeps the same result).
        $totalEtudiantsParModule = [];
        $notesSaisiesParModule = [];
        if (!empty($moduleIds)) {
            $totalEtudiantsParModule = DB::table('module_groupe')
                ->join('etudiants', 'module_groupe.groupe_id', '=', 'etudiants.groupe_id')
                ->whereIn('module_groupe.module_id', $moduleIds)
                ->select('module_groupe.module_id', DB::raw('count(*) as total'))
                ->groupBy('module_groupe.module_id')
                ->pluck('total', 'module_id')
                ->all();

            $notesSaisiesParModule = Note::whereIn('module_id', $moduleIds)
                ->where('annee_universitaire', config('scolarite.annee', '2025-2026'))
                ->select('module_id', DB::raw('count(*) as total'))
                ->groupBy('module_id')
                ->pluck('total', 'module_id')
                ->all();
        }

        foreach ($modules as $module) {
            // On compte le total d'étudiants attendus pour ce module
            $totalEtudiants = (int)($totalEtudiantsParModule[$module->id] ?? 0);

            // On compte le nombre de notes déjà saisies pour ce module
            $notesSaisies = (int)($notesSaisiesParModule[$module->id] ?? 0);
            
            $progress = $totalEtudiants > 0 ? round(($notesSaisies / $totalEtudiants) * 100) : 0;
            $modulesProgress[] = [
                'nom' => $module->nom,
                'progress' => $progress
            ];
        }

        return view('professeur.dashboard', compact(
            'heuresAssurees',
            'etudiantsSuivis',
            'modulesActifs',
            'prochainesSeances',
            'modulesProgress',
            'professeur'
        ));
    }
}
