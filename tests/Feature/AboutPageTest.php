<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\ArtworkSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_client_can_view_about_page_and_navigate_to_artworks_and_artists(): void
    {
        $this->seed(ArtworkSeeder::class);

        $response = $this->actingAs(User::factory()->create())->get('/about');

        $response->assertOk()
            ->assertSee('<title>ARTFOLIO - About Us</title>', false)
            ->assertSee('Find art that', false)
            ->assertSee('Creativity deserves to be', false)
            ->assertSee('Fluid Geometric')
            ->assertSee('Cristine Joy Montes')
            ->assertSee('Follow what moves you.')
            ->assertSee('Keep what you love')
            ->assertSee('href="'.url('/about').'" class="nav-item-link active"', false)
            ->assertSee('href="'.url('/artworks').'" class="about-button about-button-primary"', false)
            ->assertSee('href="'.url('/artists').'" class="about-button about-button-secondary"', false)
            ->assertDontSee('artist-cristine-joy-montes.png')
            ->assertDontSee('Meet Cristine Joy Montes');
    }

    public function test_guest_is_redirected_to_login_when_visiting_about_page(): void
    {
        $this->get('/about')->assertRedirect('/login');
    }
}
