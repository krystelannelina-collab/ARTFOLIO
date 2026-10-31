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
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-customers.css') }}">
</head>
<body>
<div class="admin-layout">
    @include('admin.partials.sidebar', ['active' => 'customers'])

    <main class="admin-main">
        <header class="admin-page-heading customers-page-heading">
            <div>
                <span class="admin-eyebrow">STORE MANAGEMENT</span>
                <h1>Customers</h1>
                <p>Get to know your community and follow their order activity.</p>
            </div>
        </header>

        <section class="customers-overview" aria-label="Customer totals">
            <article>
                <span>Total customers</span>
                <strong>{{ number_format($stats['totalCustomers']) }}</strong>
                <small>Registered client accounts</small>
            </article>
            <article>
                <span>Customers with orders</span>
                <strong>{{ number_format($stats['customersWithOrders']) }}</strong>
                <small>Have placed at least one order</small>
            </article>
            <article>
                <span>Orders placed</span>
                <strong>{{ number_format($stats['ordersPlaced']) }}</strong>
                <small>Associated with customer accounts</small>
            </article>
            <article>
                <span>Paid sales</span>
                <strong>${{ number_format($stats['paidSales'], 2) }}</strong>
                <small>Completed customer orders</small>
            </article>
        </section>

        <section class="admin-panel customers-table-panel" aria-label="Customer directory">
            <form method="GET" action="{{ route('admin.customers.index') }}" class="customers-filter-bar">
                <label class="customers-search">
                    <span class="customers-visually-hidden">Search customer name or email</span>
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m16 16 4 4"></path>
                    </svg>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search customer name or email...">
                </label>

                <label class="customers-filter">
                    <span class="customers-visually-hidden">Filter customers by orders</span>
                    <select name="status">
                        <option value="all" @selected(($filters['status'] ?? 'all') === 'all')>All customers</option>
                        <option value="ordered" @selected(($filters['status'] ?? '') === 'ordered')>Has orders</option>
                        <option value="no_orders" @selected(($filters['status'] ?? '') === 'no_orders')>No orders yet</option>
                    </select>
                </label>

                <label class="customers-filter customers-sort">
                    <span class="customers-visually-hidden">Sort customers</span>
                    <select name="sort">
                        <option value="newest" @selected($sort === 'newest')>Newest first</option>
                        <option value="oldest" @selected($sort === 'oldest')>Oldest first</option>
                        <option value="most_orders" @selected($sort === 'most_orders')>Most orders</option>
                        <option value="highest_spend" @selected($sort === 'highest_spend')>Highest paid total</option>
                        <option value="name" @selected($sort === 'name')>Name A–Z</option>
                    </select>
                </label>

                <button type="submit" class="customers-filter-submit">Apply</button>
                <a href="{{ route('admin.customers.index') }}" class="customers-filter-reset">Reset</a>
            </form>

            @if ($customers->isNotEmpty())
                <div class="customers-table-scroll">
                    <table class="customers-table">
                        <thead>
                            <tr>
                                <th scope="col">CUSTOMER</th>
                                <th scope="col">EMAIL</th>
                                <th scope="col">ORDERS</th>
                                <th scope="col">PAID TOTAL</th>
                                <th scope="col">JOINED</th>
                                <th scope="col">STATUS</th>
                                <th scope="col">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <td>
                                        <span class="customer-name-cell">
                                            <span class="customer-avatar" aria-hidden="true">{{ strtoupper(substr(trim($customer->name), 0, 1)) }}</span>
                                            <strong>{{ $customer->name }}</strong>
                                        </span>
                                    </td>
                                    <td class="customer-email">{{ $customer->email }}</td>
                                    <td class="customer-orders-count">{{ number_format((int) $customer->orders_count) }}</td>
                                    <td class="customer-paid-total">${{ number_format((float) $customer->paid_total, 2) }}</td>
                                    <td class="customer-date">{{ $customer->created_at->format('M j, Y') }}</td>
                                    <td>
                                        <span class="customer-status {{ $customer->orders_count > 0 ? 'has-orders' : 'no-orders' }}">
                                            {{ $customer->orders_count > 0 ? 'Has orders' : 'New customer' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('admin.orders.index', ['search' => $customer->name]) }}"
                                            class="customer-view-link"
                                            aria-label="View orders for {{ $customer->name }}"
                                        >View orders <span aria-hidden="true">↗</span></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <footer class="customers-table-footer">
                    <span>Showing {{ $customers->firstItem() }}–{{ $customers->lastItem() }} of {{ number_format($customers->total()) }} customers</span>
                    {{ $customers->links() }}
                </footer>
            @else
                <div class="customers-empty-state">
                    <strong>{{ ($filters['search'] ?? '') !== '' || ($filters['status'] ?? 'all') !== 'all' ? 'No matching customers' : 'No customers yet' }}</strong>
                    <span>{{ ($filters['search'] ?? '') !== '' || ($filters['status'] ?? 'all') !== 'all' ? 'Try adjusting your search or filters.' : 'Customer accounts will appear here after they sign up.' }}</span>
                    @if (($filters['search'] ?? '') !== '' || ($filters['status'] ?? 'all') !== 'all')
                        <a href="{{ route('admin.customers.index') }}" class="customers-filter-reset">Reset filters</a>
                    @endif
                </div>
            @endif
        </section>

        <p class="customers-footnote">Paid totals include completed orders only. Pending orders are not counted as revenue.</p>
    </main>
</div>
</body>
</html>
