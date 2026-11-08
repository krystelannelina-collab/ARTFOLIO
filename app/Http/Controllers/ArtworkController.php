<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtworkController extends Controller
{
    public function index(Request $request): View
    {
        FavoriteController::ensureUserFavoritesInitialized($request);

        $catalogArtworks = Artwork::query()
            ->visibleToClients()
            ->availableForPurchase()
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
        $user = $request->user();
        $isAdmin = $user?->role === 'admin';
        $isPurchasedByViewer = $catalogArtwork->isPurchasedBy($user);

        abort_unless($catalogArtwork->status === 'published' || $isAdmin || $isPurchasedByViewer, 404);

        $hiddenCategories = Category::query()
            ->where('is_visible', false)
            ->pluck('name');

        $isHiddenCategory = $hiddenCategories->contains(
            fn (string $category): bool => strcasecmp($category, $catalogArtwork->category) === 0,
        );

        abort_unless(! $isHiddenCategory || $isAdmin || $isPurchasedByViewer, 404);

        $isFavorited = $user !== null
            && $user->favoriteArtworks()->where('artworks.id', $catalogArtwork->id)->exists();

        $purchasedOrderItem = $isPurchasedByViewer
            ? $catalogArtwork->orderItems()
                ->with('order')
                ->whereHas('order', fn (Builder $order): Builder => $order
                    ->where('user_id', $user->id)
                    ->where('status', Order::STATUS_COMPLETED))
                ->latest()
                ->first()
            : null;

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
            'availability' => $catalogArtwork->availability,
            'is_available' => $catalogArtwork->isAvailableForPurchase(),
            'is_purchased_by_viewer' => $isPurchasedByViewer,
            'download_url' => $purchasedOrderItem
                ? route('orders.items.download', [$purchasedOrderItem->order, $purchasedOrderItem])
                : null,
            'specifications' => array_filter([
                'Image size' => $catalogArtwork->image_width && $catalogArtwork->image_height
                    ? number_format($catalogArtwork->image_width).' × '.number_format($catalogArtwork->image_height).' px'
                    : null,
                'Aspect ratio' => $catalogArtwork->aspectRatio(),
                'File format' => $catalogArtwork->file_format ? strtoupper($catalogArtwork->file_format) : null,
                'File size' => $catalogArtwork->readableFileSize(),
                'Canvas size' => $catalogArtwork->canvas_size,
            ]),
        ];

        $relatedQuery = Artwork::query()
            ->where('status', 'published')
            ->availableForPurchase()
            ->where('slug', '!=', $catalogArtwork->slug);

        if (! $isAdmin && $hiddenCategories->isNotEmpty()) {
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
