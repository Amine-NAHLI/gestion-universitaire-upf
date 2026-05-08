<?php

namespace Database\Seeders;

use App\Models\Groupe;
use App\Models\Niveau;
use Illuminate\Database\Seeder;

class GroupeSeeder extends Seeder
{
    public function run(): void
    {
        $codes = ['GINFO', 'GC', 'GIND'];

        foreach ($codes as $code) {
            $niveau = Niveau::whereHas('filiere', fn($q) => $q->where('code', $code))
                ->where('numero', 3)
                ->first();

            if ($niveau) {
                Groupe::create(['nom' => $code . '3A', 'niveau_id' => $niveau->id, 'effectif_max' => 30]);
                Groupe::create(['nom' => $code . '3B', 'niveau_id' => $niveau->id, 'effectif_max' => 30]);
            }
        }
    }
}
