<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Default starter slugs seeded into a user's favorites on their first visit.
     *
     * @var list<string>
     */
    private const DEFAULT_FAVORITE_SLUGS = [
        'fluid-geometric',
        'stranger-things-poster',
        'minimalist-organic',
        'geometric-contrast',
        'colorful-abstract',
        'geometric-abstract',
    ];

    public static function ensureUserFavoritesInitialized(Request $request): void
    {
        $user = $request->user();
        if ($user === null) {
            return;
        }

        if ($request->session()->get('favorites_initialized', false)) {
            return;
        }

        if ($user->favoriteArtworks()->exists()) {
            $request->session()->put('favorites_initialized', true);

            return;
        }

        $defaultArtworkIds = Artwork::query()
            ->whereIn('slug', self::DEFAULT_FAVORITE_SLUGS)
            ->where('status', 'published')
            ->orderBy('id')
            ->pluck('id')
            ->all();

        if (! empty($defaultArtworkIds)) {
            $user->favoriteArtworks()->syncWithoutDetaching($defaultArtworkIds);
            $request->session()->put('favorites_initialized', true);
        }
    }

    public function index(Request $request): View
    {
        self::ensureUserFavoritesInitialized($request);

        $hiddenCategories = Category::query()
            ->where('is_visible', false)
            ->pluck('name');

        $favoriteModels = $request->user()
            ->favoriteArtworks()
            ->where('status', 'published')
            ->whereNotIn('category', $hiddenCategories)
            ->get();

        $favorites = $favoriteModels
            ->map(fn (Artwork $artwork): array => [
                'id' => $artwork->id,
                'slug' => $artwork->slug,
                'title' => $artwork->title,
                'category' => $artwork->category,
                'author' => $artwork->artist_name,
                'rating' => '5.0',
                'price' => (float) $artwork->price,
                'image' => asset($artwork->image_path),
            ])
            ->all();

        $favoriteCategories = $favoriteModels
            ->pluck('category')
            ->unique()
            ->values()
            ->all();

        return view('favorites', [
            'favorites' => $favorites,
            'favoriteCategories' => $favoriteCategories,
        ]);
    }

    public function toggle(Request $request, string $slug): JsonResponse|RedirectResponse
    {
        self::ensureUserFavoritesInitialized($request);
        $request->session()->put('favorites_initialized', true);

        $hiddenCategories = Category::query()
            ->where('is_visible', false)
            ->pluck('name');

        $artwork = Artwork::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->whereNotIn('category', $hiddenCategories)
            ->firstOrFail();

        $user = $request->user();
        $alreadySaved = $user->favoriteArtworks()->where('artworks.id', $artwork->id)->exists();

        if ($alreadySaved) {
            $user->favoriteArtworks()->detach($artwork->id);
            $saved = false;
        } else {
            $user->favoriteArtworks()->syncWithoutDetaching([$artwork->id]);
            $saved = true;
        }

        $count = $user->favoriteArtworks()
            ->where('status', 'published')
            ->whereNotIn('category', $hiddenCategories)
            ->count();

        if ($request->expectsJson()) {
            return response()->json([
                'saved' => $saved,
                'count' => $count,
            ]);
        }

        return back()->with('status', $saved ? 'Artwork saved to your favorites.' : 'Artwork removed from your favorites.');
    }

    public function destroy(Request $request, string $slug): JsonResponse|RedirectResponse
    {
        self::ensureUserFavoritesInitialized($request);
        $request->session()->put('favorites_initialized', true);

        $artwork = Artwork::query()->where('slug', $slug)->firstOrFail();
        $user = $request->user();
        $user->favoriteArtworks()->detach($artwork->id);

        $hiddenCategories = Category::query()
            ->where('is_visible', false)
            ->pluck('name');

        $count = $user->favoriteArtworks()
            ->where('status', 'published')
            ->whereNotIn('category', $hiddenCategories)
            ->count();

        if ($request->expectsJson()) {
            return response()->json([
                'removed' => true,
                'count' => $count,
            ]);
        }

        return back()->with('status', 'Artwork removed from your favorites.');
    }
}
