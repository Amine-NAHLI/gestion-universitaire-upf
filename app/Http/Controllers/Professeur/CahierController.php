<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\CahierTexte;
use Illuminate\Http\Request;

class CahierController extends Controller
{
    public function index()
    {
        $professeur = auth()->user()->professeur;
        $seances = Seance::with(['module', 'groupe', 'cahierTexte'])
            ->where('professeur_id', $professeur->id)
            ->where('statut', 'effectuee')
            ->orderByDesc('date')
            ->orderByDesc('heure_debut')
            ->get();

        return view('professeur.cahier.index', compact('seances'));
    }

    public function create(Seance $seance)
    {
        $this->authorize('manageCahier', $seance);

        $cahier = $seance->cahierTexte ?? new CahierTexte(['seance_id' => $seance->id]);
        return view('professeur.cahier.create', compact('seance', 'cahier'));
    }

    public function store(Request $request, Seance $seance)
    {
        $this->authorize('manageCahier', $seance);

        $request->validate([
            'objectif' => 'required|min:10',
            'contenu' => 'nullable',
            'nature' => 'required|in:cours,td,tp'
        ]);

        CahierTexte::updateOrCreate(
            ['seance_id' => $seance->id],
            [
                'objectif' => $request->objectif,
                'contenu' => $request->contenu,
                'nature' => $request->nature
            ]
        );

        return redirect()->route('professeur.cahier.index')->with('success', 'Cahier de textes mis à jour avec succès.');
    }
}
