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

        // 2. Créer 5 nouveaux profs
        $newProfs = [
            ['name' => 'NAJI', 'prenom' => 'Said', 'email' => 'naji@upf.ma', 'specialite' => 'Réseaux', 'grade' => 'maitre_assistant'],
            ['name' => 'ALAMI', 'prenom' => 'Fatima', 'email' => 'alami@upf.ma', 'specialite' => 'Bases de Données', 'grade' => 'professeur'],
            ['name' => 'TAZI', 'prenom' => 'Mohamed', 'email' => 'tazi@upf.ma', 'specialite' => 'Intelligence Artificielle', 'grade' => 'maitre_assistant'],
            ['name' => 'BENJELLOUN', 'prenom' => 'Sara', 'email' => 'benjelloun@upf.ma', 'specialite' => 'Cybersécurité', 'grade' => 'assistant'],
            ['name' => 'IDRISSI', 'prenom' => 'Karim', 'email' => 'idrissi@upf.ma', 'specialite' => 'Génie Civil', 'grade' => 'professeur'],
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
