<?php

namespace Database\Seeders;

use App\Models\Groupe;
use App\Models\Module;
use App\Models\Professeur;
use Illuminate\Database\Seeder;

class AffectationSeeder extends Seeder
{
    public function run(): void
    {
        $annee = '2025-2026';
        $profs = Professeur::all();
        
        if ($profs->isEmpty()) {
            return;
        }
        
        $groupesGINFO3 = Groupe::whereIn('nom', ['GINFO3A', 'GINFO3B'])->get();
        $groupesGC3 = Groupe::whereIn('nom', ['GC3A'])->get();
        $groupesGIND3 = Groupe::whereIn('nom', ['GIND3A'])->get();

        // GINFO3
        $modulesGINFO3 = Module::whereHas('niveau.filiere', fn($q) => $q->where('code', 'GINFO'))->get();
        foreach ($modulesGINFO3 as $module) {
            $prof = $profs->random();
            $module->professeurs()->attach($prof->id, ['annee_universitaire' => $annee]);
            foreach ($groupesGINFO3 as $g) {
                $module->groupes()->attach($g->id, ['annee_universitaire' => $annee]);
            }
        }

        // GC3
        $modulesGC3 = Module::whereHas('niveau.filiere', fn($q) => $q->where('code', 'GC'))->get();
        foreach ($modulesGC3 as $module) {
            $prof = $profs->random();
            $module->professeurs()->attach($prof->id, ['annee_universitaire' => $annee]);
            foreach ($groupesGC3 as $g) {
                $module->groupes()->attach($g->id, ['annee_universitaire' => $annee]);
            }
        }

        // GIND3
        $modulesGIND3 = Module::whereHas('niveau.filiere', fn($q) => $q->where('code', 'GIND'))->get();
        foreach ($modulesGIND3 as $module) {
            $prof = $profs->random();
            $module->professeurs()->attach($prof->id, ['annee_universitaire' => $annee]);
            foreach ($groupesGIND3 as $g) {
                $module->groupes()->attach($g->id, ['annee_universitaire' => $annee]);
            }
        }
    }
}
