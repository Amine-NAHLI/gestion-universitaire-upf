<?php

namespace Database\Seeders;

use App\Models\Filiere;
use Illuminate\Database\Seeder;

class FiliereSeeder extends Seeder
{
    public function run(): void
    {
        Filiere::create(['nom' => 'Génie Informatique', 'code' => 'GINFO', 'description' => 'Formation en informatique et systèmes']);
        Filiere::create(['nom' => 'Génie Civil', 'code' => 'GC', 'description' => 'Formation en construction et bâtiment']);
        Filiere::create(['nom' => 'Génie Industriel', 'code' => 'GIND', 'description' => 'Formation en gestion industrielle']);
    }
}
