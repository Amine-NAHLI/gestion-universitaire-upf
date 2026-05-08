<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use Illuminate\Http\Request;

class EdtController extends Controller
{
    public function index()
    {
        $professeur = auth()->user()->professeur;
        
        $seances = Seance::with(['module', 'salle', 'groupe'])
            ->where('professeur_id', $professeur->id)
            ->where('statut', '!=', 'annulee')
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->get()
            ->groupBy(fn($s) => $s->date->format('Y-m-d'));

        $prochaines = Seance::with(['module', 'salle', 'groupe'])
            ->where('professeur_id', $professeur->id)
            ->where('date', '>=', today())
            ->orderBy('date')
            ->take(5)
            ->get();

        return view('professeur.edt.index', compact('seances', 'prochaines'));
    }
}
