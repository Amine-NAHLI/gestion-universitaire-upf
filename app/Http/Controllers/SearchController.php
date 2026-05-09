<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Module;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = [];
        $authUser = auth()->user();

        // Les étudiants n'ont pas accès à la recherche globale
        if ($authUser->isEtudiant()) {
            return response()->json([]);
        }

        // Construire la requête utilisateur selon le rôle
        $userQuery = User::where(function($q) use ($request) {
            $term = $request->get('q', '');
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('prenom', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });

        // Les professeurs ne peuvent chercher que les étudiants
        if ($authUser->isProfesseur()) {
            $userQuery->where('role', 'etudiant');
        }

        $users = $userQuery->limit(5)->get();

        foreach ($users as $user) {
            $roleLabel = match($user->role) {
                'admin' => 'Admin',
                'professeur' => 'Professeur',
                'etudiant' => 'Étudiant',
                default => $user->role,
            };
            
            // Lien vers la fiche de l'utilisateur
            $link = '#';
            if ($authUser->isAdmin()) {
                $link = route('admin.users.show', $user->id);
            }

            $results[] = [
                'type' => $roleLabel,
                'icon' => 'fa-user',
                'title' => $user->prenom . ' ' . $user->name,
                'subtitle' => $user->email,
                'link' => $link,
            ];
        }

        // Seuls les admins peuvent chercher dans les modules
        if ($authUser->isAdmin()) {
            $modules = Module::where('nom', 'like', "%{$q}%")
                ->orWhere('code', 'like', "%{$q}%")
                ->limit(5)
                ->get();

            foreach ($modules as $module) {
                $results[] = [
                    'type' => 'Module',
                    'icon' => 'fa-book',
                    'title' => $module->nom,
                    'subtitle' => $module->code ?? '',
                    'link' => route('admin.modules.index'),
                ];
            }
        }

        return response()->json($results);
    }
}
