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
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/artists.css') }}">
</head>
<body>

@php
    $categories = [
        ['label' => 'Abstract', 'url' => url('/artworks?category=Abstract'), 'type' => 'abstract'],
        ['label' => 'Typography', 'url' => url('/artworks?category=Typography'), 'type' => 'typography'],
        ['label' => 'Pixel', 'url' => url('/artworks?category=Pixel'), 'type' => 'pixel'],
        ['label' => 'Poster', 'url' => url('/artworks?category=Poster'), 'type' => 'poster'],
        ['label' => 'Digital', 'url' => url('/artworks?category=Digital'), 'type' => 'digital'],
    ];

    $artists = [
        [
            'id' => 1,
            'name' => 'Cristine Joy Montes',
            'category_label' => 'Abstract',
            'categories' => ['Abstract'],
            'bio' => 'Flowing shapes, bold forms, and a fresh perspective.',
            'image' => asset('images/artist-cristine-joy-montes.png'),
        ],
        [
            'id' => 2,
            'name' => 'Gian Gomez',
            'category_label' => 'Poster · Abstract',
            'categories' => ['Poster', 'Abstract'],
            'bio' => 'From cinematic posters to expressive abstract pieces.',
            'image' => asset('images/artist-gian-gomez.png'),
        ],
        [
            'id' => 3,
            'name' => 'Kalvin Dave Ramos',
            'category_label' => 'Abstract',
            'categories' => ['Abstract'],
            'bio' => 'Explore color, contrast, and expressive compositions.',
            'image' => asset('images/artist-kalvin-dave-ramos.png'),
        ],
        [
            'id' => 4,
            'name' => 'Krystel Anne Lina',
            'category_label' => 'Abstract',
            'categories' => ['Abstract'],
            'bio' => 'Discover layered forms and imaginative geometry.',
            'image' => asset('images/artist-krystel-anne-lina.png'),
        ],
    ];

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

@include('partials.mobile-header')

<div class="app-layout">

    @include('partials.sidebar', ['active' => 'artists'])

    <!-- ================= MAIN CONTENT ================= -->
    <main class="main-content">

        <!-- Top Bar -->
        <header class="top-bar">
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

            <!-- Top Actions -->
            <div class="top-bar-actions">
                <button type="button" class="topbar-btn" title="Notifications" aria-label="Notifications">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </button>
                <div class="user-avatar-pill" title="{{ Auth::check() ? Auth::user()->name : 'User Profile' }}">
                    {{ $userInitials }}
                </div>
            </div>
        </header>

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
                <button type="button" class="category-filter-btn" data-filter="Abstract">Abstract</button>
                <button type="button" class="category-filter-btn" data-filter="Typography">Typography</button>
                <button type="button" class="category-filter-btn" data-filter="Pixel">Pixel</button>
                <button type="button" class="category-filter-btn" data-filter="Poster">Poster</button>
                <button type="button" class="category-filter-btn" data-filter="Digital">Digital</button>
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
                            <div class="artist-badge-text">SAMPLE PROFILE PHOTO</div>
                            <a href="{{ url('/artworks?search=' . urlencode($artist['name'])) }}"
                               class="btn-view-artist"
                               title="View artworks by {{ $artist['name'] }}">
                                <span>View artist</span>
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="7" y1="17" x2="17" y2="7"></line>
                                    <polyline points="7 7 17 7 17 17"></polyline>
                                </svg>
                            </a>
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
            <span id="artists-count-label">4 artists</span> · Sample portraits and profile descriptions for layout preview.
        </footer>

    </main>
</div>

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
