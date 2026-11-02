<?php

namespace Tests\Feature;

use App\Models\Artwork;
use App\Models\User;
use Database\Seeders\ArtworkSeeder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminArtistsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_browse_artist_directory_and_catalog_metrics(): void
    {
        $this->seed(ArtworkSeeder::class);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.artists.index'))
            ->assertOk()
            ->assertSee('Artists')
            ->assertSee('Active artists')
            ->assertSee('Total artists')
            ->assertSee('Published artworks')
            ->assertSee('Draft artworks')
            ->assertSee('Cristine Joy Montes')
            ->assertSee('Kalvin Dave Ramos')
            ->assertSee(route('admin.artworks.index', ['search' => 'Cristine Joy Montes']), false)
            ->assertSee('aria-current="page"', false)
            ->assertViewHas('stats', [
                'activeArtists' => 4,
                'totalArtists' => 4,
                'publishedArtworks' => 10,
                'draftArtworks' => 0,
                'totalArtworks' => 10,
            ]);
    }

    public function test_admin_can_search_and_sort_the_artist_directory(): void
    {
        $this->seed(ArtworkSeeder::class);
        Artwork::query()->create([
            'slug' => 'ari-first-artwork',
            'title' => 'Ari First Artwork',
            'artist_name' => 'Ari Artist',
            'category' => 'Digital',
            'price' => 10,
            'image_path' => 'images/artwork-fluid-geometric.png',
            'description' => 'A new artist contribution.',
            'status' => 'draft',
        ]);
        Artwork::query()->create([
            'slug' => 'ari-second-artwork',
            'title' => 'Ari Second Artwork',
            'artist_name' => 'Ari Artist',
            'category' => 'Digital',
            'price' => 12,
            'image_path' => 'images/artwork-fluid-geometric.png',
            'description' => 'Another contribution by the same artist.',
            'status' => 'draft',
        ]);

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->get(route('admin.artists.index', ['sort' => 'most_artworks']))
            ->assertOk()
            ->assertViewHas('artists', function (LengthAwarePaginator $artists): bool {
                return $artists->pluck('artist_name')->all() === [
                    'Kalvin Dave Ramos',
                    'Gian Gomez',
                    'Ari Artist',
                    'Cristine Joy Montes',
                    'Krystel Anne Lina',
                ];
            })
            ->assertViewHas('stats', fn (array $stats): bool => $stats['totalArtists'] === 5 && $stats['activeArtists'] === 4);

        $this->actingAs($admin)
            ->get(route('admin.artists.index', ['search' => 'Ari', 'sort' => 'most_artworks']))
            ->assertOk()
            ->assertSee('Ari Artist')
            ->assertDontSee('Gian Gomez')
            ->assertSee('name="sort"', false)
            ->assertSee('selected>Most artworks', false);
    }

    public function test_admin_artist_directory_handles_no_artworks_and_no_search_matches(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.artists.index'))
            ->assertOk()
            ->assertSee('No artists in the catalog yet');

        Artwork::query()->create([
            'slug' => 'known-artist-work',
            'title' => 'Known Artist Work',
            'artist_name' => 'Known Artist',
            'category' => 'Digital',
            'price' => 10,
            'image_path' => 'images/artwork-fluid-geometric.png',
            'description' => 'A published contribution.',
            'status' => 'published',
        ]);

        $this->get(route('admin.artists.index', ['search' => 'Unknown']))
            ->assertOk()
            ->assertSee('No matching artists')
            ->assertSee('Reset search');
    }

    public function test_clients_cannot_access_artist_directory_and_guests_are_redirected(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'client']))
            ->get(route('admin.artists.index'))
            ->assertForbidden();

        auth()->logout();

        $this->get(route('admin.artists.index'))
            ->assertRedirect(route('login'));
    }
}
