<?php

namespace Database\Seeders;

use App\Models\Etudiant;
use App\Models\Groupe;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EtudiantSeeder extends Seeder
{
    public function run(): void
    {
        // Aucun étudiant n'est pré-généré. Ils s'inscriront d'eux-mêmes via le portail d'inscription.
    }
}
