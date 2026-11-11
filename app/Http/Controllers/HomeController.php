<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
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
                'id' => $artwork->id,
                'slug' => $artwork->slug,
                'title' => $artwork->title,
                'author' => $artwork->artist_name,
                'category' => strtoupper($artwork->category),
                'image' => asset($artwork->image_path),
                'url' => route('artworks.show', ['slug' => $artwork->slug]),
            ])
            ->all();

        return view('welcome', [
            'featuredArtworks' => $featuredArtworks,
        ]);
    }
}
