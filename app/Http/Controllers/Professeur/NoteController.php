<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Module;
use App\Models\Groupe;
use App\Models\Etudiant;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $professeur = auth()->user()->professeur;
        $modules = $professeur->modules()->with('groupes')->get();
        return view('professeur.notes.index', compact('modules'));
    }

    public function saisir(Module $module, Groupe $groupe)
    {
        $etudiants = Etudiant::with(['user', 'notes' => fn($q) => $q->where('module_id', $module->id)])
            ->where('groupe_id', $groupe->id)
            ->get();
            
        return view('professeur.notes.saisir', compact('module', 'groupe', 'etudiants'));
    }

    public function enregistrer(Request $request, Module $module, Groupe $groupe)
    {
        foreach ($request->notes as $etudiant_id => $noteData) {
            Note::updateOrCreate(
                [
                    'etudiant_id' => $etudiant_id, 
                    'module_id' => $module->id, 
                    'annee_universitaire' => '2025-2026'
                ],
                array_filter($noteData, fn($v) => $v !== null && $v !== '')
            );
        }

        return back()->with('success', 'Notes enregistrées avec succès.');
    }
}
