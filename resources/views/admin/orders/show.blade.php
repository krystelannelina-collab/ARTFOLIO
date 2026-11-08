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

        @if (session('status'))
            <div class="orders-status-flash" role="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="orders-error-flash" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="orders-payment-notice status-{{ $order->status }}" role="note">
            @if ($order->isAwaitingApproval())
                Check that this payment arrived before approving it. Approving marks the artworks as sold and unlocks the buyer's downloads. Rejecting puts the artworks back on sale.
            @elseif ($order->isPaid())
                Payment approved{{ $order->payment_reviewed_at ? ' on '.$order->payment_reviewed_at->format('F j, Y \a\t g:i A') : '' }}{{ $order->reviewer ? ' by '.$order->reviewer->name : '' }}. The buyer can download the original files.
            @elseif ($order->status === 'failed')
                Payment rejected{{ $order->payment_reviewed_at ? ' on '.$order->payment_reviewed_at->format('F j, Y') : '' }}.{{ $order->rejection_reason ? ' Reason: '.$order->rejection_reason : '' }}
            @else
                {{ $order->statusLabel() }}. The artworks are back on sale.
            @endif
        </div>

        <div class="order-details-grid">
            <section class="admin-panel order-details-panel">
                <div class="orders-panel-heading">
                    <div>
                        <h2>Items in this order</h2>
                        <p>{{ $order->items->count() }} {{ \Illuminate\Support\Str::plural('artwork', $order->items->count()) }}</p>
                    </div>
                    <span class="order-payment status-{{ $order->status }}">{{ $order->statusLabel() }}</span>
                </div>

                <div class="order-detail-items">
                    @foreach ($order->items as $item)
                        <article class="order-detail-item">
                            <img src="{{ asset($item->image_path) }}" alt="" loading="lazy">
                            <div>
                                <strong>{{ $item->title }}</strong>
                                <span>by {{ $item->artist_name }}</span>
                                <small>Qty {{ $item->quantity }} × @peso($item->unit_price)</small>
                            </div>
                            <b>@peso($item->unit_price * $item->quantity)</b>
                        </article>
                    @endforeach
                </div>

                <div class="order-detail-total">
                    <span>Total</span>
                    <strong>@peso($order->total)</strong>
                </div>
            </section>

            <aside class="admin-panel order-customer-panel">
                <h2>Customer &amp; payment</h2>
                <dl>
                    <div><dt>Name</dt><dd>{{ $order->customer_name }}</dd></div>
                    <div><dt>Email</dt><dd>{{ $order->customer_email }}</dd></div>
                    <div><dt>Payment status</dt><dd>{{ $order->statusLabel() }}</dd></div>
                    <div><dt>Payment method</dt><dd>{{ $order->payment_method === 'not_configured' ? 'Not configured' : $order->payment_method }}</dd></div>
                    @if ($order->payment_account_name)
                        <div><dt>Account name</dt><dd>{{ $order->payment_account_name }}</dd></div>
                    @endif
                    @if ($order->payment_reference)
                        <div><dt>{{ $order->payment_method === 'GCash' ? 'GCash reference no.' : 'Payment reference' }}</dt><dd>{{ $order->payment_reference }}</dd></div>
                    @endif
                </dl>

                @if ($order->isAwaitingApproval())
                    <div class="order-review-actions">
                        <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="order-approve-button">Approve payment</button>
                        </form>

                        <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="order-reject-form">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="failed">
                            <label for="rejection-reason" class="order-status-label">Reason for rejecting</label>
                            <textarea id="rejection-reason" name="rejection_reason" rows="3" maxlength="500" placeholder="e.g. No matching GCash transaction found." required>{{ old('rejection_reason') }}</textarea>
                            <button type="submit" class="order-reject-button" onclick="return confirm('Reject this payment? The artworks will go back on sale.')">Reject payment</button>
                        </form>
                    </div>
                @elseif ($order->isPaid())
                    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="order-status-form">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="refunded">
                        <button type="submit" class="orders-filter-reset" onclick="return confirm('Mark this order as refunded? The buyer loses download access and the artworks go back on sale.')">Mark as refunded</button>
                    </form>
                @endif
            </aside>
        </div>
    </main>
</div>
</body>
</html>
