<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\User;
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

    /**
     * Seed the starter favorites exactly once per account. The timestamp is
     * stored on the user, so removing every favorite never brings them back.
     */
    public static function ensureUserFavoritesInitialized(Request $request): void
    {
        $user = $request->user();
        if ($user === null || $user->favorites_seeded_at !== null) {
            return;
        }

        if (! $user->favoriteArtworks()->exists()) {
            $defaultArtworkIds = Artwork::query()
                ->whereIn('slug', self::DEFAULT_FAVORITE_SLUGS)
                ->visibleToClients()
                ->availableForPurchase()
                ->orderBy('id')
                ->pluck('id')
                ->all();

            $user->favoriteArtworks()->syncWithoutDetaching($defaultArtworkIds);
        }

        $user->forceFill(['favorites_seeded_at' => now()])->save();
    }

    public function index(Request $request): View
    {
        self::ensureUserFavoritesInitialized($request);

        $favoriteModels = $request->user()
            ->favoriteArtworks()
            ->visibleToClients()
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
                'is_available' => $artwork->isAvailableForPurchase(),
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

        $user = $request->user();
        $artwork = Artwork::query()->where('slug', $slug)->firstOrFail();
        $alreadySaved = $user->favoriteArtworks()->where('artworks.id', $artwork->id)->exists();

        if ($alreadySaved) {
            $user->favoriteArtworks()->detach($artwork->id);
            $favorited = false;
        } else {
            abort_unless(Artwork::query()->whereKey($artwork->id)->visibleToClients()->exists(), 404);
            $user->favoriteArtworks()->syncWithoutDetaching([$artwork->id]);
            $favorited = true;
        }

        return $this->favoriteResponse(
            $request,
            $user,
            $favorited,
            $favorited ? 'Artwork saved to your favorites.' : 'Artwork removed from your favorites.',
        );
    }

    public function destroy(Request $request, string $slug): JsonResponse|RedirectResponse
    {
        self::ensureUserFavoritesInitialized($request);

        $artwork = Artwork::query()->where('slug', $slug)->firstOrFail();
        $user = $request->user();
        $user->favoriteArtworks()->detach($artwork->id);

        return $this->favoriteResponse($request, $user, false, 'Artwork removed from your favorites.');
    }

    private function favoriteResponse(Request $request, User $user, bool $favorited, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'favorited' => $favorited,
                'saved' => $favorited,
                'removed' => ! $favorited,
                'count' => $user->favoriteArtworks()->visibleToClients()->count(),
            ]);
        }

        return back()->with('status', $message);
    }
}
