<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $modules = Module::with('niveau.filiere')
            ->get()
            ->map(fn($m) => [
                'id' => $m->id,
                'nom' => $m->nom,
                'code' => $m->code,
                'coefficient' => $m->coefficient,
                'heures_cours' => $m->heures_cours,
                'heures_td' => $m->heures_td,
                'heures_tp' => $m->heures_tp,
                'semestre' => $m->semestre,
                'filiere' => $m->niveau->filiere->nom,
                'niveau' => $m->niveau->nom,
            ]);

        return response()->json([
            'total' => $modules->count(),
            'modules' => $modules
        ]);
    }

    public function show(Module $module)
    {
        $module->load(['niveau.filiere', 'professeurs.user', 'groupes']);
        
        return response()->json([
            'id' => $module->id,
            'nom' => $module->nom,
            'code' => $module->code,
            'coefficient' => $module->coefficient,
            'description' => $module->description,
            'heures' => [
                'cours' => $module->heures_cours, 
                'td' => $module->heures_td, 
                'tp' => $module->heures_tp
            ],
            'semestre' => $module->semestre,
            'filiere' => $module->niveau->filiere->nom,
            'niveau' => $module->niveau->nom,
            'professeurs' => $module->professeurs->map(fn($p) => $p->user->prenom . ' ' . $p->user->name),
            'groupes' => $module->groupes->pluck('nom'),
        ]);
    }
}
