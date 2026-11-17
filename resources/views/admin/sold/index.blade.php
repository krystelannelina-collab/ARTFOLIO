<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sold Items | ARTFOLIO Admin</title>
    <meta name="description" content="Every ARTFOLIO artwork sold through an approved order.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    {{-- Shares the Customers page layout (stat cards, filter bar, table) so both pages match. --}}
    <link rel="stylesheet" href="{{ asset('css/admin-customers.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-sold.css') }}">
</head>
<body>
<div class="admin-layout">
    @include('admin.partials.sidebar', ['active' => 'sold'])

    <main class="admin-main customers-main">
        <header class="admin-page-heading customers-page-heading">
            <div>
                <span class="admin-eyebrow">STORE MANAGEMENT</span>
                <h1>Sold items</h1>
                <p>Every artwork sold through an approved payment, with its buyer and order.</p>
            </div>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="customers-heading-link">
                Review pending payments <span aria-hidden="true">→</span>
            </a>
        </header>

        <section class="customers-overview" aria-label="Sales totals">
            <article class="customers-stat">
                <span class="customers-stat-label">Artworks sold</span>
                <strong class="customers-stat-value">{{ number_format($stats['itemsSold']) }}</strong>
                <small class="customers-stat-note">
                    {{ $stats['soldLast30Days'] > 0 ? number_format($stats['soldLast30Days']).' sold in the last 30 days' : 'None sold in the last 30 days' }}
                </small>
            </article>
            <article class="customers-stat">
                <span class="customers-stat-label">Sales total</span>
                <strong class="customers-stat-value">@peso($stats['salesTotal'])</strong>
                <small class="customers-stat-note">From approved payments only</small>
            </article>
            <article class="customers-stat">
                <span class="customers-stat-label">Average price</span>
                <strong class="customers-stat-value">@peso($stats['averagePrice'])</strong>
                <small class="customers-stat-note">Per artwork sold</small>
            </article>
            <article class="customers-stat">
                <span class="customers-stat-label">Top-selling artist</span>
                <strong class="customers-stat-value sold-stat-text">{{ $stats['topArtist'] ?? '—' }}</strong>
                <small class="customers-stat-note">
                    {{ $stats['topArtist'] ? number_format($stats['topArtistSold']).' '.\Illuminate\Support\Str::plural('artwork', $stats['topArtistSold']).' sold' : 'No sales yet' }}
                </small>
            </article>
        </section>

        <section class="admin-panel customers-table-panel" aria-labelledby="sold-list-title">
            <div class="customers-panel-header">
                <div>
                    <h2 id="sold-list-title">Sales history</h2>
                    <p>
                        @if ($soldItems->total() > 0)
                            {{ number_format($soldItems->total()) }} {{ \Illuminate\Support\Str::plural('sale', $soldItems->total()) }}{{ $hasFilters ? ' match your filters' : '' }}
                        @else
                            Search by artwork, artist, buyer, or order number.
                        @endif
                    </p>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.sold.index') }}" class="customers-filter-bar">
                <label class="customers-search">
                    <span class="visually-hidden">Search by artwork, artist, buyer, or order number</span>
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m16 16 4 4"></path>
                    </svg>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search artwork, artist, buyer, or order #">
                </label>

                <label class="customers-filter">
                    <span class="customers-filter-label">Sold</span>
                    <select name="period">
                        <option value="all" @selected($period === 'all')>Any time</option>
                        <option value="last_30_days" @selected($period === 'last_30_days')>Last 30 days</option>
                        <option value="this_month" @selected($period === 'this_month')>This month</option>
                        <option value="this_year" @selected($period === 'this_year')>This year</option>
                    </select>
                </label>

                <label class="customers-filter">
                    <span class="customers-filter-label">Sort</span>
                    <select name="sort">
                        <option value="newest" @selected($sort === 'newest')>Newest first</option>
                        <option value="oldest" @selected($sort === 'oldest')>Oldest first</option>
                        <option value="highest_price" @selected($sort === 'highest_price')>Highest price</option>
                        <option value="title" @selected($sort === 'title')>Title A–Z</option>
                    </select>
                </label>

                <div class="customers-filter-actions">
                    <button type="submit" class="customers-filter-submit">Apply</button>
                    @if ($hasFilters || $sort !== 'newest')
                        <a href="{{ route('admin.sold.index') }}" class="customers-filter-reset">Reset</a>
                    @endif
                </div>
            </form>

            @if ($soldItems->isNotEmpty())
                <div class="customers-table-scroll">
                    <table class="customers-table sold-table">
                        <thead>
                            <tr>
                                <th scope="col">Artwork</th>
                                <th scope="col">Buyer</th>
                                <th scope="col">Order</th>
                                <th scope="col">Sold on</th>
                                <th scope="col" class="customers-num">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($soldItems as $soldItem)
                                @php
                                    $soldAt = \Illuminate\Support\Carbon::parse($soldItem->sold_at);
                                @endphp
                                <tr>
                                    <td data-label="Artwork">
                                        <span class="sold-artwork-cell">
                                            <img src="{{ asset($soldItem->image_path) }}" alt="" class="sold-thumb" loading="lazy">
                                            <span class="customer-identity">
                                                <strong>{{ $soldItem->title }}</strong>
                                                <span class="customer-email">
                                                    {{ $soldItem->artist_name }}@if ($soldItem->artwork?->category) · {{ $soldItem->artwork->category }}@endif
                                                </span>
                                            </span>
                                        </span>
                                    </td>
                                    <td data-label="Buyer">
                                        <span class="customer-identity">
                                            <strong>{{ $soldItem->customer_name }}</strong>
                                            <span class="customer-email">{{ $soldItem->customer_email }}</span>
                                        </span>
                                    </td>
                                    <td data-label="Order">
                                        <a href="{{ route('admin.orders.show', $soldItem->order_id) }}" class="sold-order-link">{{ $soldItem->order_number }}</a>
                                    </td>
                                    <td data-label="Sold on" class="customer-date">
                                        <time datetime="{{ $soldAt->toIso8601String() }}" title="{{ $soldAt->format('M j, Y g:i A') }}">{{ $soldAt->format('M j, Y') }}</time>
                                    </td>
                                    <td data-label="Price" class="customer-paid-total customers-num">@peso((float) $soldItem->subtotal)</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <footer class="customers-table-footer">
                    <span>Showing <strong>{{ $soldItems->firstItem() }}–{{ $soldItems->lastItem() }}</strong> of {{ number_format($soldItems->total()) }}</span>
                    {{ $soldItems->links() }}
                </footer>
            @else
                <div class="customers-empty-state">
                    <span class="customers-empty-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8Z"></path>
                            <circle cx="7.5" cy="7.5" r="1.5"></circle>
                        </svg>
                    </span>
                    <strong>{{ $hasFilters ? 'No matching sales' : 'No sold items yet' }}</strong>
                    <span>{{ $hasFilters ? 'Try a different search or time period.' : 'Artworks appear here once you approve an order\'s payment.' }}</span>
                    @if ($hasFilters)
                        <a href="{{ route('admin.sold.index') }}" class="customers-filter-reset">Reset filters</a>
                    @endif
                </div>
            @endif
        </section>

        <p class="customers-footnote">
            <strong>Sold</strong> means the order's payment was approved. Orders awaiting review, rejected, or refunded are not listed.
        </p>
    </main>
</div>
</body>
</html>
