<?php

namespace Tests\Feature;

use App\Models\Artwork;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_add_artwork_and_place_a_paid_order_with_card_or_gcash(): void
    {
        $artwork = $this->createArtwork(['price' => 28.56]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('cart.add', $artwork->slug))
            ->assertRedirect(route('cart'))
            ->assertSessionHas('cart', [$artwork->id => 1]);

        $this->post(route('checkout.store'), [
            'contact_email' => 'receipt@example.com',
            'payment_method' => 'gcash',
            'gcash_name' => 'Test Collector',
            'gcash_number' => '09171234567',
        ])->assertSessionMissing('cart');

        $order = Order::query()->with('items')->sole();

        $this->assertSame('ART-'.str_pad((string) $order->id, 6, '0', STR_PAD_LEFT), $order->order_number);
        $this->assertSame('completed', $order->status);
        $this->assertSame('GCash', $order->payment_method);
        $this->assertSame('receipt@example.com', $order->customer_email);
        $this->assertEquals(28.56, (float) $order->total);
        $this->assertSame('Original artwork', $order->items->sole()->title);
        $this->assertEquals(28.56, (float) $order->items->sole()->unit_price);

        $item = $order->items->sole();

        $this->get(route('order.success', $order))
            ->assertOk()
            ->assertSee('Your next masterpiece is yours.')
            ->assertSee('Payment received. Your digital artwork is ready to download.')
            ->assertSee('GCash')
            ->assertSee(route('orders.items.download', [$order, $item]), false)
            ->assertSee(route('orders.receipt', $order), false);

        $this->get(route('orders.items.download', [$order, $item]))
            ->assertOk()
            ->assertDownload('original-artwork.png');

        $receiptResponse = $this->get(route('orders.receipt', $order));
        $receiptResponse->assertOk()
            ->assertDownload('receipt-'.strtolower($order->order_number).'.txt');
        $this->assertStringContainsString($order->order_number, $receiptResponse->streamedContent());
    }

    public function test_checkout_uses_artwork_price_from_database_instead_of_session_data(): void
    {
        $artwork = $this->createArtwork(['price' => 23.45]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession(['cart' => [$artwork->id => 100]])
            ->post(route('checkout.store'), ['contact_email' => $user->email])
            ->assertRedirect();

        $order = Order::query()->with('items')->sole();

        $this->assertEquals(23.45, (float) $order->total);
        $this->assertEquals(23.45, (float) $order->items->sole()->unit_price);
        $this->assertSame(1, $order->items->sole()->quantity);
    }

    public function test_checkout_rejects_invalid_email_and_empty_cart(): void
    {
        $artwork = $this->createArtwork();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession(['cart' => [$artwork->id => 1]])
            ->post(route('checkout.store'), ['contact_email' => 'not-an-email'])
            ->assertSessionHasErrors('contact_email');

        $this->assertSame(0, Order::query()->count());

        $this->withSession(['cart' => []])
            ->get(route('checkout'))
            ->assertRedirect(route('cart'))
            ->assertSessionHas('status');
    }

    public function test_cart_rejects_unpublished_artwork_and_can_remove_published_items(): void
    {
        $published = $this->createArtwork();
        $draft = $this->createArtwork(['status' => 'draft']);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('cart.add', $draft->slug))
            ->assertNotFound();

        $this->post(route('cart.add', $published->slug))
            ->assertRedirect(route('cart'));

        $this->delete(route('cart.remove', $published))
            ->assertRedirect(route('cart'))
            ->assertSessionHas('cart', []);
    }

    public function test_clients_can_only_view_their_own_orders(): void
    {
        $owner = User::factory()->create();
        $otherClient = User::factory()->create();
        $order = Order::query()->create([
            'order_number' => 'ART-000777',
            'user_id' => $owner->id,
            'customer_name' => $owner->name,
            'customer_email' => $owner->email,
            'total' => 10,
            'status' => 'pending',
            'payment_method' => 'not_configured',
        ]);

        $this->actingAs($otherClient)
            ->get(route('orders.show', $order))
            ->assertNotFound();

        $this->actingAs($owner)
            ->get(route('orders'))
            ->assertOk()
            ->assertSee($order->order_number)
            ->assertSee('No charge has been made.');
    }

    private function createArtwork(array $attributes = []): Artwork
    {
        return Artwork::query()->create(array_merge([
            'slug' => Str::slug(Str::random(12)),
            'title' => 'Original artwork',
            'artist_name' => 'Example Artist',
            'category' => 'Digital',
            'price' => 10,
            'image_path' => 'images/artwork-fluid-geometric.png',
            'description' => 'A test artwork.',
            'status' => 'published',
        ], $attributes));
    }
}
