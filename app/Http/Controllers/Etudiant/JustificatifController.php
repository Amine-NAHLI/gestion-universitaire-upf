<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Justificatif;
use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JustificatifController extends Controller
{
    public function index()
    {
        $etudiant = auth()->user()->etudiant;
        
        $absences_non_justifiees = Absence::with('seance.module')
            ->where('etudiant_id', $etudiant->id)
            ->where('justifiee', false)
            ->whereDoesntHave('justificatif')
            ->get();

        $justificatifs = Justificatif::with('absence.seance.module')
            ->where('etudiant_id', $etudiant->id)
            ->latest()
            ->get();

        return view('etudiant.justificatifs.index', compact('absences_non_justifiees', 'justificatifs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'absence_id' => 'required|exists:absences,id',
            'motif' => 'required|string|min:10',
            'fichier' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('fichier')->store('justificatifs', 'public');

        Justificatif::create([
            'etudiant_id' => auth()->user()->etudiant->id,
            'absence_id' => $request->absence_id,
            'fichier' => $path,
            'motif' => $request->motif,
            'statut' => 'en_attente',
        ]);

        return back()->with('success', 'Justificatif déposé avec succès.');
    }
}
