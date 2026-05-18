<?php

namespace Database\Seeders;

use App\Models\Professeur;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProfesseurSeeder extends Seeder
{
    public function run(): void
    {
        // Aucun professeur n'est pré-généré. C'est l'administrateur qui les créera manuellement.
    }
}
