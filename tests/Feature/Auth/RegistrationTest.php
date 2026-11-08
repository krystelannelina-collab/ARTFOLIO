<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200)
            ->assertSee('Create Account')
            ->assertSee('name="username"', false)
            ->assertSee('name="password_confirmation"', false)
            ->assertSee('name="terms"', false);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => '1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $user = User::query()->where('email', 'test@example.com')->sole();
        $this->assertSame('client', $user->role);
        $this->assertSame('testuser', $user->username);
        $this->assertNotSame('password', $user->password);
    }

    public function test_registration_cannot_create_an_admin_account(): void
    {
        $this->post('/register', [
            'name' => 'Public Admin',
            'username' => 'publicadmin',
            'email' => 'public-admin@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => '1',
            'role' => 'admin',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'public-admin@example.com', 'role' => 'client']);
    }

    public function test_registration_validation_errors_are_shown(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->from('/register')
            ->followingRedirects()
            ->post('/register', [
                'name' => 'Alex Creator',
                'username' => 'alex',
                'email' => 'taken@example.com',
                'password' => 'short',
                'password_confirmation' => 'short',
            ])
            ->assertOk()
            ->assertSee('An account with this email address already exists.')
            ->assertSee('You must agree to the Terms and Conditions to create an account.')
            ->assertSee('value="Alex Creator"', false);

        $this->assertGuest();
    }
}
