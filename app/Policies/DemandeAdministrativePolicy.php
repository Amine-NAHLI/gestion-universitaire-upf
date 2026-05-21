<?php

namespace App\Policies;

use App\Models\DemandeAdministrative;
use App\Models\User;

class DemandeAdministrativePolicy
{
    public function before(User $user): ?bool
    {
        if ($user->role === 'admin') return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'etudiant', 'professeur']);
    }

    public function view(User $user, DemandeAdministrative $demande): bool
    {
        return $demande->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['etudiant', 'professeur']);
    }

    public function update(User $user, DemandeAdministrative $demande): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, DemandeAdministrative $demande): bool
    {
        return $demande->user_id === $user->id && $demande->statut === 'en_attente';
    }

    public function download(User $user, DemandeAdministrative $demande): bool
    {
        return $demande->user_id === $user->id && $demande->statut === 'validee';
    }
}
