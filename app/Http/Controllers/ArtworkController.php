<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtworkController extends Controller
{
    public function index(Request $request): View
    {
        FavoriteController::ensureUserFavoritesInitialized($request);

        $hiddenCategories = Category::query()
            ->where('is_visible', false)
            ->pluck('name');

        $catalogArtworks = Artwork::query()
            ->where('status', 'published')
            ->whereNotIn('category', $hiddenCategories)
            ->orderBy('id')
            ->get();

        $visibleCategories = Category::query()
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name');

        $catalogCategories = $visibleCategories
            ->merge($catalogArtworks->pluck('category'))
            ->unique(fn (string $category): string => mb_strtolower($category))
            ->values();

        $favoriteArtworkIds = Auth::check()
            ? Auth::user()->favoriteArtworks()->pluck('artworks.id')->all()
            : [];

        $artworks = $catalogArtworks
            ->map(fn (Artwork $artwork): array => [
                'id' => $artwork->id,
                'slug' => $artwork->slug,
                'title' => $artwork->title,
                'price' => (float) $artwork->price,
                'author' => $artwork->artist_name,
                'category' => $artwork->category,
                'rating' => '5.0 (1k reviews)',
                'image' => asset($artwork->image_path),
                'is_favorited' => in_array($artwork->id, $favoriteArtworkIds, true),
            ])
            ->all();

        return view('artworks', [
            'artworks' => $artworks,
            'catalogCategories' => $catalogCategories,
            'selectedCategory' => trim((string) $request->query('category', '')),
        ]);
    }

    public function show(Request $request, string $slug): View
    {
        FavoriteController::ensureUserFavoritesInitialized($request);

        $catalogArtwork = Artwork::query()->where('slug', $slug)->firstOrFail();

        abort_unless($catalogArtwork->status === 'published' || Auth::user()?->role === 'admin', 404);

        $hiddenCategories = Category::query()
            ->where('is_visible', false)
            ->pluck('name');

        $isHiddenCategory = $hiddenCategories->contains(
            fn (string $category): bool => strcasecmp($category, $catalogArtwork->category) === 0,
        );

        abort_unless(! $isHiddenCategory || Auth::user()?->role === 'admin', 404);

        $isFavorited = Auth::check()
            && Auth::user()->favoriteArtworks()->where('artworks.id', $catalogArtwork->id)->exists();

        $artwork = [
            'id' => $catalogArtwork->id,
            'slug' => $catalogArtwork->slug,
            'title' => $catalogArtwork->title,
            'author' => $catalogArtwork->artist_name,
            'category' => $catalogArtwork->category,
            'price' => (float) $catalogArtwork->price,
            'rating' => '5.0',
            'image' => asset($catalogArtwork->image_path),
            'description' => $catalogArtwork->description,
            'is_favorited' => $isFavorited,
        ];

        $relatedQuery = Artwork::query()
            ->where('status', 'published')
            ->where('slug', '!=', $catalogArtwork->slug);

        if (Auth::user()?->role !== 'admin' && $hiddenCategories->isNotEmpty()) {
            $relatedQuery->whereNotIn('category', $hiddenCategories);
        }

        $relatedArtworks = $relatedQuery
            ->orderBy('id')
            ->take(3)
            ->get()
            ->map(fn (Artwork $related): array => [
                'slug' => $related->slug,
                'title' => $related->title,
                'author' => $related->artist_name,
                'category' => $related->category,
                'price' => (float) $related->price,
                'image' => asset($related->image_path),
                'description' => $related->description,
            ])
            ->all();

        return view('artwork-details', compact('artwork', 'relatedArtworks'));
    }
}
