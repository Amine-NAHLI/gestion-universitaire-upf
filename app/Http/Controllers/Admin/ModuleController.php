<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Niveau;
use App\Models\Filiere;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Module::with(['niveau.filiere']);

        if ($request->filled('filiere_id')) {
            $query->whereHas('niveau', function ($q) use ($request) {
                $q->where('filiere_id', $request->filiere_id);
            });
        }

        $modules = $query->paginate(15)->withQueryString();
        $filieres = Filiere::all();

        return view('admin.modules.index', compact('modules', 'filieres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $niveaux = Niveau::with('filiere')->get();
        return view('admin.modules.create', compact('niveaux'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:modules'],
            'description' => ['nullable', 'string'],
            'coefficient' => ['required', 'numeric', 'min:1', 'max:10'],
            'heures_cours' => ['required', 'integer', 'min:0'],
            'heures_td' => ['required', 'integer', 'min:0'],
            'heures_tp' => ['required', 'integer', 'min:0'],
            'niveau_id' => ['required', 'exists:niveaux,id'],
            'semestre' => ['required', 'integer', 'in:1,2,3,4,5,6'],
        ]);

        Module::create($request->all());

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module)
    {
        $niveaux = Niveau::with('filiere')->get();
        $groupes = \App\Models\Groupe::with('niveau.filiere')->get();
        return view('admin.modules.edit', compact('module', 'niveaux', 'groupes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Module $module)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:modules,code,' . $module->id],
            'description' => ['nullable', 'string'],
            'coefficient' => ['required', 'numeric', 'min:1', 'max:10'],
            'heures_cours' => ['required', 'integer', 'min:0'],
            'heures_td' => ['required', 'integer', 'min:0'],
            'heures_tp' => ['required', 'integer', 'min:0'],
            'niveau_id' => ['required', 'exists:niveaux,id'],
            'semestre' => ['required', 'integer', 'in:1,2,3,4,5,6'],
            'groupes' => ['nullable', 'array'],
            'groupes.*' => ['exists:groupes,id'],
        ]);

        $module->update($request->except('groupes'));

        $syncData = [];
        if ($request->has('groupes')) {
            foreach ($request->groupes as $groupeId) {
                $syncData[$groupeId] = ['annee_universitaire' => '2025-2026'];
            }
        }
        $module->groupes()->sync($syncData);

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module)
    {
        $module->delete();

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module supprimé avec succès.');
    }
}
