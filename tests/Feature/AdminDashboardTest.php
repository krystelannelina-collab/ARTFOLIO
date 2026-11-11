<?php

namespace Tests\Feature;

use App\Models\Artwork;
use App\Models\User;
use Database\Seeders\ArtworkSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_sqlite_backed_dashboard_metrics_and_charts(): void
    {
        $this->seed(ArtworkSeeder::class);
        User::factory()->create(['role' => 'client']);
        User::factory()->create(['role' => 'client']);

        $response = $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.dashboard'));

        $response->assertOk()
            ->assertSee('Your creative workspace.')
            ->assertSee('Catalog snapshot')
            ->assertSee('From the collection')
            ->assertSee('Account menu for')
            ->assertSee('Settings')
            ->assertSee('Log out')
            ->assertSee(route('logout'), false)
            ->assertSee('Client signups')
            ->assertSee('Recently added artworks')
            ->assertSee('Order tracking is available.')
            ->assertSee(route('admin.orders.index'), false)
            ->assertSee('FEATURED ARTISTS')
            ->assertSee(route('admin.artworks.index'), false)
            ->assertViewHas('stats', [
                'artworks' => 10,
                'artists' => 4,
                'clients' => 2,
                'categories' => 5,
                'pendingPayments' => 0,
            ])
            ->assertViewHas('categoryChart', function (array $chart): bool {
                return $chart['labels']->all() === ['Abstract', 'Digital', 'Pixel', 'Poster', 'Typography']
                    && $chart['values']->all() === [5, 1, 1, 2, 1];
            });
    }

    public function test_client_cannot_access_admin_dashboard(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'client']))
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_from_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_login_redirects_to_the_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->followingRedirects()
            ->post(route('login'), [
                'email' => $admin->email,
                'password' => 'password',
            ])
            ->assertOk()
            ->assertSee('Your creative workspace.');
    }

    public function test_promote_admin_command_updates_an_existing_account(): void
    {
        $user = User::factory()->create(['email' => 'new-admin@example.com', 'role' => 'client']);

        $this->artisan('app:promote-admin', ['email' => $user->email])
            ->expectsOutput("Admin access granted to {$user->email}.")
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'admin',
        ]);
    }

    public function test_dashboard_handles_an_empty_artwork_catalog(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Catalog data will appear here after artworks are added.')
            ->assertSee('New client signups will appear here.');

        $this->assertSame(0, Artwork::query()->count());
    }

    public function test_admin_signup_page_does_not_allow_public_admin_registration(): void
    {
        $this->get('/admin-signup')
            ->assertRedirect(route('login'))
            ->assertSessionHas('status');
    }
}
