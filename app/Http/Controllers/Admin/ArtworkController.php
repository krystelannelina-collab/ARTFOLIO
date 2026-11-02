<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RuntimeException;

class ArtworkController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['published', 'draft', 'archived'])],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'title', 'price_low', 'price_high'])],
        ]);
        $query = Artwork::query();
        $search = trim($filters['search'] ?? '');

        if ($search !== '') {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('artist_name', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $sort = $filters['sort'] ?? 'newest';
        match ($sort) {
            'oldest' => $query->oldest(),
            'title' => $query->orderBy('title'),
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            default => $query->latest(),
        };

        $editArtwork = new Artwork(['status' => 'draft']);
        if (old('_form_context') === 'edit-artwork' && old('_artwork_id')) {
            $editArtwork = Artwork::query()->find(old('_artwork_id')) ?? $editArtwork;
        }

        return view('admin.artworks.index', [
            'artworks' => $query->paginate(8)->withQueryString(),
            'categories' => Category::query()->orderBy('name')->get(),
            'newArtwork' => new Artwork(['status' => 'draft']),
            'editArtwork' => $editArtwork,
            'editModalOpen' => old('_form_context') === 'edit-artwork' && $editArtwork->exists,
            'filters' => $filters,
            'hasFilters' => $search !== '' || ! empty($filters['category']) || ! empty($filters['status']),
            'sort' => $sort,
            'stats' => [
                'total' => Artwork::query()->count(),
                'published' => Artwork::query()->where('status', 'published')->count(),
                'draft' => Artwork::query()->where('status', 'draft')->count(),
            ],
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.artworks.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateArtwork($request, imageIsRequired: true);
        $image = $data['image'];
        unset($data['image']);
        $data['image_path'] = $this->storeImage($image);
        $data['slug'] = $this->uniqueSlug($data['title']);

        $artwork = Artwork::query()->create($data);
        $this->ensureArtistProfileExists($artwork->artist_name, $artwork->category, $artwork->image_path);

        return redirect()->route('admin.artworks.index')
            ->with('status', 'Artwork added to the catalog.');
    }

    public function edit(Artwork $artwork): RedirectResponse
    {
        return redirect()->route('admin.artworks.index');
    }

    public function update(Request $request, Artwork $artwork): RedirectResponse
    {
        $data = $this->validateArtwork($request, imageIsRequired: false);
        $previousTitle = $artwork->title;
        $previousImagePath = $artwork->image_path;

        if (isset($data['image'])) {
            $data['image_path'] = $this->storeImage($data['image']);
            unset($data['image']);
        }

        if ($data['title'] !== $previousTitle) {
            $data['slug'] = $this->uniqueSlug($data['title'], $artwork);
        }

        $artwork->update($data);
        $this->ensureArtistProfileExists($artwork->artist_name, $artwork->category, $artwork->image_path);

        if (isset($data['image_path'])) {
            $this->removeStoredImage($previousImagePath);
        }

        return redirect()->route('admin.artworks.index')
            ->with('status', 'Artwork details updated.');
    }

    public function destroy(Artwork $artwork): RedirectResponse
    {
        $imagePath = $artwork->image_path;
        $artwork->delete();
        $this->removeStoredImage($imagePath);

        return redirect()->route('admin.artworks.index')
            ->with('status', 'Artwork removed from the catalog.');
    }

    private function validateArtwork(Request $request, bool $imageIsRequired): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'artist_name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100', Rule::exists('categories', 'name')],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'description' => ['required', 'string', 'max:5000'],
            'status' => ['required', Rule::in(['published', 'draft', 'archived'])],
            'image' => $imageIsRequired
                ? ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']
                : ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
    }

    private function storeImage(UploadedFile $image): string
    {
        $path = $image->store('artworks', 'public');

        if ($path === false) {
            throw new RuntimeException('The artwork image could not be saved.');
        }

        return "storage/{$path}";
    }

    private function uniqueSlug(string $title, ?Artwork $artwork = null): string
    {
        $baseSlug = Str::slug($title) ?: 'artwork';
        $slug = $baseSlug;
        $suffix = 2;

        while (Artwork::query()
            ->where('slug', $slug)
            ->when($artwork !== null, fn (Builder $query): Builder => $query->where('id', '!=', $artwork->getKey()))
            ->exists()) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function removeStoredImage(string $path): void
    {
        if (! str_starts_with($path, 'storage/artworks/')) {
            return;
        }

        $disk = Storage::disk('public');
        $storedPath = substr($path, strlen('storage/'));

        if ($disk->exists($storedPath) && ! $disk->delete($storedPath)) {
            throw new RuntimeException('The artwork was saved, but its previous uploaded image could not be removed.');
        }
    }

    private function ensureArtistProfileExists(string $artistName, string $category, string $imagePath): void
    {
        $trimmedName = trim($artistName);
        if ($trimmedName === '' || Artist::query()->whereRaw('LOWER(name) = ?', [mb_strtolower($trimmedName)])->exists()) {
            return;
        }

        $baseSlug = Str::slug($trimmedName) ?: 'artist';
        $slug = $baseSlug;
        $suffix = 2;

        while (Artist::query()->where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        Artist::query()->create([
            'name' => $trimmedName,
            'slug' => $slug,
            'bio' => 'Explore original '.strtolower($category).' works in the ARTFOLIO collection.',
            'image_path' => $imagePath,
            'sort_order' => ((int) Artist::query()->max('sort_order')) + 1,
        ]);
    }
}
