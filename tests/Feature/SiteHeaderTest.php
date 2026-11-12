<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\ArtworkSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteHeaderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_top_navigation_with_log_in_and_register(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('class="site-header', false)
            ->assertSee('id="site-navbar-links"', false)
            ->assertSee(route('login'), false)
            ->assertSee(route('register'), false)
            ->assertDontSee('My orders')
            ->assertDontSee('class="sidebar"', false);
    }

    public function test_client_sees_account_links_instead_of_guest_buttons(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('home'))
            ->assertOk()
            ->assertSee('My orders')
            ->assertSee('aria-label="Favorites"', false)
            ->assertSee('data-account-menu', false)
            ->assertDontSee('site-register-btn', false);
    }

    public function test_artworks_menu_lists_visible_categories_with_filter_hooks(): void
    {
        $this->get(route('artworks'))
            ->assertOk()
            ->assertSee('id="nav-artworks-all-link"', false)
            ->assertSee('data-category-filter="Abstract"', false);
    }

    public function test_artworks_link_opens_the_catalog_and_does_not_need_a_click_toggle(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<a href="'.route('artworks').'" class="nav-link dropdown-toggle', false)
            ->assertDontSee('data-bs-toggle="dropdown" aria-expanded="false">'."\n".'                            Artworks', false);
    }

    /**
     * The header is three columns on desktop (logo, centered links, actions), so the
     * log in / register buttons sit in the right-hand group on every page, including
     * pages that hide the header search.
     */
    public function test_guest_buttons_are_in_the_right_hand_group_on_every_page(): void
    {
        foreach ([route('home'), route('artworks'), route('artists')] as $pageUrl) {
            $html = $this->get($pageUrl)->assertOk()->getContent();

            $rightGroup = substr($html, strpos($html, 'class="site-header-right"'));
            $this->assertStringContainsString('site-register-btn', strstr($rightGroup, 'site-navbar-toggler', true), "Register button is not in the right-hand group on {$pageUrl}");
            $this->assertLessThan(
                strpos($html, 'class="site-header-right"'),
                strpos($html, 'class="navbar-nav site-nav-links"'),
                "Main links should come before the right-hand group on {$pageUrl}",
            );
        }
    }

    public function test_current_section_is_highlighted(): void
    {
        $this->get(route('artists'))
            ->assertOk()
            ->assertSeeInOrder(['href="'.route('artists').'"', 'nav-link active', 'aria-current="page"'], false);
    }

    public function test_cart_icon_shows_the_number_of_items(): void
    {
        $this->seed(ArtworkSeeder::class);

        $this->actingAs(User::factory()->create())
            ->post(route('cart.add', 'fluid-geometric'));

        $this->get(route('home'))
            ->assertSee('aria-label="Cart (1 items)"', false);
    }

    public function test_pages_with_their_own_search_hide_the_header_search(): void
    {
        $this->get(route('artworks'))
            ->assertOk()
            ->assertSee('id="search-input"', false)
            ->assertDontSee('class="search-pill-container site-search"', false)
            ->assertDontSee('aria-label="Search" title="Search"', false);

        $this->get(route('home'))
            ->assertSee('class="search-pill-container site-search"', false)
            ->assertSee('aria-label="Search" title="Search"', false);
    }
}
