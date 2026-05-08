<?php

namespace Database\Seeders;

use App\Models\Etudiant;
use App\Models\Note;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Etudiant::all() as $etudiant) {
            $modules = $etudiant->groupe->modules;
            foreach ($modules as $module) {
                Note::create([
                    'etudiant_id' => $etudiant->id,
                    'module_id' => $module->id,
                    'cc1' => round(rand(80, 180) / 10, 2),
                    'cc2' => round(rand(80, 180) / 10, 2),
                    'examen' => round(rand(70, 190) / 10, 2),
                    'annee_universitaire' => '2025-2026',
                ]);
            }
        }
    }
}
