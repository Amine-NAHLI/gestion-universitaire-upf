<?php

namespace App\Exports;

use App\Models\Etudiant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EtudiantsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $groupe_id;
    protected $filiere_id;

    public function __construct($groupe_id = null, $filiere_id = null)
    {
        $this->groupe_id = $groupe_id;
        $this->filiere_id = $filiere_id;
    }

    public function collection()
    {
        return Etudiant::with(['user', 'groupe.niveau.filiere'])
            ->when($this->groupe_id, fn($q) => $q->where('groupe_id', $this->groupe_id))
            ->when($this->filiere_id, fn($q) => $q->whereHas('groupe.niveau', fn($sq) => $sq->where('filiere_id', $this->filiere_id)))
            ->get();
    }

    public function headings(): array
    {
        return ['Nom', 'Prénom', 'CNE', 'Matricule', 'Email', 'Groupe', 'Filière', 'Statut', 'Date Inscription'];
    }

    public function map($etudiant): array
    {
        return [
            $etudiant->user->name,
            $etudiant->user->prenom,
            $etudiant->cne ?? 'N/A',
            $etudiant->matricule ?? 'N/A',
            $etudiant->user->email,
            $etudiant->groupe->nom ?? 'N/A',
            $etudiant->groupe->niveau->filiere->nom ?? 'N/A',
            ucfirst($etudiant->statut ?? 'actif'),
            $etudiant->date_inscription?->format('d/m/Y') ?? 'N/A',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF10B981']],
            ],
        ];
    }
}
