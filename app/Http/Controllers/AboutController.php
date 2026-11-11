<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use Illuminate\Contracts\View\View;

class AboutController extends Controller
{
    public function __invoke(): View
    {
        $featuredArtworks = Artwork::query()
            ->visibleToClients()
            ->availableForPurchase()
            ->orderBy('id')
            ->take(3)
            ->get()
            ->map(fn (Artwork $artwork): array => [
                'slug' => $artwork->slug,
                'title' => $artwork->title,
                'author' => $artwork->artist_name,
                'category' => strtoupper($artwork->category),
                'image' => asset($artwork->image_path),
                'url' => route('artworks.show', ['slug' => $artwork->slug]),
            ])
            ->all();

        return view('about', [
            'featuredArtworks' => $featuredArtworks,
        ]);
    }
}
