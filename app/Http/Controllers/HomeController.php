<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Category;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $hiddenCategories = Category::query()
            ->where('is_visible', false)
            ->pluck('name');

        $featuredArtworks = Artwork::query()
            ->where('status', 'published')
            ->whereNotIn('category', $hiddenCategories)
            ->orderBy('id')
            ->take(3)
            ->get()
            ->map(fn (Artwork $artwork): array => [
                'id' => $artwork->id,
                'slug' => $artwork->slug,
                'title' => $artwork->title,
                'author' => $artwork->artist_name,
                'category' => strtoupper($artwork->category),
                'rating' => '5.0',
                'image' => asset($artwork->image_path),
                'url' => route('artworks.show', ['slug' => $artwork->slug]),
            ])
            ->all();

        return view('welcome', [
            'featuredArtworks' => $featuredArtworks,
        ]);
    }
}
