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

@include('partials.mobile-header')

<div class="app-layout">

    @include('partials.sidebar', ['active' => 'cart'])

    <!-- ================= MAIN CONTENT ================= -->
    <main class="main-content">

        <!-- Top Bar -->
        <header class="top-bar">
            <!-- Search Pill -->
            <form action="{{ url('/artworks') }}" method="GET" class="search-pill-container" role="search">
                <svg class="search-icon-left" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input
                    type="search"
                    name="search"
                    id="checkout-search-input"
                    class="search-input"
                    placeholder="Search artworks, artists, and inspiration"
                    aria-label="Search artworks, artists, and inspiration"
                    value="{{ request('search') }}"
                >
            </form>

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
        <form method="POST" action="{{ route('checkout.store') }}" class="checkout-layout-grid" id="checkout-form">
            @csrf

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
                            name="contact_email"
                            value="{{ old('contact_email', $userEmail) }}"
                            required
                        >
                        @error('contact_email')
                            <small class="field-error" role="alert">{{ $message }}</small>
                        @enderror
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
                @php
                    $selectedMethod = strtolower((string) old('payment_method', 'card')) === 'gcash' ? 'gcash' : 'card';
                @endphp
                <section class="checkout-step-card">
                    <h2 class="step-title">2. Payment method</h2>

                    <input type="hidden" name="payment_method" id="payment-method-input" value="{{ $selectedMethod }}">

                    <div class="payment-tabs-group" role="tablist" aria-label="Choose payment method">
                        <button
                            type="button"
                            class="payment-tab-btn {{ $selectedMethod === 'card' ? 'active' : '' }}"
                            data-payment-tab="card"
                            role="tab"
                            aria-selected="{{ $selectedMethod === 'card' ? 'true' : 'false' }}"
                        >
                            <span class="tab-dot" aria-hidden="true"></span>
                            <span>Credit / debit card</span>
                        </button>
                        <button
                            type="button"
                            class="payment-tab-btn {{ $selectedMethod === 'gcash' ? 'active' : '' }}"
                            data-payment-tab="gcash"
                            role="tab"
                            aria-selected="{{ $selectedMethod === 'gcash' ? 'true' : 'false' }}"
                        >
                            <span class="tab-dot" aria-hidden="true"></span>
                            <span>GCash</span>
                        </button>
                    </div>

                    <!-- Credit / Debit Card Panel -->
                    <div id="payment-panel-card" class="payment-method-panel {{ $selectedMethod !== 'card' ? 'is-hidden' : '' }}">
                        <div class="form-group">
                            <label for="cardholder-name" class="form-label">Cardholder name</label>
                            <input
                                type="text"
                                id="cardholder-name"
                                name="cardholder_name"
                                class="form-control-custom"
                                placeholder="Name as shown on card"
                                value="{{ old('cardholder_name', Auth::user()->name ?? '') }}"
                            >
                        </div>

                        <div class="form-group">
                            <label for="card-number" class="form-label">Card number</label>
                            <input
                                type="text"
                                id="card-number"
                                name="card_number"
                                class="form-control-custom"
                                placeholder="0000 0000 0000 0000"
                                inputmode="numeric"
                                autocomplete="cc-number"
                                value="{{ old('card_number') }}"
                            >
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="card-expiry" class="form-label">Expiry date</label>
                                <input
                                    type="text"
                                    id="card-expiry"
                                    name="card_expiry"
                                    class="form-control-custom"
                                    placeholder="MM / YY"
                                    autocomplete="cc-exp"
                                    value="{{ old('card_expiry') }}"
                                >
                            </div>
                            <div class="form-group">
                                <label for="card-cvc" class="form-label">Security code</label>
                                <input
                                    type="text"
                                    id="card-cvc"
                                    name="card_cvc"
                                    class="form-control-custom"
                                    placeholder="CVC"
                                    inputmode="numeric"
                                    autocomplete="cc-csc"
                                    value="{{ old('card_cvc') }}"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- GCash Panel -->
                    <div id="payment-panel-gcash" class="payment-method-panel {{ $selectedMethod !== 'gcash' ? 'is-hidden' : '' }}">
                        <div class="form-group">
                            <label for="gcash-name" class="form-label">GCash account name</label>
                            <input
                                type="text"
                                id="gcash-name"
                                name="gcash_name"
                                class="form-control-custom"
                                placeholder="Full name on GCash account"
                                value="{{ old('gcash_name', Auth::user()->name ?? '') }}"
                            >
                        </div>

                        <div class="form-group">
                            <label for="gcash-number" class="form-label">GCash mobile number</label>
                            <input
                                type="tel"
                                id="gcash-number"
                                name="gcash_number"
                                class="form-control-custom"
                                placeholder="09XX XXX XXXX"
                                inputmode="tel"
                                value="{{ old('gcash_number') }}"
                            >
                        </div>
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

                <button type="submit" class="btn-place-order" id="btn-place-order">
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

        </form>

    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const methodInput = document.getElementById('payment-method-input');
        const tabButtons = document.querySelectorAll('[data-payment-tab]');
        const cardPanel = document.getElementById('payment-panel-card');
        const gcashPanel = document.getElementById('payment-panel-gcash');

        tabButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                const selected = btn.dataset.paymentTab;
                if (methodInput) {
                    methodInput.value = selected;
                }

                tabButtons.forEach((tab) => {
                    const isActive = tab.dataset.paymentTab === selected;
                    tab.classList.toggle('active', isActive);
                    tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });

                if (cardPanel) {
                    cardPanel.classList.toggle('is-hidden', selected !== 'card');
                }
                if (gcashPanel) {
                    gcashPanel.classList.toggle('is-hidden', selected !== 'gcash');
                }
            });
        });

        const checkoutForm = document.getElementById('checkout-form');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', (event) => {
                const button = document.getElementById('btn-place-order');
                if (button.disabled) {
                    event.preventDefault();
                    return;
                }

                button.disabled = true;
                button.querySelector('span').textContent = 'Placing order...';
            });
        }
    });
</script>

</body>
</html>
