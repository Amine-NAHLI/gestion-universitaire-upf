<?php

namespace App\Http\Controllers\Professeur;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Annonce;
use App\Models\SupportCours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\NotificationApp;
use App\Models\Etudiant;

class ClassroomController extends Controller
{
    public function index()
    {
        $professeur = auth()->user()->professeur;
        $modules = $professeur->modules()
            ->with(['annonces' => fn($q) => $q->latest()->take(3), 'supportsCours' => fn($q) => $q->latest()->take(3)])
            ->get();
            
        return view('professeur.classroom.index', compact('modules'));
    }

    public function show(Module $module)
    {
        $annonces = $module->annonces()->with('commentaires.user')->latest()->get();
        $supports = $module->supportsCours()->latest()->get();
        
        return view('professeur.classroom.show', compact('module', 'annonces', 'supports'));
    }

    public function publierAnnonce(Request $request, Module $module)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string|min:10',
            'epinglee' => 'boolean'
        ]);

        Annonce::create([
            'module_id' => $module->id,
            'professeur_id' => auth()->user()->professeur->id,
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'epinglee' => $request->boolean('epinglee'),
        ]);

        // Notifier tous les étudiants concernés par ce module
        // Un module appartient à un Niveau, et un Niveau a des Groupes qui ont des Étudiants
        $etudiants = Etudiant::whereHas('groupe.niveau', function($q) use ($module) {
            $q->whereHas('modules', function($q2) use ($module) {
                $q2->where('modules.id', $module->id);
            });
        })->get();

        foreach ($etudiants as $etudiant) {
            NotificationApp::create([
                'user_id' => $etudiant->user_id,
                'type' => 'CLASSROOM',
                'titre' => 'Nouvelle annonce : ' . $module->nom,
                'message' => 'Votre professeur a publié une nouvelle annonce.',
                'lien' => route('etudiant.classroom.show', $module->id),
            ]);
        }

        return back()->with('success', 'L\'annonce a été publiée et les étudiants ont été notifiés.');
    }

    public function uploadSupport(Request $request, Module $module)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'fichier' => 'required|file|max:20480', // 20MB max
            'description' => 'nullable|string'
        ]);

        $path = $request->file('fichier')->store('supports', 'public');

        SupportCours::create([
            'module_id' => $module->id,
            'professeur_id' => auth()->user()->professeur->id,
            'titre' => $request->titre,
            'description' => $request->description,
            'fichier' => $path,
            'type_fichier' => $request->file('fichier')->getClientOriginalExtension(),
            'taille' => $request->file('fichier')->getSize(),
        ]);

        // Notifier tous les étudiants concernés
        $etudiants = Etudiant::whereHas('groupe.niveau', function($q) use ($module) {
            $q->whereHas('modules', function($q2) use ($module) {
                $q2->where('modules.id', $module->id);
            });
        })->get();

        foreach ($etudiants as $etudiant) {
            NotificationApp::create([
                'user_id' => $etudiant->user_id,
                'type' => 'CLASSROOM',
                'titre' => 'Nouveau support : ' . $module->nom,
                'message' => 'Un nouveau support de cours a été mis en ligne.',
                'lien' => route('etudiant.classroom.show', $module->id),
            ]);
        }

        return back()->with('success', 'Le support de cours a été mis en ligne et les étudiants notifiés.');
    }
}
