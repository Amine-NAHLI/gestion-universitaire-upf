<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use Illuminate\Http\Request;

class EdtController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Seance::with(['module:id,nom,code', 'salle:id,nom', 'groupe:id,nom', 'professeur.user:id,name,prenom'])
            ->where('statut', '!=', 'annulee')
            ->orderBy('date')
            ->orderBy('heure_debut');
        
        if ($user->isEtudiant()) {
            $query->where('groupe_id', $user->etudiant->groupe_id);
        } elseif ($user->isProfesseur()) {
            $query->where('professeur_id', $user->professeur->id);
        } else {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }
        
        $seances = $query->get()->map(fn($s) => [
            'id' => $s->id,
            'date' => $s->date->format('Y-m-d'),
            'heure_debut' => $s->heure_debut,
            'heure_fin' => $s->heure_fin,
            'type' => $s->type,
            'statut' => $s->statut,
            'module' => $s->module->nom,
            'code_module' => $s->module->code,
            'salle' => $s->salle?->nom,
            'groupe' => $s->groupe->nom,
            'professeur' => $s->professeur->user->prenom . ' ' . $s->professeur->user->name,
        ]);
        
        return response()->json([
            'total' => $seances->count(),
            'seances' => $seances,
        ]);
    }
}
