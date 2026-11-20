<?php

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\Artwork;
use App\Models\User;
use Database\Seeders\ArtworkSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArtistsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_artist_profile_is_listed_with_their_photo(): void
    {
        $this->seed(ArtworkSeeder::class);

        $this->actingAs(User::factory()->create())
            ->get(route('artists'))
            ->assertOk()
            ->assertSee('Cristine Joy Montes')
            ->assertSee('Gian Gomez')
            ->assertSee('Kalvin Dave Ramos')
            ->assertSee('Krystel Anne Lina')
            ->assertSee('images/artist-krystel-anne-lina.png', false)
            ->assertDontSee('SAMPLE PROFILE PHOTO');
    }

    public function test_view_artist_opens_a_profile_modal_with_headline_and_featured_artworks(): void
    {
        $this->seed(ArtworkSeeder::class);
        $artist = Artist::query()->where('name', 'Gian Gomez')->firstOrFail();
        $artwork = Artwork::query()->where('artist_name', 'Gian Gomez')->where('status', 'published')->firstOrFail();

        $this->actingAs(User::factory()->create())
            ->get(route('artists'))
            ->assertOk()
            ->assertSee('data-bs-target="#artist-modal-'.$artist->id.'"', false)
            ->assertSee('id="artist-modal-'.$artist->id.'"', false)
            ->assertSee('Professional artist')
            ->assertSee('Artist’s featured arts')
            ->assertSee(route('artworks.show', ['slug' => $artwork->slug]), false);
    }

    public function test_profile_modal_shows_only_the_social_links_an_artist_has(): void
    {
        $this->seed(ArtworkSeeder::class);
        Artist::query()->where('name', 'Kalvin Dave Ramos')->update([
            'headline' => 'Digital illustrator',
            'github_url' => 'https://github.com/example-artist',
            'email' => 'artist@example.test',
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('artists'))
            ->assertOk()
            ->assertSee('Digital illustrator')
            ->assertSee('href="https://github.com/example-artist"', false)
            ->assertSee('href="mailto:artist@example.test"', false)
            ->assertDontSee('aria-label="Kalvin Dave Ramos on Facebook"', false)
            ->assertDontSee('aria-label="Kalvin Dave Ramos on Instagram"', false);
    }

    public function test_artist_without_published_artworks_still_appears(): void
    {
        $this->seed(ArtworkSeeder::class);
        Artwork::query()->where('artist_name', 'Krystel Anne Lina')->delete();

        $this->actingAs(User::factory()->create())
            ->get(route('artists'))
            ->assertOk()
            ->assertSee('Krystel Anne Lina')
            ->assertSee('No published artworks yet')
            ->assertSee('4 artists');
    }
}
