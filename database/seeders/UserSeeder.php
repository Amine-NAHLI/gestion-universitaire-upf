<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création de l'Administrateur
        User::create([
            'name' => 'NAHLI',
            'prenom' => 'Amine',
            'email' => 'admin@upf.ma',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'telephone' => '0600000001',
            'is_active' => true,
        ]);

        // Création du Professeur
        User::create([
            'name' => 'KZADRI',
            'prenom' => 'Marwane',
            'email' => 'prof@upf.ma',
            'password' => Hash::make('password'),
            'role' => 'professeur',
            'telephone' => '0600000002',
            'is_active' => true,
        ]);

        // Création de l'Étudiant
        User::create([
            'name' => 'BENNANI',
            'prenom' => 'Yassine',
            'email' => 'etudiant@upf.ma',
            'password' => Hash::make('password'),
            'role' => 'etudiant',
            'telephone' => '0600000003',
            'is_active' => true,
        ]);
    }
}
