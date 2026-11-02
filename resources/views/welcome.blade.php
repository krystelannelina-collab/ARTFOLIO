<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - Home</title>
    <meta name="description" content="Discover original art and the artists behind it">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Dedicated CSS Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

@php
    $featuredArtworks = $featuredArtworks ?? [];

    $userInitials = 'CJ';
    if (Auth::check() && !empty(Auth::user()->name)) {
        $nameParts = explode(' ', trim(Auth::user()->name));
        if (count($nameParts) >= 2) {
            $userInitials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
        } else {
            $userInitials = strtoupper(substr($nameParts[0], 0, 2));
        }
    }
@endphp

@include('partials.mobile-header')

<div class="app-layout">

    @include('partials.sidebar', ['active' => 'home'])

    <!-- ================= MAIN CONTENT ================= -->
    <main class="main-content">

        <!-- Top Bar -->
        <header class="top-bar">
            <!-- Search Pill -->
            <form action="{{ url('/artworks') }}" method="GET" class="search-pill-container" role="search">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input
                    type="search"
                    name="search"
                    class="search-input"
                    placeholder="Search artworks, artists, and inspiration"
                    aria-label="Search artworks, artists, and inspiration"
                    value="{{ request('search') }}"
                >
            </form>

            <!-- Actions (Notification Bell & Profile Avatar) -->
            <div class="top-right-actions">
                <button type="button" class="notif-btn" aria-label="Notifications">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </button>

                <div class="user-avatar-pill" title="{{ Auth::user()->name ?? 'User' }}">
                    {{ $userInitials }}
                </div>
            </div>
        </header>

        <!-- Hero Card -->
        <section class="hero-banner">
            <!-- Painter studio image background -->
            <img
                src="{{ asset('images/hero-banner.png') }}"
                alt=""
                class="hero-banner-img"
            >
            <div class="hero-banner-overlay"></div>

            <div class="hero-banner-content">
                <span class="hero-badge-tag">A SPACE FOR ORIGINAL IDEAS</span>
                <h1 class="hero-heading">Find your next<br>masterpiece.</h1>
                <p class="hero-desc">Discover original art and the artists behind it.</p>
                <a href="{{ url('/artworks') }}" class="hero-explore-btn">
                    <span>Explore collection</span>
                    <svg width="13" height="13" viewBox="0 0 16 16" fill="none">
                        <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </section>

        <!-- Featured Artworks Section -->
        <section aria-labelledby="featured-artworks-title">
            <div class="featured-header-row">
                <div>
                    <h2 id="featured-artworks-title" class="featured-title">Featured artworks</h2>
                    <p class="featured-subtitle">Handpicked pieces. Independent voices.</p>
                </div>
                <a href="{{ url('/artworks') }}" class="view-all-pill-btn">
                    <span>View all artworks</span>
                    <svg width="11" height="11" viewBox="0 0 16 16" fill="none">
                        <path d="M4 12L12 4M12 4H6M12 4V10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            <!-- Artworks 3-Column Grid -->
            <div class="artworks-grid">
                @foreach ($featuredArtworks as $art)
                    <article class="art-card">
                        <a href="{{ $art['url'] }}" class="art-img-container" aria-label="View {{ $art['title'] }} details">
                            <img src="{{ $art['image'] }}" alt="{{ $art['title'] }}" class="art-img" loading="lazy">
                        </a>
                        <div class="art-content">
                            <span class="art-tag-label">{{ $art['category'] }}</span>
                            <h3 class="art-title-text"><a href="{{ $art['url'] }}">{{ $art['title'] }}</a></h3>
                            <span class="art-author-text">by {{ $art['author'] }}</span>

                            <div class="art-footer-actions">
                                <div class="art-rating-box">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="#E5A83B">
                                        <path d="M12 1.5l3.09 6.26L22 8.77l-5 4.87 1.18 6.86L12 17.27l-6.18 3.23L7 13.64l-5-4.87 6.91-1.01L12 1.5z"/>
                                    </svg>
                                    <span>{{ $art['rating'] }}</span>
                                </div>
                                <a href="{{ $art['url'] }}" class="art-details-btn">
                                    <span>View details</span>
                                    <svg width="9" height="9" viewBox="0 0 16 16" fill="none">
                                        <path d="M4 12L12 4M12 4H6M12 4V10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Footer Tagline -->
            <div class="bottom-tagline">
                <span class="tagline-primary">ORIGINAL ART. ENDLESS POSSIBILITIES.</span>
                <span class="tagline-secondary">Discover something that feels like you.</span>
            </div>
        </section>

    </main>
</div>

</body>
</html>
