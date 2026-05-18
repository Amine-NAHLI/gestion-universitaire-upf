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
        // 1. Convertir le prof existant
        $profUser = User::where('email', 'prof@upf.ma')->first();
        if ($profUser) {
            Professeur::create([
                'user_id' => $profUser->id,
                'matricule' => 'P001',
                'specialite' => 'Génie Logiciel',
                'grade' => 'professeur',
                'date_recrutement' => now()->subYears(5)
            ]);
        }

        // 2. Créer 1 nouveau prof (total = 2)
        $newProfs = [
            ['name' => 'NAJI', 'prenom' => 'Said', 'email' => 'naji@upf.ma', 'specialite' => 'Réseaux', 'grade' => 'maitre_assistant'],
        ];

        foreach ($newProfs as $i => $data) {
            $user = User::create([
                'name' => $data['name'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'professeur',
                'is_active' => true,
            ]);

            Professeur::create([
                'user_id' => $user->id,
                'matricule' => 'P00' . ($i + 2),
                'specialite' => $data['specialite'],
                'grade' => $data['grade'],
                'date_recrutement' => now()->subYears(rand(1, 10)),
            ]);
        }
    }
}
