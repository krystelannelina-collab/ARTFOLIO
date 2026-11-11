<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $artwork['title'] }} | ARTFOLIO</title>
    <meta name="description" content="{{ $artwork['description'] }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
                @include('partials.account-menu')
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
                <div class="artwork-preview-image-wrap {{ $artwork['is_purchased_by_viewer'] ? '' : 'is-locked' }}">
                    <img src="{{ $artwork['image'] }}" alt="{{ $artwork['title'] }}" class="artwork-preview-image" draggable="false">
                    @unless ($artwork['is_purchased_by_viewer'])
                        <div class="artwork-lock-overlay" aria-hidden="true">
                            <span class="artwork-lock-icon">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="4" y="11" width="16" height="10" rx="2"></rect>
                                    <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                                </svg>
                            </span>
                            <span class="artwork-lock-text">Full resolution unlocks after purchase</span>
                        </div>
                    @endunless
                </div>
                <figcaption>
                    @if ($artwork['is_purchased_by_viewer'])
                        YOU OWN THIS ARTWORK <span aria-hidden="true">·</span> ORIGINAL FILE AVAILABLE
                    @else
                        PREVIEW ONLY <span aria-hidden="true">·</span> ORIGINAL FILE AFTER PURCHASE
                    @endif
                </figcaption>
            </figure>

            <div class="artwork-purchase-card">
                <span class="artwork-category-label">{{ strtoupper($artwork['category']) }} ART <span aria-hidden="true">/</span> DIGITAL COLLECTION</span>
                <h1 id="artwork-title">{{ $artwork['title'] }}</h1>

                <a href="{{ url('/artists') }}" class="artwork-artist-link">
                    <span class="artwork-artist-avatar" aria-hidden="true">{{ strtoupper(substr($artwork['author'], 0, 1)) }}</span>
                    <span>{{ $artwork['author'] }}</span>
                </a>

                <span class="edition-label">One of one · Original digital artwork</span>

                <p class="artwork-detail-price">@peso($artwork['price'])</p>
                <p class="artwork-short-description">{{ $artwork['description'] }}</p>

                @if (session('status'))
                    <p class="artwork-status-notice" role="status">{{ session('status') }}</p>
                @endif

                <div class="artwork-delivery-note">
                    @if ($artwork['is_purchased_by_viewer'])
                        <strong>You own this one-of-one artwork</strong>
                        <span>Download the original full-resolution file anytime.</span>
                    @elseif ($artwork['availability'] === 'sold')
                        <strong>Sold · One of one</strong>
                        <span>This original has been collected and is no longer for sale.</span>
                    @elseif ($artwork['availability'] === 'reserved')
                        <strong>Reserved · Payment under review</strong>
                        <span>Another collector's payment for this original is being reviewed.</span>
                    @else
                        <strong>One of one · Digital original</strong>
                        <span>Only one collector can buy this artwork. The full-resolution file unlocks once your payment is approved.</span>
                    @endif
                </div>

                <div class="artwork-purchase-actions">
                    @if ($artwork['is_purchased_by_viewer'] && $artwork['download_url'])
                        <a href="{{ $artwork['download_url'] }}" class="artwork-action artwork-action-primary w-100">Download original</a>
                    @elseif ($artwork['is_available'])
                    <form method="POST" action="{{ route('cart.add', ['slug' => $artwork['slug']]) }}">
                        @csrf
                        <button type="submit" class="artwork-action artwork-action-primary w-100">Add to cart</button>
                    </form>
                    <form method="POST" action="{{ route('checkout.buy-now', ['slug' => $artwork['slug']]) }}">
                        @csrf
                        <button type="submit" class="artwork-action artwork-action-dark w-100">
                            Buy now
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </form>
                    @else
                        <button type="button" class="artwork-action artwork-action-primary w-100" disabled>
                            {{ $artwork['availability'] === 'sold' ? 'Sold' : 'Reserved' }}
                        </button>
                    @endif
                    <form method="POST" action="{{ route('favorites.toggle', ['slug' => $artwork['slug']]) }}" class="artwork-favorite-form" data-favorite-form>
                        @csrf
                        <button type="submit"
                                class="artwork-action artwork-action-outline w-100 {{ ! empty($artwork['is_favorited']) ? 'is-favorited' : '' }}"
                                aria-pressed="{{ ! empty($artwork['is_favorited']) ? 'true' : 'false' }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="{{ ! empty($artwork['is_favorited']) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <span data-favorite-label>{{ ! empty($artwork['is_favorited']) ? 'Saved in favorites' : 'Save to favorites' }}</span>
                        </button>
                    </form>
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
                <div><dt>Edition</dt><dd>One of one</dd></div>
                @foreach ($artwork['specifications'] as $specLabel => $specValue)
                    <div><dt>{{ $specLabel }}</dt><dd>{{ $specValue }}</dd></div>
                @endforeach
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        document.querySelectorAll('[data-favorite-form]').forEach(favForm => {
            favForm.addEventListener('submit', async function (e) {
                if (!window.fetch || !csrfToken) {
                    return;
                }
                e.preventDefault();

                const button = this.querySelector('button[type="submit"]');
                const svg = button ? button.querySelector('svg') : null;
                const label = button ? button.querySelector('[data-favorite-label]') : null;

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
                    }

                    if (svg) {
                        svg.setAttribute('fill', isFav ? 'currentColor' : 'none');
                    }

                    if (label) {
                        label.textContent = isFav ? 'Saved in favorites' : 'Save to favorites';
                    }
                } catch (err) {
                    this.submit();
                }
            });
        });
    });
</script>

</body>
</html>
