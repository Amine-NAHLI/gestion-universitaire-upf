<?php

namespace App\Exports;

use App\Models\Note;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class NotesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $groupe_id;
    protected $module_id;

    public function __construct($groupe_id = null, $module_id = null)
    {
        $this->groupe_id = $groupe_id;
        $this->module_id = $module_id;
    }

    public function collection()
    {
        return Note::with(['etudiant.user', 'module'])
            ->when($this->module_id, fn($q) => $q->where('module_id', $this->module_id))
            ->when($this->groupe_id, fn($q) => $q->whereHas('etudiant', fn($sq) => $sq->where('groupe_id', $this->groupe_id)))
            ->get();
    }

    public function headings(): array
    {
        return ['Étudiant', 'CNE', 'Module', 'CC1', 'CC2', 'Examen', 'Note Finale', 'Statut'];
    }

    public function map($note): array
    {
        $statut = $note->note_finale === null
            ? 'En attente'
            : ($note->note_finale >= 10 ? 'Validé' : 'Ajourné');

        return [
            $note->etudiant?->user?->full_name ?? '—',
            $note->etudiant?->cne ?? '—',
            $note->module?->nom ?? '—',
            $note->cc1 ?? 'N/A',
            $note->cc2 ?? 'N/A',
            $note->examen ?? 'N/A',
            $note->note_finale ?? 'N/A',
            $statut,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4F46E5']],
            ],
        ];
    }
}
