<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BannerArtTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_banner_shows_the_artwork_without_a_label(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('class="hero-art hero-art-home" aria-hidden="true"', false)
            ->assertDontSee('hero-art-label', false)
            ->assertDontSee('hero-banner-overlay', false);
    }

    public function test_about_banner_shows_the_artwork_without_a_label(): void
    {
        $this->get(route('about'))
            ->assertOk()
            ->assertSee('class="hero-art hero-art-about" aria-hidden="true"', false)
            ->assertDontSee('hero-art-label', false)
            ->assertDontSee('about-hero-overlay', false)
            ->assertDontSee('01 / 04');
    }
}
