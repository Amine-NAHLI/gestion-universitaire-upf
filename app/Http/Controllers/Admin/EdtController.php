<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\Module;
use App\Models\Professeur;
use App\Models\Groupe;
use App\Models\Salle;
use Illuminate\Http\Request;
use App\Models\NotificationApp;
use App\Models\Etudiant;

class EdtController extends Controller
{
    /**
     * Display the timetable index.
     */
    public function index()
    {
        $modules = Module::with('niveau.filiere')->get();
        $professeurs = Professeur::with(['user', 'modules'])->get();
        $groupes = Groupe::with('niveau.filiere')->get();
        $salles = Salle::where('is_disponible', true)->get();
        
        return view('admin.edt.index', compact('modules', 'professeurs', 'groupes', 'salles'));
    }

    /**
     * Fetch calendar event data.
     */
    public function data(Request $request)
    {
        $query = Seance::with(['module', 'professeur.user', 'groupe', 'salle']);

        // Filter by date range if provided by FullCalendar
        if ($request->has('start') && $request->has('end')) {
            $query->whereBetween('date', [
                substr($request->start, 0, 10),
                substr($request->end, 0, 10)
            ]);
        }

        $seances = $query->get();
        
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
                'start' => $seance->date->format('Y-m-d') . 'T' . \Carbon\Carbon::parse($seance->heure_debut)->format('H:i:s'),
                'end' => $seance->date->format('Y-m-d') . 'T' . \Carbon\Carbon::parse($seance->heure_fin)->format('H:i:s'),
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
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'type' => 'required|in:cours,td,tp,examen',
        ]);

        // Vérification des conflits salle (si une salle est spécifiée)
        if ($request->salle_id) {
            $conflitSalle = Seance::where('salle_id', $request->salle_id)
                ->where('date', $request->date)
                ->where('statut', '!=', 'annulee')
                ->where(function ($q) use ($request) {
                    $q->where(function ($q2) use ($request) {
                        $q2->where('heure_debut', '<', $request->heure_fin)
                           ->where('heure_fin', '>', $request->heure_debut);
                    });
                })->exists();

            if ($conflitSalle) {
                return response()->json(['success' => false, 'message' => 'Conflit : cette salle est déjà occupée sur ce créneau.'], 422);
            }
        }

        // Vérification des conflits professeur
        $conflitProf = Seance::where('professeur_id', $request->professeur_id)
            ->where('date', $request->date)
            ->where('statut', '!=', 'annulee')
            ->where(function ($q) use ($request) {
                $q->where('heure_debut', '<', $request->heure_fin)
                  ->where('heure_fin', '>', $request->heure_debut);
            })->exists();

        if ($conflitProf) {
            return response()->json(['success' => false, 'message' => 'Conflit : ce professeur a déjà une séance sur ce créneau.'], 422);
        }

        $seance = Seance::create(array_merge(
            $request->only(['module_id', 'professeur_id', 'groupe_id', 'salle_id', 'date', 'heure_debut', 'heure_fin', 'type']),
            ['statut' => 'planifiee']
        ));
        
        // Notification batch data
        $notifications = [];

        // Notifier le professeur
        $notifications[] = [
            'user_id' => $seance->professeur->user_id,
            'type' => 'EDT',
            'titre' => 'Nouvelle séance planifiée',
            'message' => 'Une séance de ' . $seance->module->nom . ' a été ajoutée à votre emploi du temps.',
            'lien' => route('professeur.dashboard'),
            'lue' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Notifier les étudiants du groupe
        $etudiantUserIds = Etudiant::where('groupe_id', $seance->groupe_id)->pluck('user_id');
        foreach ($etudiantUserIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => 'EDT',
                'titre' => 'Mise à jour Emploi du Temps',
                'message' => 'Une séance de ' . $seance->module->nom . ' a été planifiée.',
                'lien' => route('etudiant.dashboard'),
                'lue' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Bulk insert for performance
        NotificationApp::insert($notifications);
        
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

        $notifications = [];

        // Notifier le professeur
        $notifications[] = [
            'user_id' => $seance->professeur->user_id,
            'type' => 'EDT',
            'titre' => 'Séance modifiée',
            'message' => 'Votre séance de ' . $seance->module->nom . ' du ' . $seance->date->format('d/m/Y') . ' a été modifiée.',
            'lien' => route('professeur.dashboard'),
            'lue' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Notifier les étudiants (bulk insert)
        $etudiantUserIds = Etudiant::where('groupe_id', $seance->groupe_id)->pluck('user_id');
        foreach ($etudiantUserIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => 'EDT',
                'titre' => 'Changement d\'emploi du temps',
                'message' => 'La séance de ' . $seance->module->nom . ' du ' . $seance->date->format('d/m/Y') . ' a été modifiée.',
                'lien' => route('etudiant.dashboard'),
                'lue' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($notifications)) {
            NotificationApp::insert($notifications);
        }
        
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
