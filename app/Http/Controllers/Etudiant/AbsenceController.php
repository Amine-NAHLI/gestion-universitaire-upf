<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    public function index()
    {
        $etudiant = auth()->user()->etudiant;
        
        $absences = Absence::with(['seance.module', 'justificatif'])
            ->where('etudiant_id', $etudiant->id)
            ->latest()
            ->get();

        $stats = [
            'total' => $absences->count(),
            'justifiees' => $absences->where('justifiee', true)->count(),
            'non_justifiees' => $absences->where('justifiee', false)->count(),
        ];

        return view('etudiant.absences.index', compact('absences', 'stats'));
    }
}
