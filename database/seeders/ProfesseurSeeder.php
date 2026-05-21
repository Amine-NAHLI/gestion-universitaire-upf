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
        $professeurs = [
            [
                'name'     => 'BENALI',
                'prenom'   => 'Ahmed',
                'email'    => 'a.benali@upf.ma',
                'tel'      => '0661000001',
                'grade'    => 'maitre_assistant',
                'specialite' => 'Informatique & Systèmes',
            ],
            [
                'name'     => 'ALAOUI',
                'prenom'   => 'Fatima',
                'email'    => 'f.alaoui@upf.ma',
                'tel'      => '0661000002',
                'grade'    => 'professeur',
                'specialite' => 'Génie Civil & Structures',
            ],
            [
                'name'     => 'IDRISSI',
                'prenom'   => 'Karim',
                'email'    => 'k.idrissi@upf.ma',
                'tel'      => '0661000003',
                'grade'    => 'assistant',
                'specialite' => 'Génie Industriel & Production',
            ],
        ];

        foreach ($professeurs as $i => $data) {
            $user = User::create([
                'name'      => $data['name'],
                'prenom'    => $data['prenom'],
                'email'     => $data['email'],
                'password'  => Hash::make('password'),
                'role'      => 'professeur',
                'telephone' => $data['tel'],
                'is_active' => true,
            ]);

            Professeur::create([
                'user_id'          => $user->id,
                'matricule'        => 'P' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'specialite'       => $data['specialite'],
                'grade'            => $data['grade'],
                'date_recrutement' => now()->subYears(rand(2, 12))->toDateString(),
            ]);
        }
    }
}
