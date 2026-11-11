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

    public function test_cart_content_stays_beside_the_sidebar_and_artworks_menu_is_closed(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/cart');

        $response->assertOk();

        $document = new DOMDocument();
        $previousLibxmlErrorSetting = libxml_use_internal_errors(true);
        $documentLoaded = $document->loadHTML($response->getContent());
        libxml_clear_errors();
        libxml_use_internal_errors($previousLibxmlErrorSetting);
        $this->assertTrue($documentLoaded);

        $xpath = new DOMXPath($document);
        $mainContent = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " main-content ")]')->item(0);
        $artworksSubmenu = $xpath->query('//*[@id="artworks-submenu"]')->item(0);
        $navigationCarets = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " nav-chevron ") or contains(concat(" ", normalize-space(@class), " "), " dropdown-chevron-down ")]');

        $this->assertNotNull($mainContent);
        $this->assertSame('app-layout', $mainContent->parentNode->attributes->getNamedItem('class')->nodeValue);
        $this->assertNotNull($artworksSubmenu);
        $this->assertSame('artworks-submenu', $artworksSubmenu->getAttribute('class'));
        $this->assertSame(0, $navigationCarets->length);
        $response->assertSee('aria-expanded="false"', false);
    }

    public function test_artworks_menu_is_open_when_browsing_artworks(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/artworks');

        $response->assertOk();
        $response->assertSee('class="artworks-submenu is-open"', false);
        $response->assertSee('aria-expanded="true"', false);
    }

    public function test_cart_checkout_and_orders_pages_have_consistent_top_bar_search_form(): void
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
                ->assertSee('<header class="top-bar">', false)
                ->assertSee('action="'.url('/artworks').'"', false)
                ->assertSee('name="search"', false)
                ->assertSee('CJ');
        }
    }
}
