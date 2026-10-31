<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | ARTFOLIO Admin</title>
    <meta name="description" content="Track customer orders and payment status in ARTFOLIO.">
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
                <span class="admin-eyebrow">STORE MANAGEMENT</span>
                <h1>Orders</h1>
                <p>Track customer purchases, payment status, and order details.</p>
            </div>
            <a href="{{ route('admin.orders.export', request()->query()) }}" class="orders-export-button">
                Export orders <span aria-hidden="true">↓</span>
            </a>
        </header>

        <div class="orders-payment-notice" role="note">
            Payment processing is not connected. New checkout orders are saved as pending; no payment is charged.
        </div>

        <section class="orders-overview" aria-label="Order totals">
            <article>
                <span>Revenue</span>
                <strong>${{ number_format($stats['revenue'], 2) }}</strong>
                <small>Completed payments</small>
            </article>
            <article>
                <span>Completed</span>
                <strong>{{ number_format($stats['completed']) }}</strong>
                <small>Payments confirmed</small>
            </article>
            <article>
                <span>Pending payment</span>
                <strong>{{ number_format($stats['pending']) }}</strong>
                <small>Awaiting payment setup</small>
            </article>
            <article>
                <span>Refunded</span>
                <strong>{{ number_format($stats['refunded']) }}</strong>
                <small>Refunds processed</small>
            </article>
        </section>

        <section class="admin-panel orders-table-panel" aria-label="Order directory">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="orders-filter-bar">
                <label class="orders-search">
                    <span class="visually-hidden">Search order ID, customer, or artwork</span>
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m16 16 4 4"></path>
                    </svg>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search order ID, customer, or artwork...">
                </label>

                <label class="orders-filter">
                    <span class="visually-hidden">Filter by payment status</span>
                    <select name="status">
                        <option value="">All statuses</option>
                        @foreach (['pending' => 'Pending payment', 'completed' => 'Completed', 'refunded' => 'Refunded', 'failed' => 'Failed'] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="orders-filter">
                    <span class="visually-hidden">Filter by date</span>
                    <select name="date">
                        <option value="all" @selected(($filters['date'] ?? 'all') === 'all')>All dates</option>
                        <option value="7" @selected(($filters['date'] ?? '') === '7')>Last 7 days</option>
                        <option value="30" @selected(($filters['date'] ?? '') === '30')>Last 30 days</option>
                        <option value="365" @selected(($filters['date'] ?? '') === '365')>Last 12 months</option>
                    </select>
                </label>

                <label class="orders-filter">
                    <span class="visually-hidden">Sort orders</span>
                    <select name="sort">
                        <option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>Newest first</option>
                        <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Oldest first</option>
                    </select>
                </label>

                <button type="submit" class="orders-filter-submit">Apply</button>
                <a href="{{ route('admin.orders.index') }}" class="orders-filter-reset">Reset</a>
            </form>

            @if ($orders->isNotEmpty())
                <div class="orders-table-scroll">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th scope="col">ORDER ID</th>
                                <th scope="col">ARTWORK</th>
                                <th scope="col">CUSTOMER</th>
                                <th scope="col">DATE</th>
                                <th scope="col">PAYMENT</th>
                                <th scope="col">TOTAL</th>
                                <th scope="col">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                @php($firstItem = $order->items->first())
                                <tr>
                                    <td class="order-number">#{{ $order->order_number }}</td>
                                    <td>
                                        @if ($firstItem)
                                            <span class="order-artwork-cell">
                                                <img src="{{ asset($firstItem->image_path) }}" alt="" loading="lazy">
                                                <span>
                                                    <strong>{{ $firstItem->title }}</strong>
                                                    <small>{{ $order->items->count() > 1 ? '+'.($order->items->count() - 1).' more artworks' : '1 artwork' }}</small>
                                                </span>
                                            </span>
                                        @else
                                            <span class="order-artwork-cell">Artwork removed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="order-customer-cell">
                                            <strong>{{ $order->customer_name }}</strong>
                                            <small>{{ $order->customer_email }}</small>
                                        </span>
                                    </td>
                                    <td class="order-date">{{ $order->created_at->format('M j, Y') }}</td>
                                    <td><span class="order-payment status-{{ $order->status }}">{{ $order->status === 'pending' ? 'Pending' : ucfirst($order->status) }}</span></td>
                                    <td class="order-total">${{ number_format((float) $order->total, 2) }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="order-view-link">View order <span aria-hidden="true">↗</span></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <footer class="orders-table-footer">
                    <span>Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ number_format($orders->total()) }} orders</span>
                    {{ $orders->links() }}
                </footer>
            @else
                <div class="orders-empty-state">
                    <strong>{{ ($filters['search'] ?? '') !== '' || ! empty($filters['status']) ? 'No matching orders' : 'No orders yet' }}</strong>
                    <span>{{ ($filters['search'] ?? '') !== '' || ! empty($filters['status']) ? 'Try adjusting your search or filters.' : 'Orders will appear here after a customer places an order at checkout.' }}</span>
                    @if (($filters['search'] ?? '') !== '' || ! empty($filters['status']) || ($filters['date'] ?? 'all') !== 'all')
                        <a href="{{ route('admin.orders.index') }}" class="orders-filter-reset">Reset filters</a>
                    @endif
                </div>
            @endif
        </section>

        <p class="orders-footnote">Pending orders are not paid. Digital downloads remain unavailable until payment is confirmed.</p>
    </main>
</div>
</body>
</html>
