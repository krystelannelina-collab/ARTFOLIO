<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - About Us</title>
    <meta name="description" content="Discover how ARTFOLIO brings digital art and creative voices together.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
</head>
<body>

@php
    $userName = Auth::user()->name ?? 'User';
    $nameParts = preg_split('/\s+/', trim($userName));
    $userInitials = strtoupper(substr($nameParts[0] ?? 'U', 0, 1));
    if (count($nameParts) > 1) {
        $userInitials .= strtoupper(substr($nameParts[1], 0, 1));
    }
@endphp

@include('partials.mobile-header')

<div class="app-layout">
    @include('partials.sidebar', ['active' => 'about'])

    <main class="main-content about-content">
        <header class="top-bar">
            <form action="{{ url('/artworks') }}" method="GET" class="search-pill-container" role="search">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input
                    type="search"
                    name="search"
                    class="search-input"
                    placeholder="Search artworks, artists, and inspiration"
                    aria-label="Search artworks, artists, and inspiration"
                >
            </form>

            <div class="top-right-actions">
                <button type="button" class="notif-btn" aria-label="Notifications">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </button>
                <div class="user-avatar-pill" title="{{ $userName }}" aria-label="{{ $userName }}">
                    {{ $userInitials }}
                </div>
            </div>
        </header>

        <section class="about-hero" aria-labelledby="about-title">
            <div class="about-hero-copy">
                <span class="about-eyebrow">ART LIVES HERE</span>
                <h1 id="about-title">A space for art.<br>A place for you.</h1>
                <p>ARTFOLIO brings digital art and creative voices together. Discover pieces you connect with and support the people who make them.</p>
            </div>
            <img src="{{ asset('images/hero-banner.png') }}" alt="A bright, welcoming artist studio filled with works in progress" class="about-hero-image">
        </section>

        <section class="about-intro" aria-labelledby="why-artfolio-title">
            <div class="about-intro-heading">
                <span class="about-eyebrow">WHY ARTFOLIO</span>
                <h2 id="why-artfolio-title">Creativity deserves<br>to be discovered.</h2>
            </div>
            <div class="about-intro-copy">
                <h3>We make discovering digital art feel personal.</h3>
                <p>From expressive abstracts to cinematic posters, every artwork offers a different perspective. ARTFOLIO is a place to explore those perspectives, find new favorites, and meet the artists behind them.</p>
                <p class="about-intro-note">Find what moves you. Make it part of your collection.</p>
            </div>
        </section>

        <section class="about-values" aria-label="What you can do on ARTFOLIO">
            <article class="about-value-card">
                <span class="about-value-number">01</span>
                <h3>Discover your style</h3>
                <p>Explore digital artwork across abstract, typography, pixel, poster, and digital art categories.</p>
            </article>
            <article class="about-value-card">
                <span class="about-value-number">02</span>
                <h3>Meet the creators</h3>
                <p>Get to know the artists behind the work and explore their individual perspectives.</p>
            </article>
            <article class="about-value-card">
                <span class="about-value-number">03</span>
                <h3>Keep the art you love</h3>
                <p>Save your favorites and access purchased artwork downloads through My Orders.</p>
            </article>
        </section>

        <section class="about-cta" aria-labelledby="about-cta-title">
            <div class="about-cta-artworks" aria-hidden="true">
                <img src="{{ asset('images/artwork-fluid-geometric.png') }}" alt="" loading="lazy">
                <img src="{{ asset('images/artwork-minimalist-organic.png') }}" alt="" loading="lazy">
            </div>
            <div class="about-cta-copy">
                <span class="about-eyebrow">YOUR NEXT FAVORITE IS OUT THERE</span>
                <h2 id="about-cta-title">Bring a little more art into your day.</h2>
                <p>Explore the collection or get to know our artists.</p>
                <div class="about-cta-actions">
                    <a href="{{ url('/artworks') }}" class="about-button about-button-primary">
                        Explore artworks
                        <svg width="13" height="13" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                            <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a href="{{ url('/artists') }}" class="about-button about-button-secondary">Meet the artists</a>
                </div>
            </div>
        </section>

        <footer class="about-footer">
            <span>ARTFOLIO</span>
            <span aria-hidden="true">·</span>
            <span>Art lives here.</span>
        </footer>
    </main>
</div>

</body>
</html>
