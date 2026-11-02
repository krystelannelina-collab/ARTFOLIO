<?php

namespace Tests\Feature;

use App\Models\Artwork;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\ArtworkSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class AdminCategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_category_metrics_and_catalog_counts(): void
    {
        $this->seed(ArtworkSeeder::class);
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.categories.index'))
            ->assertOk()
            ->assertSee('Categories')
            ->assertSee('Shapes, color, and expressive compositions')
            ->assertSee('Showing 1–5 of 5 categories')
            ->assertViewHas('stats', [
                'total' => 5,
                'visible' => 5,
                'publishedArtworks' => 10,
                'uncategorized' => 0,
            ]);
    }

    public function test_admin_can_add_and_rename_categories_without_losing_artwork_assignments(): void
    {
        $this->seed(ArtworkSeeder::class);
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                '_form_context' => 'new-category',
                'name' => '  Modern Art  ',
                'description' => 'Contemporary visual work',
            ])
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('status', 'Category added to your catalog.');

        $category = Category::query()->where('name', 'Modern Art')->firstOrFail();
        $this->assertSame('modern-art', $category->slug);
        $this->assertTrue($category->is_visible);

        $artwork = Artwork::query()->where('category', 'Abstract')->firstOrFail();
        $this->put(route('admin.categories.update', $category), [
            '_form_context' => 'edit-category',
            '_category_id' => $category->id,
            'name' => 'Contemporary Art',
            'description' => 'Updated category description',
        ])->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Contemporary Art',
            'slug' => 'contemporary-art',
            'description' => 'Updated category description',
        ]);
        $this->assertDatabaseHas('artworks', [
            'id' => $artwork->id,
            'category' => 'Abstract',
        ]);

        $artwork->update(['category' => 'Contemporary Art']);
        $this->put(route('admin.categories.update', $category), [
            'name' => 'Contemporary Work',
            'description' => 'Renamed category',
        ])->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('artworks', [
            'id' => $artwork->id,
            'category' => 'Contemporary Work',
        ]);
    }

    public function test_category_names_are_unique_and_validation_reopens_the_correct_modal(): void
    {
        $this->seed(ArtworkSeeder::class);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('admin.categories.index'))
            ->followingRedirects()
            ->post(route('admin.categories.store'), [
                '_form_context' => 'new-category',
                'name' => 'abstract',
                'description' => '',
            ])
            ->assertOk()
            ->assertSee('A category with this name already exists.')
            ->assertSee('newCategoryModal.showModal();', false);

        $category = Category::query()->where('name', 'Abstract')->firstOrFail();
        $this->from(route('admin.categories.index'))
            ->followingRedirects()
            ->put(route('admin.categories.update', $category), [
                '_form_context' => 'edit-category',
                '_category_id' => $category->id,
                'name' => '',
                'description' => '',
            ])
            ->assertOk()
            ->assertSee('editCategoryModal.showModal();', false)
            ->assertSee('name="_category_id" value="'.$category->id.'"', false);
    }

    public function test_category_search_visibility_filter_and_sort_are_applied(): void
    {
        $this->seed(ArtworkSeeder::class);
        $digital = Category::query()->where('name', 'Digital')->firstOrFail();
        $digital->update(['is_visible' => false]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.categories.index', ['search' => 'Dig', 'visibility' => 'hidden']))
            ->assertOk()
            ->assertSee('Digital')
            ->assertSee('Hidden')
            ->assertDontSee('Abstract');

        $this->get(route('admin.categories.index', ['visibility' => 'visible', 'sort' => 'name_desc']))
            ->assertOk()
            ->assertViewHas('categories', fn (LengthAwarePaginator $categories): bool => $categories->pluck('name')->all() === [
                'Typography',
                'Poster',
                'Pixel',
                'Abstract',
            ]);
    }

    public function test_hiding_a_category_removes_its_artworks_from_client_browsing_and_cart(): void
    {
        $this->seed(ArtworkSeeder::class);
        $category = Category::query()->where('name', 'Abstract')->firstOrFail();
        $artwork = Artwork::query()->where('category', 'Abstract')->firstOrFail();
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);

        $this->actingAs($admin)
            ->patch(route('admin.categories.visibility', $category))
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('status', 'Category hidden from storefront navigation. Artwork assignments were kept.');

        $this->actingAs($client)
            ->get(route('artworks'))
            ->assertOk()
            ->assertDontSee('Fluid Geometric')
            ->assertDontSee('category=Abstract', false);

        $this->get(route('artworks.show', $artwork->slug))->assertNotFound();
        $this->post(route('cart.add', $artwork->slug))->assertNotFound();

        $this->actingAs($admin)
            ->patch(route('admin.categories.visibility', $category))
            ->assertRedirect(route('admin.categories.index'));
        $this->assertTrue($category->fresh()->is_visible);
    }

    public function test_legacy_artwork_without_a_category_record_is_counted_as_uncategorized(): void
    {
        Artwork::query()->create([
            'slug' => 'orphan-category-artwork',
            'title' => 'Orphan category artwork',
            'artist_name' => 'Test Artist',
            'category' => 'Legacy Category',
            'price' => 10,
            'image_path' => 'images/artwork-fluid-geometric.png',
            'description' => 'Existing artwork with a category not yet in the category directory.',
            'status' => 'published',
        ]);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.categories.index'))
            ->assertOk()
            ->assertViewHas('stats', fn (array $stats): bool => $stats['uncategorized'] === 1);
    }

    public function test_non_admins_cannot_access_category_management(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'client']))
            ->get(route('admin.categories.index'))
            ->assertForbidden();

        auth()->logout();

        $this->get(route('admin.categories.index'))
            ->assertRedirect(route('login'));
    }
}
