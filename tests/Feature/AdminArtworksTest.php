<?php

namespace Tests\Feature;

use App\Models\Artwork;
use App\Models\Order;
use App\Models\User;
use Database\Seeders\ArtworkSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminArtworksTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_browse_and_filter_the_artwork_catalog(): void
    {
        $this->seed(ArtworkSeeder::class);
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.artworks.index', ['search' => 'Fluid', 'category' => 'Abstract']))
            ->assertOk()
            ->assertSee('Artwork catalog')
            ->assertSee('Fluid Geometric')
            ->assertDontSee('Stranger Things Poster')
            ->assertSee('id="open-artwork-modal"', false)
            ->assertSee('id="artwork-modal"', false);
    }

    public function test_admin_can_preview_artwork_details_in_a_drawer(): void
    {
        $this->seed(ArtworkSeeder::class);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.artworks.index'))
            ->assertOk()
            ->assertSee('id="artwork-preview-drawer"', false)
            ->assertSee('data-artwork-preview', false)
            ->assertSee('aria-label="Preview Fluid Geometric"', false)
            ->assertSee('data-title="Fluid Geometric"', false)
            ->assertSee('data-artist="Cristine Joy Montes"', false)
            ->assertSee('data-category="Abstract"', false)
            ->assertSee('data-price="₱28.56"', false)
            ->assertSee('data-status="published"', false)
            ->assertSee('A study in movement and color', false)
            ->assertSee('View client page')
            ->assertSee('Edit artwork')
            ->assertSee('id="artwork-edit-modal"', false)
            ->assertSee('data-open-edit-modal', false);
    }

    public function test_admin_edit_action_opens_catalog_modal_and_validation_reopens_it_with_old_input(): void
    {
        $this->seed(ArtworkSeeder::class);
        $admin = User::factory()->create(['role' => 'admin']);
        $artwork = Artwork::query()->where('slug', 'fluid-geometric')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.artworks.edit', $artwork))
            ->assertRedirect(route('admin.artworks.index'));

        $this->followingRedirects()
            ->from(route('admin.artworks.index'))
            ->put(route('admin.artworks.update', $artwork), [
                '_form_context' => 'edit-artwork',
                '_artwork_id' => $artwork->id,
                'title' => 'Unsubmitted artwork title',
                'artist_name' => $artwork->artist_name,
                'category' => '',
                'price' => '28.56',
                'description' => $artwork->description,
                'status' => 'published',
            ])
            ->assertOk()
            ->assertSee('id="artwork-edit-modal"', false)
            ->assertSee('Please check the highlighted fields.')
            ->assertSee('name="_artwork_id" value="'.$artwork->id.'"', false)
            ->assertSee('name="title" value="Unsubmitted artwork title"', false)
            ->assertSee('artworkEditModal.showModal();', false);
    }

    public function test_admin_can_open_add_artwork_modal_and_paginate_catalog(): void
    {
        $this->seed(ArtworkSeeder::class);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.artworks.create'))
            ->assertRedirect(route('admin.artworks.index'));

        $this->get(route('admin.artworks.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('Artwork title')
            ->assertSee('Artwork image')
            ->assertSee('name="_form_context" value="artwork"', false)
            ->assertSee('Showing 9–10 of 10 artworks');
    }

    public function test_artwork_validation_returns_to_catalog_and_reopens_modal_with_old_input(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->followingRedirects()
            ->from(route('admin.artworks.index'))
            ->post(route('admin.artworks.store'), [
                '_form_context' => 'artwork',
                'title' => 'Remember this title',
                'artist_name' => 'Ari Artist',
                'category' => 'Abstract',
                'price' => '19.95',
                'description' => 'An artwork with a missing image.',
                'status' => 'draft',
            ])
            ->assertOk()
            ->assertSee('Please check the highlighted fields.')
            ->assertSee('value="Remember this title"', false)
            ->assertSee('artworkModal.showModal();', false);
    }

    public function test_admin_can_create_an_artwork_and_upload_its_image(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.artworks.store'), [
            'title' => 'New Canvas',
            'artist_name' => 'Ari Artist',
            'category' => 'Abstract',
            'price' => '19.95',
            'description' => 'A new digital artwork for the catalog.',
            'status' => 'published',
            'canvas_size' => '24 × 36 in',
            'image' => $this->fakeArtworkImage('new-canvas.png'),
        ]);

        $artwork = Artwork::query()->where('title', 'New Canvas')->firstOrFail();

        $response->assertRedirect(route('admin.artworks.index'))
            ->assertSessionHas('status', 'Artwork added to the catalog.');
        $this->assertSame('new-canvas', $artwork->slug);
        $this->assertSame('storage/artworks/'.basename($artwork->image_path), $artwork->image_path);
        Storage::disk('public')->assertExists(substr($artwork->image_path, strlen('storage/')));
        Storage::disk('local')->assertExists($artwork->original_path);
        $this->assertStringStartsWith('artworks/originals/', $artwork->original_path);
        $this->assertSame(1, $artwork->image_width);
        $this->assertSame(1, $artwork->image_height);
        $this->assertSame('1:1', $artwork->aspectRatio());
        $this->assertSame('png', $artwork->file_format);
        $this->assertSame('24 × 36 in', $artwork->canvas_size);

        $this->get(route('artworks.show', ['slug' => $artwork->slug]))
            ->assertOk()
            ->assertSee('1 × 1 px')
            ->assertSee('24 × 36 in');
    }

    public function test_upload_errors_are_explained_to_the_admin(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->from(route('admin.artworks.index'))
            ->post(route('admin.artworks.store'), [
                '_form_context' => 'artwork',
                'title' => 'Wrong File',
                'artist_name' => 'Ari Artist',
                'category' => 'Abstract',
                'price' => '19.95',
                'description' => 'A file that is not an image.',
                'status' => 'draft',
                'image' => UploadedFile::fake()->createWithContent('notes.txt', 'plain text'),
            ])
            ->assertSessionHasErrors(['image' => 'The file must be an image.']);

        $this->assertDatabaseMissing('artworks', ['title' => 'Wrong File']);
    }

    public function test_artwork_with_orders_cannot_be_deleted(): void
    {
        $this->seed(ArtworkSeeder::class);
        $artwork = Artwork::query()->firstOrFail();
        $order = Order::query()->create([
            'order_number' => 'ART-000900',
            'customer_name' => 'Buyer',
            'customer_email' => 'buyer@example.com',
            'total' => 10,
            'status' => 'completed',
        ]);
        $order->items()->create([
            'artwork_id' => $artwork->id,
            'title' => $artwork->title,
            'artist_name' => $artwork->artist_name,
            'image_path' => $artwork->image_path,
            'unit_price' => 10,
        ]);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->delete(route('admin.artworks.destroy', $artwork))
            ->assertSessionHasErrors('artwork');

        $this->assertModelExists($artwork);
    }

    public function test_new_artwork_slugs_are_unique(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        $this->seed(ArtworkSeeder::class);
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->post(route('admin.artworks.store'), [
                'title' => 'Fluid Geometric',
                'artist_name' => 'Ari Artist',
                'category' => 'Abstract',
                'price' => '19.95',
                'description' => 'A second artwork with a matching title.',
                'status' => 'draft',
                'image' => $this->fakeArtworkImage('fluid-copy.png'),
            ])
            ->assertRedirect(route('admin.artworks.index'));

        $this->assertDatabaseHas('artworks', [
            'slug' => 'fluid-geometric-2',
            'status' => 'draft',
        ]);
    }

    public function test_admin_can_update_artwork_details_without_reuploading_the_image(): void
    {
        $this->seed(ArtworkSeeder::class);
        $artwork = Artwork::query()->where('slug', 'fluid-geometric')->firstOrFail();

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->put(route('admin.artworks.update', $artwork), [
                'title' => 'Fluid Geometric Updated',
                'artist_name' => $artwork->artist_name,
                'category' => $artwork->category,
                'price' => '29.50',
                'description' => $artwork->description,
                'status' => 'archived',
            ])
            ->assertRedirect(route('admin.artworks.index'));

        $this->assertDatabaseHas('artworks', [
            'id' => $artwork->id,
            'slug' => 'fluid-geometric-updated',
            'price' => '29.50',
            'status' => 'archived',
            'image_path' => 'images/artwork-fluid-geometric.png',
        ]);
    }

    public function test_admin_can_delete_an_artwork_and_its_uploaded_image(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        $path = $this->fakeArtworkImage('remove-me.png')->store('artworks', 'public');
        $artwork = Artwork::query()->create([
            'slug' => 'remove-me',
            'title' => 'Remove Me',
            'artist_name' => 'Ari Artist',
            'category' => 'Abstract',
            'price' => 10,
            'image_path' => 'storage/'.$path,
            'description' => 'This artwork will be removed.',
            'status' => 'draft',
        ]);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->delete(route('admin.artworks.destroy', $artwork))
            ->assertRedirect(route('admin.artworks.index'));

        $this->assertDatabaseMissing('artworks', ['id' => $artwork->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_clients_cannot_manage_artworks_and_guests_are_redirected(): void
    {
        $artwork = Artwork::query()->create([
            'slug' => 'restricted',
            'title' => 'Restricted Artwork',
            'artist_name' => 'Ari Artist',
            'category' => 'Abstract',
            'price' => 10,
            'image_path' => 'images/artwork-fluid-geometric.png',
            'description' => 'A protected catalog record.',
            'status' => 'published',
        ]);

        $this->actingAs(User::factory()->create(['role' => 'client']))
            ->get(route('admin.artworks.index'))
            ->assertForbidden();

        auth()->logout();

        $this->get(route('admin.artworks.index'))
            ->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['role' => 'client']))
            ->delete(route('admin.artworks.destroy', $artwork))
            ->assertForbidden();
    }

    private function fakeArtworkImage(string $filename): UploadedFile
    {
        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGNgYAAAAAMAASsJTYQAAAAASUVORK5CYII=',
            true,
        );

        return UploadedFile::fake()->createWithContent($filename, $png);
    }
}
