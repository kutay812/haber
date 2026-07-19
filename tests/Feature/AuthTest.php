<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create the default User role required by model boot event
        Role::create(['name' => 'User']);
    }

    /**
     * Test successful login
     */
    public function test_user_can_login_with_correct_credentials(): void
    {
        $this->withoutMiddleware([
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class
        ]);

        $user = User::factory()->create([
            'email'    => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login.submit'), [
            'email'    => 'testuser@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test invalid login validation errors
     */
    public function test_user_cannot_login_with_incorrect_credentials(): void
    {
        $this->withoutMiddleware([
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class
        ]);

        $user = User::factory()->create([
            'email'    => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login.submit'), [
            'email'    => 'testuser@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test registration validations and creation
     */
    public function test_user_can_register_successfully(): void
    {
        $this->withoutMiddleware([
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class
        ]);

        $response = $this->post(route('register.submit'), [
            'name'                  => 'New User',
            'email'                 => 'newuser@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'name'  => 'New User',
        ]);
    }
}
