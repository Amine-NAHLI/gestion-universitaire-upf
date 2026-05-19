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
        // Absences distribution in a single query
        $absencesRaw = Absence::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN justifiee = 1 THEN 1 ELSE 0 END) as justifiees,
            SUM(CASE WHEN justifiee = 0 THEN 1 ELSE 0 END) as non_justifiees
        ')->first();

        // Notes distribution in a single query
        $notesRaw = Note::whereNotNull('note_finale')->selectRaw('
            SUM(CASE WHEN note_finale >= 16 THEN 1 ELSE 0 END) as excellentes,
            SUM(CASE WHEN note_finale >= 12 AND note_finale < 16 THEN 1 ELSE 0 END) as bonnes,
            SUM(CASE WHEN note_finale >= 10 AND note_finale < 12 THEN 1 ELSE 0 END) as passables,
            SUM(CASE WHEN note_finale < 10 THEN 1 ELSE 0 END) as echecs
        ')->first();

        $stats = [
            'etudiants_count'   => Etudiant::count(),
            'professeurs_count' => Professeur::count(),
            'seances_jour'      => Seance::whereDate('date', today())->count(),
            'demandes_attente'  => DemandeAdministrative::where('statut', 'en_attente')->count(),

            // Pour graphiques
            'absences' => [
                'justifiees'     => (int) ($absencesRaw->justifiees ?? 0),
                'non_justifiees' => (int) ($absencesRaw->non_justifiees ?? 0),
            ],
            'notes_distribution' => [
                'excellentes' => (int) ($notesRaw->excellentes ?? 0),
                'bonnes'      => (int) ($notesRaw->bonnes ?? 0),
                'passables'   => (int) ($notesRaw->passables ?? 0),
                'echecs'      => (int) ($notesRaw->echecs ?? 0),
            ],
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
