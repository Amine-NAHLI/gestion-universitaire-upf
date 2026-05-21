<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Annonce;
use App\Models\Commentaire;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $etudiant = auth()->user()->etudiant;

        if (! $etudiant || ! $etudiant->groupe) {
            return view('etudiant.classroom.index', ['modules' => collect()]);
        }

        $modules = $etudiant->groupe->niveau->modules()
            ->with(['professeurs.user', 'annonces' => fn($q) => $q->latest()->take(1)])
            ->get();

        return view('etudiant.classroom.index', compact('modules'));
    }

    public function show(Module $module)
    {
        $etudiant = auth()->user()->etudiant;

        // Vérifier que le groupe de l'étudiant est inscrit à ce module
        if ($etudiant && $etudiant->groupe) {
            $inscrit = $etudiant->groupe->modules()->where('modules.id', $module->id)->exists();
            if (!$inscrit) {
                abort(403, 'Vous n\'êtes pas inscrit à ce module.');
            }
        }

        $annonces = $module->annonces()
            ->with(['professeur.user', 'commentaires.user'])
            ->latest()
            ->get();

        $supports = $module->supportsCours()
            ->with('professeur.user')
            ->latest()
            ->get();

        return view('etudiant.classroom.show', compact('module', 'annonces', 'supports'));
    }

    public function commenter(Request $request, Annonce $annonce)
    {
        $request->validate([
            'contenu' => 'required|string|min:2|max:500'
        ]);

        Commentaire::create([
            'annonce_id' => $annonce->id,
            'user_id' => auth()->id(),
            'contenu' => $request->contenu,
        ]);

        return back()->with('success', 'Votre commentaire a été ajouté.');
    }
}
