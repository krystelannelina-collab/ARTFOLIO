<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArtistController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', Rule::in(['artist', 'most_artworks', 'recently_added'])],
        ]);
        $search = trim($filters['search'] ?? '');
        $query = Artwork::query()
            ->select('artist_name')
            ->selectRaw('COUNT(*) as artwork_count')
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as published_count', ['published'])
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as draft_count', ['draft'])
            ->selectRaw('MAX(created_at) as latest_artwork_at')
            ->groupBy('artist_name');

        if ($search !== '') {
            $query->where('artist_name', 'like', "%{$search}%");
        }

        $sort = $filters['sort'] ?? 'artist';
        match ($sort) {
            'most_artworks' => $query->orderByDesc('artwork_count')->orderBy('artist_name'),
            'recently_added' => $query->orderByDesc('latest_artwork_at')->orderBy('artist_name'),
            default => $query->orderBy('artist_name'),
        };

        return view('admin.artists.index', [
            'artists' => $query->paginate(8)->withQueryString(),
            'filters' => $filters,
            'sort' => $sort,
            'stats' => [
                'activeArtists' => Artwork::query()->where('status', 'published')->distinct()->count('artist_name'),
                'totalArtists' => Artwork::query()->distinct()->count('artist_name'),
                'publishedArtworks' => Artwork::query()->where('status', 'published')->count(),
                'draftArtworks' => Artwork::query()->where('status', 'draft')->count(),
                'totalArtworks' => Artwork::query()->count(),
            ],
        ]);
    }
}
