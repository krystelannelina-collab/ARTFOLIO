<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artists | ARTFOLIO Admin</title>
    <meta name="description" content="Browse the artists represented in the ARTFOLIO catalog.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-artists.css') }}">
</head>
<body>
<div class="admin-layout">
    @include('admin.partials.sidebar', ['active' => 'artists', 'artworkCount' => $stats['totalArtworks']])

    <main class="admin-main">
        <header class="admin-page-heading artists-page-heading">
            <div>
                <span class="admin-eyebrow">STORE MANAGEMENT</span>
                <h1>Artists</h1>
                <p>Explore the artists and creative work represented in your catalog.</p>
            </div>
            <span class="artists-data-note">Artist profiles are grouped from artwork credits.</span>
        </header>

        <section class="artists-overview" aria-label="Artist and artwork totals">
            <article>
                <span>Active artists</span>
                <strong>{{ number_format($stats['activeArtists']) }}</strong>
                <small>With published work</small>
            </article>
            <article>
                <span>Total artists</span>
                <strong>{{ number_format($stats['totalArtists']) }}</strong>
                <small>Represented in the catalog</small>
            </article>
            <article>
                <span>Published artworks</span>
                <strong>{{ number_format($stats['publishedArtworks']) }}</strong>
                <small>Available in the store</small>
            </article>
            <article>
                <span>Draft artworks</span>
                <strong>{{ number_format($stats['draftArtworks']) }}</strong>
                <small>Not yet published</small>
            </article>
        </section>

        <section class="admin-panel artists-table-panel" aria-label="Artist directory">
            <form method="GET" action="{{ route('admin.artists.index') }}" class="artists-filter-bar">
                <label class="artists-search">
                    <span class="visually-hidden">Search artist names</span>
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m16 16 4 4"></path>
                    </svg>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search artist name...">
                </label>

                <label class="artists-sort">
                    <span class="visually-hidden">Sort artists</span>
                    <select name="sort">
                        <option value="artist" @selected($sort === 'artist')>Artist name A–Z</option>
                        <option value="most_artworks" @selected($sort === 'most_artworks')>Most artworks</option>
                        <option value="recently_added" @selected($sort === 'recently_added')>Recently active</option>
                    </select>
                </label>

                <button type="submit" class="artists-filter-submit">Apply</button>
                <a href="{{ route('admin.artists.index') }}" class="artists-filter-reset">Reset</a>
            </form>

            @if ($artists->isNotEmpty())
                <div class="artists-table-scroll">
                    <table class="artists-table">
                        <thead>
                            <tr>
                                <th scope="col">ARTIST</th>
                                <th scope="col">TOTAL ARTWORKS</th>
                                <th scope="col">PUBLISHED</th>
                                <th scope="col">DRAFTS</th>
                                <th scope="col">LATEST CONTRIBUTION</th>
                                <th scope="col">STATUS</th>
                                <th scope="col">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($artists as $artist)
                                <tr>
                                    <td>
                                        <span class="artist-name-cell">
                                            <span class="artist-avatar" aria-hidden="true">{{ strtoupper(substr(trim($artist->artist_name), 0, 1)) }}</span>
                                            <strong>{{ $artist->artist_name }}</strong>
                                        </span>
                                    </td>
                                    <td class="artist-count">{{ number_format($artist->artwork_count) }}</td>
                                    <td>{{ number_format($artist->published_count) }}</td>
                                    <td>{{ number_format($artist->draft_count) }}</td>
                                    <td class="artist-date">{{ \Illuminate\Support\Carbon::parse($artist->latest_artwork_at)->format('M j, Y') }}</td>
                                    <td>
                                        <span class="artist-status {{ $artist->published_count > 0 ? 'is-active' : 'is-inactive' }}">
                                            {{ $artist->published_count > 0 ? 'Active' : 'No published work' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('admin.artworks.index', ['search' => $artist->artist_name]) }}"
                                            class="artist-view-link"
                                            aria-label="View artworks by {{ $artist->artist_name }}"
                                        >View artworks <span aria-hidden="true">↗</span></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <footer class="artists-table-footer">
                    <span>Showing {{ $artists->firstItem() }}–{{ $artists->lastItem() }} of {{ number_format($artists->total()) }} artists</span>
                    {{ $artists->links() }}
                </footer>
            @else
                <div class="artists-empty-state">
                    <strong>{{ ($filters['search'] ?? '') !== '' ? 'No matching artists' : 'No artists in the catalog yet' }}</strong>
                    <span>{{ ($filters['search'] ?? '') !== '' ? 'Try another artist name or reset your search.' : 'Artists will appear here after you add artwork to the catalog.' }}</span>
                    @if (($filters['search'] ?? '') !== '')
                        <a href="{{ route('admin.artists.index') }}" class="artists-filter-reset">Reset search</a>
                    @endif
                </div>
            @endif
        </section>
    </main>
</div>
</body>
</html>
