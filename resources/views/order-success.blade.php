<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $order->order_number }} | ARTFOLIO</title>
    <meta name="description" content="Order details and payment status for {{ $order->order_number }}.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order-success.css') }}">
</head>
<body>
@php
    $isPendingPayment = $order->status === 'pending';
    $isPaidOrder = in_array($order->status, ['completed', 'paid'], true);
    $paymentMethodLabel = $order->payment_method === 'not_configured'
        ? 'Not configured'
        : ($order->payment_method === 'gcash' ? 'GCash' : ucfirst($order->payment_method));
    $nameParts = preg_split('/\s+/', trim(auth()->user()->name));
    $userInitials = strtoupper(substr($nameParts[0] ?? 'U', 0, 1));
    if (count($nameParts) > 1) {
        $userInitials .= strtoupper(substr($nameParts[1], 0, 1));
    }
@endphp
@include('partials.mobile-header')
<div class="app-layout">
    @include('partials.sidebar', ['active' => 'orders'])

    <main class="main-content">
        <header class="top-bar">
            <form action="{{ url('/artworks') }}" method="GET" class="search-pill-container" role="search">
                <svg class="search-icon-left" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
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

            <div class="top-bar-actions">
                <button type="button" class="topbar-btn" title="Notifications" aria-label="Notifications">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </button>
                <div class="user-avatar-pill" title="{{ auth()->user()->name }}">
                    {{ $userInitials }}
                </div>
            </div>
        </header>

        <section class="success-banner-card {{ $isPendingPayment ? 'order-pending-banner' : '' }}">
            <div class="success-check-badge" aria-hidden="true">
                @if ($isPaidOrder)
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                @else
                    !
                @endif
            </div>
            <h1 class="success-title">
                @if ($isPaidOrder)
                    Your next masterpiece is yours.
                @elseif ($isPendingPayment)
                    Your order is recorded.
                @else
                    Your order details.
                @endif
            </h1>
            <p class="success-subtitle">
                @if ($isPaidOrder)
                    Payment received. Your digital artwork is ready to download.
                @elseif ($isPendingPayment)
                    Payment processing is pending. No payment has been charged.
                @else
                    Current payment status: {{ ucfirst($order->status) }}.
                @endif
            </p>
            <div class="success-meta-row">
                <span class="order-number-tag">ORDER #{{ $order->order_number }}</span>
                <span aria-hidden="true">·</span>
                <span>{{ $order->items->count() }} {{ \Illuminate\Support\Str::plural('artwork', $order->items->count()) }}</span>
                <span aria-hidden="true">·</span>
                <span>${{ number_format((float) $order->total, 2) }} {{ $isPaidOrder ? 'paid' : ($isPendingPayment ? 'pending' : strtolower($order->status)) }}</span>
            </div>
            <p class="success-email-notice">
                @if ($isPaidOrder)
                    A confirmation and receipt have been sent to your email.
                @elseif ($isPendingPayment)
                    Downloads will become available only after payment is confirmed.
                @else
                    Status updated for this order.
                @endif
            </p>
        </section>

        <div class="order-success-grid">
            <section class="digital-artworks-card">
                <h2 class="digital-card-title">{{ $isPaidOrder ? 'Your digital artworks' : 'Items in your order' }}</h2>
                <p class="digital-card-desc">
                    @if ($isPaidOrder)
                        Download now, or find your files anytime in My Orders.
                    @else
                        Order placed {{ $order->created_at->format('F j, Y \a\t g:i A') }}.
                    @endif
                </p>
                <div class="digital-card-divider"></div>
                <div class="digital-items-list">
                    @foreach ($order->items as $item)
                        <div class="digital-item-row">
                            <div class="digital-item-left">
                                <div class="digital-item-thumb">
                                    <img src="{{ asset($item->image_path) }}" alt="{{ $item->title }}" class="digital-item-img">
                                </div>
                                <div class="digital-item-info">
                                    <h3 class="digital-item-name">{{ $item->title }}</h3>
                                    <p class="digital-item-author">by {{ $item->artist_name }}</p>
                                </div>
                            </div>
                            @if ($isPaidOrder)
                                <a href="{{ route('orders.items.download', [$order, $item]) }}" class="btn-download-art">
                                    <span>Download</span>
                                    <span aria-hidden="true">↓</span>
                                </a>
                            @else
                                <strong>${{ number_format((float) $item->unit_price * $item->quantity, 2) }}</strong>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>

            <aside class="payment-summary-card">
                <h2 class="summary-card-title">Payment summary</h2>
                <div class="summary-meta-row">
                    <span class="summary-meta-label">Payment status</span>
                    <span class="summary-meta-val">{{ $isPaidOrder ? 'Paid' : ucfirst($order->status) }}</span>
                </div>
                <div class="summary-meta-row">
                    <span class="summary-meta-label">Payment method</span>
                    <span class="summary-meta-val">{{ $paymentMethodLabel }}</span>
                </div>
                <div class="summary-card-divider"></div>
                <div class="summary-paid-row">
                    <span class="summary-paid-label">{{ $isPaidOrder ? 'Total paid' : 'Order total' }}</span>
                    <span class="summary-paid-val">${{ number_format((float) $order->total, 2) }}</span>
                </div>
                @if ($isPaidOrder)
                    <a href="{{ route('orders.receipt', $order) }}" class="btn-download-receipt">
                        <span>Download receipt</span>
                        <span aria-hidden="true">↓</span>
                    </a>
                @endif
                <p class="receipt-no-shipping">
                    @if ($isPaidOrder)
                        Digital purchase · No shipping required
                    @elseif ($isPendingPayment)
                        Digital purchase · Downloads locked until payment is confirmed
                    @else
                        Digital purchase · Status: {{ ucfirst($order->status) }}
                    @endif
                </p>
            </aside>
        </div>

        <div class="success-bottom-actions">
            <a href="{{ route('orders') }}" class="btn-go-orders"><span>Go to My Orders</span> <span aria-hidden="true">→</span></a>
            <a href="{{ url('/artworks') }}" class="btn-continue-nav"><span>Continue exploring</span></a>
        </div>
    </main>
</div>
</body>
</html>
