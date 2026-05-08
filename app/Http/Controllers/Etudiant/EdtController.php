<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use Illuminate\Http\Request;

class EdtController extends Controller
{
    public function index()
    {
        $etudiant = auth()->user()->etudiant;
        
        $seances = Seance::with(['module', 'salle', 'professeur.user'])
            ->where('groupe_id', $etudiant->groupe_id)
            ->where('statut', '!=', 'annulee')
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->get()
            ->groupBy(fn($s) => $s->date->format('Y-m-d'));

        $prochaines = Seance::with(['module', 'salle'])
            ->where('groupe_id', $etudiant->groupe_id)
            ->where('date', '>=', today())
            ->where('statut', 'planifiee')
            ->orderBy('date')
            ->take(5)
            ->get();

        return view('etudiant.edt.index', compact('seances', 'prochaines'));
    }
}
