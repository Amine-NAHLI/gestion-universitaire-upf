<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use Illuminate\Http\Request;

class FiliereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filieres = Filiere::withCount(['niveaux'])->paginate(10);
        return view('admin.filieres.index', compact('filieres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.filieres.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:filieres'],
            'code' => ['required', 'string', 'max:10', 'unique:filieres', 'uppercase'],
            'description' => ['nullable', 'string'],
        ]);

        Filiere::create($request->all());

        return redirect()->route('admin.filieres.index')
            ->with('success', 'Filière créée avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Filiere $filiere)
    {
        return view('admin.filieres.edit', compact('filiere'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Filiere $filiere)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:filieres,nom,' . $filiere->id],
            'code' => ['required', 'string', 'max:10', 'unique:filieres,code,' . $filiere->id, 'uppercase'],
            'description' => ['nullable', 'string'],
        ]);

        $filiere->update($request->all());

        return redirect()->route('admin.filieres.index')
            ->with('success', 'Filière mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Filiere $filiere)
    {
        $filiere->delete();

        return redirect()->route('admin.filieres.index')
            ->with('success', 'Filière supprimée avec succès.');
    }
}
