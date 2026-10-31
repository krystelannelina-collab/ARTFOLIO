<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - Checkout</title>
    <meta name="description" content="Complete your details below to place your order.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Dedicated CSS Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
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

    $orderItems = [
        [
            'id' => 1,
            'title' => 'Fluid Geometric',
            'author' => 'Cristine Joy Montes',
            'price' => 28.56,
            'quantity' => 1,
            'image' => asset('images/artwork-fluid-geometric.png'),
        ],
        [
            'id' => 2,
            'title' => 'Stranger Things Poster',
            'author' => 'Gian Gomez',
            'price' => 35.76,
            'quantity' => 1,
            'image' => asset('images/artwork-stranger-things.png'),
        ],
        [
            'id' => 3,
            'title' => 'Minimalist Organic',
            'author' => 'Gian Gomez',
            'price' => 15.67,
            'quantity' => 1,
            'image' => asset('images/artwork-minimalist-organic.png'),
        ],
    ];

    $total = array_sum(array_map(function ($item) {
        return $item['price'] * $item['quantity'];
    }, $orderItems));

    $userInitials = 'CJ';
    $userEmail = Auth::check() ? Auth::user()->email : '';
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

    @include('partials.sidebar', ['active' => 'cart'])

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
                    id="checkout-search-input"
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

        <!-- Page Header -->
        <div class="checkout-header-section">
            <div class="header-breadcrumb-tag">CART / CHECKOUT</div>
            <h1 class="header-main-title">Make it yours.</h1>
            <p class="header-description">Complete your details below to place your order.</p>
        </div>

        <!-- Main 2-Column Layout -->
        <div class="checkout-layout-grid">

            <!-- LEFT COLUMN: Forms -->
            <div class="checkout-forms-col">

                <!-- 1. Contact Information Card -->
                <section class="checkout-step-card">
                    <h2 class="step-title">1. Contact information</h2>
                    <div class="form-group">
                        <label for="contact-email" class="form-label">Email address</label>
                        <input
                            type="email"
                            id="contact-email"
                            class="form-control-custom"
                            placeholder="you@example.com"
                            value="{{ $userEmail }}"
                            required
                        >
                    </div>
                </section>

                <!-- Notice Banner: Digital art. Ready when you are. -->
                <div class="digital-notice-banner">
                    <h3 class="notice-title">Digital art. Ready when you are.</h3>
                    <p class="notice-text">
                        Your files will be available in My Orders after payment.<br>
                        An order confirmation and receipt will be sent to your email.
                    </p>
                </div>

                <!-- 2. Payment Method Card -->
                <section class="checkout-step-card">
                    <h2 class="step-title">2. Payment method</h2>

                    <!-- Payment Option Tabs -->
                    <div class="payment-tabs-group" role="tablist">
                        <button type="button" class="payment-tab-btn active" id="tab-credit-card" role="tab" aria-selected="true">
                            <span class="tab-dot"></span>
                            <span>Credit / debit card</span>
                        </button>
                        <button type="button" class="payment-tab-btn" id="tab-paypal" role="tab" aria-selected="false">
                            <span class="tab-dot"></span>
                            <span>PayPal</span>
                        </button>
                    </div>

                    <!-- Card Form Fields -->
                    <div id="credit-card-fields">
                        <div class="form-group">
                            <label for="card-name" class="form-label">Cardholder name</label>
                            <input
                                type="text"
                                id="card-name"
                                class="form-control-custom"
                                placeholder="Name as shown on card"
                            >
                        </div>

                        <div class="form-group">
                            <label for="card-number" class="form-label">Card number</label>
                            <input
                                type="text"
                                id="card-number"
                                class="form-control-custom"
                                placeholder="0000 0000 0000 0000"
                                maxlength="19"
                            >
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="card-expiry" class="form-label">Expiry date</label>
                                <input
                                    type="text"
                                    id="card-expiry"
                                    class="form-control-custom"
                                    placeholder="MM / YY"
                                    maxlength="7"
                                >
                            </div>
                            <div class="form-group">
                                <label for="card-cvc" class="form-label">Security code</label>
                                <input
                                    type="text"
                                    id="card-cvc"
                                    class="form-control-custom"
                                    placeholder="CVC"
                                    maxlength="4"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- PayPal info (hidden by default) -->
                    <div id="paypal-fields" style="display: none; padding: 16px 0; font-size: 13px; color: #52525B;">
                        You will be redirected to PayPal to complete your purchase securely.
                    </div>
                </section>

                <!-- Back to cart button -->
                <div>
                    <a href="{{ url('/cart') }}" class="btn-back-to-cart">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        <span>Back to cart</span>
                    </a>
                </div>

            </div>

            <!-- RIGHT COLUMN: Your order summary -->
            <aside class="order-checkout-card">
                <h2 class="order-checkout-title">Your order</h2>
                <p class="order-checkout-subtitle">{{ count($orderItems) }} artworks</p>

                <!-- Items mini list -->
                <div class="checkout-items-list">
                    @foreach ($orderItems as $item)
                        <div class="checkout-item-row">
                            <div class="checkout-item-thumb">
                                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="checkout-item-img" loading="lazy">
                            </div>
                            <div class="checkout-item-info">
                                <div class="checkout-item-name">{{ $item['title'] }}</div>
                                <div class="checkout-item-meta">Qty {{ $item['quantity'] }} · ${{ number_format($item['price'], 2) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="order-divider"></div>

                <div class="checkout-subtotal-row">
                    <span>Subtotal</span>
                    <span class="checkout-subtotal-val">${{ number_format($total, 2) }}</span>
                </div>

                <div class="order-divider"></div>

                <div class="checkout-total-row">
                    <span class="checkout-total-label">Total</span>
                    <span class="checkout-total-val">${{ number_format($total, 2) }}</span>
                </div>

                <p class="order-download-note">
                    Your artwork will be ready to download from My Orders once payment is confirmed.
                </p>

                <button type="button" class="btn-place-order" id="btn-place-order">
                    <span>Place order</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>

                <p class="order-review-note">
                    Review your details before placing your order.
                </p>
            </aside>

        </div>

    </main>
</div>

<!-- Interactive Checkout Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- 1. Payment Method Tabs Toggle ---
        const tabCreditCard = document.getElementById('tab-credit-card');
        const tabPayPal = document.getElementById('tab-paypal');
        const creditCardFields = document.getElementById('credit-card-fields');
        const paypalFields = document.getElementById('paypal-fields');

        if (tabCreditCard && tabPayPal) {
            tabCreditCard.addEventListener('click', function () {
                tabCreditCard.classList.add('active');
                tabPayPal.classList.remove('active');
                if (creditCardFields) creditCardFields.style.display = 'block';
                if (paypalFields) paypalFields.style.display = 'none';
            });

            tabPayPal.addEventListener('click', function () {
                tabPayPal.classList.add('active');
                tabCreditCard.classList.remove('active');
                if (creditCardFields) creditCardFields.style.display = 'none';
                if (paypalFields) paypalFields.style.display = 'block';
            });
        }

        // --- 4. Card Formatting Helpers ---
        const cardNumberInput = document.getElementById('card-number');
        if (cardNumberInput) {
            cardNumberInput.addEventListener('input', function (e) {
                let val = this.value.replace(/\D/g, '');
                let formatted = val.match(/.{1,4}/g)?.join(' ') || val;
                this.value = formatted.substring(0, 19);
            });
        }

        const expiryInput = document.getElementById('card-expiry');
        if (expiryInput) {
            expiryInput.addEventListener('input', function () {
                let val = this.value.replace(/\D/g, '');
                if (val.length >= 2) {
                    val = val.substring(0, 2) + ' / ' + val.substring(2, 4);
                }
                this.value = val.substring(0, 7);
            });
        }

        // --- 5. Place Order Button ---
        const placeOrderBtn = document.getElementById('btn-place-order');
        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', function () {
                const email = document.getElementById('contact-email')?.value.trim();
                if (!email) {
                    alert('Please enter your email address for order confirmation.');
                    return;
                }

                this.disabled = true;
                this.innerHTML = `<span>Processing order...</span>`;

                setTimeout(() => {
                    window.location.href = "{{ url('/order-success') }}";
                }, 400);
            });
        }
    });
</script>

</body>
</html>
