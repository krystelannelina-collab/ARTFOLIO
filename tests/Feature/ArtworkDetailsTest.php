<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtworkDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_client_can_view_an_artwork_and_related_artworks(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get('/artworks/stranger-things-poster');

        $response->assertOk()
            ->assertSee('<title>Stranger Things Poster | ARTFOLIO</title>', false)
            ->assertSee('Stranger Things Poster')
            ->assertSee('Gian Gomez')
            ->assertSee('Poster')
            ->assertSee('$35.76')
            ->assertSee('About this artwork')
            ->assertSee('More to explore')
            ->assertSee(route('artworks.show', ['slug' => 'fluid-geometric']), false);
    }

    public function test_unknown_artwork_slug_returns_not_found(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/artworks/not-a-real-artwork')
            ->assertNotFound();
    }

    public function test_guest_is_redirected_to_login_from_artwork_details(): void
    {
        $this->get('/artworks/stranger-things-poster')
            ->assertRedirect('/login');
    }

    public function test_all_catalog_artworks_have_a_detail_page(): void
    {
        $user = User::factory()->create();

        foreach ([
            'fluid-geometric',
            'stranger-things-poster',
            'minimalist-organic',
            'geometric-contrast',
            'colorful-abstract',
            'the-one-poster',
            'geometric-abstract',
            'goldmidnight-pixel-art',
            'golden-mood-typography',
            'modern-muse-digital',
        ] as $slug) {
            $this->actingAs($user)
                ->get(route('artworks.show', ['slug' => $slug]))
                ->assertOk();
        }
    }

    public function test_artwork_cards_link_to_their_details_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/artworks')
            ->assertOk()
            ->assertSee(route('artworks.show', ['slug' => 'stranger-things-poster']), false);

        $this->get('/home')
            ->assertOk()
            ->assertSee(route('artworks.show', ['slug' => 'fluid-geometric']), false);

        $this->get('/about')
            ->assertOk()
            ->assertSee(route('artworks.show', ['slug' => 'minimalist-organic']), false);

        $this->get('/favorites')
            ->assertOk()
            ->assertSee(route('artworks.show', ['slug' => 'colorful-abstract']), false);
    }
}
