<?php

namespace App\Policies;

use App\Models\Justificatif;
use App\Models\User;

class JustificatifPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->role === 'admin') return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'etudiant']);
    }

    public function view(User $user, Justificatif $justificatif): bool
    {
        return $justificatif->etudiant_id === $user->etudiant?->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'etudiant';
    }

    public function update(User $user, Justificatif $justificatif): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Justificatif $justificatif): bool
    {
        return $justificatif->etudiant_id === $user->etudiant?->id
            && $justificatif->statut === 'en_attente';
    }
}
