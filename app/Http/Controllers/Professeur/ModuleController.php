<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index()
    {
        $professeur = auth()->user()->professeur;
        $modules = $professeur->modules()->with(['niveau.filiere', 'groupes'])->get();
        return view('professeur.modules.index', compact('modules'));
    }
}
