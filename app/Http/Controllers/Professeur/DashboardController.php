<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $professeur = auth()->user()->professeur;
        
        if (!$professeur) {
            return view('professeur.dashboard')->with('error', 'Profil professeur introuvable.');
        }

        // 1. Heures assurées (Somme des durées des séances effectuées)
        // Dans une vraie application, il faut calculer la durée entre heure_debut et heure_fin
        // Pour faire simple ici on compte les séances * 2h
        $seancesEffectuees = \App\Models\Seance::where('professeur_id', $professeur->id)
            ->where('statut', 'effectuee')
            ->count();
        $heuresAssurees = $seancesEffectuees * 2;

        // 2. Étudiants suivis (Étudiants dans les groupes des modules du prof)
        $groupesIds = \Illuminate\Support\Facades\DB::table('module_professeur')
            ->join('module_groupe', 'module_professeur.module_id', '=', 'module_groupe.module_id')
            ->where('module_professeur.professeur_id', $professeur->id)
            ->pluck('module_groupe.groupe_id')
            ->unique();
        $etudiantsSuivis = \App\Models\Etudiant::whereIn('groupe_id', $groupesIds)->count();

        // 3. Modules actifs
        $modulesActifs = $professeur->modules()->count();

        // 4. Prochaines Séances
        $prochainesSeances = \App\Models\Seance::with(['module', 'groupe', 'salle'])
            ->where('professeur_id', $professeur->id)
            ->whereDate('date', '>=', \Carbon\Carbon::today())
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->limit(3)
            ->get();

        // 5. Progression des notes
        $modulesProgress = [];
        $modules = $professeur->modules()->with(['groupes.etudiants.notes'])->get();
        
        foreach ($modules as $module) {
            $totalEtudiants = 0;
            $notesSaisies = 0;
            
            foreach ($module->groupes as $groupe) {
                $totalEtudiants += $groupe->etudiants->count();
                foreach ($groupe->etudiants as $etud) {
                    if ($etud->notes->where('module_id', $module->id)->first()) {
                        $notesSaisies++;
                    }
                }
            }
            
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
