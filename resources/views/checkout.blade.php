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
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
</head>
<body>


<div class="app-layout">

    @include('partials.site-header', ['active' => 'cart'])

    <!-- ================= MAIN CONTENT ================= -->
    <main class="main-content">

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
                        Each artwork is one of one. Placing your order reserves it for you.<br>
                        The original full-resolution files unlock in My Orders once your payment is approved.
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
                            @error('cardholder_name')<small class="field-error" role="alert">{{ $message }}</small>@enderror
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
                            @error('card_number')<small class="field-error" role="alert">{{ $message }}</small>@enderror
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
                            @error('card_expiry')<small class="field-error" role="alert">{{ $message }}</small>@enderror
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
                            @error('card_cvc')<small class="field-error" role="alert">{{ $message }}</small>@enderror
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
                            @error('gcash_name')<small class="field-error" role="alert">{{ $message }}</small>@enderror
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
                            @error('gcash_number')<small class="field-error" role="alert">{{ $message }}</small>@enderror
                        </div>

                        <div class="form-group">
                            <label for="gcash-reference" class="form-label">GCash reference number</label>
                            <input
                                type="text"
                                id="gcash-reference"
                                name="gcash_reference"
                                class="form-control-custom"
                                placeholder="From your GCash payment receipt"
                                maxlength="30"
                                autocomplete="off"
                                value="{{ old('gcash_reference') }}"
                            >
                            <small class="form-hint">Send the order total by GCash first, then enter the reference number so we can confirm it.</small>
                            @error('gcash_reference')<small class="field-error" role="alert">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <p class="payment-approval-note">
                        Your card details are only used to identify this payment. We keep just the last four digits.
                        Every order starts as <strong>Awaiting approval</strong> until an ARTFOLIO admin confirms the payment.
                    </p>
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
                                <div class="checkout-item-meta">Qty {{ $item['quantity'] }} · @peso($item['price'])</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="order-divider"></div>

                <div class="checkout-subtotal-row">
                    <span>Subtotal</span>
                    <span class="checkout-subtotal-val">@peso($total)</span>
                </div>

                <div class="order-divider"></div>

                <div class="checkout-total-row">
                    <span class="checkout-total-label">Total</span>
                    <span class="checkout-total-val">@peso($total)</span>
                </div>

                <p class="order-download-note">
                    Your artwork will be ready to download from My Orders once an admin approves your payment.
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
