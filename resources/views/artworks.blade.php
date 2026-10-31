<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - Artworks from our Artists</title>
    <meta name="description" content="Explore original artworks created by talented artists">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Dedicated CSS Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/artworks.css') }}">
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

    $allArtworks = [
        [
            'id' => 1,
            'title' => 'Fluid Geometric',
            'price' => 28.56,
            'author' => 'Cristine Joy Montes',
            'category' => 'Abstract',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-fluid-geometric.png'),
        ],
        [
            'id' => 2,
            'title' => 'Stranger Things Poster',
            'price' => 35.76,
            'author' => 'Gian Gomez',
            'category' => 'Poster',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-stranger-things.png'),
        ],
        [
            'id' => 3,
            'title' => 'Minimalist Organic',
            'price' => 15.67,
            'author' => 'Gian Gomez',
            'category' => 'Abstract',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-minimalist-organic.png'),
        ],
        [
            'id' => 4,
            'title' => 'Geometric Contrast',
            'price' => 20.43,
            'author' => 'Kalvin Dave Ramos',
            'category' => 'Abstract',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-geometric-contrast.png'),
        ],
        [
            'id' => 5,
            'title' => 'The One Poster',
            'price' => 25.86,
            'author' => 'Kalvin Dave Ramos',
            'category' => 'Poster',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-the-one-poster.png'),
        ],
        [
            'id' => 6,
            'title' => 'Geometric Abstract',
            'price' => 21.43,
            'author' => 'Krystel Anne Lina',
            'category' => 'Abstract',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-geometric-abstract.png'),
        ],
        [
            'id' => 7,
            'title' => 'GoldMidnight Pixel Art',
            'price' => 18.50,
            'author' => 'Kalvin Dave Ramos',
            'category' => 'Pixel',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-geometric-contrast.png'),
        ],
        [
            'id' => 8,
            'title' => 'Golden Mood Typography',
            'price' => 24.50,
            'author' => 'Gian Gomez',
            'category' => 'Typography',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-stranger-things.png'),
        ],
        [
            'id' => 9,
            'title' => 'Modern Muse Digital',
            'price' => 27.00,
            'author' => 'Cristine Joy Montes',
            'category' => 'Digital',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-fluid-geometric.png'),
        ],
    ];

    $selectedCategory = trim((string) request('category'));
@endphp

@include('partials.mobile-header')

