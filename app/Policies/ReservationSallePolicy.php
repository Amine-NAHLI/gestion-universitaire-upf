<?php

namespace App\Policies;

use App\Models\ReservationSalle;
use App\Models\User;

class ReservationSallePolicy
{
    public function before(User $user): ?bool
    {
        if ($user->role === 'admin') return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'professeur']);
    }

    public function view(User $user, ReservationSalle $reservation): bool
    {
        return $reservation->professeur_id === $user->professeur?->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'professeur';
    }

    public function update(User $user, ReservationSalle $reservation): bool
    {
        return $reservation->professeur_id === $user->professeur?->id;
    }

    public function delete(User $user, ReservationSalle $reservation): bool
    {
        return $reservation->professeur_id === $user->professeur?->id;
    }
}
