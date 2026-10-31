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
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
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
                            <span class="client-order-status status-{{ $order->status }}">{{ $order->status === 'pending' ? 'Pending payment' : ucfirst($order->status) }}</span>
                        </header>
                        <div class="client-order-summary">
                            <span>{{ $order->items->count() }} {{ \Illuminate\Support\Str::plural('artwork', $order->items->count()) }}</span>
                            <span>{{ $order->created_at->format('M j, Y') }}</span>
                            <strong>${{ number_format((float) $order->total, 2) }}</strong>
                        </div>
                        <p>{{ $order->status === 'pending' ? 'Payment processing is not available yet. No charge has been made.' : 'Payment status: '.ucfirst($order->status).'.' }}</p>
                        <a href="{{ route('orders.show', $order) }}" class="client-order-link">View order details <span aria-hidden="true">→</span></a>
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