<div class="app-layout">

    @include('partials.sidebar', ['active' => 'artworks', 'activeCategory' => $selectedCategory])

    <!-- ================= MAIN CONTENT ================= -->
    <main class="main-content">

        <!-- Top Bar -->
        <header class="top-bar">
            <!-- Search Pill with Hamburger/Filter + Magnifying Glass -->
            <form action="{{ url('/artworks') }}" method="GET" class="search-pill-container" role="search" id="artworks-search-form">
                <button type="button" class="search-menu-btn" aria-label="Menu filter" id="search-filter-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="4" y1="7" x2="20" y2="7"></line>
                        <line x1="4" y1="12" x2="20" y2="12"></line>
                        <line x1="4" y1="17" x2="20" y2="17"></line>
                    </svg>
                </button>
                <input
                    type="search"
                    name="search"
                    id="search-input"
                    class="search-input"
                    placeholder="Search Artworks..."
                    aria-label="Search Artworks"
                    value="{{ request('search') }}"
                >
                <button type="submit" class="search-submit-btn" aria-label="Search">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </form>

            <!-- User Avatar Circle -->
            <button type="button" class="user-avatar-btn" title="{{ Auth::user()->name ?? 'Account' }}" aria-label="User Account">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 5C13.66 5 15 6.34 15 8C15 9.66 13.66 11 12 11C10.34 11 9 9.66 9 8C9 6.34 10.34 5 12 5ZM12 19.2C9.5 19.2 7.29 17.92 6 15.98C6.03 13.99 10 12.9 12 12.9C13.99 12.9 17.97 13.99 18 15.98C16.71 17.92 14.5 19.2 12 19.2Z"/>
                </svg>
            </button>
        </header>

        <!-- Section Header Row -->
        <div class="section-header-row">
            <h1 class="section-title">
                Artworks <span class="artists-sub">from our Artists</span>
                <span id="active-category-indicator" class="active-filter-badge" style="{{ empty($selectedCategory) ? 'display: none;' : '' }}">
                    <span id="active-category-text">{{ $selectedCategory }}</span>
                    <span style="font-size: 14px; line-height: 1;" title="Clear filter">&times;</span>
                </span>
            </h1>

            <!-- Sort By Box -->
            <div class="sort-box">
                <span class="sort-label">Sort By</span>
                <div class="sort-divider"></div>
                <select id="sort-select" class="sort-select" aria-label="Sort artworks">
                    <option value="default">Default</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="name">Name (A-Z)</option>
                    <option value="filter-Abstract" {{ strcasecmp($selectedCategory, 'Abstract') === 0 ? 'selected' : '' }}>Category: Abstract</option>
                    <option value="filter-Poster" {{ strcasecmp($selectedCategory, 'Poster') === 0 ? 'selected' : '' }}>Category: Poster</option>
                    <option value="filter-Pixel" {{ strcasecmp($selectedCategory, 'Pixel') === 0 ? 'selected' : '' }}>Category: Pixel Art</option>
                    <option value="filter-Typography" {{ strcasecmp($selectedCategory, 'Typography') === 0 ? 'selected' : '' }}>Category: Typography</option>
                    <option value="filter-Digital" {{ strcasecmp($selectedCategory, 'Digital') === 0 ? 'selected' : '' }}>Category: Digital Art</option>
                </select>
                <svg class="sort-chevron" width="11" height="11" viewBox="0 0 16 16" fill="none">
                    <path d="M3 6L8 11L13 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        <!-- Artworks 3-Column Grid -->
        <div class="artworks-grid" id="artworks-grid">
            @foreach ($allArtworks as $art)
                @php
                    $isInitiallyHidden = false;
                    if (!empty($selectedCategory) && strcasecmp($art['category'], $selectedCategory) !== 0) {
                        $isInitiallyHidden = true;
                    }
                @endphp
                <article class="artwork-item {{ $isInitiallyHidden ? 'is-hidden' : '' }}"
                         data-category="{{ $art['category'] }}"
                         data-price="{{ $art['price'] }}"
                         data-name="{{ $art['title'] }}"
                         data-artist="{{ $art['author'] }}">
                    <div class="artwork-image-box">
                        <img src="{{ $art['image'] }}" alt="{{ $art['title'] }}" class="artwork-image" loading="lazy">
                    </div>

                    <div class="artwork-title-row">
                        <h2 class="artwork-name">{{ $art['title'] }}</h2>
                        <span class="artwork-price">${{ number_format($art['price'], 2) }}</span>
                    </div>

                    <p class="artwork-artist">by {{ $art['author'] }}</p>

                    <div class="artwork-rating-row">
                        <svg class="artwork-star-icon" width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 1.5l3.09 6.26L22 8.77l-5 4.87 1.18 6.86L12 17.27l-6.18 3.23L7 13.64l-5-4.87 6.91-1.01L12 1.5z"/>
                        </svg>
                        <span class="artwork-rating-text">{{ $art['rating'] }}</span>
                    </div>

                    <div class="artwork-actions-row">
                        <button type="button" class="btn-cart-outline">
                            Add to cart
                        </button>
                        <button type="button" class="btn-buy-solid">
                            Buy now
                        </button>
                        <button type="button" class="btn-icon-square ms-auto" aria-label="Share artwork">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="18" cy="5" r="3"></circle>
                                <circle cx="6" cy="12" r="3"></circle>
                                <circle cx="18" cy="19" r="3"></circle>
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                            </svg>
                        </button>
                        <button type="button" class="btn-icon-square" aria-label="Save to favorites">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                            </svg>
                        </button>
                    </div>
                </article>
            @endforeach

            <!-- Empty State message -->
            <div id="no-results" class="no-results-msg">
                No artworks found matching your selection.
            </div>
        </div>

    </main>
</div>

