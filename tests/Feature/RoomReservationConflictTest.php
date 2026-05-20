<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Professeur;
use App\Models\Salle;
use App\Models\ReservationSalle;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoomReservationConflictTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test a professor cannot reserve a room during an already reserved and confirmed slot.
     */
    public function test_room_reservation_detects_conflicts_and_blocks_overlapping_slots(): void
    {
        // 1. Create a Professor user
        $user = User::create([
            'name' => 'Kzadri',
            'prenom' => 'Marwane',
            'email' => 'prof@upf.ma',
            'password' => bcrypt('password'),
            'role' => 'professeur',
            'is_active' => true
        ]);

        $professeur = Professeur::create([
            'user_id' => $user->id,
            'matricule' => 'PROF-001',
            'specialite' => 'Génie Logiciel',
            'grade' => 'professeur',
            'date_recrutement' => now()
        ]);

        // 2. Create a Room
        $salle = Salle::create([
            'nom' => 'Salle TP A',
            'capacite' => 30,
            'type' => 'tp',
            'is_disponible' => true
        ]);

        // 3. Create an existing confirmed reservation
        // From 09:00 to 11:00
        ReservationSalle::create([
            'salle_id' => $salle->id,
            'professeur_id' => $professeur->id,
            'date' => now()->addDays(2)->format('Y-m-d'),
            'heure_debut' => '09:00:00',
            'heure_fin' => '11:00:00',
            'motif' => 'Soutenance de Projet GINFO',
            'statut' => 'confirmee'
        ]);

        // 4. Try to submit a new overlapping reservation
        // From 10:00 to 12:00 (overlapping with 09:00-11:00)
        $response = $this->actingAs($user)->post(route('professeur.reservations.store'), [
            'salle_id' => $salle->id,
            'date' => now()->addDays(2)->format('Y-m-d'),
            'heure_debut' => '10:00:00',
            'heure_fin' => '12:00:00',
            'motif' => 'Cours avancé Laravel 12'
        ]);

        // 5. Assert the request redirects back
        $response->assertStatus(302);
        
        // Assert session has custom error message
        $response->assertSessionHas('error', 'Cette salle est déjà réservée sur ce créneau.');

        // Assert the second reservation was NOT saved in database
        $this->assertEquals(1, ReservationSalle::count());
    }

    /**
     * Test a professor can reserve a room if there is no time overlap.
     */
    public function test_room_reservation_succeeds_when_no_overlap(): void
    {
        $user = User::create([
            'name' => 'Kzadri',
            'prenom' => 'Marwane',
            'email' => 'prof@upf.ma',
            'password' => bcrypt('password'),
            'role' => 'professeur',
            'is_active' => true
        ]);

        $professeur = Professeur::create([
            'user_id' => $user->id,
            'matricule' => 'PROF-001',
            'specialite' => 'Génie Logiciel',
            'grade' => 'professeur',
            'date_recrutement' => now()
        ]);

        $salle = Salle::create([
            'nom' => 'Salle TP B',
            'capacite' => 30,
            'type' => 'tp',
            'is_disponible' => true
        ]);

        // Confirmed reservation from 09:00 to 11:00
        ReservationSalle::create([
            'salle_id' => $salle->id,
            'professeur_id' => $professeur->id,
            'date' => now()->addDays(2)->format('Y-m-d'),
            'heure_debut' => '09:00:00',
            'heure_fin' => '11:00:00',
            'motif' => 'Soutenance de Projet GINFO',
            'statut' => 'confirmee'
        ]);

        // Non-overlapping reservation from 14:00 to 16:00
        $response = $this->actingAs($user)->post(route('professeur.reservations.store'), [
            'salle_id' => $salle->id,
            'date' => now()->addDays(2)->format('Y-m-d'),
            'heure_debut' => '14:00:00',
            'heure_fin' => '16:00:00',
            'motif' => 'Cours avancé Laravel 12'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Salle réservée avec succès.');
        
        // Assert both reservations exist
        $this->assertEquals(2, ReservationSalle::count());
    }
}
