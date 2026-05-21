<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EdtController extends Controller
{
    public function index()
    {
        $etudiant = auth()->user()->etudiant;
        
        $seances = Seance::with(['module', 'salle', 'professeur.user'])
            ->where('groupe_id', $etudiant->groupe_id)
            ->where('statut', '!=', 'annulee')
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->get()
            ->groupBy(fn($s) => $s->date->format('Y-m-d'));

        $prochaines = Seance::with(['module', 'salle'])
            ->where('groupe_id', $etudiant->groupe_id)
            ->where('date', '>=', today())
            ->where('statut', 'planifiee')
            ->orderBy('date')
            ->take(5)
            ->get();

        return view('etudiant.edt.index', compact('seances', 'prochaines'));
    }

    public function data(Request $request)
    {
        $etudiant = auth()->user()->etudiant;
        $colors = ['cours' => '#6366f1', 'td' => '#10b981', 'tp' => '#f59e0b', 'examen' => '#ef4444'];

        $query = Seance::with(['module', 'salle', 'professeur.user'])
            ->where('groupe_id', $etudiant->groupe_id)
            ->where('statut', '!=', 'annulee');

        if ($request->start) {
            $query->where('date', '>=', substr($request->start, 0, 10));
        }
        if ($request->end) {
            $query->where('date', '<=', substr($request->end, 0, 10));
        }

        $events = $query->get()->map(fn($s) => [
            'id'              => $s->id,
            'title'           => $s->module->nom,
            'start'           => $s->date->format('Y-m-d') . 'T' . Carbon::parse($s->heure_debut)->format('H:i:s'),
            'end'             => $s->date->format('Y-m-d') . 'T' . Carbon::parse($s->heure_fin)->format('H:i:s'),
            'backgroundColor' => $colors[$s->type] ?? '#6366f1',
            'borderColor'     => $colors[$s->type] ?? '#6366f1',
            'extendedProps'   => [
                'type'       => $s->type,
                'salle'      => $s->salle?->nom ?? 'N/A',
                'professeur' => $s->professeur->user->prenom . ' ' . $s->professeur->user->name,
            ],
        ]);

        return response()->json($events);
    }
}
