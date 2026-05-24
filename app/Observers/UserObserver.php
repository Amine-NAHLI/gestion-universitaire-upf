<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Ensures every student (etudiant) always has a linked parent account.
 * The parent email follows the convention: {student_email}+parent
 */
class UserObserver
{
    /**
     * When a student is created, auto-create the parent account if missing.
     */
    public function created(User $user): void
    {
        if (! $user->isEtudiant()) {
            return;
        }

        $this->ensureParentExists($user);
    }

    /**
     * When a student is updated (email, password, is_active…),
     * sync or create the parent account accordingly.
     */
    public function updated(User $user): void
    {
        if (! $user->isEtudiant()) {
            return;
        }

        $parentEmail = $user->email . '+parent';
        $parent      = User::where('email', $parentEmail)->first();

        if ($parent) {
            // Sync active status with student
            if ($parent->is_active !== $user->is_active) {
                $parent->is_active = $user->is_active;
                $parent->saveQuietly(); // avoid infinite loop
            }

            // If student email changed, update parent email too
            $oldEmail = $user->getOriginal('email');
            if ($oldEmail && $oldEmail !== $user->email) {
                $oldParent = User::where('email', $oldEmail . '+parent')->first();
                if ($oldParent) {
                    $oldParent->email = $parentEmail;
                    $oldParent->saveQuietly();
                }
            }
        } else {
            // Parent doesn't exist yet — create it
            $this->ensureParentExists($user);
        }
    }

    /**
     * Create a parent account for the given student if it doesn't exist.
     */
    private function ensureParentExists(User $user): void
    {
        $parentEmail = $user->email . '+parent';

        if (User::where('email', $parentEmail)->exists()) {
            return;
        }

        User::withoutEvents(function () use ($user, $parentEmail) {
            User::create([
                'name'              => $user->name,
                'prenom'            => 'Parent de ' . ($user->prenom ?? $user->name),
                'email'             => $parentEmail,
                'password'          => $user->getOriginal('password') ?: Hash::make('parent123'),
                'role'              => 'parent',
                'is_active'         => $user->is_active,
                'email_verified_at' => $user->email_verified_at ?? now(),
                'telephone'         => $user->telephone,
            ]);
        });

        Log::info("Parent account auto-created for student: {$user->email} → {$parentEmail}");
    }
}
