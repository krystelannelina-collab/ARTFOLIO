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
    private const MAX_UPLOAD_KILOBYTES = 30720;

    private const PREVIEW_MAX_EDGE = 1600;

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['published', 'draft', 'archived'])],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'title', 'price_low', 'price_high'])],
        ]);
        $query = $this->unsoldArtworks();
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
                'total' => $this->unsoldArtworks()->count(),
                'published' => $this->unsoldArtworks()->where('status', 'published')->count(),
                'draft' => $this->unsoldArtworks()->where('status', 'draft')->count(),
                'sold' => Artwork::query()->where('availability', Artwork::AVAILABILITY_SOLD)->count(),
            ],
        ]);
    }

    /**
     * Artworks still in the catalog. Sold one-of-one pieces leave this list and
     * are tracked on the Sold items page instead.
     *
     * @return Builder<Artwork>
     */
    private function unsoldArtworks(): Builder
    {
        return Artwork::query()->where('availability', '!=', Artwork::AVAILABILITY_SOLD);
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
        try {
            $data = [...$data, ...$this->storeImage($image)];
        } catch (RuntimeException $exception) {
            return back()->withInput()->withErrors(['image' => $exception->getMessage().' Check that the storage folder is writable and try again.']);
        }
        $data['slug'] = $this->uniqueSlug($data['title']);

        $this->ensureArtistProfileExists($data['artist_name'], $data['category'], $data['image_path']);
        Artwork::query()->create($data);

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
        $previousOriginalPath = $artwork->original_path;
        $hasNewImage = isset($data['image']);

        if ($hasNewImage) {
            try {
                $data = [...$data, ...$this->storeImage($data['image'])];
            } catch (RuntimeException $exception) {
                return back()->withInput()->withErrors(['image' => $exception->getMessage().' Check that the storage folder is writable and try again.']);
            }
        }
        unset($data['image']);

        if ($data['title'] !== $previousTitle) {
            $data['slug'] = $this->uniqueSlug($data['title'], $artwork);
        }

        $this->ensureArtistProfileExists($data['artist_name'], $data['category'], $data['image_path'] ?? $artwork->image_path);
        $artwork->update($data);

        if ($hasNewImage) {
            $this->removeStoredImage($previousImagePath);
            $this->removeOriginalFile($previousOriginalPath);
        }

        return redirect()->route('admin.artworks.index')
            ->with('status', 'Artwork details updated.');
    }

    public function destroy(Artwork $artwork): RedirectResponse
    {
        if ($artwork->orderItems()->exists()) {
            return redirect()->route('admin.artworks.index')
                ->withErrors(['artwork' => "\"{$artwork->title}\" is part of a customer order, so it cannot be deleted. Set its status to Archived to hide it instead."]);
        }

        $imagePath = $artwork->image_path;
        $originalPath = $artwork->original_path;
        $artwork->delete();
        $this->removeStoredImage($imagePath);
        $this->removeOriginalFile($originalPath);

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
            'canvas_size' => ['nullable', 'string', 'max:100'],
            'image' => [
                $imageIsRequired ? 'required' : 'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:'.self::MAX_UPLOAD_KILOBYTES,
            ],
        ], [
            'image.required' => 'Choose an artwork image to upload.',
            'image.uploaded' => 'The image could not be uploaded. It may be larger than the server allows ('.ini_get('upload_max_filesize').').',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'Upload a JPG, JPEG, PNG, or WEBP image.',
            'image.max' => 'The image must be '.(self::MAX_UPLOAD_KILOBYTES / 1024).' MB or smaller.',
        ]);
    }

    /**
     * Keep the untouched original on the private disk for buyers, and put a
     * web-sized preview on the public disk for the catalog.
     *
     * @return array{image_path: string, original_path: string, image_width: ?int, image_height: ?int, file_format: string, file_size: int}
     */
    private function storeImage(UploadedFile $image): array
    {
        $extension = strtolower($image->getClientOriginalExtension() ?: $image->extension());
        $fileName = Str::uuid().'.'.$extension;

        $originalPath = $image->storeAs('artworks/originals', $fileName, 'local');
        if ($originalPath === false) {
            throw new RuntimeException('The artwork image could not be saved.');
        }

        $dimensions = @getimagesize($image->getRealPath()) ?: [null, null];
        $previewPath = "artworks/{$fileName}";

        if (! $this->writePreview($image->getRealPath(), $previewPath, $extension)
            && ! Storage::disk('public')->putFileAs('artworks', $image, $fileName)) {
            Storage::disk('local')->delete($originalPath);

            throw new RuntimeException('The artwork preview could not be saved.');
        }

        return [
            'image_path' => "storage/{$previewPath}",
            'original_path' => $originalPath,
            'image_width' => $dimensions[0],
            'image_height' => $dimensions[1],
            'file_format' => $extension === 'jpeg' ? 'jpg' : $extension,
            'file_size' => $image->getSize(),
        ];
    }

    /**
     * Write a downscaled copy for the public catalog. Returns false when the GD
     * extension is missing or the image is already small, so the caller can
     * fall back to copying the file as-is.
     */
    private function writePreview(string $sourcePath, string $previewPath, string $extension): bool
    {
        if (! extension_loaded('gd')) {
            return false;
        }

        $size = @getimagesize($sourcePath);
        if ($size === false || max($size[0], $size[1]) <= self::PREVIEW_MAX_EDGE) {
            return false;
        }

        $source = @imagecreatefromstring((string) file_get_contents($sourcePath));
        if ($source === false) {
            return false;
        }

        $scale = self::PREVIEW_MAX_EDGE / max($size[0], $size[1]);
        $preview = imagescale($source, (int) round($size[0] * $scale), (int) round($size[1] * $scale));
        if ($preview === false) {
            return false;
        }

        imagealphablending($preview, false);
        imagesavealpha($preview, true);

        ob_start();
        $written = match ($extension) {
            'png' => imagepng($preview, null, 6),
            'webp' => imagewebp($preview, null, 82),
            default => imagejpeg($preview, null, 82),
        };
        $contents = (string) ob_get_clean();

        return $written && Storage::disk('public')->put($previewPath, $contents);
    }

    private function removeOriginalFile(?string $path): void
    {
        if ($path !== null && str_starts_with($path, 'artworks/originals/')) {
            Storage::disk('local')->delete($path);
        }
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
