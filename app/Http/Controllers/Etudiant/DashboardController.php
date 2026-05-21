<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Note;
use App\Models\Absence;
use App\Models\Etudiant;
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

        // 2. Notes & Moyenne (utilise note_finale calculée par le modèle)
        $notes = Note::with('module')->where('etudiant_id', $etudiant->id)->get();
        $notesAvecFinale = $notes->whereNotNull('note_finale');
        $creditsValides = $notesAvecFinale->where('note_finale', '>=', 10)->count();
        $totalModules = ($etudiant->groupe ? $etudiant->groupe->modules()->count() : 0) ?: 1;
        $moyenneGenerale = $notesAvecFinale->isNotEmpty()
            ? round($notesAvecFinale->avg('note_finale'), 2)
            : 0;

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

        // 5. Notes par module pour radar chart
        $notesParModule = $notes->whereNotNull('note_finale')
            ->mapWithKeys(fn($n) => [$n->module->nom => (float) $n->note_finale]);

        // 6. Absences par module pour bar chart
        $absencesParModule = DB::table('absences')
            ->join('seances', 'absences.seance_id', '=', 'seances.id')
            ->join('modules', 'seances.module_id', '=', 'modules.id')
            ->where('absences.etudiant_id', $etudiant->id)
            ->select('modules.nom', DB::raw('COUNT(absences.id) as total'))
            ->groupBy('modules.id', 'modules.nom')
            ->orderBy('modules.nom')
            ->get()
            ->pluck('total', 'nom');

        // 7. Rang dans le groupe
        $rang = null;
        $totalGroupeEtudiants = 0;
        if ($etudiant->groupe_id) {
            $groupeMoyennes = Etudiant::where('groupe_id', $etudiant->groupe_id)
                ->with('notes')
                ->get()
                ->map(fn($e) => [
                    'id'      => $e->id,
                    'moyenne' => $e->notes->whereNotNull('note_finale')->avg('note_finale') ?? 0,
                ])
                ->sortByDesc('moyenne')
                ->values();

            $totalGroupeEtudiants = $groupeMoyennes->count();
            $pos = $groupeMoyennes->search(fn($e) => $e['id'] === $etudiant->id);
            $rang = $pos !== false ? $pos + 1 : null;
        }

        return view('etudiant.dashboard', compact(
            'etudiant',
            'absencesCount',
            'notes',
            'moyenneGenerale',
            'creditsValides',
            'totalModules',
            'supportsCount',
            'planningJour',
            'notesParModule',
            'absencesParModule',
            'rang',
            'totalGroupeEtudiants'
        ));
    }
}
