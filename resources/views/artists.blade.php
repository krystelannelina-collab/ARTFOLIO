<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - Meet the Artists</title>
    <meta name="description" content="Explore original perspectives and find your next favorite creator.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Dedicated CSS Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/artists.css') }}">
</head>
<body>

@php
    $artists = $artists ?? [];
    $categories = $categories ?? [];

    $userInitials = 'CJ';
    if (Auth::check() && Auth::user()->name) {
        $words = explode(' ', trim(Auth::user()->name));
        if (count($words) >= 2) {
            $userInitials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        } else {
            $userInitials = strtoupper(substr(Auth::user()->name, 0, 2));
        }
    }
@endphp


<div class="app-layout">

    @include('partials.site-header', [
        'showSearch' => false,'active' => 'artists'])

    <!-- ================= MAIN CONTENT ================= -->
    <main class="main-content">

        <!-- Top Bar -->
        <div class="top-bar page-toolbar">
            <!-- Search Pill -->
            <div class="search-pill-container">
                <svg class="search-icon-left" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input
                    type="text"
                    id="artist-search-input"
                    class="search-input"
                    placeholder="Search artworks, artists, and inspiration"
                    aria-label="Search artworks, artists, and inspiration"
                    value=""
                >
            </div>

        </div>

        <!-- Page Header -->
        <div class="artists-header-section">
            <div class="header-category-tag">THE PEOPLE BEHIND THE ART</div>
            <h1 class="header-main-title">Meet the artists.</h1>
            <p class="header-description">Explore original perspectives and find your next favorite creator.</p>
        </div>

        <!-- Filter & Sort Bar -->
        <div class="filter-sort-row">
            <div class="filter-pills-group" id="filter-pills-group">
                <button type="button" class="category-filter-btn active" data-filter="all">All artists</button>
                @foreach ($categories as $category)
                    <button type="button" class="category-filter-btn" data-filter="{{ $category }}">{{ $category }}</button>
                @endforeach
            </div>

            <div class="sort-dropdown-container">
                <button type="button" class="sort-btn" id="sort-toggle-btn" aria-expanded="false" aria-haspopup="true">
                    <span id="sort-current-label">Sort: Featured</span>
                    <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6L8 11L13 6"/>
                    </svg>
                </button>
                <div class="sort-menu" id="sort-dropdown-menu">
                    <button type="button" class="sort-option-btn active" data-sort="featured">Featured</button>
                    <button type="button" class="sort-option-btn" data-sort="name-asc">Name (A-Z)</button>
                    <button type="button" class="sort-option-btn" data-sort="name-desc">Name (Z-A)</button>
                </div>
            </div>
        </div>

        <!-- 2x2 Artists Grid -->
        <div class="artists-grid" id="artists-grid">
            @foreach ($artists as $artist)
                <div class="artist-card"
                     data-id="{{ $artist['id'] }}"
                     data-name="{{ $artist['name'] }}"
                     data-categories="{{ implode(',', $artist['categories']) }}"
                     data-bio="{{ $artist['bio'] }}">
                    <div class="artist-photo-container">
                        <img src="{{ $artist['image'] }}"
                             alt="{{ $artist['name'] }}"
                             class="artist-photo"
                             loading="lazy">
                    </div>
                    <div class="artist-content">
                        <div class="artist-info-top">
                            <h2 class="artist-name">{{ $artist['name'] }}</h2>
                            <div class="artist-category-label">{{ $artist['category_label'] }}</div>
                            <p class="artist-bio">{{ $artist['bio'] }}</p>
                        </div>
                        <div class="artist-info-bottom">
                            <button type="button"
                                    class="btn-view-artist"
                                    data-bs-toggle="modal"
                                    data-bs-target="#artist-modal-{{ $artist['id'] }}"
                                    aria-label="View profile of {{ $artist['name'] }}">
                                <span>View artist</span>
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <line x1="7" y1="17" x2="17" y2="7"></line>
                                    <polyline points="7 7 17 7 17 17"></polyline>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Empty state -->
            <div class="empty-artists-state" id="empty-artists-state">
                <div class="empty-artists-title">No artists found</div>
                <div class="empty-artists-desc">We couldn't find any artists matching your current filter.</div>
                <button type="button" class="category-filter-btn active" id="reset-filter-btn">View All Artists</button>
            </div>
        </div>

        <!-- Footer -->
        <footer class="artists-footer">
            <span id="artists-count-label">{{ count($artists) }} {{ \Illuminate\Support\Str::plural('artist', count($artists)) }}</span> · Sample profile descriptions for layout preview.
        </footer>

    </main>
