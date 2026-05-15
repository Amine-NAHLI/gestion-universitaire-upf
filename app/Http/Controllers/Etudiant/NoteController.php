<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Module;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $etudiant = auth()->user()->etudiant;
        
        $notes = Note::with('module')
            ->where('etudiant_id', $etudiant->id)
            ->where('annee_universitaire', config('scolarite.annee', '2025-2026'))
            ->get();

        $moyenne = round($notes->whereNotNull('note_finale')->avg('note_finale'), 2);
        $modules_valides = $notes->filter(fn($n) => $n->note_finale >= 10)->count();
        $modules_total = $notes->count();

        return view('etudiant.notes.index', compact('notes', 'moyenne', 'modules_valides', 'modules_total'));
    }
}
