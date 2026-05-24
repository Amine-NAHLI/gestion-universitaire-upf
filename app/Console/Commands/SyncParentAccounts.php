<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SyncParentAccounts extends Command
{
    protected $signature = 'parents:sync {--dry-run : Show what would be created without actually creating}';

    protected $description = 'Vérifie que chaque étudiant a un compte parent associé et crée les comptes manquants';

    public function handle(): int
    {
        $dryRun    = $this->option('dry-run');
        $students  = User::where('role', 'etudiant')->get();
        $created   = 0;
        $existing  = 0;
        $fixed     = 0;

        $this->info("🔍 Vérification de {$students->count()} étudiant(s)...");
        $this->newLine();

        foreach ($students as $student) {
            $parentEmail = $student->email . '+parent';
            $parent      = User::where('email', $parentEmail)->first();

            if ($parent) {
                $existing++;

                // Sync is_active if mismatched
                if ($parent->is_active !== $student->is_active) {
                    if (! $dryRun) {
                        $parent->is_active = $student->is_active;
                        $parent->saveQuietly();
                    }
                    $status = $student->is_active ? 'activé' : 'désactivé';
                    $this->warn("  🔧 {$parentEmail} → statut synchronisé ({$status})");
                    $fixed++;
                } else {
                    $this->line("  ✅ {$parentEmail} — OK");
                }
            } else {
                // Create missing parent
                if (! $dryRun) {
                    User::withoutEvents(function () use ($student, $parentEmail) {
                        User::create([
                            'name'              => $student->name,
                            'prenom'            => 'Parent de ' . ($student->prenom ?? $student->name),
                            'email'             => $parentEmail,
                            'password'          => $student->password ?: Hash::make('parent123'),
                            'role'              => 'parent',
                            'is_active'         => $student->is_active,
                            'email_verified_at' => $student->email_verified_at ?? now(),
                            'telephone'         => $student->telephone,
                        ]);
                    });
                }

                $this->info("  🆕 {$parentEmail} — " . ($dryRun ? 'SERAIT créé' : 'CRÉÉ'));
                $created++;
            }
        }

        $this->newLine();
        $this->table(
            ['Métrique', 'Valeur'],
            [
                ['Étudiants vérifiés', $students->count()],
                ['Parents existants',  $existing],
                ['Parents créés',      $created],
                ['Statuts corrigés',   $fixed],
            ]
        );

        if ($dryRun && $created > 0) {
            $this->newLine();
            $this->warn("⚠️  Mode dry-run : aucun compte n'a été créé. Relancez sans --dry-run pour appliquer.");
        }

        if ($created > 0 && ! $dryRun) {
            $this->newLine();
            $this->info("✅ Tous les comptes parents manquants ont été créés avec succès !");
            $this->line("   Le mot de passe par défaut est le même que celui de l'étudiant.");
        }

        return self::SUCCESS;
    }
}
