<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $artwork['title'] }} | ARTFOLIO</title>
    <meta name="description" content="{{ $artwork['description'] }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/artwork-details.css') }}">
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
    @include('partials.sidebar', ['active' => 'artworks'])

    <main class="main-content artwork-detail-content">
        <header class="top-bar">
            <form action="{{ url('/artworks') }}" method="GET" class="search-pill-container" role="search">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="search" name="search" class="search-input" placeholder="Search artworks, artists, and inspiration" aria-label="Search artworks, artists, and inspiration">
            </form>

            <div class="top-right-actions">
                <button type="button" class="notif-btn" aria-label="Notifications">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </button>
                <div class="user-avatar-pill" title="{{ $userName }}" aria-label="{{ $userName }}">{{ $userInitials }}</div>
            </div>
        </header>

        <nav class="artwork-breadcrumbs" aria-label="Breadcrumb">
            <a href="{{ url('/artworks') }}">Artworks</a>
            <span aria-hidden="true">/</span>
            <a href="{{ url('/artworks?category=' . urlencode($artwork['category'])) }}">{{ $artwork['category'] }}</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">{{ $artwork['title'] }}</span>
        </nav>

        <section class="artwork-detail-grid" aria-labelledby="artwork-title">
            <figure class="artwork-preview-card">
                <div class="artwork-preview-image-wrap">
                    <img src="{{ $artwork['image'] }}" alt="{{ $artwork['title'] }}" class="artwork-preview-image">
                </div>
                <figcaption>ARTWORK PREVIEW <span aria-hidden="true">·</span> DIGITAL DOWNLOAD</figcaption>
            </figure>

            <div class="artwork-purchase-card">
                <span class="artwork-category-label">{{ strtoupper($artwork['category']) }} ART <span aria-hidden="true">/</span> DIGITAL COLLECTION</span>
                <h1 id="artwork-title">{{ $artwork['title'] }}</h1>

                <a href="{{ url('/artists') }}" class="artwork-artist-link">
                    <span class="artwork-artist-avatar" aria-hidden="true">{{ strtoupper(substr($artwork['author'], 0, 1)) }}</span>
                    <span>{{ $artwork['author'] }}</span>
                </a>

                <div class="artwork-rating" aria-label="Rating: {{ $artwork['rating'] }} out of 5">
                    <span class="artwork-rating-stars" aria-hidden="true">★★★★★</span>
                    <span>{{ $artwork['rating'] }}</span>
                </div>

                <p class="artwork-detail-price">${{ number_format($artwork['price'], 2) }}</p>
                <p class="artwork-short-description">{{ $artwork['description'] }}</p>

                <div class="artwork-delivery-note">
                    <strong>Digital artwork · No shipping</strong>
                    <span>Download your file after purchase.</span>
                </div>

                <div class="artwork-purchase-actions">
                    <a href="{{ url('/cart') }}" class="artwork-action artwork-action-primary">Add to cart</a>
                    <a href="{{ url('/checkout') }}" class="artwork-action artwork-action-dark">
                        Buy now
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                            <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <section class="artwork-about-card" aria-labelledby="about-artwork-title">
            <div>
                <h2 id="about-artwork-title">About this artwork</h2>
                <p>{{ $artwork['description'] }}</p>
            </div>
            <dl class="artwork-metadata">
                <div><dt>Artist</dt><dd>{{ $artwork['author'] }}</dd></div>
                <div><dt>Category</dt><dd>{{ $artwork['category'] }}</dd></div>
                <div><dt>Delivery</dt><dd>Digital download</dd></div>
            </dl>
        </section>

        <section class="related-artworks-section" aria-labelledby="related-artworks-title">
            <div class="related-artworks-heading">
                <div>
                    <span class="artwork-category-label">KEEP EXPLORING</span>
                    <h2 id="related-artworks-title">More to explore</h2>
                </div>
                <a href="{{ url('/artworks') }}" class="related-view-all">View all artworks <span aria-hidden="true">→</span></a>
            </div>

            <div class="related-artworks-grid">
                @foreach ($relatedArtworks as $relatedArtwork)
                    <a href="{{ route('artworks.show', ['slug' => $relatedArtwork['slug']]) }}" class="related-artwork-card">
                        <span class="related-artwork-image-wrap">
                            <img src="{{ $relatedArtwork['image'] }}" alt="{{ $relatedArtwork['title'] }}" loading="lazy">
                        </span>
                        <span class="related-artwork-title">{{ $relatedArtwork['title'] }}</span>
                        <span class="related-artwork-artist">by {{ $relatedArtwork['author'] }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    </main>
</div>

</body>
</html>
