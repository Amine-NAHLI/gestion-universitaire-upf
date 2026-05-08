<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\Module;
use App\Models\Professeur;
use App\Models\Groupe;
use App\Models\Salle;
use Illuminate\Http\Request;

class EdtController extends Controller
{
    /**
     * Display the timetable index.
     */
    public function index()
    {
        $modules = Module::with('niveau.filiere')->get();
        $professeurs = Professeur::with('user')->get();
        $groupes = Groupe::with('niveau.filiere')->get();
        $salles = Salle::where('is_disponible', true)->get();
        
        return view('admin.edt.index', compact('modules', 'professeurs', 'groupes', 'salles'));
    }

    /**
     * Fetch calendar event data.
     */
    public function data()
    {
        $seances = Seance::with(['module', 'professeur.user', 'groupe', 'salle'])->get();
        
        $events = $seances->map(function($seance) {
            $colors = [
                'cours' => '#6366f1', // indigo
                'td' => '#10b981',    // emerald
                'tp' => '#f59e0b',    // amber
                'examen' => '#ef4444' // red
            ];
            
            return [
                'id' => $seance->id,
                'title' => $seance->module->nom . ' (' . $seance->groupe->nom . ')',
                'start' => $seance->date->format('Y-m-d') . 'T' . $seance->heure_debut,
                'end' => $seance->date->format('Y-m-d') . 'T' . $seance->heure_fin,
                'backgroundColor' => $colors[$seance->type] ?? '#6366f1',
                'borderColor' => $colors[$seance->type] ?? '#6366f1',
                'extendedProps' => [
                    'type' => $seance->type,
                    'salle' => $seance->salle?->nom,
                    'professeur' => $seance->professeur->user->prenom . ' ' . $seance->professeur->user->name,
                    'statut' => $seance->statut,
                ]
            ];
        });
        
        return response()->json($events);
    }

    /**
     * Store a new session.
     */
    public function store(Request $request)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'professeur_id' => 'required|exists:professeurs,id',
            'groupe_id' => 'required|exists:groupes,id',
            'salle_id' => 'nullable|exists:salles,id',
            'date' => 'required|date',
            'heure_debut' => 'required',
            'heure_fin' => 'required|after:heure_debut',
            'type' => 'required|in:cours,td,tp,examen',
        ]);

        $seance = Seance::create($request->all() + ['statut' => 'planifiee']);
        
        return response()->json([
            'success' => true, 
            'seance' => $seance->load(['module', 'groupe', 'salle', 'professeur.user'])
        ]);
    }

    /**
     * Update an existing session.
     */
    public function update(Request $request, Seance $seance)
    {
        $seance->update($request->only(['date', 'heure_debut', 'heure_fin', 'salle_id', 'statut']));
        
        return response()->json(['success' => true]);
    }

    /**
     * Delete a session.
     */
    public function destroy(Seance $seance)
    {
        $seance->delete();
        
        return response()->json(['success' => true]);
    }
}
