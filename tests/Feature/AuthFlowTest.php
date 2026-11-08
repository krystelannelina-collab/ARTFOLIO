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
                'username' => 'janedoe',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'terms' => '1',
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

    public function test_public_registration_cannot_create_an_admin_account(): void
    {
        $this->withSession(['_token' => 'test-token'])
            ->post('/register', [
                '_token' => 'test-token',
                'name' => 'Public Admin',
                'email' => 'public-admin@example.com',
                'username' => 'publicadmin',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'terms' => '1',
                'role' => 'admin',
            ])
            ->assertRedirect('/login');

        $this->assertDatabaseHas('users', [
            'email' => 'public-admin@example.com',
            'role' => 'client',
        ]);
    }

    public function test_guest_is_redirected_to_login_route_when_accessing_homepage(): void
    {
        $response = $this->get('/home');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_registration_success_notice_is_displayed_on_login_page(): void
    {
        $this->followingRedirects()
            ->post('/register', [
                'name' => 'Sam Collector',
                'username' => 'samcollects',
                'email' => 'sam@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'terms' => '1',
            ])
            ->assertOk()
            ->assertSee('Account created successfully. Please log in.');
    }

    public function test_signup_validation_errors_and_old_inputs_are_displayed(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->from('/client-signup')
            ->followingRedirects()
            ->post('/register', [
                'name' => 'Alex Creator',
                'email' => 'taken@example.com',
                'password' => 'short',
            ])
            ->assertOk()
            ->assertSee('An account with this email address already exists.')
            ->assertSee('Password must be at least 8 characters.')
            ->assertSee('value="Alex Creator"', false)
            ->assertSee('value="taken@example.com"', false);
    }

    public function test_hardcoded_default_admin_account_can_login_and_reach_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@artfolio.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
        $this->assertSame('admin', auth()->user()->role);
    }
}
