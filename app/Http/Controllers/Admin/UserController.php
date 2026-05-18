<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtre par statut en attente ou par rôle
        if ($request->has('status') && $request->status === 'pending') {
            $query->where('is_active', false);
        } elseif ($request->has('role') && in_array($request->role, ['admin', 'professeur', 'etudiant'])) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        // Statistiques
        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'professeurs' => User::where('role', 'professeur')->count(),
            'etudiants' => User::where('role', 'etudiant')->count(),
            'pending' => User::where('is_active', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,professeur,etudiant'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ]);

        User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'telephone' => $request->telephone,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['etudiant.groupe.niveau.filiere', 'professeur']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,professeur,etudiant'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ]);

        $data = [
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'role' => $request->role,
            'telephone' => $request->telephone,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            // Supprimer les demandes administratives
            \App\Models\DemandeAdministrative::where('user_id', $user->id)->delete();
            \App\Models\NotificationApp::where('user_id', $user->id)->delete();

            if ($user->isEtudiant() && $user->etudiant) {
                // Supprimer les notes et absences de l'étudiant
                \App\Models\Note::where('etudiant_id', $user->etudiant->id)->delete();
                \App\Models\Absence::where('etudiant_id', $user->etudiant->id)->delete();
                $user->etudiant()->delete();
            }

            if ($user->isProfesseur() && $user->professeur) {
                // Détacher les modules si nécessaire
                $user->professeur->modules()->detach();
                $user->professeur()->delete();
            }

            $user->delete();
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Toggle the active status of a user.
     */
    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activé' : 'désactivé';
        $type = $user->is_active ? 'success' : 'warning';

        return redirect()->back()
            ->with($type, "Le compte de {$user->full_name} a été {$status}.");
    }
}
