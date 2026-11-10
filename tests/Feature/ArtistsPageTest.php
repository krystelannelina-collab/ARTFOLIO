<?php

namespace Tests\Feature;

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
