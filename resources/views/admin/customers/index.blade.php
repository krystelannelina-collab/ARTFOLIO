<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers | ARTFOLIO Admin</title>
    <meta name="description" content="Browse ARTFOLIO customers and their order history.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-customers.css') }}">
</head>
<body>
<div class="admin-layout">
    @include('admin.partials.sidebar', ['active' => 'customers'])

    <main class="admin-main customers-main">
        <header class="admin-page-heading customers-page-heading">
            <div>
                <span class="admin-eyebrow">STORE MANAGEMENT</span>
                <h1>Customers</h1>
                <p>Registered client accounts, their orders, and how much they have spent.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="customers-heading-link">
                Go to orders <span aria-hidden="true">→</span>
            </a>
        </header>

        <section class="customers-overview" aria-label="Customer totals">
            <article class="customers-stat">
                <span class="customers-stat-label">Total customers</span>
                <strong class="customers-stat-value">{{ number_format($stats['totalCustomers']) }}</strong>
                <small class="customers-stat-note">
                    {{ $stats['newCustomers'] > 0 ? '+'.number_format($stats['newCustomers']).' joined in the last 30 days' : 'No new sign-ups in the last 30 days' }}
                </small>
            </article>
            <article class="customers-stat">
                <span class="customers-stat-label">Customers with orders</span>
                <strong class="customers-stat-value">{{ number_format($stats['customersWithOrders']) }}</strong>
                <small class="customers-stat-note">{{ $stats['orderingShare'] }}% of all customers have ordered</small>
                <span class="customers-stat-meter" aria-hidden="true"><span style="width: {{ $stats['orderingShare'] }}%"></span></span>
            </article>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="customers-stat customers-stat-link {{ $stats['pendingOrders'] > 0 ? 'needs-attention' : '' }}">
                <span class="customers-stat-label">Orders placed</span>
                <strong class="customers-stat-value">{{ number_format($stats['ordersPlaced']) }}</strong>
                <small class="customers-stat-note">
                    @if ($stats['pendingOrders'] > 0)
                        {{ number_format($stats['pendingOrders']) }} awaiting payment review <span aria-hidden="true">→</span>
                    @else
                        No payments waiting for review
                    @endif
                </small>
            </a>
            <article class="customers-stat">
                <span class="customers-stat-label">Paid sales</span>
                <strong class="customers-stat-value">@peso($stats['paidSales'])</strong>
                <small class="customers-stat-note">
                    @if ($stats['averagePerPayingCustomer'] > 0)
                        Avg. @peso($stats['averagePerPayingCustomer']) per paying customer
                    @else
                        No approved payments yet
                    @endif
                </small>
            </article>
        </section>

        <section class="admin-panel customers-table-panel" aria-labelledby="customers-directory-title">
            <div class="customers-panel-header">
                <div>
                    <h2 id="customers-directory-title">Customer directory</h2>
                    <p>
                        @if ($customers->total() > 0)
                            {{ number_format($customers->total()) }} {{ \Illuminate\Support\Str::plural('customer', $customers->total()) }}{{ $hasFilters ? ' match your filters' : '' }}
                        @else
                            Search by name, username, or email.
                        @endif
                    </p>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.customers.index') }}" class="customers-filter-bar">
                <label class="customers-search">
                    <span class="visually-hidden">Search by name, username, or email</span>
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m16 16 4 4"></path>
                    </svg>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by name, username, or email">
                </label>

                <label class="customers-filter">
                    <span class="customers-filter-label">Show</span>
                    <select name="status">
                        <option value="all" @selected(($filters['status'] ?? 'all') === 'all')>All customers</option>
                        <option value="ordered" @selected(($filters['status'] ?? '') === 'ordered')>Has orders</option>
                        <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Payment pending</option>
                        <option value="no_orders" @selected(($filters['status'] ?? '') === 'no_orders')>No orders yet</option>
                    </select>
                </label>

                <label class="customers-filter">
                    <span class="customers-filter-label">Sort</span>
                    <select name="sort">
                        <option value="newest" @selected($sort === 'newest')>Newest first</option>
                        <option value="oldest" @selected($sort === 'oldest')>Oldest first</option>
                        <option value="most_orders" @selected($sort === 'most_orders')>Most orders</option>
                        <option value="highest_spend" @selected($sort === 'highest_spend')>Highest paid total</option>
                        <option value="name" @selected($sort === 'name')>Name A–Z</option>
                    </select>
                </label>

                <div class="customers-filter-actions">
                    <button type="submit" class="customers-filter-submit">Apply</button>
                    @if ($hasFilters || $sort !== 'newest')
                        <a href="{{ route('admin.customers.index') }}" class="customers-filter-reset">Reset</a>
                    @endif
                </div>
            </form>

            @if ($customers->isNotEmpty())
                <div class="customers-table-scroll">
                    <table class="customers-table">
                        <thead>
                            <tr>
                                <th scope="col">Customer</th>
                                <th scope="col" class="customers-num">Orders</th>
                                <th scope="col" class="customers-num">Paid total</th>
                                <th scope="col">Last order</th>
                                <th scope="col">Joined</th>
                                <th scope="col">Status</th>
                                <th scope="col"><span class="visually-hidden">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                @php
                                    $lastOrderAt = $customer->last_order_at ? \Illuminate\Support\Carbon::parse($customer->last_order_at) : null;
                                    [$statusClass, $statusLabel] = match (true) {
                                        $customer->pending_count > 0 => ['is-pending', 'Payment pending'],
                                        $customer->paid_total > 0 => ['is-collector', 'Collector'],
                                        $customer->orders_count > 0 => ['is-unpaid', 'No paid orders'],
                                        default => ['is-new', 'No orders yet'],
                                    };
                                @endphp
                                <tr>
                                    <td data-label="Customer">
                                        <span class="customer-name-cell">
                                            <span class="customer-avatar" aria-hidden="true">{{ $customer->initials() }}</span>
                                            <span class="customer-identity">
                                                <strong>{{ $customer->name }}</strong>
                                                <span class="customer-email">
                                                    @if ($customer->username)<span class="customer-username">{{ '@'.$customer->username }}</span> · @endif{{ $customer->email }}
                                                </span>
                                            </span>
                                        </span>
                                    </td>
                                    <td data-label="Orders" class="customer-orders-count customers-num">{{ number_format((int) $customer->orders_count) }}</td>
                                    <td data-label="Paid total" class="customer-paid-total customers-num">@peso((float) $customer->paid_total)</td>
                                    <td data-label="Last order" class="customer-date">
                                        @if ($lastOrderAt)
                                            <time datetime="{{ $lastOrderAt->toIso8601String() }}" title="{{ $lastOrderAt->format('M j, Y g:i A') }}">{{ $lastOrderAt->format('M j, Y') }}</time>
                                        @else
                                            <span class="customer-muted">—</span>
                                        @endif
                                    </td>
                                    <td data-label="Joined" class="customer-date">
                                        <time datetime="{{ $customer->created_at->toIso8601String() }}">{{ $customer->created_at->format('M j, Y') }}</time>
                                    </td>
                                    <td data-label="Status">
                                        <span class="customer-status {{ $statusClass }}">{{ $statusLabel }}</span>
                                    </td>
                                    <td class="customer-action-cell">
                                        @if ($customer->orders_count > 0)
                                            <a
                                                href="{{ route('admin.orders.index', ['search' => $customer->email]) }}"
                                                class="customer-view-link"
                                                aria-label="View orders for {{ $customer->name }}"
                                            >View orders <span aria-hidden="true">→</span></a>
                                        @else
                                            <span class="customer-muted customer-no-action">No orders</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <footer class="customers-table-footer">
                    <span>Showing <strong>{{ $customers->firstItem() }}–{{ $customers->lastItem() }}</strong> of {{ number_format($customers->total()) }}</span>
                    {{ $customers->links() }}
                </footer>
            @else
                <div class="customers-empty-state">
                    <span class="customers-empty-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </span>
                    <strong>{{ $hasFilters ? 'No matching customers' : 'No customers yet' }}</strong>
                    <span>{{ $hasFilters ? 'Try a different search or filter.' : 'Customer accounts will appear here after they sign up.' }}</span>
                    @if ($hasFilters)
                        <a href="{{ route('admin.customers.index') }}" class="customers-filter-reset">Reset filters</a>
                    @endif
                </div>
            @endif
        </section>

        <p class="customers-footnote">
            <strong>Paid total</strong> counts approved payments only. Orders awaiting review, rejected, or refunded are not counted as sales.
        </p>
    </main>
</div>
</body>
</html>
