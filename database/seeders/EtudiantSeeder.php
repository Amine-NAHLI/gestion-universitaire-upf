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

        // 2. Répartition pour les 29 autres
        $repartition = [
            ['groupe' => 'GINFO3A', 'count' => 9],
            ['groupe' => 'GINFO3B', 'count' => 10],
            ['groupe' => 'GC3A', 'count' => 5],
            ['groupe' => 'GIND3A', 'count' => 5],
        ];

        $compteur = 2;
        foreach ($repartition as $rep) {
            $groupe = Groupe::where('nom', $rep['groupe'])->first();
            if (!$groupe) continue;

            for ($j = 0; $j < $rep['count']; $j++) {
                $prenom = fake()->firstName();
                $name = strtoupper(fake()->lastName());
                $emailBase = strtolower(str_replace(' ', '', $prenom . '.' . $name));
                
                $user = User::create([
                    'name' => $name,
                    'prenom' => $prenom,
                    'email' => $emailBase . $compteur . '@etu.upf.ma',
                    'password' => Hash::make('password'),
                    'role' => 'etudiant',
                    'is_active' => true,
                ]);

                Etudiant::create([
                    'user_id' => $user->id,
                    'cne' => 'CNE' . str_pad($compteur, 3, '0', STR_PAD_LEFT),
                    'matricule' => 'E' . str_pad($compteur, 3, '0', STR_PAD_LEFT),
                    'groupe_id' => $groupe->id,
                    'date_inscription' => now()->subMonths(rand(1, 12)),
                    'statut' => 'inscrit',
                ]);
                $compteur++;
            }
        }
    }
}
