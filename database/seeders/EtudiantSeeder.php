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
        // 1. Convertir l'étudiant existant
        $etuUser = User::where('email', 'etudiant@upf.ma')->first();
        $groupeGINFO3A = Groupe::where('nom', 'GINFO3A')->first();
        
        if ($etuUser && $groupeGINFO3A) {
            Etudiant::create([
                'user_id' => $etuUser->id,
                'cne' => 'CNE001',
                'matricule' => 'E001',
                'groupe_id' => $groupeGINFO3A->id,
                'date_inscription' => now()->subMonths(8),
                'statut' => 'inscrit'
            ]);
        }

        // 2. Créer 1 nouvel étudiant (total = 2)
        $groupe = Groupe::where('nom', 'GINFO3A')->first();
        if ($groupe) {
            $user = User::create([
                'name' => 'EL ALAMI',
                'prenom' => 'Anass',
                'email' => 'anass.alami@etu.upf.ma',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'is_active' => true,
            ]);

            Etudiant::create([
                'user_id' => $user->id,
                'cne' => 'CNE002',
                'matricule' => 'E002',
                'groupe_id' => $groupe->id,
                'date_inscription' => now()->subMonths(6),
                'statut' => 'inscrit',
            ]);
        }
    }
}
