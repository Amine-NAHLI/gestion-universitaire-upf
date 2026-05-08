<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\DemandeAdministrative;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
    public function index()
    {
        $demandes = DemandeAdministrative::where('user_id', auth()->id())->latest()->get();
        return view('professeur.demandes.index', compact('demandes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:attestation_travail,ordre_mission',
            'donnees_supplementaires' => 'nullable|array',
        ]);

        DemandeAdministrative::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'donnees_supplementaires' => $request->donnees_supplementaires,
            'statut' => 'en_attente',
        ]);

        return back()->with('success', 'Demande administrative soumise avec succès.');
    }
}
