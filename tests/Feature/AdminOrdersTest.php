<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class AdminOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_order_metrics_and_empty_state(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee('Orders')
            ->assertSee('Payment processing is not connected.')
            ->assertSee('No orders yet')
            ->assertViewHas('stats', [
                'revenue' => 0.0,
                'completed' => 0,
                'pending' => 0,
                'refunded' => 0,
                'failed' => 0,
            ]);
    }

    public function test_admin_can_search_filter_and_sort_orders(): void
    {
        $oldOrder = $this->createOrder([
            'order_number' => 'ART-000001',
            'customer_name' => 'Avery Customer',
            'customer_email' => 'avery@example.com',
            'status' => 'pending',
            'created_at' => now()->subDays(10),
        ], 'Needlework artwork');
        $newOrder = $this->createOrder([
            'order_number' => 'ART-000002',
            'customer_name' => 'Jordan Customer',
            'customer_email' => 'jordan@example.com',
            'status' => 'completed',
            'created_at' => now()->subDay(),
        ], 'Landscape study');
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.orders.index', [
                'search' => 'Needlework',
                'status' => 'pending',
                'date' => '30',
                'sort' => 'oldest',
            ]))
            ->assertOk()
            ->assertSee($oldOrder->order_number)
            ->assertDontSee($newOrder->order_number)
            ->assertSee('name="sort"', false)
            ->assertSee('value="oldest" selected', false);

        $this->get(route('admin.orders.index', ['search' => 'Customer', 'sort' => 'oldest']))
            ->assertOk()
            ->assertViewHas('orders', fn (LengthAwarePaginator $orders): bool => $orders->pluck('order_number')->all() === [
                $oldOrder->order_number,
                $newOrder->order_number,
            ]);
    }

    public function test_admin_can_open_order_details_and_export_csv(): void
    {
        $order = $this->createOrder([
            'order_number' => 'ART-000003',
            'customer_name' => '=HYPERLINK("https://example.invalid")',
            'customer_email' => 'customer@example.com',
            'status' => 'pending',
        ], '=Formula artwork');
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.orders.show', $order))
            ->assertOk()
            ->assertSee($order->order_number)
            ->assertSee('No payment has been processed and downloads are not available.');

        $response = $this->get(route('admin.orders.export'));

        $response->assertOk()
            ->assertDownload('artfolio-orders-'.now()->format('Y-m-d').'.csv');

        $csv = $response->streamedContent();
        $this->assertStringContainsString("'=HYPERLINK", $csv);
        $this->assertStringContainsString("'=Formula artwork", $csv);

        $this->patch(route('admin.orders.status', $order), [
            'status' => 'completed',
        ])->assertRedirect(route('admin.orders.show', $order));

        $this->assertSame('completed', $order->fresh()->status);
    }

    public function test_non_admins_cannot_access_admin_orders(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'client']))
            ->get(route('admin.orders.index'))
            ->assertForbidden();

        auth()->logout();

        $this->get(route('admin.orders.index'))
            ->assertRedirect(route('login'));
    }

    private function createOrder(array $attributes, string $artworkTitle): Order
    {
        $createdAt = $attributes['created_at'] ?? null;
        unset($attributes['created_at']);

        $order = Order::query()->create(array_merge([
            'order_number' => 'ART-TEST-'.fake()->unique()->numerify('######'),
            'user_id' => null,
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'total' => 42.50,
            'status' => 'pending',
            'payment_method' => 'not_configured',
        ], $attributes));

        if ($createdAt !== null) {
            $order->forceFill(['created_at' => $createdAt])->save();
        }

        OrderItem::query()->create([
            'order_id' => $order->id,
            'artwork_id' => null,
            'title' => $artworkTitle,
            'artist_name' => 'Test Artist',
            'image_path' => 'images/artwork-fluid-geometric.png',
            'unit_price' => $order->total,
            'quantity' => 1,
        ]);

        return $order;
    }
}
