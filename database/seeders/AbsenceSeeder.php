<?php

namespace Database\Seeders;

use App\Models\Absence;
use App\Models\Etudiant;
use App\Models\Seance;
use Illuminate\Database\Seeder;

class AbsenceSeeder extends Seeder
{
    public function run(): void
    {
        $seances = Seance::where('statut', 'effectuee')->get();
        foreach ($seances as $seance) {
            if (rand(1, 100) <= 30) {
                $etudiantsGroupe = Etudiant::where('groupe_id', $seance->groupe_id)->get();
                if ($etudiantsGroupe->isEmpty()) continue;

                $absentsCount = rand(1, min(4, $etudiantsGroupe->count()));
                $absents = $etudiantsGroupe->random($absentsCount);
                foreach ($absents as $etu) {
                    Absence::firstOrCreate([
                        'etudiant_id' => $etu->id,
                        'seance_id' => $seance->id,
                    ], [
                        'justifiee' => rand(1, 100) <= 30,
                    ]);
                }
            }
        }
    }
}
