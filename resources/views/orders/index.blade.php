<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | ARTFOLIO</title>
    <meta name="description" content="Review your ARTFOLIO order history and payment status.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
</head>
<body>
@php
    $nameParts = preg_split('/\s+/', trim(auth()->user()->name));
    $userInitials = strtoupper(substr($nameParts[0] ?? 'U', 0, 1));
    if (count($nameParts) > 1) {
        $userInitials .= strtoupper(substr($nameParts[1], 0, 1));
    }
@endphp
@include('partials.mobile-header')
<div class="app-layout">
    @include('partials.sidebar', ['active' => 'orders'])

    <main class="main-content orders-content">
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
                @include('partials.account-menu')
            </div>
        </header>

        <header class="orders-heading">
            <span class="orders-eyebrow">YOUR ACCOUNT</span>
            <h1>My orders.</h1>
            <p>Track order status and find your digital purchases.</p>
        </header>

        @if (session('status'))
            <p class="orders-notice" role="status">{{ session('status') }}</p>
        @endif

        @if ($orders->isNotEmpty())
            <section class="client-orders-list" aria-label="Your orders">
                @foreach ($orders as $order)
                    <article class="client-order-card">
                        <header>
                            <div>
                                <span class="client-order-label">ORDER</span>
                                <strong>#{{ $order->order_number }}</strong>
                            </div>
                            <span class="client-order-status status-{{ $order->status }}">
                                @if ($order->status === 'completed')
                                    Payment approved
                                @elseif ($order->status === 'pending')
                                    Awaiting approval
                                @else
                                    {{ $order->statusLabel() }}
                                @endif
                            </span>
                        </header>
                        <div class="client-order-summary">
                            <span>{{ $order->items->count() }} {{ \Illuminate\Support\Str::plural('artwork', $order->items->count()) }}</span>
                            <span>{{ $order->created_at->format('M j, Y') }}</span>
                            <strong>@peso((float) $order->total)</strong>
                        </div>
                        <p>
                            @if ($order->status === 'completed')
                                Payment approved via {{ $order->payment_method === 'not_configured' ? 'Card' : $order->payment_method }}. Original files are ready to download.
                            @elseif ($order->status === 'pending')
                                Payment submitted and awaiting admin approval. Downloads unlock once it is approved.
                            @else
                                {{ $order->statusLabel() }}.{{ $order->rejection_reason ? ' Reason: '.$order->rejection_reason : '' }}
                            @endif
                        </p>
                        <a href="{{ route('orders.show', $order) }}" class="client-order-link">View order &amp; downloads <span aria-hidden="true">→</span></a>
                    </article>
                @endforeach
            </section>
            {{ $orders->links() }}
        @else
            <section class="client-orders-empty">
                <h2>No orders yet</h2>
                <p>When you place an order, its payment status and details will appear here.</p>
                <a href="{{ url('/artworks') }}">Explore artworks <span aria-hidden="true">→</span></a>
            </section>
        @endif
    </main>
</div>
</body>
</html>
