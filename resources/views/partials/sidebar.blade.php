@php
    $navCategories = [
        ['label' => 'Abstract', 'url' => url('/artworks?category=Abstract'), 'type' => 'abstract'],
        ['label' => 'Typography', 'url' => url('/artworks?category=Typography'), 'type' => 'typography'],
        ['label' => 'Pixel', 'url' => url('/artworks?category=Pixel'), 'type' => 'pixel'],
        ['label' => 'Poster', 'url' => url('/artworks?category=Poster'), 'type' => 'poster'],
        ['label' => 'Digital', 'url' => url('/artworks?category=Digital'), 'type' => 'digital'],
    ];

    $currentActive = $active ?? '';
    $currentCategory = $activeCategory ?? '';
    $isArtworksExpanded = $currentActive === 'artworks';
@endphp

<!-- ================= SHARED REUSABLE SIDEBAR ================= -->
<aside class="sidebar" id="sidebar">
    <!-- Brand Logo -->
    <div class="sidebar-brand-container">
        <svg class="brand-logo-mark" viewBox="0 0 32 32" fill="none" aria-hidden="true">
            <path d="M5.33325 27.5556L14.2221 4.44444H17.7777L11.5555 22.2222H18.6666L20.4444 27.5556H5.33325Z" fill="#1C1D1B"/>
            <path d="M19.5556 11.5556L25.7778 27.5556H21.3334L16.8889 16L19.5556 11.5556Z" fill="#1C1D1B"/>
            <circle cx="24" cy="8" r="2.22" fill="#1C1D1B"/>
        </svg>
        <div class="brand-logo-title">ARTFOLIO</div>
        <div class="brand-logo-sub">ART LIVES HERE</div>
    </div>

    <!-- Navigation Links -->
    <nav class="sidebar-nav">
        <!-- 1. Home Page -->
        <a href="{{ url('/home') }}" class="nav-item-link {{ $currentActive === 'home' ? 'active' : '' }}">
            <span class="nav-left">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3 9.5L12 3L21 9.5V20C21 20.5523 20.5523 21 20 21H15V14H9V21H4C3.44772 21 3 20.5523 3 20V9.5Z"/>
                </svg>
                <span>Home Page</span>
            </span>
        </a>

        <!-- 2. Artworks -->
        <div>
            <div class="nav-item-link-group {{ $currentActive === 'artworks' && empty($currentCategory) ? 'active' : '' }}">
                <a href="{{ url('/artworks') }}" class="nav-item-main-link" title="Explore artworks">
                    <span class="nav-left">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.49 2 2 6.49 2 12C2 17.51 6.49 22 12 22C12.83 22 13.5 21.33 13.5 20.5C13.5 20.11 13.35 19.76 13.11 19.49C12.88 19.23 12.73 18.88 12.73 18.5C12.73 17.67 13.4 17 14.23 17H16C19.31 17 22 14.31 22 11C22 6.04 17.51 2 12 2ZM6.5 12C5.67 12 5 11.33 5 10.5C5 9.67 5.67 9 6.5 9C7.33 9 8 9.67 8 10.5C8 11.33 7.33 12 6.5 12ZM9.5 8C8.67 8 8 7.33 8 6.5C8 5.67 8.67 5 9.5 5C10.33 5 11 5.67 11 6.5C11 7.33 10.33 8 9.5 8ZM14.5 8C13.67 8 13 7.33 13 6.5C13 5.67 13.67 5 14.5 5C15.33 5 16 5.67 16 6.5C16 7.33 15.33 8 14.5 8ZM17.5 12C16.67 12 16 11.33 16 10.5C16 9.67 16.67 9 17.5 9C18.33 9 19 9.67 19 10.5C19 11.33 18.33 12 17.5 12Z"/>
                        </svg>
                        <span>Artworks</span>
                    </span>
                </a>
                <div class="nav-right-actions">
                    <span class="nav-badge">05</span>
                    <button type="button" class="nav-chevron-btn" id="artworks-toggle" aria-expanded="{{ $isArtworksExpanded ? 'true' : 'false' }}" aria-controls="artworks-submenu" aria-label="Toggle artworks submenu">
                        <svg class="dropdown-chevron{{ $isArtworksExpanded ? ' is-open' : '' }}" id="artworks-chevron" width="10" height="10" viewBox="0 0 16 16" fill="none">
                            <path d="M3 6L8 11L13 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Keep category navigation expanded only while browsing artworks. -->
            <div id="artworks-submenu" class="artworks-submenu{{ $isArtworksExpanded ? ' is-open' : '' }}">
                @foreach ($navCategories as $cat)
                    @php
                        $isCatActive = !empty($currentCategory) && strcasecmp($currentCategory, $cat['label']) === 0;
                    @endphp
                    <a href="{{ $cat['url'] }}"
                       class="submenu-link {{ $isCatActive ? 'active' : '' }}"
                       data-category-filter="{{ $cat['label'] }}">
                        @if ($cat['type'] === 'abstract')
                            <svg class="submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14.93V17c0 .55-.45 1-1 1s-1-.45-1-1v-.07C8.06 16.48 6.5 14.44 6.5 12c0-3.03 2.47-5.5 5.5-5.5s5.5 2.47 5.5 5.5c0 2.44-1.56 4.48-3.5 4.93z" fill="currentColor"/>
                            </svg>
                        @elseif ($cat['type'] === 'typography')
                            <svg class="submenu-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 3L5 20H8.5L10 16H14L15.5 20H19L12 3ZM10.8 13.5L12 9.5L13.2 13.5H10.8Z"/>
                            </svg>
                        @elseif ($cat['type'] === 'pixel')
                            <svg class="submenu-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M4 4H10V10H4V4ZM14 4H20V10H14V4ZM4 14H10V20H4V14ZM14 14H20V20H14V14Z"/>
                            </svg>
                        @elseif ($cat['type'] === 'poster')
                            <svg class="submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                        @elseif ($cat['type'] === 'digital')
                            <svg class="submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                                <line x1="12" y1="18" x2="12.01" y2="18"></line>
                            </svg>
                        @endif
                        <span>{{ $cat['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- 3. Artists -->
        <a href="{{ url('/artists') }}" class="nav-item-link {{ $currentActive === 'artists' ? 'active' : '' }}">
            <span class="nav-left">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                </svg>
                <span>Artists</span>
            </span>
        </a>

        <!-- 4. Favorites -->
        <a href="{{ url('/favorites') }}" class="nav-item-link {{ $currentActive === 'favorites' ? 'active' : '' }}">
            <span class="nav-left">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17 3H7C5.9 3 5 3.9 5 5V21L12 18L19 21V5C19 3.9 18.1 3 17 3Z"/>
                </svg>
                <span>Favorites</span>
            </span>
        </a>

        <!-- 5. My cart -->
        <a href="{{ url('/cart') }}" class="nav-item-link {{ $currentActive === 'cart' ? 'active' : '' }}">
            <span class="nav-left">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M7 18C5.9 18 5.01 18.9 5.01 20C5.01 21.1 5.9 22 7 22C8.1 22 9 21.1 9 20C9 18.9 8.1 18 7 18ZM1 2V4H3L6.6 11.59L5.25 14.04C5.09 14.32 5 14.65 5 15C5 16.1 5.9 17 7 17H19V15H7.42C7.28 15 7.17 14.89 7.17 14.75L7.2 14.63L8.1 13H15.55C16.3 13 16.96 12.59 17.3 11.97L20.88 5.48C20.96 5.34 21 5.17 21 5C21 4.45 20.55 4 20 4H5.21L4.27 2H1ZM17 18C15.9 18 15.01 18.9 15.01 20C15.01 21.1 15.9 22 17 22C18.1 22 19 21.1 19 20C19 18.9 18.1 18 17 18Z"/>
                </svg>
                <span>My cart</span>
            </span>
        </a>

        <!-- 6. My orders -->
        <a href="{{ url('/orders') }}" class="nav-item-link {{ $currentActive === 'orders' ? 'active' : '' }}">
            <span class="nav-left">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21 16.5C21 16.88 20.79 17.21 20.47 17.38L12.57 21.82C12.41 21.91 12.23 21.96 12.05 21.96C11.87 21.96 11.69 21.91 11.53 21.82L3.63 17.38C3.31 17.21 3.1 16.88 3.1 16.5V7.5C3.1 7.12 3.31 6.79 3.63 6.62L11.53 2.18C11.85 2 12.25 2 12.57 2.18L20.47 6.62C20.79 6.79 21 7.12 21 7.5V16.5ZM12 4.15L5.74 7.65L12 11.16L18.26 7.65L12 4.15ZM5.1 9.25V15.75L11 19.06V12.56L5.1 9.25ZM13 19.06L18.9 15.75V9.25L13 12.56V19.06Z"/>
                </svg>
                <span>My orders</span>
            </span>
        </a>

        <!-- 7. About us -->
        <a href="{{ url('/about') }}" class="nav-item-link {{ $currentActive === 'about' ? 'active' : '' }}">
            <span class="nav-left">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 2H4C2.9 2 2 2.9 2 4V22L6 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2ZM13 14H11V10H13V14ZM13 8H11V6H13V8Z"/>
                </svg>
                <span>About us</span>
            </span>
        </a>

        <!-- 8. Log out (Anchored at bottom) -->
        <div class="sidebar-bottom">
            <form method="POST" action="{{ url('/logout') }}">
                @csrf
                <button type="submit" class="nav-item-btn">
                    <span class="nav-left">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17 7L15.59 8.41L18.17 11H8V13H18.17L15.59 15.58L17 17L22 12L17 7ZM4 5H12V3H4C2.9 3 2 3.9 2 5V19C2 20.1 2.9 21 4 21H12V19H4V5Z"/>
                        </svg>
                        <span>Log out</span>
                    </span>
                </button>
            </form>
        </div>
    </nav>
</aside>

<script>
    (function () {
        // Toggle Artworks submenu consistently
        const artworksToggle = document.getElementById('artworks-toggle');
        const artworksSubmenu = document.getElementById('artworks-submenu');
        const artworksChevron = document.getElementById('artworks-chevron');

        if (artworksToggle && artworksSubmenu && artworksChevron) {
            artworksToggle.addEventListener('click', function (e) {
                e.stopPropagation();
                const isOpen = artworksSubmenu.classList.toggle('is-open');
                artworksChevron.classList.toggle('is-open', isOpen);
                artworksToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        }

        // Toggle mobile drawer
        const mobileToggle = document.getElementById('mobile-toggle');
        const sidebar = document.getElementById('sidebar');
        if (mobileToggle && sidebar) {
            mobileToggle.addEventListener('click', function () {
                sidebar.classList.toggle('is-mobile-open');
            });
        }
    })();
</script>
