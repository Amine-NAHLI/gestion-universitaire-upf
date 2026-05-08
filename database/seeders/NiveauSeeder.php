<?php

namespace Database\Seeders;

use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Database\Seeder;

class NiveauSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Filiere::all() as $filiere) {
            foreach ([1, 2, 3] as $numero) {
                $prefix = $numero == 1 ? '1ère année ' : $numero . 'ème année ';
                Niveau::create([
                    'nom' => $prefix . $filiere->code,
                    'numero' => $numero,
                    'filiere_id' => $filiere->id
                ]);
            }
        }
    }
}
