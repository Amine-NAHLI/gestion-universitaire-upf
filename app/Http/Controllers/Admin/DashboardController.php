<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Etudiant;
use App\Models\Professeur;
use App\Models\DemandeAdministrative;
use App\Models\Absence;
use App\Models\Note;
use App\Models\Seance;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'etudiants_count' => Etudiant::count(),
            'professeurs_count' => Professeur::count(),
            'seances_jour' => Seance::whereDate('date', today())->count(),
            'demandes_attente' => DemandeAdministrative::where('statut', 'en_attente')->count(),
            
            // Pour graphiques
            'absences' => [
                'justifiees' => Absence::where('justifiee', true)->count(),
                'non_justifiees' => Absence::where('justifiee', false)->count(),
            ],
            'notes_distribution' => [
                'excellentes' => Note::where('note_finale', '>=', 16)->count(),
                'bonnes' => Note::whereBetween('note_finale', [12, 15.99])->count(),
                'passables' => Note::whereBetween('note_finale', [10, 11.99])->count(),
                'echecs' => Note::where('note_finale', '<', 10)->count(),
            ]
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
