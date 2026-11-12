{{--
    Shared client navigation bar (Bootstrap 5 navbar).

    Desktop: logo on the left, main links centered, search and account actions on the right.
    Phones: logo and icons stay in the bar; links, search, and log in / register fold into the menu.

    Parameters:
      $active            Current section: home, artworks, artists, about, favorites, cart, orders, profile.
      $activeCategory    Selected artwork category, when browsing artworks.
      $catalogCategories Extra category names to list in the Artworks menu.
      $showSearch        Show the header search (false on pages that have their own live search).

    The Artworks menu items keep the data-category-filter attribute and the
    #nav-artworks-all-link id, which the artworks page uses to filter without reloading.
--}}
@php
    $navCategories = collect($storeCategories ?? []);
    foreach ($catalogCategories ?? [] as $catalogCategory) {
        $categoryExists = $navCategories->contains(
            fn (array $category): bool => strcasecmp($category['label'], $catalogCategory) === 0,
        );

        if (! $categoryExists) {
            $navCategories->push([
                'label' => $catalogCategory,
                'url' => url('/artworks?category='.urlencode($catalogCategory)),
            ]);
        }
    }

    $currentActive = $active ?? '';
    $currentCategory = $activeCategory ?? '';
    $showHeaderSearch = $showSearch ?? true;
    $cartCount = auth()->check() ? count((array) session('cart', [])) : 0;
@endphp

<header class="site-header sticky-top">
    <nav class="navbar navbar-expand-lg site-navbar" aria-label="Main navigation">
        <div class="site-header-inner">
            {{-- Left: logo --}}
            <a href="{{ route('home') }}" class="navbar-brand site-brand" aria-label="ARTFOLIO home">
                <svg class="brand-logo-mark" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                    <path d="M5.33325 27.5556L14.2221 4.44444H17.7777L11.5555 22.2222H18.6666L20.4444 27.5556H5.33325Z" fill="currentColor"/>
                    <path d="M19.5556 11.5556L25.7778 27.5556H21.3334L16.8889 16L19.5556 11.5556Z" fill="currentColor"/>
                    <circle cx="24" cy="8" r="2.22" fill="currentColor"/>
                </svg>
                <span class="site-brand-text">
                    <span class="site-brand-title">ARTFOLIO</span>
                    <span class="site-brand-sub">ART LIVES HERE</span>
                </span>
            </a>

            {{-- Center: main links (fold into the menu on phones) --}}
            <div class="collapse navbar-collapse site-header-center" id="site-navbar-links">
                <ul class="navbar-nav site-nav-links">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ $currentActive === 'home' ? 'active' : '' }}" @if ($currentActive === 'home') aria-current="page" @endif>Home</a>
                    </li>
                    <li class="nav-item dropdown site-nav-dropdown">
                        <a href="{{ route('artworks') }}" class="nav-link dropdown-toggle {{ $currentActive === 'artworks' ? 'active' : '' }}" aria-haspopup="true" @if ($currentActive === 'artworks') aria-current="page" @endif>Artworks</a>
                        <ul class="dropdown-menu site-dropdown" aria-label="Artwork categories">
                            <li>
                                <a href="{{ route('artworks') }}" id="nav-artworks-all-link" class="dropdown-item {{ $currentActive === 'artworks' && $currentCategory === '' ? 'active' : '' }}">All artworks</a>
                            </li>
                            @if ($navCategories->isNotEmpty())
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Categories</h6></li>
                            @endif
                            @foreach ($navCategories as $category)
                                <li>
                                    <a href="{{ $category['url'] }}"
                                       class="dropdown-item {{ $currentCategory !== '' && strcasecmp($currentCategory, $category['label']) === 0 ? 'active' : '' }}"
                                       data-category-filter="{{ $category['label'] }}">{{ $category['label'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('artists') }}" class="nav-link {{ $currentActive === 'artists' ? 'active' : '' }}" @if ($currentActive === 'artists') aria-current="page" @endif>Artists</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a href="{{ route('orders') }}" class="nav-link {{ $currentActive === 'orders' ? 'active' : '' }}" @if ($currentActive === 'orders') aria-current="page" @endif>My orders</a>
                        </li>
                    @endauth
                    <li class="nav-item">
                        <a href="{{ route('about') }}" class="nav-link {{ $currentActive === 'about' ? 'active' : '' }}" @if ($currentActive === 'about') aria-current="page" @endif>About us</a>
                    </li>
                </ul>

                {{-- Phone menu only: full-width search and log in / register --}}
                <div class="site-mobile-extras d-lg-none">
                    @if ($showHeaderSearch)
                        <form action="{{ route('artworks') }}" method="GET" class="search-pill-container site-search" role="search">
                            <svg class="search-icon-left" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="search" name="search" class="search-input" placeholder="Search artworks and artists" aria-label="Search artworks and artists" value="{{ request('search') }}">
                        </form>
                    @endif
                    @guest
                        <div class="site-guest-actions">
                            <a href="{{ route('login') }}" class="btn btn-link site-login-link">Log in</a>
                            <a href="{{ route('register') }}" class="btn site-register-btn">Register</a>
                        </div>
                    @endguest
                </div>
            </div>

            {{-- Right: search, account actions, and the phone menu button --}}
            <div class="site-header-right">
                @if ($showHeaderSearch)
                    <div class="dropdown d-none d-lg-block">
                        <button type="button" class="topbar-btn" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" aria-label="Search" title="Search">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end site-search-panel">
                            <form action="{{ route('artworks') }}" method="GET" class="search-pill-container site-search" role="search">
                                <svg class="search-icon-left" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                <input type="search" name="search" class="search-input" placeholder="Search artworks and artists" aria-label="Search artworks and artists" value="{{ request('search') }}">
                            </form>
                        </div>
                    </div>
                @endif

                @auth
                    <a href="{{ route('favorites') }}" class="topbar-btn {{ $currentActive === 'favorites' ? 'is-active' : '' }}" aria-label="Favorites" title="Favorites">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </a>
                    <a href="{{ route('cart') }}" class="topbar-btn {{ $currentActive === 'cart' ? 'is-active' : '' }}" aria-label="Cart{{ $cartCount > 0 ? " ({$cartCount} items)" : '' }}" title="Cart">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        @if ($cartCount > 0)
                            <span class="account-menu-badge">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
                        @endif
                    </a>
                    @include('partials.account-menu')
                @endauth

                @guest
                    <div class="site-guest-actions d-none d-lg-flex">
                        <a href="{{ route('login') }}" class="btn btn-link site-login-link">Log in</a>
                        <a href="{{ route('register') }}" class="btn site-register-btn">Register</a>
                    </div>
                @endguest

                <button class="navbar-toggler site-navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#site-navbar-links" aria-controls="site-navbar-links" aria-expanded="false" aria-label="Toggle navigation">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    </nav>
</header>

@once
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script>
        // Put the cursor in the header search as soon as it opens.
        document.addEventListener('shown.bs.dropdown', (event) => {
            event.target.closest('.dropdown')?.querySelector('.site-search-panel .search-input')?.focus();
        });
    </script>
@endonce