</div>

{{-- Artist profile modals: one per artist, opened by its "View artist" button. --}}
@foreach ($artists as $artist)
    <div class="modal fade artist-modal" id="artist-modal-{{ $artist['id'] }}" tabindex="-1" aria-labelledby="artist-modal-title-{{ $artist['id'] }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <button type="button" class="btn-close artist-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>

                <div class="modal-body">
                    <section class="artist-modal-profile">
                        <div class="artist-modal-photo">
                            <img src="{{ $artist['image'] }}" alt="{{ $artist['name'] }}" loading="lazy">
                        </div>

                        <div class="artist-modal-info">
                            <h2 class="artist-modal-name" id="artist-modal-title-{{ $artist['id'] }}">{{ $artist['name'] }}</h2>
                            <p class="artist-modal-headline">{{ $artist['headline'] }}</p>
                            <p class="artist-modal-bio">{{ $artist['bio'] }}</p>

                            @if (! empty($artist['categories']))
                                <ul class="artist-modal-styles" aria-label="Art styles">
                                    @foreach ($artist['categories'] as $style)
                                        <li>{{ $style }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            @if (! empty($artist['socials']))
                                <div class="artist-modal-socials">
                                    @foreach ($artist['socials'] as $social)
                                        <a href="{{ $social['url'] }}"
                                           class="artist-social-link"
                                           @if ($social['network'] !== 'email') target="_blank" rel="noopener noreferrer" @endif
                                           aria-label="{{ $artist['name'] }} on {{ $social['label'] }}"
                                           title="{{ $social['label'] }}">
                                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                @switch($social['network'])
                                                    @case('facebook')
                                                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                                                        @break
                                                    @case('github')
                                                        <path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.4 5.4 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"/>
                                                        <path d="M9 18c-4.51 2-5-2-7-2"/>
                                                        @break
                                                    @case('instagram')
                                                        <rect x="2" y="2" width="20" height="20" rx="5"/>
                                                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                                                        @break
                                                    @default
                                                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                                @endswitch
                                            </svg>
                                            <span>{{ $social['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </section>

                    <section class="artist-modal-featured" aria-labelledby="artist-featured-title-{{ $artist['id'] }}">
                        <h3 class="artist-modal-featured-title" id="artist-featured-title-{{ $artist['id'] }}">Artist’s featured arts</h3>

                        @if (empty($artist['featured_artworks']))
                            <p class="artist-modal-empty">No published artworks yet. Check back soon.</p>
                        @else
                            <div class="artist-modal-artworks">
                                @foreach ($artist['featured_artworks'] as $artwork)
                                    <a href="{{ $artwork['url'] }}" class="artist-modal-artwork">
                                        <span class="artist-modal-artwork-image">
                                            <img src="{{ $artwork['image'] }}" alt="{{ $artwork['title'] }}" loading="lazy">
                                        </span>
                                        <span class="artist-modal-artwork-category">{{ $artwork['category'] }}</span>
                                        <span class="artist-modal-artwork-title">{{ $artwork['title'] }}</span>
                                        <span class="artist-modal-artwork-price">@peso($artwork['price'])</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Interactive Filtering Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- 1. Filter Pills Logic ---
        const filterButtons = document.querySelectorAll('.category-filter-btn[data-filter]');
        const artistCards = Array.from(document.querySelectorAll('.artist-card'));
        const emptyState = document.getElementById('empty-artists-state');
        const searchInput = document.getElementById('artist-search-input');
        const countLabel = document.getElementById('artists-count-label');
        const resetFilterBtn = document.getElementById('reset-filter-btn');

        let currentCategory = 'all';
        let currentSearch = '';
        let currentSort = 'featured';

        function applyFilters() {
            let visibleCount = 0;

            artistCards.forEach(card => {
                const name = (card.getAttribute('data-name') || '').toLowerCase();
                const categories = (card.getAttribute('data-categories') || '').split(',').map(c => c.trim().toLowerCase());
                const bio = (card.getAttribute('data-bio') || '').toLowerCase();

                // Category match
                const matchesCategory = currentCategory === 'all' || categories.includes(currentCategory.toLowerCase());

                // Search match
                const matchesSearch = !currentSearch ||
                    name.includes(currentSearch) ||
                    bio.includes(currentSearch) ||
                    categories.some(cat => cat.includes(currentSearch));

                if (matchesCategory && matchesSearch) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }

            if (countLabel) {
                countLabel.textContent = `${visibleCount} artist${visibleCount === 1 ? '' : 's'}`;
            }
        }

        filterButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                filterButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentCategory = this.getAttribute('data-filter') || 'all';
                applyFilters();
            });
        });

        if (resetFilterBtn) {
            resetFilterBtn.addEventListener('click', function () {
                filterButtons.forEach(b => {
                    b.classList.toggle('active', b.getAttribute('data-filter') === 'all');
                });
                currentCategory = 'all';
                if (searchInput) searchInput.value = '';
                currentSearch = '';
                applyFilters();
            });
        }

        // --- 4. Real-time Search Input ---
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                currentSearch = this.value.trim().toLowerCase();
                applyFilters();
            });
        }

        // --- 5. Sort Dropdown ---
        const sortToggleBtn = document.getElementById('sort-toggle-btn');
        const sortMenu = document.getElementById('sort-dropdown-menu');
        const sortLabel = document.getElementById('sort-current-label');
        const sortOptions = document.querySelectorAll('.sort-option-btn');
        const grid = document.getElementById('artists-grid');

        if (sortToggleBtn && sortMenu) {
            sortToggleBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                sortMenu.classList.toggle('is-open');
                sortToggleBtn.setAttribute('aria-expanded', sortMenu.classList.contains('is-open') ? 'true' : 'false');
            });

            document.addEventListener('click', function (e) {
                if (!sortToggleBtn.contains(e.target) && !sortMenu.contains(e.target)) {
                    sortMenu.classList.remove('is-open');
                    sortToggleBtn.setAttribute('aria-expanded', 'false');
                }
            });

            sortOptions.forEach(opt => {
                opt.addEventListener('click', function () {
                    sortOptions.forEach(o => o.classList.remove('active'));
                    this.classList.add('active');
                    currentSort = this.getAttribute('data-sort');
                    const text = this.textContent.trim();
                    if (sortLabel) {
                        sortLabel.textContent = `Sort: ${text}`;
                    }
                    sortMenu.classList.remove('is-open');

                    // Sort cards
                    const cards = Array.from(grid.querySelectorAll('.artist-card'));
                    cards.sort((a, b) => {
                        const nameA = a.getAttribute('data-name') || '';
                        const nameB = b.getAttribute('data-name') || '';
                        const idA = parseInt(a.getAttribute('data-id') || '0', 10);
                        const idB = parseInt(b.getAttribute('data-id') || '0', 10);

                        if (currentSort === 'name-asc') {
                            return nameA.localeCompare(nameB);
                        } else if (currentSort === 'name-desc') {
                            return nameB.localeCompare(nameA);
                        } else {
                            // Featured / ID
                            return idA - idB;
                        }
                    });

                    cards.forEach(card => grid.appendChild(card));
                    if (emptyState) grid.appendChild(emptyState);
                });
            });
        }

        // URL query parameter support (e.g. ?category=Abstract)
        const urlParams = new URLSearchParams(window.location.search);
        const catParam = urlParams.get('category');
        if (catParam) {
            const matchingBtn = Array.from(filterButtons).find(b =>
                (b.getAttribute('data-filter') || '').toLowerCase() === catParam.toLowerCase()
            );
            if (matchingBtn) {
                matchingBtn.click();
            }
        }
    });
</script>

</body>
</html>
