<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin can access admin dashboard.
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'prenom' => 'Test',
            'email' => 'admin@upf.ma',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    /**
     * Test student cannot access admin dashboard.
     */
    public function test_student_is_forbidden_from_accessing_admin_dashboard(): void
    {
        $student = User::create([
            'name' => 'Etudiant',
            'prenom' => 'Test',
            'email' => 'etudiant@upf.ma',
            'password' => bcrypt('password'),
            'role' => 'etudiant',
            'is_active' => true
        ]);

        $response = $this->actingAs($student)->get(route('admin.dashboard'));

        // Asserts HTTP 403 Forbidden
        $response->assertStatus(403);
    }

    /**
     * Test inactive and unverified user is logged out with email verification error.
     */
    public function test_inactive_unverified_user_is_logged_out_with_verification_error(): void
    {
        $inactiveUser = User::create([
            'name' => 'Blocked',
            'prenom' => 'User',
            'email' => 'blocked@upf.ma',
            'password' => bcrypt('password'),
            'role' => 'etudiant',
            'is_active' => false
        ]);

        $response = $this->actingAs($inactiveUser)->get(route('etudiant.dashboard'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $response->assertSessionHas('error', 'Veuillez d\'abord valider votre adresse email en cliquant sur le lien reçu.');
    }

    /**
     * Test inactive but verified user is logged out with pending approval error.
     */
    public function test_inactive_verified_user_is_logged_out_with_pending_approval_error(): void
    {
        $inactiveUser = User::create([
            'name' => 'Blocked',
            'prenom' => 'User',
            'email' => 'blocked@upf.ma',
            'password' => bcrypt('password'),
            'role' => 'etudiant',
            'is_active' => false,
        ]);
        $inactiveUser->email_verified_at = now();
        $inactiveUser->save();

        $response = $this->actingAs($inactiveUser)->get(route('etudiant.dashboard'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $response->assertSessionHas('error', 'Votre inscription a été validée par email. Elle est maintenant en cours d\'approbation par l\'administration.');
    }
}
