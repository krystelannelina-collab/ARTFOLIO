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
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
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


<div class="app-layout">

    @include('partials.site-header', ['active' => 'home'])

    <!-- ================= MAIN CONTENT ================= -->
    <main class="main-content">

        <!-- Hero Card -->
        <section class="hero-banner">
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
                                <span class="edition-label">One of one</span>
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
