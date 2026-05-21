<?php

namespace App\Exports;

use App\Models\Absence;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AbsencesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
        return ['Étudiant', 'Groupe', 'Module', 'Date Séance', 'Heure', 'Justifiée'];
    }

    public function map($absence): array
    {
        $heureDebut = $absence->seance?->heure_debut ? $absence->seance->heure_debut->format('H:i') : '—';
        $heureFin   = $absence->seance?->heure_fin   ? $absence->seance->heure_fin->format('H:i')   : '—';

        return [
            $absence->etudiant?->user?->full_name ?? '—',
            $absence->etudiant?->groupe?->nom     ?? '—',
            $absence->seance?->module?->nom       ?? '—',
            $absence->seance?->date?->format('d/m/Y') ?? '—',
            $heureDebut . ' - ' . $heureFin,
            $absence->justifiee ? 'OUI' : 'NON',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEF4444']],
            ],
        ];
    }
}
