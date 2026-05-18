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
     * Test inactive user is logged out and redirected to login.
     */
    public function test_inactive_user_is_logged_out_automatically(): void
    {
        $inactiveUser = User::create([
            'name' => 'Blocked',
            'prenom' => 'User',
            'email' => 'blocked@upf.ma',
            'password' => bcrypt('password'),
            'role' => 'etudiant',
            'is_active' => false // Inactive
        ]);

        $response = $this->actingAs($inactiveUser)->get(route('etudiant.dashboard'));

        // Assert redirect to login
        $response->assertRedirect(route('login'));
        
        // Assert user has been logged out of session
        $this->assertGuest();
        
        // Assert error message is present in session
        $response->assertSessionHas('error', 'Votre compte a été désactivé.');
    }
}
