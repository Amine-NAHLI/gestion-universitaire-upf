<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Note;
use App\Models\Absence;
use App\Models\Seance;
use App\Models\Module;
use App\Models\Etudiant;
use App\Models\Professeur;
use App\Models\Filiere;
use Illuminate\Http\Request;

class StatistiqueController extends Controller
{
    /**
     * Display a listing of statistics.
     */
    public function index()
    {
        // General Stats
        $stats = [
            'total_etudiants' => Etudiant::count(),
            'total_professeurs' => Professeur::count(),
            'total_modules' => Module::count(),
            'total_seances' => Seance::count(),
            'moyenne_generale' => round(Note::whereNotNull('note_finale')->avg('note_finale') ?? 0, 2),
            'taux_absence' => Seance::count() > 0 ? round((Absence::count() / (Seance::count() * 5)) * 100, 1) : 0,
        ];

        // Distribution of grades (by ranges)
        $distribution = [
            'Moins de 8' => Note::whereNotNull('note_finale')->where('note_finale', '<', 8)->count(),
            '8 à 10' => Note::whereNotNull('note_finale')->whereBetween('note_finale', [8, 10])->count(),
            '10 à 12' => Note::whereNotNull('note_finale')->whereBetween('note_finale', [10, 12])->count(),
            '12 à 14' => Note::whereNotNull('note_finale')->whereBetween('note_finale', [12, 14])->count(),
            '14 à 16' => Note::whereNotNull('note_finale')->whereBetween('note_finale', [14, 16])->count(),
            '16 à 18' => Note::whereNotNull('note_finale')->whereBetween('note_finale', [16, 18])->count(),
            '18 à 20' => Note::whereNotNull('note_finale')->where('note_finale', '>=', 18)->count(),
        ];

        // Absences per month (last 6 months)
        $absencesParMois = [];
        for ($i = 5; $i >= 0; $i--) {
            $mois = now()->subMonths($i);
            $key = $mois->locale('fr')->isoFormat('MMM YY');
            $absencesParMois[$key] = Absence::whereYear('created_at', $mois->year)
                ->whereMonth('created_at', $mois->month)
                ->count();
        }

        // Average grades per module (top 8)
        $moyennesModules = Module::withCount(['notes as nb_notes' => fn($q) => $q->whereNotNull('note_finale')])
            ->get()
            ->map(fn($m) => [
                'nom' => $m->code,
                'moyenne' => round(Note::where('module_id', $m->id)->whereNotNull('note_finale')->avg('note_finale') ?? 0, 2)
            ])
            ->sortByDesc('moyenne')
            ->take(8)
            ->values();

        // Student distribution per Filière
        $repartitionFilieres = Filiere::all()->map(fn($f) => [
            'nom' => $f->nom,
            'count' => Etudiant::whereHas('groupe.niveau.filiere', fn($q) => $q->where('filieres.id', $f->id))->count()
        ]);

        return view('admin.statistiques.index', compact('stats', 'distribution', 'absencesParMois', 'moyennesModules', 'repartitionFilieres'));
    }
}
