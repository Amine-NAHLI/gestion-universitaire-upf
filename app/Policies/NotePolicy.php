<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;

class NotePolicy
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

    public function view(User $user, Note $note): bool
    {
        if ($user->isEtudiant()) {
            return $note->etudiant_id === $user->etudiant?->id;
        }
        if ($user->isProfesseur()) {
            return $user->professeur?->modules->contains($note->module_id) ?? false;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'professeur']);
    }

    public function update(User $user, Note $note): bool
    {
        if ($user->isProfesseur()) {
            return $user->professeur?->modules->contains($note->module_id) ?? false;
        }
        return false;
    }

    public function delete(User $user, Note $note): bool
    {
        return $user->role === 'admin';
    }
}
