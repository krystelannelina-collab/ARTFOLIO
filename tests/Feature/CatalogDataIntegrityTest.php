<?php

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CatalogDataIntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function test_artwork_is_linked_to_its_category_and_artist_rows(): void
    {
        $artwork = $this->createArtwork(['category' => 'Abstract', 'artist_name' => 'Gian Gomez']);

        $this->assertSame('Abstract', $artwork->catalogCategory->name);
        $this->assertSame('Gian Gomez', $artwork->artist->name);
        $this->assertTrue(Category::query()->where('name', 'Abstract')->sole()->artworks->contains($artwork));
        $this->assertTrue(Artist::query()->where('name', 'Gian Gomez')->sole()->artworks->contains($artwork));
    }

    public function test_changing_an_artworks_category_updates_its_foreign_key(): void
    {
        $artwork = $this->createArtwork(['category' => 'Abstract']);

        $artwork->update(['category' => 'Poster']);

        $this->assertSame(Category::query()->where('name', 'Poster')->value('id'), $artwork->fresh()->category_id);
    }

    public function test_renaming_a_category_keeps_artworks_linked(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::query()->where('name', 'Abstract')->sole();
        $artwork = $this->createArtwork(['category' => 'Abstract']);

        $this->actingAs($admin)
            ->put(route('admin.categories.update', $category), ['name' => 'Abstract Art', 'description' => 'Shapes'])
            ->assertSessionHasNoErrors();

        $artwork->refresh();
        $this->assertSame('Abstract Art', $artwork->category);
        $this->assertSame($category->id, $artwork->category_id);
    }

    public function test_database_refuses_to_delete_a_category_that_still_has_artworks(): void
    {
        $this->createArtwork(['category' => 'Abstract']);

        $this->expectException(QueryException::class);

        Category::query()->where('name', 'Abstract')->delete();
    }

    public function test_classroom_order_totals_exactly_six_hundred_pesos_with_stored_subtotals(): void
    {
        $buyer = User::factory()->create();
        $first = $this->createArtwork(['price' => 250.00]);
        $second = $this->createArtwork(['price' => 250.00]);
        $third = $this->createArtwork(['price' => 100.00]);

        $this->actingAs($buyer)
            ->withSession(['cart' => [$first->id => 1, $second->id => 1, $third->id => 1]])
            ->post(route('checkout.store'), $this->cardCheckout($buyer))
            ->assertSessionHasNoErrors();

        $order = Order::query()->with('items')->sole();
        $this->assertSame('600.00', number_format((float) $order->total, 2, '.', ''));
        $this->assertEqualsCanonicalizing(
            ['250.00', '250.00', '100.00'],
            $order->items->map(fn ($item): string => number_format((float) $item->subtotal, 2, '.', ''))->all(),
        );
    }

    public function test_order_subtotal_keeps_its_value_after_the_catalog_price_changes(): void
    {
        $buyer = User::factory()->create();
        $artwork = $this->createArtwork(['price' => 0.10]);
        $secondArtwork = $this->createArtwork(['price' => 0.20]);

        $this->actingAs($buyer)
            ->withSession(['cart' => [$artwork->id => 1, $secondArtwork->id => 1]])
            ->post(route('checkout.store'), $this->cardCheckout($buyer));

        $artwork->update(['price' => 999.99]);

        $order = Order::query()->with('items')->sole();
        $this->assertSame('0.30', number_format((float) $order->total, 2, '.', ''));
        $this->assertSame('0.10', number_format((float) $order->items->firstWhere('artwork_id', $artwork->id)->subtotal, 2, '.', ''));
    }

    /**
     * @return array<string, array{0: mixed}>
     */
    public static function invalidQuantities(): array
    {
        return [
            'zero' => [0],
            'negative' => [-1],
            'fractional' => [1.5],
            'more than the single original' => [2],
            'not a number' => ['abc'],
        ];
    }

    #[DataProvider('invalidQuantities')]
    public function test_invalid_quantities_are_rejected_without_changing_the_cart(mixed $quantity): void
    {
        $artwork = $this->createArtwork();

        $this->actingAs(User::factory()->create())
            ->from(route('artworks.show', $artwork->slug))
            ->post(route('cart.add', $artwork->slug), ['quantity' => $quantity])
            ->assertRedirect(route('artworks.show', $artwork->slug))
            ->assertSessionHasErrors('quantity')
            ->assertSessionMissing('cart');
    }

    public function test_quantity_of_one_is_accepted(): void
    {
        $artwork = $this->createArtwork();

        $this->actingAs(User::factory()->create())
            ->post(route('cart.add', $artwork->slug), ['quantity' => 1])
            ->assertRedirect(route('cart'))
            ->assertSessionHas('cart', [$artwork->id => 1]);
    }

    public function test_catalog_and_details_show_the_unique_artwork_number(): void
    {
        $artwork = $this->createArtwork(['title' => 'Numbered Piece']);

        $this->get(route('artworks'))->assertSee($artwork->catalogNumber());
        $this->get(route('artworks.show', $artwork->slug))->assertSee($artwork->catalogNumber());
        $this->assertMatchesRegularExpression('/^AW-\d{4,}$/', $artwork->catalogNumber());
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
            'artist_name' => 'Gian Gomez',
            'category' => 'Digital',
            'price' => 10,
            'image_path' => 'images/artwork-fluid-geometric.png',
            'description' => 'A test artwork.',
            'status' => 'published',
        ], $attributes));
    }
}
