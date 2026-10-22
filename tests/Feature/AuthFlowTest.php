<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_login_and_reach_homepage(): void
    {
        $response = $this->withSession(['_token' => 'test-token'])
            ->post('/register', [
                '_token' => 'test-token',
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'password' => 'password123',
                'role' => 'client',
            ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'name' => 'Jane Doe',
            'role' => 'client',
        ]);

        $loginResponse = $this->withSession(['_token' => 'test-token'])
            ->post('/login', [
                '_token' => 'test-token',
                'email' => 'jane@example.com',
                'password' => 'password123',
            ]);

        $loginResponse->assertRedirect('/home');
        $this->assertAuthenticatedAs(User::first());
    }

    public function test_login_without_an_account_shows_warning_to_create_one(): void
    {
        $response = $this->withSession(['_token' => 'test-token'])
            ->from('/login')
            ->post('/login', [
                '_token' => 'test-token',
                'email' => 'missing@example.com',
                'password' => 'password123',
            ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors([
            'email' => 'No account found. Please create an account first.',
        ]);
        $this->assertGuest();
    }

    public function test_guest_is_redirected_to_login_route_when_accessing_homepage(): void
    {
        $response = $this->get('/home');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
