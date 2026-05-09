<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Etudiant;
use App\Models\Module;
use App\Models\Groupe;
use App\Models\Filiere;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NotesExport;

class NoteController extends Controller
{
    public function export(Request $request)
    {
        $groupe_id = $request->query('groupe_id');
        $module_id = $request->query('module_id');
        
        return Excel::download(new NotesExport($groupe_id, $module_id), 'notes_etudiants.xlsx');
    }

    /**
     * Display a listing of the notes.
     */
    public function index(Request $request)
    {
        $filieres = Filiere::with('niveaux.groupes')->get();
        $groupe_id = $request->query('groupe_id');
        $module_id = $request->query('module_id');
        
        $notes = collect();
        $modules = collect();
        $groupe = null;
        
        if ($groupe_id) {
            $groupe = Groupe::with('modules')->find($groupe_id);
            if ($groupe) {
                $modules = $groupe->modules;
                
                if ($module_id) {
                    $notes = Note::with(['etudiant.user', 'module'])
                        ->whereHas('etudiant', fn($q) => $q->where('groupe_id', $groupe_id))
                        ->where('module_id', $module_id)
                        ->get();
                }
            }
        }
        
        return view('admin.notes.index', compact('filieres', 'notes', 'modules', 'groupe_id', 'module_id', 'groupe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Etudiant $etudiant, Module $module)
    {
        $note = Note::firstOrNew(['etudiant_id' => $etudiant->id, 'module_id' => $module->id]);
        return view('admin.notes.edit', compact('note', 'etudiant', 'module'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Etudiant $etudiant, Module $module)
    {
        $request->validate([
            'cc1' => 'nullable|numeric|min:0|max:20',
            'cc2' => 'nullable|numeric|min:0|max:20',
            'examen' => 'nullable|numeric|min:0|max:20',
        ]);

        Note::updateOrCreate(
            ['etudiant_id' => $etudiant->id, 'module_id' => $module->id],
            [
                'cc1' => $request->cc1,
                'cc2' => $request->cc2,
                'examen' => $request->examen,
                'annee_universitaire' => config('scolarite.annee', '2025-2026')
            ]
        );

        return redirect()->route('admin.notes.index', [
            'groupe_id' => $etudiant->groupe_id,
            'module_id' => $module->id
        ])->with('success', 'Notes mises à jour avec succès.');
    }
}
