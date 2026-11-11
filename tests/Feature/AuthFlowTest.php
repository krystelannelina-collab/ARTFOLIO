<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_client_registers_and_lands_on_the_storefront_home(): void
    {
        $this->followingRedirects()
            ->post('/register', [
                'name' => 'Jane Doe',
                'username' => 'janedoe',
                'email' => 'jane@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'terms' => '1',
            ])
            ->assertOk()
            ->assertSee('ARTFOLIO - Home', false);

        $this->assertAuthenticatedAs(User::query()->where('email', 'jane@example.com')->sole());
    }

    public function test_client_can_log_out_and_log_back_in(): void
    {
        $client = User::factory()->create(['password' => 'password123']);

        $this->actingAs($client)->post('/logout')->assertRedirect('/');
        $this->assertGuest();

        $this->post('/login', ['email' => $client->email, 'password' => 'password123'])
            ->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($client);
    }

    public function test_failed_login_does_not_reveal_whether_the_account_exists(): void
    {
        $this->from('/login')
            ->post('/login', ['email' => 'nobody@example.com', 'password' => 'password123'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email' => trans('auth.failed')]);

        $this->assertGuest();
    }

    public function test_old_auth_addresses_redirect_to_breeze_pages(): void
    {
        $this->get('/client-login')->assertRedirect('/login');
        $this->get('/client-signup')->assertRedirect('/register');
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_logged_in_users_are_sent_away_from_guest_pages(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/login')
            ->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_admin_account_is_seeded_from_configuration_not_hardcoded(): void
    {
        $this->post('/login', ['email' => 'admin@artfolio.com', 'password' => 'admin123']);
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'admin@artfolio.com']);

        config(['artfolio.admin.password' => 'a-strong-test-password']);
        $this->seed(DatabaseSeeder::class);

        $this->post('/login', ['email' => 'admin@artfolio.com', 'password' => 'a-strong-test-password'])
            ->assertRedirect(route('dashboard', absolute: false));
        $this->assertSame('admin', auth()->user()->role);
    }

    public function test_seeder_skips_admin_when_no_password_is_configured(): void
    {
        config(['artfolio.admin.password' => null]);
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseMissing('users', ['role' => 'admin']);
    }
}
