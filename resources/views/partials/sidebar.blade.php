@php
    $navCategories = $storeCategories ?? [];
    foreach ($catalogCategories ?? [] as $catalogCategory) {
        $categoryExists = collect($navCategories)->contains(
            fn (array $category): bool => strcasecmp($category['label'], $catalogCategory) === 0,
        );

        if (! $categoryExists) {
            $navCategories[] = [
                'label' => $catalogCategory,
                'url' => url('/artworks?category='.urlencode($catalogCategory)),
                'type' => 'abstract',
            ];
        }
    }

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
                    <path d="M10 20V14H14V20H19V12H22L12 3L2 12H5V20H10Z"/>
                </svg>
                <span>Home Page</span>
            </span>
        </a>

        <!-- 2. Artworks -->
        <div>
            <div class="nav-item-link-group {{ $currentActive === 'artworks' && empty($currentCategory) ? 'active' : '' }}">
                <a href="{{ url('/artworks') }}" class="nav-item-main-link" id="nav-artworks-all-link" title="Explore artworks">
                    <span class="nav-left">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.49 2 2 6.49 2 12C2 17.51 6.49 22 12 22C12.83 22 13.5 21.33 13.5 20.5C13.5 20.11 13.35 19.76 13.11 19.49C12.88 19.23 12.73 18.88 12.73 18.5C12.73 17.67 13.4 17 14.23 17H16C19.31 17 22 14.31 22 11C22 6.04 17.51 2 12 2ZM6.5 12C5.67 12 5 11.33 5 10.5C5 9.67 5.67 9 6.5 9C7.33 9 8 9.67 8 10.5C8 11.33 7.33 12 6.5 12ZM9.5 8C8.67 8 8 7.33 8 6.5C8 5.67 8.67 5 9.5 5C10.33 5 11 5.67 11 6.5C11 7.33 10.33 8 9.5 8ZM14.5 8C13.67 8 13 7.33 13 6.5C13 5.67 13.67 5 14.5 5C15.33 5 16 5.67 16 6.5C16 7.33 15.33 8 14.5 8ZM17.5 12C16.67 12 16 11.33 16 10.5C16 9.67 16.67 9 17.5 9C18.33 9 19 9.67 19 10.5C19 11.33 18.33 12 17.5 12Z"/>
                        </svg>
                        <span>Artworks</span>
                    </span>
                </a>
                <div class="nav-right-actions">
                    <span class="nav-badge">{{ str_pad((string) count($navCategories), 2, '0', STR_PAD_LEFT) }}</span>
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
                            {{-- Shapes: circle, triangle and square. --}}
                            <svg class="submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round">
                                <circle cx="6.5" cy="17" r="3.5"/>
                                <path d="M17 2.5L21 9H13L17 2.5Z"/>
                                <rect x="13" y="13" width="8" height="8"/>
                            </svg>
                        @elseif ($cat['type'] === 'typography')
                            {{-- Bold letter A. --}}
                            <svg class="submenu-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9.6 3H14.4L21 21H16.6L15.3 17H8.7L7.4 21H3L9.6 3ZM9.9 13.4H14.1L12 7L9.9 13.4Z"/>
                            </svg>
                        @elseif ($cat['type'] === 'pixel')
                            {{-- Framed pixel blocks. --}}
                            <svg class="submenu-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M2 2H22V22H2V2ZM4.5 4.5V19.5H19.5V4.5H4.5Z"/>
                                <rect x="6.5" y="6.5" width="5" height="5"/>
                                <rect x="13" y="6.5" width="4.5" height="5"/>
                                <rect x="6.5" y="13" width="5" height="4.5"/>
                                <rect x="13" y="13" width="2" height="2"/>
                                <rect x="15.5" y="15.5" width="2" height="2"/>
                            </svg>
                        @elseif ($cat['type'] === 'poster')
                            {{-- Portrait face. --}}
                            <svg class="submenu-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 11.75C8.31 11.75 7.75 12.31 7.75 13C7.75 13.69 8.31 14.25 9 14.25C9.69 14.25 10.25 13.69 10.25 13C10.25 12.31 9.69 11.75 9 11.75ZM15 11.75C14.31 11.75 13.75 12.31 13.75 13C13.75 13.69 14.31 14.25 15 14.25C15.69 14.25 16.25 13.69 16.25 13C16.25 12.31 15.69 11.75 15 11.75ZM12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 11.71 4.02 11.42 4.05 11.14C6.41 10.09 8.28 8.16 9.26 5.77C11.07 8.33 14.05 10 17.42 10C18.2 10 18.95 9.91 19.67 9.74C19.88 10.45 20 11.21 20 12C20 16.41 16.41 20 12 20Z"/>
                            </svg>
                        @elseif ($cat['type'] === 'digital')
                            {{-- Desktop screen. --}}
                            <svg class="submenu-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2H20C21.1 2 22 2.9 22 4V16C22 17.1 21.1 18 20 18H13.5V20H17V22H7V20H10.5V18H4C2.9 18 2 17.1 2 16V4C2 2.9 2.9 2 4 2ZM4 4V16H20V4H4Z"/>
                                <rect x="6" y="6" width="12" height="8" rx="1"/>
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
                {{-- Bookmark with a heart badge. --}}
                <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15 2H5C3.9 2 3 2.9 3 4V22L10 19L11.2 19.5C11.07 18.85 11 18.18 11 17.5C11 13.36 14.36 10 18.5 10C18.67 10 18.83 10 19 10.02V6C19 3.8 17.2 2 15 2Z"/>
                    <circle cx="18.5" cy="17.5" r="5.5"/>
                    <path d="M18.5 20.6L17.95 20.1C16.1 18.43 14.9 17.35 14.9 16.02C14.9 14.94 15.75 14.1 16.82 14.1C17.43 14.1 18.01 14.38 18.5 14.84C18.99 14.38 19.57 14.1 20.18 14.1C21.25 14.1 22.1 14.94 22.1 16.02C22.1 17.35 20.9 18.43 19.05 20.1L18.5 20.6Z" fill="#ffffff"/>
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
                    {{-- Solid box: three faces split by thin gaps. --}}
                    <path d="M12 1.8L20.9 6.7L12 11.6L3.1 6.7L12 1.8Z"/>
                    <path d="M2.5 8.1L11.2 12.9V22.4L2.5 17.6V8.1Z"/>
                    <path d="M21.5 8.1V17.6L12.8 22.4V12.9L21.5 8.1Z"/>
                </svg>
                <span>My orders</span>
            </span>
        </a>

        <!-- 7. About us -->
        <a href="{{ url('/about') }}" class="nav-item-link {{ $currentActive === 'about' ? 'active' : '' }}">
            <span class="nav-left">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13 17H11V11H13V17ZM13 9H11V7H13V9Z"/>
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
