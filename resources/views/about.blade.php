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
            <img src="{{ asset('images/hero-banner.png') }}" alt="" class="about-hero-image">
            <div class="about-hero-overlay"></div>
            <div class="about-hero-copy">
                <span class="about-eyebrow">ART LIVES HERE <span aria-hidden="true">·</span> MADE TO BE DISCOVERED</span>
                <h1 id="about-title">Find art that<br>feels like <em>you.</em></h1>
                <p>Meet original digital art and the independent voices behind it. Your next favorite is waiting to be found.</p>
                <a href="{{ url('/artworks') }}" class="about-button about-button-primary">
                    Explore the collection
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                        <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
            <span class="about-hero-index" aria-hidden="true">01 / 04</span>
        </section>

        <section class="about-intro" aria-labelledby="why-artfolio-title">
            <div class="about-intro-heading">
                <span class="about-eyebrow">A DIFFERENT KIND OF ART SPACE</span>
                <h2 id="why-artfolio-title">Creativity deserves to be <em>discovered.</em></h2>
            </div>
            <div class="about-intro-copy">
                <p>We bring digital art and creative voices together in one place. Wander through expressive abstracts, bold typography, pixel art, and cinematic posters—each with a person and a perspective behind it.</p>
                <a href="{{ url('/artists') }}" class="about-text-link">
                    Get to know the artists
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                        <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </section>

        <section class="about-gallery" aria-labelledby="about-gallery-title">
            <div class="about-section-heading">
                <div>
                    <span class="about-eyebrow">A FEW PLACES TO BEGIN</span>
                    <h2 id="about-gallery-title">Art with its own point of view.</h2>
                </div>
                <a href="{{ url('/artworks') }}" class="about-text-link">
                    Browse all artworks
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                        <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            <div class="about-artwork-grid">
                @foreach ($featuredArtworks ?? [] as $index => $artwork)
                    <a href="{{ $artwork['url'] }}" class="about-artwork-card {{ $index === 0 ? 'about-artwork-card-featured' : '' }}">
                        <img src="{{ $artwork['image'] }}" alt="{{ $artwork['title'] }} by {{ $artwork['author'] }}" loading="lazy">
                        <span class="about-artwork-caption">
                            <span class="about-artwork-category">{{ $artwork['category'] }}</span>
                            <span class="about-artwork-title">{{ $artwork['title'] }}</span>
                            <span class="about-artwork-artist">{{ $artwork['author'] }}</span>
                        </span>
                        <span class="about-artwork-arrow" aria-hidden="true">↗</span>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="about-values" aria-labelledby="about-values-title">
            <div class="about-section-heading about-values-heading">
                <div>
                    <span class="about-eyebrow">YOUR ART JOURNEY</span>
                    <h2 id="about-values-title">Follow what moves you.</h2>
                </div>
            </div>
            <div class="about-values-grid">
                <article class="about-value-card">
                    <span class="about-value-number">01</span>
                    <h3>Find your style</h3>
                    <p>Explore abstract, typography, pixel, poster, and digital art.</p>
                </article>
                <article class="about-value-card">
                    <span class="about-value-number">02</span>
                    <h3>Meet the makers</h3>
                    <p>Discover the artists and perspectives behind every piece.</p>
                </article>
                <article class="about-value-card">
                    <span class="about-value-number">03</span>
                    <h3>Keep what you love</h3>
                    <p>Save your favorites and revisit the art that speaks to you.</p>
                </article>
            </div>
        </section>

        <section class="about-cta" aria-labelledby="about-cta-title">
            <span class="about-cta-orbit about-cta-orbit-one" aria-hidden="true"></span>
            <span class="about-cta-orbit about-cta-orbit-two" aria-hidden="true"></span>
            <div class="about-cta-copy">
                <span class="about-eyebrow">YOUR NEXT FAVORITE IS OUT THERE</span>
                <h2 id="about-cta-title">Bring a little more art into your day.</h2>
                <p>Take a look around. You might find something that feels like it was made for you.</p>
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
