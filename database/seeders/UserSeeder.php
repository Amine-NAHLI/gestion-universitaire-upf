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
            'email' => 'nahliamine95@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'telephone' => '0600000001',
            'is_active' => true,
            'email_verified_at' => now(), // Admin is automatically verified
        ]);
    }
}
