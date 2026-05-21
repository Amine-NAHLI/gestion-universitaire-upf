<?php

namespace App\Policies;

use App\Models\CahierTexte;
use App\Models\User;

class CahierTextePolicy
{
    public function before(User $user): ?bool
    {
        if ($user->role === 'admin') return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role === 'professeur';
    }

    public function view(User $user, CahierTexte $cahier): bool
    {
        return $cahier->seance?->professeur_id === $user->professeur?->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'professeur';
    }

    public function update(User $user, CahierTexte $cahier): bool
    {
        return $cahier->seance?->professeur_id === $user->professeur?->id;
    }

    public function delete(User $user, CahierTexte $cahier): bool
    {
        return $cahier->seance?->professeur_id === $user->professeur?->id;
    }
}
