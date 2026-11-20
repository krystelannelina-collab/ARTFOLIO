<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

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
                'headline' => $profile->headline,
                'category_label' => empty($categories) ? 'No published artworks yet' : implode(' · ', $categories),
                'categories' => $categories,
                'bio' => $profile->bio,
                'image' => asset($profile->image_path),
                'socials' => $this->socialLinks($profile),
                'featured_artworks' => $this->featuredArtworks($artistArtworks),
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
                'headline' => 'Professional artist',
                'category_label' => implode(' · ', $categories),
                'categories' => $categories,
                'bio' => 'Explore original '.strtolower(implode(' and ', $categories)).' works in the ARTFOLIO collection.',
                'image' => asset($firstArtwork->image_path),
                'socials' => [],
                'featured_artworks' => $this->featuredArtworks($artistArtworks),
            ];
        }

        return view('artists', [
            'artists' => $artists,
            'categories' => $visibleCategories->all(),
        ]);
    }

    /**
     * The social links an artist has filled in, in display order.
     *
     * @return list<array{network: string, label: string, url: string}>
     */
    private function socialLinks(Artist $artist): array
    {
        $links = [
            ['network' => 'facebook', 'label' => 'Facebook', 'url' => $artist->facebook_url],
            ['network' => 'github', 'label' => 'GitHub', 'url' => $artist->github_url],
            ['network' => 'instagram', 'label' => 'Instagram', 'url' => $artist->instagram_url],
            ['network' => 'email', 'label' => 'Gmail', 'url' => filled($artist->email) ? 'mailto:'.$artist->email : null],
        ];

        return array_values(array_filter($links, fn (array $link): bool => filled($link['url'])));
    }

    /**
     * Up to six of the artist's published artworks for the profile modal.
     *
     * @param  Collection<int, Artwork>  $artworks
     * @return list<array{title: string, category: string, price: float, image: string, url: string}>
     */
    private function featuredArtworks(Collection $artworks): array
    {
        return $artworks
            ->take(6)
            ->map(fn (Artwork $artwork): array => [
                'title' => $artwork->title,
                'category' => $artwork->category,
                'price' => (float) $artwork->price,
                'image' => asset($artwork->image_path),
                'url' => route('artworks.show', ['slug' => $artwork->slug]),
            ])
            ->values()
            ->all();
    }
}
