<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Salle;
use App\Models\Seance;
use Illuminate\Database\Seeder;

class SeanceSeeder extends Seeder
{
    public function run(): void
    {
        $creneaux = [['08:00', '10:00'], ['10:00', '12:00'], ['14:00', '16:00'], ['16:00', '18:00']];
        $sallesCours = Salle::whereIn('type', ['cours', 'amphi'])->get();
        $sallesTD = Salle::where('type', 'td')->get();
        $sallesTP = Salle::where('type', 'tp')->get();

        foreach (Module::all() as $module) {
            foreach ($module->groupes as $groupe) {
                $prof = $module->professeurs->first();
                if (!$prof) continue;

                for ($i = 0; $i < 6; $i++) {
                    $type = $i < 3 ? 'cours' : ($i < 5 ? 'td' : 'tp');
                    $salle = match ($type) {
                        'cours' => $sallesCours->random(),
                        'td' => $sallesTD->random(),
                        'tp' => $sallesTP->random(),
                    };
                    $creneau = $creneaux[array_rand($creneaux)];
                    $date = now()->addDays(rand(-28, 14));

                    Seance::create([
                        'module_id' => $module->id,
                        'professeur_id' => $prof->id,
                        'groupe_id' => $groupe->id,
                        'salle_id' => $salle->id,
                        'date' => $date->toDateString(),
                        'heure_debut' => $creneau[0],
                        'heure_fin' => $creneau[1],
                        'type' => $type,
                        'statut' => $date->isPast() ? 'effectuee' : 'planifiee',
                    ]);
                }
            }
        }
    }
}
