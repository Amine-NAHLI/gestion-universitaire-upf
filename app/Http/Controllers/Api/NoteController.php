<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->isEtudiant()) {
            $notes = Note::with('module:id,nom,code,coefficient')
                ->where('etudiant_id', $user->etudiant->id)
                ->where('annee_universitaire', '2025-2026')
                ->get()
                ->map(fn($n) => [
                    'module' => $n->module->nom,
                    'code' => $n->module->code,
                    'cc1' => $n->cc1,
                    'cc2' => $n->cc2,
                    'examen' => $n->examen,
                    'note_finale' => $n->note_finale,
                    'statut' => $n->note_finale === null ? 'en_attente' : ($n->note_finale >= 10 ? 'validé' : 'ajourné'),
                ]);

            $moyenne = round($notes->whereNotNull('note_finale')->avg('note_finale'), 2);

            return response()->json([
                'annee_universitaire' => '2025-2026',
                'moyenne_generale' => $moyenne,
                'notes' => $notes,
            ]);
        }
        
        if ($user->isProfesseur()) {
            $modules = $user->professeur->modules()->with('notes.etudiant.user')->get();
            return response()->json([
                'modules' => $modules->map(fn($m) => [
                    'module' => $m->nom,
                    'code' => $m->code,
                    'nb_notes' => $m->notes->count(),
                    'moyenne' => round($m->notes->whereNotNull('note_finale')->avg('note_finale'), 2),
                ])
            ]);
        }
        
        return response()->json(['message' => 'Non autorisé.'], 403);
    }
}
