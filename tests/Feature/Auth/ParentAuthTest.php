<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ParentAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_parent_account_is_automatically_created_with_student_registration()
    {
        $response = $this->post('/register', [
            'name' => 'Amine Student',
            'email' => 'amine@upf.ma',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect();
        
        // Assert student was created in database
        $this->assertDatabaseHas('users', [
            'name' => 'Amine Student',
            'email' => 'amine@upf.ma',
            'role' => 'etudiant',
            'is_active' => false,
        ]);

        // Assert parent was automatically created in database with suffix +parent
        $this->assertDatabaseHas('users', [
            'name' => 'Amine Student',
            'email' => 'amine@upf.ma+parent',
            'role' => 'parent',
            'is_active' => false,
        ]);
    }

    public function test_parent_login_screen_can_be_rendered()
    {
        $response = $this->get('/login/parent');
        $response->assertStatus(200);
        $response->assertSee('Espace Parents');
    }

    public function test_parent_cannot_login_if_inactive()
    {
        // Register a student and parent
        $this->post('/register', [
            'name' => 'Amine Student',
            'email' => 'amine@upf.ma',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // Logout the student (who was auto-logged in upon registration)
        $this->post('/logout');

        // Attempt login as parent
        $response = $this->post('/login/parent', [
            'email' => 'amine@upf.ma',
            'password' => 'password',
        ]);

        // Should return with errors because student/parent is inactive
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_parent_can_login_when_active()
    {
        // Create an active student and active parent manually
        $student = User::create([
            'name' => 'Amine Student',
            'email' => 'amine@upf.ma',
            'password' => Hash::make('password'),
            'role' => 'etudiant',
            'is_active' => true,
        ]);

        $parent = User::create([
            'name' => 'Amine Student',
            'email' => 'amine@upf.ma+parent',
            'password' => $student->password,
            'role' => 'parent',
            'is_active' => true,
        ]);

        // Log in via parent portal
        $response = $this->post('/login/parent', [
            'email' => 'amine@upf.ma',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($parent);
        $response->assertRedirect(route('parent.dashboard'));
    }
}
