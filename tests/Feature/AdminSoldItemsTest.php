<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class AdminSoldItemsTest extends TestCase
{
    use RefreshDatabase;

    public function test_clients_and_guests_cannot_open_the_sold_items_page(): void
    {
        $this->get(route('admin.sold.index'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['role' => 'client']))
            ->get(route('admin.sold.index'))
            ->assertForbidden();
    }

    public function test_admin_sees_empty_state_and_sidebar_link(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.sold.index'))
            ->assertOk()
            ->assertSee('No sold items yet')
            ->assertSee('href="'.route('admin.sold.index').'" class="admin-nav-link is-active"', false)
            ->assertViewHas('stats', [
                'itemsSold' => 0,
                'salesTotal' => 0.0,
                'averagePrice' => 0.0,
                'soldLast30Days' => 0,
                'topArtist' => null,
                'topArtistSold' => 0,
            ]);
    }

    public function test_only_items_from_approved_orders_are_listed(): void
    {
        $buyer = User::factory()->create(['name' => 'Alex Reyes', 'role' => 'client']);
        $this->createOrderWithItem($buyer, 'completed', 'Fluid Geometric', 'Kalvin Dave Ramos', 250);
        $this->createOrderWithItem($buyer, 'completed', 'Pixel Garden', 'Kalvin Dave Ramos', 150);
        $this->createOrderWithItem($buyer, 'pending', 'Pending Piece', 'Gian Gomez', 100);
        $this->createOrderWithItem($buyer, 'failed', 'Rejected Piece', 'Gian Gomez', 100);
        $this->createOrderWithItem($buyer, 'refunded', 'Refunded Piece', 'Gian Gomez', 100);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.sold.index'))
            ->assertOk()
            ->assertSee('Fluid Geometric')
            ->assertSee('Pixel Garden')
            ->assertSee('Alex Reyes')
            ->assertDontSee('Pending Piece')
            ->assertDontSee('Rejected Piece')
            ->assertDontSee('Refunded Piece')
            ->assertViewHas('stats', [
                'itemsSold' => 2,
                'salesTotal' => 400.0,
                'averagePrice' => 200.0,
                'soldLast30Days' => 2,
                'topArtist' => 'Kalvin Dave Ramos',
                'topArtistSold' => 2,
            ]);
    }

    public function test_admin_can_search_filter_by_period_and_sort(): void
    {
        $buyer = User::factory()->create(['role' => 'client']);
        $this->createOrderWithItem($buyer, 'completed', 'Old Poster', 'Gian Gomez', 500, now()->subYears(2));
        $this->createOrderWithItem($buyer, 'completed', 'Cheap Print', 'Gian Gomez', 100);
        $this->createOrderWithItem($buyer, 'completed', 'Big Canvas', 'Gian Gomez', 450);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.sold.index', ['period' => 'this_year', 'sort' => 'highest_price']))
            ->assertOk()
            ->assertSeeInOrder(['Big Canvas', 'Cheap Print'])
            ->assertDontSee('Old Poster')
            ->assertViewHas('soldItems', fn (LengthAwarePaginator $soldItems): bool => $soldItems->total() === 2);

        $this->actingAs($admin)
            ->get(route('admin.sold.index', ['search' => 'poster']))
            ->assertOk()
            ->assertSee('Old Poster')
            ->assertDontSee('Cheap Print');

        $this->actingAs($admin)
            ->get(route('admin.sold.index', ['period' => 'forever']))
            ->assertSessionHasErrors('period');
    }

    private function createOrderWithItem(User $buyer, string $status, string $title, string $artistName, float $price, mixed $reviewedAt = null): Order
    {
        static $orderNumber = 1;

        $order = Order::query()->create([
            'order_number' => 'SOLD-'.str_pad((string) $orderNumber++, 6, '0', STR_PAD_LEFT),
            'user_id' => $buyer->id,
            'customer_name' => $buyer->name,
            'customer_email' => $buyer->email,
            'total' => $price,
            'status' => $status,
            'payment_method' => 'not_configured',
        ]);
        $order->forceFill(['payment_reviewed_at' => $reviewedAt ?? now()])->save();

        $order->items()->create([
            'title' => $title,
            'artist_name' => $artistName,
            'image_path' => 'images/artwork-fluid-geometric.png',
            'unit_price' => $price,
            'quantity' => 1,
            'subtotal' => $price,
        ]);

        return $order;
    }
}
