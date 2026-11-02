<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ARTFOLIO - Your Favorites</title>
    <meta name="description" content="Keep the art you love close. Save pieces as you explore and revisit them here.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Dedicated CSS Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/favorites.css') }}">
</head>
<body>

@php
    $favorites = $favorites ?? [];
    $favoriteCategories = $favoriteCategories ?? [];

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

    @include('partials.sidebar', ['active' => 'favorites'])

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
                    id="fav-search-input"
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
        <div class="favorites-header-section">
            <div class="header-collection-tag">YOUR PERSONAL COLLECTION</div>
            <h1 class="header-main-title">Your favorites.</h1>
            <p class="header-description">Keep the art you love close. Save pieces as you explore and revisit them here.</p>
        </div>

        <!-- Filter & Sort Bar -->
        <div class="favorites-filter-row">
            <div class="filter-pills-group" id="fav-filter-group">
                <button type="button" class="fav-filter-btn active" data-filter="all">All artworks · <span id="pill-count">{{ count($favorites) }}</span></button>
                @foreach ($favoriteCategories as $favCategory)
                    <button type="button" class="fav-filter-btn" data-filter="{{ $favCategory }}">{{ $favCategory }}</button>
                @endforeach
            </div>

            <div class="sort-dropdown-container">
                <button type="button" class="sort-btn-pill" id="fav-sort-btn" aria-expanded="false" aria-haspopup="true">
                    <span id="fav-sort-label">Recently saved</span>
                    <svg width="11" height="11" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6L8 11L13 6"/>
                    </svg>
                </button>
                <div class="sort-dropdown-menu" id="fav-sort-menu">
                    <button type="button" class="sort-menu-item active" data-sort="recent">Recently saved</button>
                    <button type="button" class="sort-menu-item" data-sort="price-low">Price: Low to High</button>
                    <button type="button" class="sort-menu-item" data-sort="price-high">Price: High to Low</button>
                    <button type="button" class="sort-menu-item" data-sort="name-asc">Name (A-Z)</button>
                </div>
            </div>
        </div>

        <!-- Subbar: Counter & Hint -->
        <div class="favorites-subbar">
            <div class="saved-count-text" id="saved-count-text">{{ count($favorites) }} saved {{ \Illuminate\Support\Str::plural('artwork', count($favorites)) }}</div>
            <div class="saved-hint-text">Click the heart to remove a favorite</div>
        </div>

        <!-- 3-Column Favorites Grid -->
        <div class="favorites-grid" id="favorites-grid">
            @foreach ($favorites as $item)
                <article class="favorite-card"
                         data-id="{{ $item['id'] }}"
                         data-title="{{ $item['title'] }}"
                         data-category="{{ $item['category'] }}"
                         data-author="{{ $item['author'] }}"
                         data-price="{{ $item['price'] }}">
                    <div class="favorite-img-box">
                        <a href="{{ route('artworks.show', ['slug' => $item['slug']]) }}" class="favorite-img-link" aria-label="View {{ $item['title'] }} details">
                            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="favorite-img" loading="lazy">
                        </a>
                        <!-- Floating Heart Button -->
                        <button type="button"
                                class="btn-fav-heart"
                                data-id="{{ $item['id'] }}"
                                data-remove-url="{{ route('favorites.destroy', ['slug' => $item['slug']]) }}"
                                title="Remove from favorites"
                                aria-label="Remove {{ $item['title'] }} from favorites">
                            <svg class="heart-icon" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </button>
                    </div>

                    <div class="favorite-content">
                        <span class="favorite-tag">{{ $item['category'] }}</span>
                        <h2 class="favorite-title"><a href="{{ route('artworks.show', ['slug' => $item['slug']]) }}">{{ $item['title'] }}</a></h2>
                        <p class="favorite-author">by {{ $item['author'] }}</p>

                        <div class="favorite-footer-row">
                            <div class="favorite-rating">
                                <span class="favorite-star">★</span>
                                <span>{{ $item['rating'] }}</span>
                            </div>
                            <a href="{{ route('artworks.show', ['slug' => $item['slug']]) }}" class="btn-view-details">
                                <span>View details</span>
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="7" y1="17" x2="17" y2="7"></line>
                                    <polyline points="7 7 17 7 17 17"></polyline>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach

            <!-- Empty State -->
            <div class="favorites-empty-state" id="favorites-empty-state">
                <div class="empty-state-title">No favorites yet</div>
                <div class="empty-state-desc">You haven't saved any artworks to your collection yet.</div>
                <a href="{{ url('/artworks') }}" class="btn-browse-collection">
                    <span>Explore Collection</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="7" y1="17" x2="17" y2="7"></line>
                        <polyline points="7 7 17 7 17 17"></polyline>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Bottom Browse More Bar -->
        <div class="bottom-browse-row">
            <span>Find more pieces for your collection.</span>
            <a href="{{ url('/artworks') }}" class="btn-browse-more">
                <span>Browse artworks</span>
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="7" y1="17" x2="17" y2="7"></line>
                    <polyline points="7 7 17 7 17 17"></polyline>
                </svg>
            </a>
        </div>

    </main>
