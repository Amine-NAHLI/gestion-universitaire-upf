<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Module;
use App\Models\Groupe;
use App\Models\Etudiant;
use Illuminate\Http\Request;
use App\Models\NotificationApp;

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
        $professeur = auth()->user()->professeur;
        // Reload modules only if not already loaded to avoid double query
        if (!$professeur->relationLoaded('modules')) {
            $professeur->load('modules');
        }
        if (!$professeur->modules->contains($module->id)) {
            abort(403, 'Vous n\'enseignez pas ce module.');
        }

        $etudiants = Etudiant::with(['user', 'notes' => fn($q) => $q->where('module_id', $module->id)])
            ->where('groupe_id', $groupe->id)
            ->get();
            
        return view('professeur.notes.saisir', compact('module', 'groupe', 'etudiants'));
    }

    public function enregistrer(Request $request, Module $module, Groupe $groupe)
    {
        $professeur = auth()->user()->professeur;
        if (!$professeur->relationLoaded('modules')) {
            $professeur->load('modules');
        }
        if (!$professeur->modules->contains($module->id)) {
            abort(403, 'Vous n\'enseignez pas ce module.');
        }

        $request->validate([
            'notes' => 'required|array',
            'notes.*.cc1' => 'nullable|numeric|min:0|max:20',
            'notes.*.cc2' => 'nullable|numeric|min:0|max:20',
            'notes.*.examen' => 'nullable|numeric|min:0|max:20',
        ]);

        // Récupérer uniquement les étudiants du groupe concerné (protection IDOR)
        $etudiantsGroupe = Etudiant::where('groupe_id', $groupe->id)
            ->pluck('user_id', 'id');

        $allowedIds = $etudiantsGroupe->keys()->toArray();
        $notifications = [];

        foreach ($request->notes as $etudiant_id => $noteData) {
            // Ignorer tout etudiant_id qui n'appartient pas au groupe
            if (!in_array((int) $etudiant_id, $allowedIds)) {
                continue;
            }

            $filteredData = array_filter($noteData, fn($v) => $v !== null && $v !== '');
            if (empty($filteredData)) continue;

            Note::updateOrCreate(
                [
                    'etudiant_id' => $etudiant_id,
                    'module_id' => $module->id,
                    'annee_universitaire' => config('scolarite.annee', '2025-2026')
                ],
                $filteredData
            );

            $notifications[] = [
                'user_id' => $etudiantsGroupe[$etudiant_id],
                'type' => 'NOTES',
                'titre' => 'Notes mises à jour : ' . $module->nom,
                'message' => 'Vos notes pour le module ' . $module->nom . ' ont été mises à jour par le professeur.',
                'lien' => route('etudiant.notes.index'),
                'lue' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($notifications)) {
            NotificationApp::insert($notifications);
        }

        return back()->with('success', 'Notes enregistrées et étudiants notifiés.');
    }
}
