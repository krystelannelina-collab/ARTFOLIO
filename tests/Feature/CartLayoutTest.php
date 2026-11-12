<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Database\Seeders\ArtworkSeeder;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_content_sits_below_the_header_and_artworks_menu_is_not_active(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/cart');

        $response->assertOk();

        $document = new DOMDocument;
        $previousLibxmlErrorSetting = libxml_use_internal_errors(true);
        $documentLoaded = $document->loadHTML($response->getContent());
        libxml_clear_errors();
        libxml_use_internal_errors($previousLibxmlErrorSetting);
        $this->assertTrue($documentLoaded);

        $xpath = new DOMXPath($document);
        $layout = $xpath->query('//*[@class="app-layout"]')->item(0);
        $header = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " site-header ")]')->item(0);
        $mainContent = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " main-content ")]')->item(0);
        $artworksToggle = $xpath->query('//a[contains(@class, "dropdown-toggle") and normalize-space(.)="Artworks"]')->item(0);

        $this->assertNotNull($layout);
        $this->assertNotNull($header);
        $this->assertNotNull($mainContent);
        $this->assertTrue($header->parentNode->isSameNode($layout));
        $this->assertTrue($mainContent->parentNode->isSameNode($layout));
        $this->assertSame(0, $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " sidebar ")]')->length);
        $this->assertNotNull($artworksToggle);
        $this->assertStringNotContainsString('active', $artworksToggle->getAttribute('class'));
        $this->assertSame(route('artworks'), $artworksToggle->getAttribute('href'));
    }

    public function test_artworks_menu_is_highlighted_when_browsing_artworks(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/artworks');

        $response->assertOk();
        $response->assertSee('class="nav-link dropdown-toggle active"', false);
    }

    public function test_cart_checkout_and_orders_pages_have_the_header_search_form(): void
    {
        $this->seed(ArtworkSeeder::class);

        $user = User::factory()->create(['name' => 'Cristine Joy']);
        $this->actingAs($user)->post(route('cart.add', ['slug' => 'fluid-geometric']));

        $order = Order::query()->create([
            'order_number' => 'ART-000101',
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'total' => 28.56,
            'status' => 'pending',
            'payment_method' => 'not_configured',
        ]);

        foreach (['/cart', '/checkout', route('order.success', $order), '/orders'] as $pageUrl) {
            $response = $this->actingAs($user)->get($pageUrl);

            $response->assertOk()
                ->assertSee('class="search-pill-container site-search"', false)
                ->assertSee('action="'.url('/artworks').'"', false)
                ->assertSee('name="search"', false)
                ->assertSee('CJ');
        }
    }
}
