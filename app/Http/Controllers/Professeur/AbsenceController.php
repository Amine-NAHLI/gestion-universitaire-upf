<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\Absence;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    public function index()
    {
        $professeur = auth()->user()->professeur;
        $seances = Seance::with(['module', 'groupe', 'salle'])
            ->where('professeur_id', $professeur->id)
            ->where(function ($query) {
                $query->where('statut', 'effectuee')
                      ->orWhere('date', today());
            })
            ->orderByDesc('date')
            ->orderByDesc('heure_debut')
            ->get();

        return view('professeur.absences.index', compact('seances'));
    }

    public function feuille(Seance $seance)
    {
        $this->authorize('manageAppel', $seance);

        $etudiants = Etudiant::with(['user', 'absences' => fn($q) => $q->where('seance_id', $seance->id)])
            ->where('groupe_id', $seance->groupe_id)
            ->get();
            
        return view('professeur.absences.feuille', compact('seance', 'etudiants'));
    }

    public function enregistrer(Request $request, Seance $seance)
    {
        $this->authorize('manageAppel', $seance);

        $absents = $request->input('absents', []);
        $etudiantsIds = Etudiant::where('groupe_id', $seance->groupe_id)->pluck('id');

        foreach ($etudiantsIds as $etudiantId) {
            if (in_array($etudiantId, $absents)) {
                Absence::firstOrCreate(['etudiant_id' => $etudiantId, 'seance_id' => $seance->id]);
            } else {
                // If they were marked absent before but are now present (unchecking the box)
                Absence::where(['etudiant_id' => $etudiantId, 'seance_id' => $seance->id])->delete();
            }
        }

        $seance->update(['statut' => 'effectuee']);

        return redirect()->route('professeur.absences.index')->with('success', 'Feuille de présence enregistrée.');
    }
}