</div>

<!-- Scripts for Interactivity -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- 1. Filtering & Heart Removing Logic ---
        const filterBtns = document.querySelectorAll('.fav-filter-btn');
        const cards = Array.from(document.querySelectorAll('.favorite-card'));
        const searchInput = document.getElementById('fav-search-input');
        const pillCount = document.getElementById('pill-count');
        const savedCountText = document.getElementById('saved-count-text');
        const emptyState = document.getElementById('favorites-empty-state');
        const grid = document.getElementById('favorites-grid');

        let activeFilter = 'all';
        let searchQuery = '';
        let currentSort = 'recent';

        function updateCounts() {
            const activeCards = cards.filter(c => !c.classList.contains('is-removed'));
            const count = activeCards.length;

            if (pillCount) pillCount.textContent = count;
            if (savedCountText) savedCountText.textContent = `${count} saved artwork${count === 1 ? '' : 's'}`;

            let visibleCount = 0;
            cards.forEach(card => {
                if (card.classList.contains('is-removed')) {
                    card.style.display = 'none';
                    return;
                }

                const cat = (card.getAttribute('data-category') || '').toLowerCase();
                const title = (card.getAttribute('data-title') || '').toLowerCase();
                const author = (card.getAttribute('data-author') || '').toLowerCase();

                const matchesFilter = activeFilter === 'all' || cat === activeFilter.toLowerCase();
                const matchesSearch = !searchQuery || title.includes(searchQuery) || author.includes(searchQuery) || cat.includes(searchQuery);

                if (matchesFilter && matchesSearch) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        // Filter button clicks
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                activeFilter = this.getAttribute('data-filter') || 'all';
                updateCounts();
            });
        });

        // Search input
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                searchQuery = this.value.trim().toLowerCase();
                updateCounts();
            });
        }

        // Heart removal click
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const heartButtons = document.querySelectorAll('.btn-fav-heart');
        heartButtons.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                const card = this.closest('.favorite-card');
                if (!card) return;

                const removeUrl = this.getAttribute('data-remove-url');
                if (removeUrl) {
                    fetch(removeUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                    }).catch(() => {});
                }

                card.classList.add('is-removed');
                setTimeout(() => {
                    updateCounts();
                }, 200);
            });
        });

        // --- 4. Sort Dropdown ---
        const sortBtn = document.getElementById('fav-sort-btn');
        const sortMenu = document.getElementById('fav-sort-menu');
        const sortLabel = document.getElementById('fav-sort-label');
        const sortItems = document.querySelectorAll('.sort-menu-item');

        if (sortBtn && sortMenu) {
            sortBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                sortMenu.classList.toggle('is-open');
                sortBtn.setAttribute('aria-expanded', sortMenu.classList.contains('is-open') ? 'true' : 'false');
            });

            document.addEventListener('click', function (e) {
                if (!sortBtn.contains(e.target) && !sortMenu.contains(e.target)) {
                    sortMenu.classList.remove('is-open');
                    sortBtn.setAttribute('aria-expanded', 'false');
                }
            });

            sortItems.forEach(item => {
                item.addEventListener('click', function () {
                    sortItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    currentSort = this.getAttribute('data-sort');
                    if (sortLabel) sortLabel.textContent = this.textContent.trim();
                    sortMenu.classList.remove('is-open');

                    const sorted = [...cards].sort((a, b) => {
                        const idA = parseInt(a.getAttribute('data-id') || '0', 10);
                        const idB = parseInt(b.getAttribute('data-id') || '0', 10);
                        const priceA = parseFloat(a.getAttribute('data-price') || '0');
                        const priceB = parseFloat(b.getAttribute('data-price') || '0');
                        const titleA = a.getAttribute('data-title') || '';
                        const titleB = b.getAttribute('data-title') || '';

                        if (currentSort === 'price-low') return priceA - priceB;
                        if (currentSort === 'price-high') return priceB - priceA;
                        if (currentSort === 'name-asc') return titleA.localeCompare(titleB);
                        return idA - idB; // recently saved / default
                    });

                    sorted.forEach(c => grid.appendChild(c));
                    if (emptyState) grid.appendChild(emptyState);
                });
            });
        }

        updateCounts();
    });
</script>

</body>
</html>
