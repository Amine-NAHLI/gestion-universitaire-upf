<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Note;
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Module;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Groupe;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test dynamic final note calculation when grades are set.
     */
    public function test_final_note_is_calculated_correctly_on_save(): void
    {
        // 1. Create structure required for an Etudiant
        $filiere = Filiere::create([
            'nom' => 'Génie Informatique',
            'code' => 'GINFO',
            'description' => 'Filière GI'
        ]);

        $niveau = Niveau::create([
            'filiere_id' => $filiere->id,
            'nom' => '3ème Année',
            'numero' => 3
        ]);

        $groupe = Groupe::create([
            'niveau_id' => $niveau->id,
            'nom' => 'G1',
            'effectif_max' => 30
        ]);

        $user = User::create([
            'name' => 'Bennani',
            'prenom' => 'Yassine',
            'email' => 'yassine@etu.upf.ma',
            'password' => bcrypt('password'),
            'role' => 'etudiant',
            'is_active' => true
        ]);

        $etudiant = Etudiant::create([
            'user_id' => $user->id,
            'groupe_id' => $groupe->id,
            'cne' => '1234567890',
            'matricule' => 'ETU-001',
            'date_inscription' => now(),
            'statut' => 'inscrit'
        ]);

        $module = Module::create([
            'niveau_id' => $niveau->id,
            'nom' => 'Technologies Web 2',
            'code' => 'TW2',
            'coefficient' => 2.0,
            'heures_cours' => 20,
            'heures_td' => 10,
            'heures_tp' => 10,
            'semestre' => 1
        ]);

        // 2. Create Note
        $note = Note::create([
            'etudiant_id' => $etudiant->id,
            'module_id' => $module->id,
            'cc1' => 15.00,
            'cc2' => 13.00,
            'examen' => 14.50,
            'annee_universitaire' => '2025-2026'
        ]);

        // 3. Assert note calculation formula is applied:
        // Formula: (($cc1 + $cc2) / 2) * 0.4 + $examen * 0.6
        // (($15 + $13) / 2) * 0.4 + 14.5 * 0.6
        // = (14 * 0.4) + (14.5 * 0.6)
        // = 5.6 + 8.7 = 14.30
        $this->assertEquals(14.30, floatval($note->note_finale));
    }

    /**
     * Test final note remains null when no grades are entered.
     */
    public function test_final_note_remains_null_when_no_grades_provided(): void
    {
        $note = new Note([
            'cc1' => null,
            'cc2' => null,
            'examen' => null,
        ]);

        $note->calculateFinalNote();

        $this->assertNull($note->note_finale);
    }
}
