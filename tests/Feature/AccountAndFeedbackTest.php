<?php

namespace Tests\Feature;

use App\Models\Artwork;
use App\Models\Feedback;
use App\Models\User;
use Database\Seeders\ArtworkSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountAndFeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_signup_requires_username_matching_passwords_and_accepted_terms(): void
    {
        User::factory()->create(['username' => 'takenname']);

        $this->from(route('register'))
            ->post(route('register'), [
                'name' => 'New Collector',
                'username' => 'takenname',
                'email' => 'new@example.com',
                'password' => 'password123',
                'password_confirmation' => 'different123',
            ])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors([
                'username' => 'That username is already taken.',
                'password' => 'The passwords do not match.',
                'terms' => 'You must agree to the Terms and Conditions to create an account.',
            ]);

        $this->assertDatabaseMissing('users', ['email' => 'new@example.com']);

        $this->post(route('register'), [
            'name' => 'New Collector',
            'username' => 'New_Collector',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', ['email' => 'new@example.com', 'username' => 'new_collector']);
    }

    public function test_removing_every_favorite_does_not_bring_starter_favorites_back(): void
    {
        $this->seed(ArtworkSeeder::class);
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('favorites'))->assertOk();
        $starterSlugs = $user->favoriteArtworks()->pluck('slug');
        $this->assertNotEmpty($starterSlugs);

        foreach ($starterSlugs as $slug) {
            $this->deleteJson(route('favorites.destroy', ['slug' => $slug]))
                ->assertOk()
                ->assertJson(['favorited' => false]);
        }

        $this->flushSession();
        $this->actingAs($user->fresh())->get(route('favorites'))->assertOk();

        $this->assertSame(0, $user->favoriteArtworks()->count());
    }

    public function test_favorite_toggle_reports_new_state_for_the_heart_button(): void
    {
        $this->seed(ArtworkSeeder::class);
        $user = User::factory()->create(['favorites_seeded_at' => now()]);
        $artwork = Artwork::query()->firstOrFail();

        $this->actingAs($user)
            ->postJson(route('favorites.toggle', ['slug' => $artwork->slug]))
            ->assertOk()
            ->assertJson(['favorited' => true, 'count' => 1]);

        $this->postJson(route('favorites.toggle', ['slug' => $artwork->slug]))
            ->assertOk()
            ->assertJson(['favorited' => false, 'count' => 0]);
    }

    public function test_favorite_can_be_removed_after_its_artwork_is_unpublished(): void
    {
        $this->seed(ArtworkSeeder::class);
        $user = User::factory()->create(['favorites_seeded_at' => now()]);
        $artwork = Artwork::query()->firstOrFail();
        $user->favoriteArtworks()->attach($artwork->id);
        $artwork->update(['status' => 'draft']);

        $this->actingAs($user)
            ->postJson(route('favorites.toggle', ['slug' => $artwork->slug]))
            ->assertOk()
            ->assertJson(['favorited' => false]);

        $this->assertSame(0, $user->favoriteArtworks()->count());
    }

    public function test_about_page_shows_contact_placeholders_and_accepts_feedback(): void
    {
        config(['artfolio.contact.email' => 'hello@artfolio.test', 'artfolio.contact.phone' => null]);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('about'))
            ->assertOk()
            ->assertSee('mailto:hello@artfolio.test', false)
            ->assertSee('Contact number coming soon')
            ->assertSee('Send us feedback');

        $this->post(route('feedback.store'), [
            'name' => $user->name,
            'email' => $user->email,
            'rating' => 5,
            'message' => 'Lovely collection, easy checkout.',
        ])->assertRedirect(route('about').'#feedback');

        $this->assertDatabaseHas('feedback', ['user_id' => $user->id, 'rating' => 5]);

        $this->post(route('feedback.store'), ['name' => '', 'email' => 'bad', 'message' => 'short'])
            ->assertSessionHasErrors(['name', 'email', 'message']);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Lovely collection, easy checkout.');

        $this->assertSame(1, Feedback::query()->count());
    }
}
