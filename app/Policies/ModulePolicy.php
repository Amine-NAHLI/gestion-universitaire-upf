<?php

namespace App\Policies;

use App\Models\Module;
use App\Models\User;

class ModulePolicy
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

    public function view(User $user, Module $module): bool
    {
        if ($user->isProfesseur()) {
            return $user->professeur?->modules->contains($module->id) ?? false;
        }
        if ($user->isEtudiant()) {
            return $user->etudiant?->groupe?->modules->contains($module->id) ?? false;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Module $module): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Module $module): bool
    {
        return $user->role === 'admin';
    }

    public function manageClassroom(User $user, Module $module): bool
    {
        return $user->isProfesseur()
            && ($user->professeur?->modules->contains($module->id) ?? false);
    }
}
