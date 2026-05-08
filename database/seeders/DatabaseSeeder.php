<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            FiliereSeeder::class,
            NiveauSeeder::class,
            GroupeSeeder::class,
            SalleSeeder::class,
            ModuleSeeder::class,
            ProfesseurSeeder::class,
            EtudiantSeeder::class,
            AffectationSeeder::class,
            SeanceSeeder::class,
            NoteSeeder::class,
            AbsenceSeeder::class,
        ]);
    }
}
