<?php

namespace Tests\Feature;

use App\Models\Artwork;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class PurchaseWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_artwork_can_only_be_reserved_by_one_buyer(): void
    {
        $artwork = $this->createArtwork();
        $firstBuyer = User::factory()->create();
        $secondBuyer = User::factory()->create();

        $this->actingAs($secondBuyer)->post(route('cart.add', $artwork->slug));

        $this->actingAs($firstBuyer)
            ->withSession(['cart' => [$artwork->id => 1]])
            ->post(route('checkout.store'), $this->cardCheckout($firstBuyer))
            ->assertRedirect();

        $this->actingAs($secondBuyer)
            ->withSession(['cart' => [$artwork->id => 1]])
            ->post(route('checkout.store'), $this->cardCheckout($secondBuyer))
            ->assertRedirect(route('cart'));

        $this->assertSame(1, Order::query()->count());
        $this->assertSame($firstBuyer->id, Order::query()->sole()->user_id);
    }

    public function test_checkout_rejects_artwork_reserved_between_cart_and_payment(): void
    {
        $artwork = $this->createArtwork();
        $buyer = User::factory()->create();

        $this->actingAs($buyer)->post(route('cart.add', $artwork->slug));
        Artwork::query()->whereKey($artwork->id)->update(['availability' => Artwork::AVAILABILITY_RESERVED]);

        $this->post(route('checkout.store'), $this->cardCheckout($buyer))
            ->assertRedirect(route('cart'));

        $this->assertSame(0, Order::query()->count());
    }

    public function test_sold_artwork_leaves_catalog_but_keeps_records_for_buyer_and_admin(): void
    {
        $artwork = $this->createArtwork(['title' => 'Only One Piece']);
        $buyer = User::factory()->create();
        $otherClient = User::factory()->create();
        $order = $this->placeOrder($buyer, $artwork);

        $order->transitionTo(Order::STATUS_COMPLETED, User::factory()->create(['role' => 'admin']));

        $this->actingAs($otherClient)
            ->get(route('artworks'))
            ->assertOk()
            ->assertDontSee('Only One Piece');

        $this->post(route('cart.add', $artwork->slug))
            ->assertRedirect(route('artworks.show', ['slug' => $artwork->slug]));
        $this->assertSame([], session('cart', []));

        $this->get(route('artworks.show', ['slug' => $artwork->slug]))
            ->assertOk()
            ->assertSee('Sold · One of one')
            ->assertDontSee(route('cart.add', ['slug' => $artwork->slug]), false);

        $this->actingAs($buyer)
            ->get(route('artworks.show', ['slug' => $artwork->slug]))
            ->assertOk()
            ->assertSee('Download original')
            ->assertDontSee('artwork-lock-overlay', false);

        $this->assertDatabaseHas('order_items', ['artwork_id' => $artwork->id, 'title' => 'Only One Piece']);
    }

    public function test_rejected_payment_releases_artwork_and_keeps_downloads_locked(): void
    {
        $artwork = $this->createArtwork();
        $buyer = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $order = $this->placeOrder($buyer, $artwork);

        $this->actingAs($admin)
            ->patch(route('admin.orders.status', $order), ['status' => Order::STATUS_FAILED])
            ->assertSessionHasErrors('rejection_reason');

        $this->patch(route('admin.orders.status', $order), [
            'status' => Order::STATUS_FAILED,
            'rejection_reason' => 'No matching GCash transaction.',
        ])->assertRedirect(route('admin.orders.show', $order));

        $this->assertSame(Order::STATUS_FAILED, $order->fresh()->status);
        $this->assertSame(Artwork::AVAILABILITY_AVAILABLE, $artwork->fresh()->availability);

        $item = $order->items()->sole();
        $this->actingAs($buyer)
            ->get(route('orders.items.download', [$order, $item]))
            ->assertForbidden();

        $this->get(route('orders'))
            ->assertOk()
            ->assertSee('Payment rejected')
            ->assertSee('No matching GCash transaction.');
    }

    public function test_admin_cannot_make_invalid_status_changes(): void
    {
        $artwork = $this->createArtwork();
        $order = $this->placeOrder(User::factory()->create(), $artwork);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch(route('admin.orders.status', $order), ['status' => Order::STATUS_REFUNDED])
            ->assertSessionHasErrors('status');

        $this->assertSame(Order::STATUS_PENDING, $order->fresh()->status);
        $this->assertSame(Artwork::AVAILABILITY_RESERVED, $artwork->fresh()->availability);
    }

    public function test_only_the_buyer_can_download_and_receives_the_private_original(): void
    {
        Storage::fake('local');
        $originalFile = 'artworks/originals/test-original.png';
        $originalContents = 'full-resolution-bytes';
        Storage::disk('local')->put($originalFile, $originalContents);

        $artwork = $this->createArtwork(['original_path' => $originalFile]);
        $buyer = User::factory()->create();
        $order = $this->placeOrder($buyer, $artwork);
        $order->transitionTo(Order::STATUS_COMPLETED, User::factory()->create(['role' => 'admin']));
        $item = $order->items()->sole();

        $this->actingAs(User::factory()->create())
            ->get(route('orders.items.download', [$order, $item]))
            ->assertNotFound();

        $response = $this->actingAs($buyer)->get(route('orders.items.download', [$order, $item]));
        $response->assertOk()->assertDownload('original-artwork.png');
        $this->assertSame($originalContents, $response->streamedContent());
    }

    public function test_unpurchased_artwork_shows_locked_preview(): void
    {
        $artwork = $this->createArtwork();

        $this->actingAs(User::factory()->create())
            ->get(route('artworks.show', ['slug' => $artwork->slug]))
            ->assertOk()
            ->assertSee('artwork-lock-overlay', false)
            ->assertSee('Full resolution unlocks after purchase');
    }

    private function placeOrder(User $buyer, Artwork $artwork): Order
    {
        $this->actingAs($buyer)
            ->withSession(['cart' => [$artwork->id => 1]])
            ->post(route('checkout.store'), $this->cardCheckout($buyer))
            ->assertSessionHasNoErrors();

        return Order::query()->where('user_id', $buyer->id)->latest('id')->firstOrFail();
    }

    /**
     * @return array<string, string>
     */
    private function cardCheckout(User $buyer): array
    {
        return [
            'contact_email' => $buyer->email,
            'payment_method' => 'card',
            'cardholder_name' => $buyer->name,
            'card_number' => '4242424242424242',
            'card_expiry' => '12/30',
            'card_cvc' => '123',
        ];
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