<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Category & Sort Management
        const grid = document.getElementById('artworks-grid');
        const items = Array.from(grid.querySelectorAll('.artwork-item'));
        const sortSelect = document.getElementById('sort-select');
        const searchInput = document.getElementById('search-input');
        const activeCategoryBadge = document.getElementById('active-category-indicator');
        const activeCategoryText = document.getElementById('active-category-text');
        const submenuLinks = Array.from(document.querySelectorAll('[data-category-filter]'));
        const allArtworksNav = document.getElementById('nav-artworks-all-link');
        const noResults = document.getElementById('no-results');

        let currentCategory = new URLSearchParams(window.location.search).get('category') || '';
        let currentSearch = '';

        function applyFilterAndSort() {
            let visibleCount = 0;

            items.forEach(item => {
                const itemCat = item.dataset.category || '';
                const itemName = (item.dataset.name || '').toLowerCase();
                const itemArtist = (item.dataset.artist || '').toLowerCase();

                let matchesCategory = true;
                if (currentCategory && currentCategory !== 'all') {
                    matchesCategory = itemCat.toLowerCase() === currentCategory.toLowerCase();
                }

                let matchesSearch = true;
                if (currentSearch) {
                    matchesSearch = itemName.includes(currentSearch) || itemArtist.includes(currentSearch) || itemCat.toLowerCase().includes(currentSearch);
                }

                if (matchesCategory && matchesSearch) {
                    item.classList.remove('is-hidden');
                    visibleCount++;
                } else {
                    item.classList.add('is-hidden');
                }
            });

            if (noResults) {
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            }

            // Update active state on sidebar links
            submenuLinks.forEach(link => {
                const linkCat = link.dataset.categoryFilter || '';
                if (currentCategory && linkCat.toLowerCase() === currentCategory.toLowerCase()) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });

            // Update badge indicator
            if (activeCategoryBadge && activeCategoryText) {
                if (currentCategory && currentCategory !== 'all') {
                    activeCategoryText.textContent = currentCategory;
                    activeCategoryBadge.style.display = 'inline-flex';
                } else {
                    activeCategoryBadge.style.display = 'none';
                }
            }

            // Sync sort dropdown if category selected
            if (sortSelect) {
                if (currentCategory && sortSelect.querySelector(`option[value="filter-${currentCategory}"]`)) {
                    sortSelect.value = `filter-${currentCategory}`;
                } else if (!currentCategory && sortSelect.value.startsWith('filter-')) {
                    sortSelect.value = 'default';
                }
            }
        }

        function sortItems(sortBy) {
            if (sortBy === 'price-low') {
                items.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
            } else if (sortBy === 'price-high') {
                items.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
            } else if (sortBy === 'name') {
                items.sort((a, b) => a.dataset.name.localeCompare(b.dataset.name));
            }

            items.forEach(item => grid.appendChild(item));
        }

        // Click on category in sidebar dropdown
        submenuLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const cat = this.dataset.categoryFilter;
                currentCategory = cat;

                // Update URL history without page reload
                const url = new URL(window.location);
                url.searchParams.set('category', cat);
                window.history.pushState({}, '', url);

                applyFilterAndSort();
            });
        });

        // Click on "Artworks" in sidebar -> Show all artworks
        if (allArtworksNav) {
            allArtworksNav.addEventListener('click', function (e) {
                e.preventDefault();
                currentCategory = '';
                const url = new URL(window.location);
                url.searchParams.delete('category');
                window.history.pushState({}, '', url);
                applyFilterAndSort();
            });
        }

        // Click badge to clear filter
        if (activeCategoryBadge) {
            activeCategoryBadge.addEventListener('click', function () {
                currentCategory = '';
                const url = new URL(window.location);
                url.searchParams.delete('category');
                window.history.pushState({}, '', url);
                applyFilterAndSort();
            });
        }

        // Sort select change
        if (sortSelect) {
            sortSelect.addEventListener('change', function () {
                const val = this.value;
                if (val.startsWith('filter-')) {
                    const cat = val.replace('filter-', '');
                    currentCategory = cat;
                    const url = new URL(window.location);
                    url.searchParams.set('category', cat);
                    window.history.pushState({}, '', url);
                    applyFilterAndSort();
                } else {
                    sortItems(val);
                }
            });
        }

        // Live search filter
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                currentSearch = this.value.trim().toLowerCase();
                applyFilterAndSort();
            });
        }

        // Initial setup
        applyFilterAndSort();
    });
</script>

</body>
</html>
