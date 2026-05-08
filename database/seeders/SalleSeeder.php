<?php

namespace Database\Seeders;

use App\Models\Salle;
use Illuminate\Database\Seeder;

class SalleSeeder extends Seeder
{
    public function run(): void
    {
        // Salles de cours
        foreach (['A101', 'A102', 'B201', 'B202'] as $nom) {
            Salle::create(['nom' => $nom, 'capacite' => 40, 'type' => 'cours', 'is_disponible' => true]);
        }

        // Salles de TD
        foreach (['TD1', 'TD2', 'TD3'] as $nom) {
            Salle::create(['nom' => $nom, 'capacite' => 25, 'type' => 'td', 'is_disponible' => true]);
        }

        // Salles de TP
        foreach (['Info1', 'Info2'] as $nom) {
            Salle::create([
                'nom' => $nom,
                'capacite' => 20,
                'type' => 'tp',
                'equipements' => 'PC + Projecteur + Tableau interactif',
                'is_disponible' => true
            ]);
        }

        // Amphi
        Salle::create(['nom' => 'Amphi Khaldoun', 'capacite' => 150, 'type' => 'amphi', 'is_disponible' => true]);
    }
}
