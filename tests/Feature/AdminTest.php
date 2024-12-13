<?php
namespace Tests\Feature;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase {
    use RefreshDatabase;

    // Log in with a user with admin perms (with an already created user)
    public function test_login_as_admin() {
        $response = $this->post('/login', [
            'email' => 'Manager1@email.com',
            'password' => '123'
        ]);
        $response->assertRedirect('/dashboard');
    }

    // Test that the admin can go to the admin panel.
    public function test_admin_panel_load() {
        $user = User::where('email', 'Manager1@email.com')->first();
        $this->actingAs($user);
        $response = $this->get(route('admin'));
        $response->assertStatus(200);
        $response->assertViewIs('Admin.index');
        $response->assertViewHas('employees');
    }

    // Update the user settings test
    public function test_update_user_settings() {
        // Log in as administrator
        $user = User::where('email', 'Manager1@email.com')->first();
        $this->actingAs($user);
        // Create a new employee
        $employee = Employee::factory()->create();
        // Update their info + get response
        $response = $this->post(route('admin.updateSettings'), [
            'id' => $employee->id,
            'first_name' => 'UpdatedFirstName',
            'last_name' => 'UpdatedLastName',
            'email' => 'updated@example.com',
        ]);
        $response->assertStatus(200);
        $response->assertViewIs('Admin.user');
        $response->assertViewHas('user', function ($user) { // Ensure user is updated
            return $user->first_name === 'UpdatedFirstName' &&
                   $user->last_name === 'UpdatedLastName' &&
                   $user->email === 'updated@example.com';
        });
    }

    // Add a new user
    public function test_add_user() {
        // Log in as administrator
        $user = User::where('email', 'Manager1@email.com')->first();
        $this->actingAs($user);
        // Get response
        $response = $this->post(route('admin.addNewUser'), [
            'first_name' => 'New',
            'last_name' => 'User',
            'email' => 'newuser@example.com',
            'password' => 'securepassword',
            'roles' => [1, 2]
        ]);
        $response->assertStatus(200);
        $response->assertViewIs('Admin.user');
        $response->assertViewHas('user', function ($user) { // Ensure that the user is created.
            return $user->email === 'newuser@example.com';
        });
    }

    public function test_deactivate_account() {
        // Log in as administrator
        $user = User::where('email', 'Manager1@email.com')->first();
        $this->actingAs($user);
        // Create a new employee
        $employee = Employee::factory()->create();
        // Get response
        $response = $this->post(route('admin.toggleAccountActivation'), [
            'id' => $employee->id,
            'password' => 'newpassword',
        ]);
        $response->assertStatus(200);
        $response->assertViewIs('Admin.user');
    }
}