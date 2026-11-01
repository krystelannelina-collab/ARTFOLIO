<aside class="admin-sidebar">
    <a href="{{ route('admin.dashboard') }}" class="admin-brand" aria-label="ARTFOLIO admin home">
        <svg class="admin-brand-mark" viewBox="0 0 32 32" fill="none" aria-hidden="true">
            <path d="M5.33325 27.5556L14.2221 4.44444H17.7777L11.5555 22.2222H18.6666L20.4444 27.5556H5.33325Z" fill="currentColor"/>
            <path d="M19.5556 11.5556L25.7778 27.5556H21.3334L16.8889 16L19.5556 11.5556Z" fill="currentColor"/>
            <circle cx="24" cy="8" r="2.22" fill="currentColor"/>
        </svg>
        <span class="admin-brand-name">ARTFOLIO</span>
        <span class="admin-brand-caption">ART LIVES HERE</span>
    </a>

    <div class="admin-nav-label">STORE MANAGEMENT</div>
    <nav class="admin-nav" aria-label="Admin navigation">
        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ $active === 'dashboard' ? 'is-active' : '' }}" @if ($active === 'dashboard') aria-current="page" @endif>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <rect x="3" y="3" width="8" height="8" rx="1.5"></rect>
                <rect x="13" y="3" width="8" height="5" rx="1.5"></rect>
                <rect x="13" y="10" width="8" height="11" rx="1.5"></rect>
                <rect x="3" y="13" width="8" height="8" rx="1.5"></rect>
            </svg>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.artworks.index') }}" class="admin-nav-link {{ $active === 'artworks' ? 'is-active' : '' }}" @if ($active === 'artworks') aria-current="page" @endif>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <rect x="4" y="3" width="16" height="18" rx="2"></rect>
                <path d="M8 8h8M8 12h8M8 16h5"></path>
            </svg>
            <span>Artworks</span>
            @if (isset($artworkCount))
                <span class="admin-nav-count">{{ $artworkCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.artists.index') }}" class="admin-nav-link {{ $active === 'artists' ? 'is-active' : '' }}" @if ($active === 'artists') aria-current="page" @endif>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="12" cy="8" r="3.5"></circle>
                <path d="M4.5 20v-1.5a7.5 7.5 0 0 1 15 0V20"></path>
            </svg>
            <span>Artists</span>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="admin-nav-link {{ $active === 'orders' ? 'is-active' : '' }}" @if ($active === 'orders') aria-current="page" @endif>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M5 4h14v16H5zM8 8h8M8 12h8M8 16h5"></path>
            </svg>
            <span>Orders</span>
        </a>
        <a href="{{ route('admin.customers.index') }}" class="admin-nav-link {{ $active === 'customers' ? 'is-active' : '' }}" @if ($active === 'customers') aria-current="page" @endif>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="9" cy="8" r="3"></circle>
                <path d="M3.5 20v-1.5A4.5 4.5 0 0 1 8 14h2a4.5 4.5 0 0 1 4.5 4.5V20"></path>
                <path d="M16 5.2a3 3 0 0 1 0 5.6M17 14h.5a3 3 0 0 1 3 3v1"></path>
            </svg>
            <span>Customers</span>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="admin-nav-link {{ $active === 'categories' ? 'is-active' : '' }}" @if ($active === 'categories') aria-current="page" @endif>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M20 13 11 22 2 13V4h9l9 9Z"></path>
                <circle cx="7" cy="8" r="1"></circle>
            </svg>
            <span>Categories</span>
        </a>
    </nav>

    <div class="admin-sidebar-bottom">
        <a href="{{ url('/home') }}" class="admin-back-link">
            <span aria-hidden="true">↗</span>
            View client site
        </a>
    </div>

    <details class="admin-account-menu">
        <summary class="admin-sidebar-account" aria-label="Account menu for {{ auth()->user()->name }}">
            <span class="admin-account-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            <span class="admin-account-copy">
                <strong>{{ auth()->user()->name }}</strong>
                <small>Administrator</small>
            </span>
            <span class="admin-account-caret" aria-hidden="true">⌄</span>
        </summary>
        <div class="admin-account-menu-panel" aria-label="Account options">
            <button type="button" class="admin-account-menu-item" disabled title="Settings are coming soon.">
                Settings
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="admin-account-menu-item">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M10 17l5-5-5-5M15 12H3"></path>
                        <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"></path>
                    </svg>
                    Log out
                </button>
            </form>
        </div>
    </details>
</aside>
