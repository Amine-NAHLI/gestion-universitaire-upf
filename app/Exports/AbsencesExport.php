<?php

namespace App\Exports;

use App\Models\Absence;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsencesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $groupe_id;

    public function __construct($groupe_id = null)
    {
        $this->groupe_id = $groupe_id;
    }

    public function collection()
    {
        return Absence::with(['etudiant.user', 'seance.module', 'etudiant.groupe'])
            ->when($this->groupe_id, fn($q) => $q->whereHas('etudiant', fn($sq) => $sq->where('groupe_id', $this->groupe_id)))
            ->get();
    }

    public function headings(): array
    {
        return [
            'Étudiant',
            'Groupe',
            'Module',
            'Date Séance',
            'Heure',
            'Justifiée'
        ];
    }

    public function map($absence): array
    {
        return [
            $absence->etudiant->user->full_name,
            $absence->etudiant->groupe->nom,
            $absence->seance->module->nom,
            $absence->seance->date->format('d/m/Y'),
            $absence->seance->heure_debut->format('H:i') . ' - ' . $absence->seance->heure_fin->format('H:i'),
            $absence->justifiee ? 'OUI' : 'NON'
        ];
    }
}
