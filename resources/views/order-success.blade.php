<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - Order Confirmation</title>
    <meta name="description" content="Your next masterpiece is yours. Payment received. Your digital artwork is ready to download.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Dedicated CSS Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order-success.css') }}">
</head>
<body>

@php
    $categories = [
        ['label' => 'Abstract', 'url' => url('/artworks?category=Abstract'), 'type' => 'abstract'],
        ['label' => 'Typography', 'url' => url('/artworks?category=Typography'), 'type' => 'typography'],
        ['label' => 'Pixel', 'url' => url('/artworks?category=Pixel'), 'type' => 'pixel'],
        ['label' => 'Poster', 'url' => url('/artworks?category=Poster'), 'type' => 'poster'],
        ['label' => 'Digital', 'url' => url('/artworks?category=Digital'), 'type' => 'digital'],
    ];

    $purchasedArtworks = [
        [
            'id' => 1,
            'title' => 'Fluid Geometric',
            'author' => 'Cristine Joy Montes',
            'price' => 28.56,
            'image' => asset('images/artwork-fluid-geometric.png'),
        ],
        [
            'id' => 2,
            'title' => 'Stranger Things Poster',
            'author' => 'Gian Gomez',
            'price' => 35.76,
            'image' => asset('images/artwork-stranger-things.png'),
        ],
        [
            'id' => 3,
            'title' => 'Minimalist Organic',
            'author' => 'Gian Gomez',
            'price' => 15.67,
            'image' => asset('images/artwork-minimalist-organic.png'),
        ],
    ];

    $userInitials = 'CJ';
    if (Auth::check() && Auth::user()->name) {
        $words = explode(' ', trim(Auth::user()->name));
        if (count($words) >= 2) {
            $userInitials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        } else {
            $userInitials = strtoupper(substr(Auth::user()->name, 0, 2));
        }
    }
@endphp

@include('partials.mobile-header')

<div class="app-layout">

    @include('partials.sidebar', ['active' => 'orders'])

    <!-- ================= MAIN CONTENT ================= -->
    <main class="main-content">

        <!-- Top Bar -->
        <header class="top-bar">
            <!-- Search Pill -->
            <div class="search-pill-container">
                <svg class="search-icon-left" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input
                    type="text"
                    id="order-search-input"
                    class="search-input"
                    placeholder="Search artworks, artists, and inspiration"
                    aria-label="Search artworks, artists, and inspiration"
                    value=""
                >
            </div>

            <!-- Top Actions -->
            <div class="top-bar-actions">
                <button type="button" class="topbar-btn" title="Notifications" aria-label="Notifications">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </button>
                <div class="user-avatar-pill" title="{{ Auth::check() ? Auth::user()->name : 'User Profile' }}">
                    {{ $userInitials }}
                </div>
            </div>
        </header>

        <!-- ================= TOP SUCCESS BANNER ================= -->
        <div class="success-banner-card">
            <div class="success-check-badge">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h1 class="success-title">Your next masterpiece is yours.</h1>
            <p class="success-subtitle">Payment received. Your digital artwork is ready to download.</p>
            <div class="success-meta-row">
                <span class="order-number-tag">ORDER #ART-1048</span>
                <span>·</span>
                <span>3 artworks</span>
                <span>·</span>
                <span>$79.99 paid</span>
            </div>
            <p class="success-email-notice">A confirmation and receipt have been sent to your email.</p>
        </div>

        <!-- ================= 2-COLUMN ORDER DETAILS ================= -->
        <div class="order-success-grid">

            <!-- Left Column: Your digital artworks -->
            <section class="digital-artworks-card">
                <h2 class="digital-card-title">Your digital artworks</h2>
                <p class="digital-card-desc">Download now, or find your files anytime in My Orders.</p>
                <div class="digital-card-divider"></div>

                <div class="digital-items-list">
                    @foreach ($purchasedArtworks as $art)
                        <div class="digital-item-row">
                            <div class="digital-item-left">
                                <div class="digital-item-thumb">
                                    <img src="{{ $art['image'] }}" alt="{{ $art['title'] }}" class="digital-item-img">
                                </div>
                                <div class="digital-item-info">
                                    <h3 class="digital-item-name">{{ $art['title'] }}</h3>
                                    <p class="digital-item-author">by {{ $art['author'] }}</p>
                                </div>
                            </div>
                            <a href="{{ $art['image'] }}" download="{{ Str::slug($art['title']) }}.png" class="btn-download-art" title="Download {{ $art['title'] }}">
                                <span>Download</span>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <polyline points="19 12 12 19 5 12"></polyline>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Right Column: Payment summary -->
            <aside class="payment-summary-card">
                <h2 class="summary-card-title">Payment summary</h2>

                <div class="summary-meta-row">
                    <span class="summary-meta-label">Payment status</span>
                    <span class="summary-meta-val">Paid</span>
                </div>

                <div class="summary-meta-row">
                    <span class="summary-meta-label">Payment method</span>
                    <span class="summary-meta-val">Card</span>
                </div>

                <div class="summary-card-divider"></div>

                <div class="summary-paid-row">
                    <span class="summary-paid-label">Total paid</span>
                    <span class="summary-paid-val">$79.99</span>
                </div>

                <button type="button" class="btn-download-receipt" id="btn-download-receipt">
                    <span>Download receipt</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <polyline points="19 12 12 19 5 12"></polyline>
                    </svg>
                </button>

                <p class="receipt-no-shipping">Digital purchase · No shipping required</p>
            </aside>

        </div>

        <!-- ================= BOTTOM ACTION BUTTONS ================= -->
        <div class="success-bottom-actions">
            <a href="{{ url('/orders') }}" class="btn-go-orders">
                <span>Go to My Orders</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
            <a href="{{ url('/artworks') }}" class="btn-continue-nav">
                <span>Continue exploring</span>
            </a>
        </div>

    </main>
</div>

<!-- Interactive Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- 1. Receipt Download Simulation ---
        const receiptBtn = document.getElementById('btn-download-receipt');
        if (receiptBtn) {
            receiptBtn.addEventListener('click', function () {
                const receiptContent = `================================================
ARTFOLIO - OFFICIAL PURCHASE RECEIPT
================================================
Order ID:       #ART-1048
Date:           ${new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}
Status:         Paid
Payment Method: Credit / Debit Card
------------------------------------------------
ITEMS PURCHASED:
1. Fluid Geometric by Cristine Joy Montes     $28.56
2. Stranger Things Poster by Gian Gomez       $35.76
3. Minimalist Organic by Gian Gomez           $15.67
------------------------------------------------
TOTAL PAID:                                   $79.99
================================================
Type: Digital Download (No shipping required)
Thank you for supporting independent artists!
Art lives here - Artfolio
================================================`;

                const blob = new Blob([receiptContent], { type: 'text/plain;charset=utf-8' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'ARTFOLIO-Receipt-ART-1048.txt';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(link.href);
            });
        }
    });
</script>

</body>
</html>
