<?php

namespace App\Policies;

use App\Models\Seance;
use App\Models\User;

class SeancePolicy
{
    public function before(User $user): ?bool
    {
        if ($user->role === 'admin') return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'professeur', 'etudiant']);
    }

    public function view(User $user, Seance $seance): bool
    {
        if ($user->isProfesseur()) {
            return $seance->professeur_id === $user->professeur?->id;
        }
        if ($user->isEtudiant()) {
            return $seance->groupe_id === $user->etudiant?->groupe_id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Seance $seance): bool
    {
        return $seance->professeur_id === $user->professeur?->id;
    }

    public function manageAppel(User $user, Seance $seance): bool
    {
        return $seance->professeur_id === $user->professeur?->id;
    }

    public function manageCahier(User $user, Seance $seance): bool
    {
        return $seance->professeur_id === $user->professeur?->id;
    }

    public function delete(User $user, Seance $seance): bool
    {
        return $user->role === 'admin';
    }
}
