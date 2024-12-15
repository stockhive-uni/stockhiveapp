<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase {
    use RefreshDatabase; // This trait resets the database after each test is run

    public function test_render_login_screen() {
        $response = $this->get('/login');
        $response->assertStatus(200); // 200 is the status code for a successful HTTP request
    }

    public function test_can_user_login() {
        // Create a new test user in the database
        $user = User::factory()->create([
            'email' => 'email@email.com',
            'password' => bcrypt('password')
        ]);

        // Attempt to login with the test user
        $response = $this->post('/login', [
            'email' => 'email@email.com',
            'password' => 'password'
        ]);

        // Check that the user was redirected to the dashboard
        $response->assertRedirect('/dashboard');
        // Check that the user is signed in.
        $this->assertAuthenticatedAs($user);
    }

    // Ensures that a user cannot login with an invalid password
    public function test_invalid_password() {
        $response = $this->post('/login', [
            'email' => 'Manager1@email.com',
            'password' => 'password' // Incorrect password
        ]);
        $response->assertSessionHasErrors();
    }
}