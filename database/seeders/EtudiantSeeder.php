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
        $students = [
            // GINFO3A — 3 étudiants
            ['name' => 'TAHIRI',       'prenom' => 'Youssef', 'email' => 'y.tahiri@etudiant.upf.ma',       'groupe' => 'GINFO3A'],
            ['name' => 'BENCHEKROUN', 'prenom' => 'Sofia',   'email' => 's.benchekroun@etudiant.upf.ma',  'groupe' => 'GINFO3A'],
            ['name' => 'ZOUARI',       'prenom' => 'Hamza',   'email' => 'h.zouari@etudiant.upf.ma',       'groupe' => 'GINFO3A'],
            // GINFO3B — 2 étudiants
            ['name' => 'BELHAJ',       'prenom' => 'Nadia',   'email' => 'n.belhaj@etudiant.upf.ma',       'groupe' => 'GINFO3B'],
            ['name' => 'LAHLOU',       'prenom' => 'Omar',    'email' => 'o.lahlou@etudiant.upf.ma',       'groupe' => 'GINFO3B'],
            // GC3A — 2 étudiants
            ['name' => 'RADI',         'prenom' => 'Imane',   'email' => 'i.radi@etudiant.upf.ma',         'groupe' => 'GC3A'],
            ['name' => 'FILALI',       'prenom' => 'Mehdi',   'email' => 'm.filali@etudiant.upf.ma',       'groupe' => 'GC3A'],
            // GIND3A — 1 étudiant
            ['name' => 'MANSOURI',     'prenom' => 'Leila',   'email' => 'l.mansouri@etudiant.upf.ma',     'groupe' => 'GIND3A'],
        ];

        $groupeCache = [];

        foreach ($students as $i => $data) {
            $nom = $data['groupe'];
            if (!isset($groupeCache[$nom])) {
                $groupeCache[$nom] = Groupe::where('nom', $nom)->first();
            }
            $groupe = $groupeCache[$nom];
            if (!$groupe) {
                continue;
            }

            $user = User::create([
                'name'      => $data['name'],
                'prenom'    => $data['prenom'],
                'email'     => $data['email'],
                'password'  => Hash::make('password'),
                'role'      => 'etudiant',
                'is_active' => true,
            ]);

            Etudiant::create([
                'user_id'          => $user->id,
                'cne'              => 'CNE' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'matricule'        => 'E' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'groupe_id'        => $groupe->id,
                'date_inscription' => now()->subMonths(rand(1, 8))->toDateString(),
                'statut'           => 'inscrit',
            ]);

            // Compte parent lié (même mot de passe, email convention)
            User::create([
                'name'      => $data['name'],
                'prenom'    => 'Parent de ' . $data['prenom'],
                'email'     => $data['email'] . '+parent',
                'password'  => Hash::make('password'),
                'role'      => 'parent',
                'is_active' => true,
            ]);
        }
    }
}
