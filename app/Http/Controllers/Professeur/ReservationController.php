<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\ReservationSalle;
use App\Models\Salle;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $professeur = auth()->user()->professeur;
        $reservations = ReservationSalle::with('salle')
            ->where('professeur_id', $professeur->id)
            ->orderByDesc('date')
            ->get();
            
        $salles = Salle::where('is_disponible', true)->get();
        
        return view('professeur.reservations.index', compact('reservations', 'salles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'salle_id' => 'required|exists:salles,id',
            'date' => 'required|date|after_or_equal:today',
            'heure_debut' => 'required',
            'heure_fin' => 'required|after:heure_debut',
            'motif' => 'required|min:5',
        ]);

        // Détection complète des conflits horaires (4 cas possibles)
        $conflit = ReservationSalle::where('salle_id', $request->salle_id)
            ->whereDate('date', $request->date)
            ->where('statut', 'confirmee')
            ->where(function ($query) use ($request) {
                // Cas 1 : début existant dans l'intervalle nouveau
                $query->where(function ($q) use ($request) {
                    $q->where('heure_debut', '>=', $request->heure_debut)
                      ->where('heure_debut', '<', $request->heure_fin);
                })
                // Cas 2 : fin existante dans l'intervalle nouveau
                ->orWhere(function ($q) use ($request) {
                    $q->where('heure_fin', '>', $request->heure_debut)
                      ->where('heure_fin', '<=', $request->heure_fin);
                })
                // Cas 3 : réservation existante englobe complètement la nouvelle
                ->orWhere(function ($q) use ($request) {
                    $q->where('heure_debut', '<=', $request->heure_debut)
                      ->where('heure_fin', '>=', $request->heure_fin);
                });
            })->exists();

        if ($conflit) {
            return back()->with('error', 'Cette salle est déjà réservée sur ce créneau.');
        }

        ReservationSalle::create([
            'salle_id' => $request->salle_id,
            'professeur_id' => auth()->user()->professeur->id,
            'date' => $request->date,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'motif' => $request->motif,
            'statut' => 'confirmee',
        ]);

        return back()->with('success', 'Salle réservée avec succès.');
    }

    public function destroy(ReservationSalle $reservation)
    {
        $this->authorize('delete', $reservation);

        $reservation->delete();
        return back()->with('success', 'Réservation annulée avec succès.');
    }
}
