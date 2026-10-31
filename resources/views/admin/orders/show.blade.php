<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $order->order_number }} | ARTFOLIO Admin</title>
    <meta name="description" content="Order details for {{ $order->order_number }}.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-orders.css') }}">
</head>
<body>
<div class="admin-layout">
    @include('admin.partials.sidebar', ['active' => 'orders'])

    <main class="admin-main">
        <header class="admin-page-heading orders-page-heading">
            <div>
                <span class="admin-eyebrow"><a href="{{ route('admin.orders.index') }}">ORDERS</a> / ORDER DETAILS</span>
                <h1>{{ $order->order_number }}</h1>
                <p>Placed {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="orders-filter-reset">Back to orders</a>
        </header>

        <div class="orders-payment-notice" role="note">
            @if ($order->status === 'pending')
                This order is pending. No payment has been processed and downloads are not available.
            @else
                Payment status: {{ ucfirst($order->status) }}.
            @endif
        </div>

        <div class="order-details-grid">
            <section class="admin-panel order-details-panel">
                <div class="orders-panel-heading">
                    <div>
                        <h2>Items in this order</h2>
                        <p>{{ $order->items->count() }} {{ \Illuminate\Support\Str::plural('artwork', $order->items->count()) }}</p>
                    </div>
                    <span class="order-payment status-{{ $order->status }}">{{ $order->status === 'pending' ? 'Pending payment' : ucfirst($order->status) }}</span>
                </div>

                <div class="order-detail-items">
                    @foreach ($order->items as $item)
                        <article class="order-detail-item">
                            <img src="{{ asset($item->image_path) }}" alt="" loading="lazy">
                            <div>
                                <strong>{{ $item->title }}</strong>
                                <span>by {{ $item->artist_name }}</span>
                                <small>Qty {{ $item->quantity }} × ${{ number_format((float) $item->unit_price, 2) }}</small>
                            </div>
                            <b>${{ number_format((float) $item->unit_price * $item->quantity, 2) }}</b>
                        </article>
                    @endforeach
                </div>

                <div class="order-detail-total">
                    <span>Total</span>
                    <strong>${{ number_format((float) $order->total, 2) }}</strong>
                </div>
            </section>

            <aside class="admin-panel order-customer-panel">
                <h2>Customer</h2>
                <dl>
                    <div><dt>Name</dt><dd>{{ $order->customer_name }}</dd></div>
                    <div><dt>Email</dt><dd>{{ $order->customer_email }}</dd></div>
                    <div><dt>Payment status</dt><dd>{{ ucfirst($order->status) }}</dd></div>
                    <div><dt>Payment method</dt><dd>Not configured</dd></div>
                </dl>
            </aside>
        </div>
    </main>
</div>
</body>
</html>
