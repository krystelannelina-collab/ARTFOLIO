<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - My Cart</title>
    <meta name="description" content="A few pieces you love, one step closer to yours.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Dedicated CSS Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
</head>
<body>

@php
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
                    id="cart-search-input"
                    class="search-input"
                    placeholder="Search artworks, artists, and inspiration"
                    aria-label="Search artworks, artists, and inspiration"
                    value="{{ request('search') }}"
                >
            </form>

            <!-- Top Actions -->
            <div class="top-bar-actions">
                @include('partials.account-menu')
            </div>
        </header>

        <!-- Page Header -->
        <div class="cart-header-section">
            <div class="header-great-find-tag">YOUR NEXT GREAT FIND</div>
            <h1 class="header-main-title">My cart.</h1>
            <p class="header-description">A few pieces you love, one step closer to yours.</p>
        </div>

        <!-- Main Cart 2-Column Layout -->
        <div class="cart-layout-grid">

            <!-- LEFT COLUMN: Your Artworks Card -->
            <section class="cart-card-container">
                <div class="cart-card-header">
                    <h2 class="cart-card-title">Your artworks</h2>
                    <span class="cart-card-count" id="cart-items-count">{{ count($cartItems) }} items</span>
                </div>

                    @if (session('status'))
                        <p class="cart-status-notice" role="status">{{ session('status') }}</p>
                    @endif

                    <!-- Items List -->
                    <div class="cart-items-list" id="cart-items-list">
                    @foreach ($cartItems as $item)
                        <article class="cart-item"
                                 id="cart-item-{{ $item['id'] }}"
                                 data-id="{{ $item['id'] }}"
                                 data-price="{{ $item['price'] }}"
                                 data-qty="{{ $item['quantity'] }}">
                            <div class="cart-item-img-box">
                                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="cart-item-img" loading="lazy">
                            </div>

                            <div class="cart-item-details">
                                <span class="cart-item-tag">{{ $item['category'] }}</span>
                                <h3 class="cart-item-title">{{ $item['title'] }}</h3>
                                <p class="cart-item-author">by {{ $item['author'] }}</p>
                                <p class="cart-item-qty">Quantity: {{ $item['quantity'] }}</p>
                            </div>

                            <div class="cart-item-right">
                                <div class="cart-item-price">@peso($item['price'])</div>
                                <form method="POST" action="{{ route('cart.remove', $item['id']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-cart-remove">Remove</button>
                                </form>
                                <form method="POST" action="{{ route('cart.save-for-later', $item['id']) }}">
                                    @csrf
                                    <button type="submit" class="btn-cart-save" data-id="{{ $item['id'] }}">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                        </svg>
                                        <span>Save for later</span>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Empty State -->
                <div class="cart-empty-state" id="cart-empty-state" @if (count($cartItems) > 0) hidden @endif>
                    <div class="empty-cart-title">Your cart is empty</div>
                    <div class="empty-cart-desc">Discover original artworks and add pieces to your cart.</div>
                    <a href="{{ url('/artworks') }}" class="btn-explore-artworks">
                        <span>Explore Collection</span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="7" y1="17" x2="17" y2="7"></line>
                            <polyline points="7 7 17 7 17 17"></polyline>
                        </svg>
                    </a>
                </div>
            </section>

            <!-- RIGHT COLUMN: Order Summary Card -->
            <aside class="order-summary-card">
                <h2 class="summary-title">Order summary</h2>
                <p class="summary-subtitle" id="summary-items-count">{{ count($cartItems) }} artworks in your cart</p>

                <div class="summary-divider"></div>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <span class="summary-row-val" id="summary-subtotal">@peso($subtotal)</span>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-total-row">
                    <span class="summary-total-label">Total</span>
                    <span class="summary-total-val" id="summary-total">@peso($subtotal)</span>
                </div>

                <a href="{{ route('checkout') }}" class="btn-checkout {{ count($cartItems) === 0 ? 'disabled' : '' }}" id="btn-checkout" @if (count($cartItems) === 0) aria-disabled="true" @endif>
                    <span>Proceed to checkout</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>

                <p class="checkout-note">
                    Digital download only. Your files will be available in My Orders after payment.
                </p>
            </aside>

        </div>

        <!-- Bottom Continue Exploring Row -->
        <div class="bottom-continue-row">
            <a href="{{ url('/artworks') }}" class="btn-continue-explore">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Continue exploring</span>
            </a>
            <span>Find more pieces from independent artists.</span>
        </div>

    </main>
</div>

<!-- Interactive Cart Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- 1. Dynamic Cart Calculations & Removal ---
        const cartItemsList = document.getElementById('cart-items-list');
        const cartItemsCount = document.getElementById('cart-items-count');
        const summaryItemsCount = document.getElementById('summary-items-count');
        const summarySubtotal = document.getElementById('summary-subtotal');
        const summaryTotal = document.getElementById('summary-total');
        const emptyState = document.getElementById('cart-empty-state');
        const checkoutBtn = document.getElementById('btn-checkout');

        function recalculateCart() {
            const items = Array.from(document.querySelectorAll('.cart-item:not(.is-removing)'));
            const count = items.length;

            let total = 0;
            items.forEach(item => {
                const price = parseFloat(item.getAttribute('data-price') || '0');
                const qty = parseInt(item.getAttribute('data-qty') || '1', 10);
                total += price * qty;
            });

            if (cartItemsCount) {
                cartItemsCount.textContent = `${count} item${count === 1 ? '' : 's'}`;
            }

            if (summaryItemsCount) {
                summaryItemsCount.textContent = `${count} artwork${count === 1 ? '' : 's'} in your cart`;
            }

            const formattedTotal = `₱${total.toLocaleString("en-PH", { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            if (summarySubtotal) summarySubtotal.textContent = formattedTotal;
            if (summaryTotal) summaryTotal.textContent = formattedTotal;

            if (count === 0) {
                if (emptyState) {
                    emptyState.hidden = false;
                    emptyState.style.display = 'block';
                }
                if (checkoutBtn) {
                    checkoutBtn.classList.add('disabled');
                    checkoutBtn.setAttribute('aria-disabled', 'true');
                }
            } else {
                if (emptyState) {
                    emptyState.hidden = true;
                    emptyState.style.display = 'none';
                }
                if (checkoutBtn) {
                    checkoutBtn.classList.remove('disabled');
                    checkoutBtn.removeAttribute('aria-disabled');
                }
            }
        }

        // Checkout Button state
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', function (e) {
                if (checkoutBtn.classList.contains('disabled')) {
                    e.preventDefault();
                }
            });
        }
    });
</script>

</body>
</html>
