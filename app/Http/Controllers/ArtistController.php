<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Category;
use Illuminate\Contracts\View\View;

class ArtistController extends Controller
{
    public function index(): View
    {
        $hiddenCategories = Category::query()
            ->where('is_visible', false)
            ->pluck('name');

        $visibleCategories = Category::query()
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('name');

        $publishedArtworks = Artwork::query()
            ->where('status', 'published')
            ->whereNotIn('category', $hiddenCategories)
            ->orderBy('id')
            ->get();

        $artistProfiles = Artist::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $profilesByName = $artistProfiles->keyBy(
            fn (Artist $artist): string => mb_strtolower($artist->name),
        );

        $artworksByArtist = $publishedArtworks->groupBy(
            fn (Artwork $artwork): string => mb_strtolower($artwork->artist_name),
        );

        $artists = [];
        $seenArtists = [];

        foreach ($artistProfiles as $profile) {
            $key = mb_strtolower($profile->name);
            $artistArtworks = $artworksByArtist->get($key, collect());

            $categories = $artistArtworks
                ->pluck('category')
                ->unique()
                ->values()
                ->all();

            $artists[] = [
                'id' => $profile->id,
                'name' => $profile->name,
                'category_label' => empty($categories) ? 'No published artworks yet' : implode(' · ', $categories),
                'categories' => $categories,
                'bio' => $profile->bio,
                'image' => asset($profile->image_path),
            ];
            $seenArtists[$key] = true;
        }

        $nextId = ($artistProfiles->max('id') ?? 0) + 1;
        foreach ($artworksByArtist as $key => $artistArtworks) {
            if (isset($seenArtists[$key])) {
                continue;
            }

            /** @var Artwork $firstArtwork */
            $firstArtwork = $artistArtworks->first();
            $categories = $artistArtworks
                ->pluck('category')
                ->unique()
                ->values()
                ->all();

            $artists[] = [
                'id' => $nextId++,
                'name' => $firstArtwork->artist_name,
                'category_label' => implode(' · ', $categories),
                'categories' => $categories,
                'bio' => 'Explore original '.strtolower(implode(' and ', $categories)).' works in the ARTFOLIO collection.',
                'image' => asset($firstArtwork->image_path),
            ];
        }

        return view('artists', [
            'artists' => $artists,
            'categories' => $visibleCategories->all(),
        ]);
    }
}
