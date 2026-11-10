<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Database\Seeders\ArtworkSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_menu_shows_initials_and_links_on_client_pages(): void
    {
        $this->seed(ArtworkSeeder::class);
        $client = User::factory()->create(['name' => 'Jane Doe']);

        foreach (['/home', '/artworks', '/about', '/artists', '/favorites', '/cart', '/orders'] as $page) {
            $this->actingAs($client)
                ->get($page)
                ->assertOk()
                ->assertSee('Account menu for Jane Doe')
                ->assertSee('>JD</button>', false)
                ->assertSee(route('orders'), false)
                ->assertSee(route('logout'), false)
                ->assertSee('Order updates');
        }
    }

    public function test_bell_lists_reviewed_orders_and_counts_unseen_updates(): void
    {
        $client = User::factory()->create();
        $this->createOrder($client, 'ART-000101', Order::STATUS_PENDING, null);
        $this->createOrder($client, 'ART-000102', Order::STATUS_COMPLETED, now()->subHour());
        $this->createOrder($client, 'ART-000103', Order::STATUS_FAILED, now()->subMinutes(5));

        $this->assertSame(2, $client->unseenOrderUpdatesCount());

        $this->actingAs($client)
            ->get('/home')
            ->assertOk()
            ->assertSee('Order updates (2 new)')
            ->assertSee('Order #ART-000102')
            ->assertSee('Order #ART-000103')
            ->assertSee('Payment approved')
            ->assertSee('Payment rejected')
            ->assertDontSee('Order #ART-000101');

        $this->postJson(route('order-updates.seen'))
            ->assertOk()
            ->assertJson(['unseen' => 0]);

        $this->assertSame(0, $client->fresh()->unseenOrderUpdatesCount());
        $this->get('/home')
            ->assertDontSee('class="account-menu-badge"', false)
            ->assertSee('aria-label="Order updates"', false);
    }

    public function test_new_review_after_marking_seen_shows_the_badge_again(): void
    {
        $client = User::factory()->create(['order_updates_seen_at' => now()->subDay()]);
        $this->createOrder($client, 'ART-000201', Order::STATUS_COMPLETED, now());

        $this->assertSame(1, $client->unseenOrderUpdatesCount());
    }

    public function test_guests_cannot_mark_updates_seen(): void
    {
        $this->post(route('order-updates.seen'))->assertRedirect(route('login'));
    }

    public function test_storefront_no_longer_shows_placeholder_ratings(): void
    {
        $this->seed(ArtworkSeeder::class);
        $client = User::factory()->create();

        foreach (['/home', '/artworks', '/artworks/fluid-geometric', '/favorites'] as $page) {
            $this->actingAs($client)
                ->get($page)
                ->assertOk()
                ->assertDontSee('1k reviews')
                ->assertDontSee('★★★★★')
                ->assertDontSee('hero-banner.png');
        }
    }

    private function createOrder(User $user, string $orderNumber, string $status, mixed $reviewedAt): Order
    {
        return Order::query()->create([
            'order_number' => $orderNumber,
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'total' => 100,
            'status' => $status,
            'payment_method' => 'GCash',
            'payment_reviewed_at' => $reviewedAt,
        ]);
    }
}
