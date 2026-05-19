<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Niveau;
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
        // Check for dependent niveaux before deleting
        if ($filiere->niveaux()->exists()) {
            return redirect()->route('admin.filieres.index')
                ->with('error', 'Impossible de supprimer cette filière : elle contient des niveaux. Supprimez d\'abord les niveaux associés.');
        }

        $filiere->delete();

        return redirect()->route('admin.filieres.index')
            ->with('success', 'Filière supprimée avec succès.');
    }

    public function niveaux(Filiere $filiere): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            $filiere->niveaux()->select('id', 'nom', 'numero')->orderBy('numero')->get()
        );
    }

    public function groupesByNiveau(Niveau $niveau): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            $niveau->groupes()->select('id', 'nom')->orderBy('nom')->get()
        );
    }
}
