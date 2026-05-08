<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salles = Salle::latest()->paginate(10);
        
        $stats = [
            'total' => Salle::count(),
            'disponibles' => Salle::where('is_disponible', true)->count(),
            'amphis' => Salle::where('type', 'amphi')->count(),
            'tp_td' => Salle::whereIn('type', ['tp', 'td'])->count(),
        ];

        return view('admin.salles.index', compact('salles', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.salles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:salles'],
            'capacite' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'in:cours,td,tp,amphi'],
            'equipements' => ['nullable', 'string'],
            'is_disponible' => ['boolean'],
        ]);

        Salle::create([
            'nom' => $request->nom,
            'capacite' => $request->capacite,
            'type' => $request->type,
            'equipements' => $request->equipements,
            'is_disponible' => $request->has('is_disponible'),
        ]);

        return redirect()->route('admin.salles.index')
            ->with('success', 'Salle créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Salle $salle)
    {
        return view('admin.salles.show', compact('salle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salle $salle)
    {
        return view('admin.salles.edit', compact('salle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Salle $salle)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:salles,nom,' . $salle->id],
            'capacite' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'in:cours,td,tp,amphi'],
            'equipements' => ['nullable', 'string'],
            'is_disponible' => ['boolean'],
        ]);

        $salle->update([
            'nom' => $request->nom,
            'capacite' => $request->capacite,
            'type' => $request->type,
            'equipements' => $request->equipements,
            'is_disponible' => $request->has('is_disponible'),
        ]);

        return redirect()->route('admin.salles.index')
            ->with('success', 'Salle mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salle $salle)
    {
        $salle->delete();

        return redirect()->route('admin.salles.index')
            ->with('success', 'Salle supprimée avec succès.');
    }
}
