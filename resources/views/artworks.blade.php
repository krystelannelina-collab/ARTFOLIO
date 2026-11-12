<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - Artworks from our Artists</title>
    <meta name="description" content="Explore original artworks created by talented artists">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Dedicated CSS Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/artworks.css') }}">
</head>
<body>

@php
    $allArtworks = $artworks ?? [];
    $catalogCategories = collect($catalogCategories ?? []);
    $selectedCategory = trim((string) ($selectedCategory ?? request('category')));
@endphp


<div class="app-layout">

    @include('partials.site-header', [
        'showSearch' => false,
        'active' => 'artworks',
        'activeCategory' => $selectedCategory,
        'catalogCategories' => $catalogCategories,
    ])

    <!-- ================= MAIN CONTENT ================= -->
    <main class="main-content">

        <!-- Section Header Row: title on the left, search and sort on the right -->
        <div class="section-header-row">
            <h1 class="section-title">
                Artworks <span class="artists-sub">from our Artists</span>
                <span id="active-category-indicator" class="active-filter-badge" style="{{ empty($selectedCategory) ? 'display: none;' : '' }}">
                    <span id="active-category-text">{{ $selectedCategory }}</span>
                    <span style="font-size: 14px; line-height: 1;" title="Clear filter">&times;</span>
                </span>
            </h1>

            <div class="catalog-controls">
                <!-- Live search (filters as you type; Enter reloads with ?search=) -->
                <form action="{{ url('/artworks') }}" method="GET" class="catalog-search" role="search" id="artworks-search-form">
                    <svg class="catalog-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input
                        type="search"
                        name="search"
                        id="search-input"
                        class="catalog-search-input"
                        placeholder="Search title, artist, or category"
                        aria-label="Search artworks by title, artist, or category"
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                </form>

                <!-- Sort By Box -->
                <div class="sort-box">
                    <span class="sort-label">Sort By</span>
                    <div class="sort-divider"></div>
                    <select id="sort-select" class="sort-select" aria-label="Sort artworks">
                        <option value="default">Default</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="name">Name (A-Z)</option>
                        @foreach ($catalogCategories as $catalogCategory)
                            <option value="filter-{{ $catalogCategory }}" @selected(strcasecmp($selectedCategory, $catalogCategory) === 0)>Category: {{ $catalogCategory }}</option>
                        @endforeach
                    </select>
                    <svg class="sort-chevron" width="11" height="11" viewBox="0 0 16 16" fill="none">
                        <path d="M3 6L8 11L13 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
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
                        <a href="{{ route('artworks.show', ['slug' => $art['slug']]) }}" aria-label="View {{ $art['title'] }} details">
                            <img src="{{ $art['image'] }}" alt="{{ $art['title'] }}" class="artwork-image" loading="lazy">
                        </a>
                    </div>

                    <div class="artwork-title-row">
                        <h2 class="artwork-name"><a href="{{ route('artworks.show', ['slug' => $art['slug']]) }}">{{ $art['title'] }}</a></h2>
                        <span class="artwork-price">@peso($art['price'])</span>
                    </div>

                    <p class="artwork-artist">by {{ $art['author'] }}</p>

                    <span class="edition-label">{{ $art['catalog_number'] }} · One of one</span>

                    <div class="artwork-actions-row">
                        <form method="POST" action="{{ route('cart.add', ['slug' => $art['slug']]) }}" class="artwork-cart-action-form">
                            @csrf
                            <button type="submit" class="btn-cart-outline">Add to cart</button>
                        </form>
                        <form method="POST" action="{{ route('checkout.buy-now', ['slug' => $art['slug']]) }}" class="artwork-cart-action-form">
                            @csrf
                            <button type="submit" class="btn-buy-solid">Buy now</button>
                        </form>
                        <button type="button"
                                class="btn-icon-square ms-auto"
                                aria-label="Share artwork"
                                title="Share artwork"
                                data-share-url="{{ route('artworks.show', ['slug' => $art['slug']]) }}"
                                data-share-title="{{ $art['title'] }}">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="18" cy="5" r="3"></circle>
                                <circle cx="6" cy="12" r="3"></circle>
                                <circle cx="18" cy="19" r="3"></circle>
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('favorites.toggle', ['slug' => $art['slug']]) }}" class="artwork-cart-action-form" data-favorite-form>
                            @csrf
                            <button type="submit"
                                    class="btn-icon-square {{ ! empty($art['is_favorited']) ? 'is-favorited' : '' }}"
                                    aria-label="{{ ! empty($art['is_favorited']) ? 'Remove from favorites' : 'Save to favorites' }}"
                                    aria-pressed="{{ ! empty($art['is_favorited']) ? 'true' : 'false' }}"
                                    title="{{ ! empty($art['is_favorited']) ? 'Saved in favorites' : 'Save to favorites' }}">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="{{ ! empty($art['is_favorited']) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                </svg>
                            </button>
                        </form>
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
        let currentSearch = (searchInput ? searchInput.value : (new URLSearchParams(window.location.search).get('search') || '')).trim().toLowerCase();

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

        // Share artwork link handler
        document.querySelectorAll('[data-share-url]').forEach(shareBtn => {
            shareBtn.addEventListener('click', async function () {
                const shareUrl = this.dataset.shareUrl;
                const shareTitle = this.dataset.shareTitle || 'ARTFOLIO Artwork';

                if (navigator.share) {
                    try {
                        await navigator.share({ title: shareTitle, url: shareUrl });
                        return;
                    } catch (err) {
                        if (err && err.name === 'AbortError') {
                            return;
                        }
                    }
                }

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    try {
                        await navigator.clipboard.writeText(shareUrl);
                        this.classList.add('is-copied');
                        this.setAttribute('title', 'Link copied!');
                        setTimeout(() => {
                            this.classList.remove('is-copied');
                            this.setAttribute('title', 'Share artwork');
                        }, 1800);
                    } catch (err) {
                        window.prompt('Copy artwork link:', shareUrl);
                    }
                } else {
                    window.prompt('Copy artwork link:', shareUrl);
                }
            });
        });

        // Seamless Favorite toggle handler
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        document.querySelectorAll('[data-favorite-form]').forEach(favForm => {
            favForm.addEventListener('submit', async function (e) {
                if (!window.fetch || !csrfToken) {
                    return;
                }
                e.preventDefault();

                const button = this.querySelector('button[type="submit"]');
                const svg = button ? button.querySelector('svg') : null;

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        this.submit();
                        return;
                    }

                    const data = await response.json();
                    const isFav = Boolean(data.favorited);

                    if (button) {
                        button.classList.toggle('is-favorited', isFav);
                        button.setAttribute('aria-pressed', isFav ? 'true' : 'false');
                        button.setAttribute('aria-label', isFav ? 'Remove from favorites' : 'Save to favorites');
                        button.setAttribute('title', isFav ? 'Saved in favorites' : 'Save to favorites');
                    }

                    if (svg) {
                        svg.setAttribute('fill', isFav ? 'currentColor' : 'none');
                    }
                } catch (err) {
                    this.submit();
                }
            });
        });

        // Initial setup
        applyFilterAndSort();
    });
</script>

</body>
</html>
