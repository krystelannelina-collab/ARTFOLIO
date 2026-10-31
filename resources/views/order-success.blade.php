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
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order-success.css') }}">
</head>
<body>
@php
    $isPendingPayment = $order->status === 'pending';
    $categories = [
        ['label' => 'Abstract', 'url' => url('/artworks?category=Abstract'), 'type' => 'abstract'],
        ['label' => 'Typography', 'url' => url('/artworks?category=Typography'), 'type' => 'typography'],
        ['label' => 'Pixel', 'url' => url('/artworks?category=Pixel'), 'type' => 'pixel'],
        ['label' => 'Poster', 'url' => url('/artworks?category=Poster'), 'type' => 'poster'],
        ['label' => 'Digital', 'url' => url('/artworks?category=Digital'), 'type' => 'digital'],
    ];
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
        <section class="success-banner-card order-pending-banner">
            <div class="success-check-badge" aria-hidden="true">!</div>
            <h1 class="success-title">{{ $isPendingPayment ? 'Your order is recorded.' : 'Your order details.' }}</h1>
            <p class="success-subtitle">
                @if ($isPendingPayment)
                    Payment processing is not connected yet. No payment has been charged.
                @else
                    Current payment status: {{ ucfirst($order->status) }}.
                @endif
            </p>
            <div class="success-meta-row">
                <span class="order-number-tag">ORDER #{{ $order->order_number }}</span>
                <span aria-hidden="true">·</span>
                <span>{{ $order->items->count() }} {{ \Illuminate\Support\Str::plural('artwork', $order->items->count()) }}</span>
                <span aria-hidden="true">·</span>
                <span>${{ number_format((float) $order->total, 2) }} {{ $isPendingPayment ? 'pending' : strtolower($order->status) }}</span>
            </div>
            <p class="success-email-notice">
                @if ($isPendingPayment)
                    Downloads will become available only after payment is confirmed.
                @else
                    Digital downloads are not available from this order page.
                @endif
            </p>
        </section>

        <div class="order-success-grid">
            <section class="digital-artworks-card">
                <h2 class="digital-card-title">Items in your order</h2>
                <p class="digital-card-desc">Order placed {{ $order->created_at->format('F j, Y \a\t g:i A') }}.</p>
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
                                    <p class="digital-item-author">by {{ $item->artist_name }} · Qty {{ $item->quantity }}</p>
                                </div>
                            </div>
                            <strong>${{ number_format((float) $item->unit_price * $item->quantity, 2) }}</strong>
                        </div>
                    @endforeach
                </div>
            </section>

            <aside class="payment-summary-card">
                <h2 class="summary-card-title">Order summary</h2>
                <div class="summary-meta-row">
                    <span class="summary-meta-label">Payment status</span>
                    <span class="summary-meta-val">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="summary-meta-row">
                    <span class="summary-meta-label">Payment method</span>
                    <span class="summary-meta-val">{{ $order->payment_method === 'not_configured' ? 'Not configured' : ucfirst($order->payment_method) }}</span>
                </div>
                <div class="summary-card-divider"></div>
                <div class="summary-paid-row">
                    <span class="summary-paid-label">Order total</span>
                    <span class="summary-paid-val">${{ number_format((float) $order->total, 2) }}</span>
                </div>
                <p class="receipt-no-shipping">
                    @if ($isPendingPayment)
                        Digital purchase · Downloads locked until payment is confirmed
                    @else
                        Digital purchase · Download delivery is not available on this page
                    @endif
                </p>
            </aside>
        </div>

        <div class="success-bottom-actions">
            <a href="{{ route('orders') }}" class="btn-go-orders"><span>Go to My Orders</span></a>
            <a href="{{ url('/artworks') }}" class="btn-continue-nav"><span>Continue exploring</span></a>
        </div>
    </main>
</div>
</body>
</html>
