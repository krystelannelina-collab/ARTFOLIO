<?php

namespace Tests\Feature;

use App\Models\Artwork;
use App\Models\User;
use Database\Seeders\ArtworkSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtworkDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_client_can_view_an_artwork_and_related_artworks(): void
    {
        $this->seed(ArtworkSeeder::class);

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

    public function test_published_admin_artwork_can_be_previewed_by_clients(): void
    {
        $artwork = Artwork::query()->create([
            'slug' => 'admin-added-artwork',
            'title' => 'Admin Added Artwork',
            'artist_name' => 'Ari Artist',
            'category' => 'Digital',
            'price' => 19.95,
            'image_path' => 'images/artwork-fluid-geometric.png',
            'description' => 'A digital artwork added from the admin catalog.',
            'status' => 'published',
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('artworks.show', ['slug' => $artwork->slug]))
            ->assertOk()
            ->assertSee('Admin Added Artwork')
            ->assertSee('Ari Artist')
            ->assertSee('A digital artwork added from the admin catalog.');
    }

    public function test_draft_artwork_is_only_visible_to_admins(): void
    {
        $artwork = Artwork::query()->create([
            'slug' => 'draft-artwork',
            'title' => 'Draft Artwork',
            'artist_name' => 'Ari Artist',
            'category' => 'Digital',
            'price' => 19.95,
            'image_path' => 'images/artwork-fluid-geometric.png',
            'description' => 'A draft artwork preview.',
            'status' => 'draft',
        ]);

        $this->actingAs(User::factory()->create(['role' => 'client']))
            ->get(route('artworks.show', ['slug' => $artwork->slug]))
            ->assertNotFound();

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('artworks.show', ['slug' => $artwork->slug]))
            ->assertOk()
            ->assertSee('Draft Artwork');
    }

    public function test_public_artworks_page_lists_published_admin_artworks_but_not_drafts(): void
    {
        Artwork::query()->create([
            'slug' => 'published-new-artwork',
            'title' => 'Published New Artwork',
            'artist_name' => 'Ari Artist',
            'category' => 'Digital Paintings',
            'price' => 19.95,
            'image_path' => 'images/artwork-fluid-geometric.png',
            'description' => 'A newly published catalog piece.',
            'status' => 'published',
        ]);
        Artwork::query()->create([
            'slug' => 'draft-new-artwork',
            'title' => 'Draft New Artwork',
            'artist_name' => 'Ari Artist',
            'category' => 'Digital Paintings',
            'price' => 20,
            'image_path' => 'images/artwork-fluid-geometric.png',
            'description' => 'A draft that is not for sale yet.',
            'status' => 'draft',
        ]);

        $this->actingAs(User::factory()->create())
            ->get('/artworks')
            ->assertOk()
            ->assertSee('Published New Artwork')
            ->assertSee(route('artworks.show', ['slug' => 'published-new-artwork']), false)
            ->assertSee(url('/artworks?category=Digital+Paintings'), false)
            ->assertDontSee('Draft New Artwork');
    }

    public function test_guest_is_redirected_to_login_from_artwork_details(): void
    {
        $this->get('/artworks/stranger-things-poster')
            ->assertRedirect('/login');
    }

    public function test_all_catalog_artworks_have_a_detail_page(): void
    {
        $this->seed(ArtworkSeeder::class);
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
        $this->seed(ArtworkSeeder::class);
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

    public function test_storefront_pages_reflect_database_changes_and_favorites_persist(): void
    {
        $this->seed(ArtworkSeeder::class);
        $user = User::factory()->create();

        $artwork = Artwork::query()->where('slug', 'fluid-geometric')->firstOrFail();
        $artwork->update([
            'title' => 'Database Updated Geometric',
            'price' => 44.00,
        ]);

        $this->actingAs($user)
            ->get('/home')
            ->assertOk()
            ->assertSee('Database Updated Geometric');

        $this->get('/artworks')
            ->assertOk()
            ->assertSee('Database Updated Geometric')
            ->assertSee('$44.00');

        $this->get('/favorites')
            ->assertOk()
            ->assertSee('Database Updated Geometric');

        $this->deleteJson(route('favorites.destroy', ['slug' => 'fluid-geometric']))
            ->assertOk()
            ->assertJson(['removed' => true, 'count' => 5]);

        $this->get('/favorites')
            ->assertOk()
            ->assertDontSee('Database Updated Geometric');

        $this->postJson(route('favorites.toggle', ['slug' => 'the-one-poster']))
            ->assertOk()
            ->assertJson(['saved' => true, 'count' => 6]);

        $this->get('/favorites')
            ->assertOk()
            ->assertSee('The One Poster');
    }
}
