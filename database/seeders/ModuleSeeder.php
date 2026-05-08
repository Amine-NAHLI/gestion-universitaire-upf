<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Niveau;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $niveauGINFO3 = Niveau::whereHas('filiere', fn($q) => $q->where('code', 'GINFO'))->where('numero', 3)->first();
        $niveauGC3 = Niveau::whereHas('filiere', fn($q) => $q->where('code', 'GC'))->where('numero', 3)->first();
        $niveauGIND3 = Niveau::whereHas('filiere', fn($q) => $q->where('code', 'GIND'))->where('numero', 3)->first();

        // GINFO3 Modules
        $modulesGINFO = [
            ['nom' => 'Technologie Web 2', 'code' => 'TW2', 'coefficient' => 3, 'hc' => 30, 'htd' => 15, 'htp' => 15],
            ['nom' => 'Base de Données 2', 'code' => 'BDD2', 'coefficient' => 3, 'hc' => 24, 'htd' => 12, 'htp' => 12],
            ['nom' => 'Intelligence Artificielle', 'code' => 'IA', 'coefficient' => 4, 'hc' => 30, 'htd' => 15, 'htp' => 15],
            ['nom' => 'Cloud Computing', 'code' => 'CLOUD', 'coefficient' => 2, 'hc' => 20, 'htd' => 10, 'htp' => 10],
            ['nom' => 'Cybersécurité', 'code' => 'CYBER', 'coefficient' => 2, 'hc' => 20, 'htd' => 10, 'htp' => 10],
            ['nom' => 'Anglais professionnel', 'code' => 'ANG6', 'coefficient' => 1, 'hc' => 20, 'htd' => 10, 'htp' => 0],
            ['nom' => 'Project Management', 'code' => 'PM', 'coefficient' => 2, 'hc' => 20, 'htd' => 10, 'htp' => 0],
            ['nom' => 'Communication', 'code' => 'COMM6', 'coefficient' => 1, 'hc' => 20, 'htd' => 10, 'htp' => 0],
        ];

        foreach ($modulesGINFO as $m) {
            Module::create([
                'nom' => $m['nom'], 'code' => $m['code'], 'coefficient' => $m['coefficient'],
                'heures_cours' => $m['hc'], 'heures_td' => $m['htd'], 'heures_tp' => $m['htp'],
                'niveau_id' => $niveauGINFO3->id, 'semestre' => 6
            ]);
        }

        // GC3 Modules
        Module::create(['nom' => 'Béton armé', 'code' => 'BA3', 'coefficient' => 3, 'niveau_id' => $niveauGC3->id, 'semestre' => 6]);
        Module::create(['nom' => 'Mécanique des sols', 'code' => 'MDS', 'coefficient' => 3, 'niveau_id' => $niveauGC3->id, 'semestre' => 6]);

        // GIND3 Modules
        Module::create(['nom' => 'Production industrielle', 'code' => 'PROD', 'coefficient' => 3, 'niveau_id' => $niveauGIND3->id, 'semestre' => 6]);
        Module::create(['nom' => 'Lean Management', 'code' => 'LEAN', 'coefficient' => 2, 'niveau_id' => $niveauGIND3->id, 'semestre' => 6]);
    }
}
