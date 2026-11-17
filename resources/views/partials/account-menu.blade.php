{{-- Top-bar bell (order updates) and avatar (account menu). Data comes from the view composer in AppServiceProvider. --}}
@if ($menuUser)
    <div class="account-menu" data-account-menu>
        <div class="account-menu-item">
            <button
                type="button"
                class="topbar-btn account-menu-trigger"
                aria-label="Order updates{{ $unseenOrderUpdates > 0 ? " ({$unseenOrderUpdates} new)" : '' }}"
                aria-haspopup="true"
                aria-expanded="false"
                aria-controls="order-updates-panel"
                data-menu-trigger
                @if ($unseenOrderUpdates > 0) data-mark-seen-url="{{ route('order-updates.seen') }}" @endif
            >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                @if ($unseenOrderUpdates > 0)
                    <span class="account-menu-badge" data-unseen-badge>{{ $unseenOrderUpdates > 9 ? '9+' : $unseenOrderUpdates }}</span>
                @endif
            </button>

            <div class="account-menu-panel order-updates-panel" id="order-updates-panel" hidden data-menu-panel>
                <p class="account-menu-heading">Order updates</p>
                @forelse ($orderUpdates as $update)
                    <a href="{{ route('orders.show', $update) }}" class="order-update-link">
                        <span class="order-update-status status-{{ $update->status }}">{{ $update->statusLabel() }}</span>
                        <span class="order-update-text">Order #{{ $update->order_number }}</span>
                        <time datetime="{{ $update->payment_reviewed_at->toIso8601String() }}">{{ $update->payment_reviewed_at->diffForHumans() }}</time>
                    </a>
                @empty
                    <p class="account-menu-empty">No updates yet. When an admin reviews a payment, it will appear here.</p>
                @endforelse
                <a href="{{ route('orders') }}" class="account-menu-footer-link">View all orders</a>
            </div>
        </div>

        <div class="account-menu-item">
            <button
                type="button"
                class="user-avatar-pill account-menu-trigger"
                aria-label="Account menu for {{ $menuUser->name }}"
                aria-haspopup="true"
                aria-expanded="false"
                aria-controls="account-panel"
                data-menu-trigger
            >{{ $menuUser->initials() }}</button>

            <div class="account-menu-panel" id="account-panel" hidden data-menu-panel>
                <div class="account-menu-identity">
                    <strong>{{ $menuUser->name }}</strong>
                    <span>{{ $menuUser->email }}</span>
                </div>
                <a href="{{ route('profile.edit') }}" class="account-menu-link">Profile</a>
                <a href="{{ route('orders') }}" class="account-menu-link">My orders</a>
                <a href="{{ route('favorites') }}" class="account-menu-link">Favorites</a>
                <a href="{{ route('cart') }}" class="account-menu-link">Cart</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="account-menu-link account-menu-logout">Log out</button>
                </form>
            </div>
        </div>
    </div>

    @once
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const menu = document.querySelector('[data-account-menu]');
                if (!menu) {
                    return;
                }

                const items = Array.from(menu.querySelectorAll('.account-menu-item'));
                const csrfToken = menu.querySelector('input[name="_token"]')?.value ?? '';

                const closeAll = (except = null) => {
                    items.forEach((item) => {
                        if (item === except) {
                            return;
                        }
                        item.querySelector('[data-menu-panel]').hidden = true;
                        item.querySelector('[data-menu-trigger]').setAttribute('aria-expanded', 'false');
                    });
                };

                items.forEach((item) => {
                    const trigger = item.querySelector('[data-menu-trigger]');
                    const panel = item.querySelector('[data-menu-panel]');

                    trigger.addEventListener('click', (event) => {
                        event.stopPropagation();
                        const willOpen = panel.hidden;
                        closeAll(item);
                        panel.hidden = !willOpen;
                        trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');

                        const markSeenUrl = trigger.dataset.markSeenUrl;
                        if (willOpen && markSeenUrl) {
                            delete trigger.dataset.markSeenUrl;
                            fetch(markSeenUrl, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                            }).then((response) => {
                                if (response.ok) {
                                    trigger.querySelector('[data-unseen-badge]')?.remove();
                                    trigger.setAttribute('aria-label', 'Order updates');
                                }
                            }).catch(() => {});
                        }
                    });
                });

                document.addEventListener('click', (event) => {
                    if (!menu.contains(event.target)) {
                        closeAll();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        closeAll();
                    }
                });
            });
        </script>
    @endonce
@endif
