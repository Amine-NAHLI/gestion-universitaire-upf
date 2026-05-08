<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->isEtudiant()) {
            $absences = Absence::with(['seance.module:id,nom', 'justificatif'])
                ->where('etudiant_id', $user->etudiant->id)
                ->get()
                ->map(fn($a) => [
                    'id' => $a->id,
                    'module' => $a->seance->module->nom,
                    'date' => $a->seance->date->format('Y-m-d'),
                    'heure' => $a->seance->heure_debut . ' - ' . $a->seance->heure_fin,
                    'justifiee' => $a->justifiee,
                    'statut_justificatif' => $a->justificatif?->statut ?? 'aucun',
                ]);

            return response()->json([
                'total' => $absences->count(),
                'justifiees' => $absences->where('justifiee', true)->count(),
                'non_justifiees' => $absences->where('justifiee', false)->count(),
                'absences' => $absences,
            ]);
        }
        
        return response()->json(['message' => 'Non autorisé.'], 403);
    }
}
