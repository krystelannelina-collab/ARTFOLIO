<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | ARTFOLIO</title>
    <meta name="description" content="ARTFOLIO catalog and client overview.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
</head>
<body>
<div class="admin-layout">
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
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-link is-active" aria-current="page">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="3" y="3" width="8" height="8" rx="1.5"></rect>
                    <rect x="13" y="3" width="8" height="5" rx="1.5"></rect>
                    <rect x="13" y="10" width="8" height="11" rx="1.5"></rect>
                    <rect x="3" y="13" width="8" height="8" rx="1.5"></rect>
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="#catalog" class="admin-nav-link">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="4" y="3" width="16" height="18" rx="2"></rect>
                    <path d="M8 8h8M8 12h8M8 16h5"></path>
                </svg>
                <span>Artworks</span>
                <span class="admin-nav-count">{{ $stats['artworks'] }}</span>
            </a>
            <a href="#artists" class="admin-nav-link">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="12" cy="8" r="3.5"></circle>
                    <path d="M4.5 20v-1.5a7.5 7.5 0 0 1 15 0V20"></path>
                </svg>
                <span>Artists</span>
            </a>
            <a href="#clients" class="admin-nav-link">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="9" cy="8" r="3"></circle>
                    <path d="M3.5 20v-1.5A4.5 4.5 0 0 1 8 14h2a4.5 4.5 0 0 1 4.5 4.5V20"></path>
                    <path d="M16 5.2a3 3 0 0 1 0 5.6M17 14h.5a3 3 0 0 1 3 3v1"></path>
                </svg>
                <span>Clients</span>
            </a>
        </nav>

        <div class="admin-sidebar-bottom">
            <a href="{{ url('/home') }}" class="admin-back-link">
                <span aria-hidden="true">↗</span>
                View client site
            </a>
            <form method="POST" action="{{ url('/logout') }}">
                @csrf
                <button type="submit" class="admin-logout">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M10 17l5-5-5-5M15 12H3"></path>
                        <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"></path>
                    </svg>
                    Log out
                </button>
            </form>
        </div>

        <div class="admin-sidebar-account">
            <span class="admin-account-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            <span class="admin-account-copy">
                <strong>{{ auth()->user()->name }}</strong>
                <small>Administrator</small>
            </span>
            <span class="admin-account-caret" aria-hidden="true">⌃</span>
        </div>
    </aside>

    <main class="admin-main">
        <header class="admin-page-heading">
            <div>
                <span class="admin-eyebrow">A LITTLE INSPIRATION. A CLEAR VIEW OF YOUR STORE.</span>
                <h1>Your creative workspace.</h1>
                <p>Welcome back, {{ auth()->user()->name }}. Here’s your store at a glance.</p>
            </div>
            <span class="admin-date-pill">{{ now()->format('D, M j') }}</span>
        </header>

        <section class="dashboard-top-grid" aria-label="Catalog overview">
            <article class="admin-panel catalog-panel">
                <div class="admin-panel-heading">
                    <div>
                        <h2>Catalog snapshot</h2>
                        <p>Artwork mix across your collection.</p>
                    </div>
                    <span class="panel-tag">LIVE DATA</span>
                </div>
                @if ($stats['artworks'] > 0)
                    <div class="category-chart-wrap">
                        <div class="category-chart-canvas">
                            <canvas id="category-chart" aria-label="Artworks grouped by category" role="img"></canvas>
                            <div class="chart-center-total" aria-hidden="true">
                                <strong>{{ number_format($stats['artworks']) }}</strong>
                                <span>ARTWORKS</span>
                            </div>
                        </div>
                    </div>
                    <ul class="category-legend" id="category-legend" aria-label="Category totals"></ul>
                @else
                    <div class="admin-chart-empty">Catalog data will appear here after artworks are added.</div>
                @endif
            </article>

            <article class="admin-panel featured-panel" id="catalog">
                <div class="admin-panel-heading featured-heading">
                    <div>
                        <h2>From the collection</h2>
                        <p>A few of the digital artworks in your store.</p>
                    </div>
                    <a href="{{ url('/artworks') }}" class="gallery-link">Visit store <span aria-hidden="true">↗</span></a>
                </div>
                @if ($recentArtworks->isNotEmpty())
                    <div class="artwork-gallery" aria-label="Recent artworks">
                        @foreach ($recentArtworks as $artwork)
                            <article class="gallery-card">
                                <a href="{{ route('artworks.show', ['slug' => $artwork->slug]) }}" class="gallery-artwork-link">
                                    <img src="{{ asset($artwork->image_path) }}" alt="{{ $artwork->title }}" loading="lazy">
                                </a>
                                <div class="gallery-card-copy">
                                    <strong>{{ $artwork->title }}</strong>
                                    <span>{{ $artwork->artist_name }}</span>
                                    <div class="gallery-card-bottom">
                                        <b>${{ number_format((float) $artwork->price, 2) }}</b>
                                        <a href="{{ route('artworks.show', ['slug' => $artwork->slug]) }}">View</a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="gallery-empty">Your collection is ready for its first artwork.</div>
                @endif
            </article>
        </section>

        <section class="dashboard-bottom-grid" aria-label="Store activity">
            <article class="admin-panel recent-artworks-panel" id="artists">
                <div class="admin-panel-heading recent-heading">
                    <div>
                        <h2>Recently added artworks</h2>
                        <p>The latest pieces and artists in your catalog.</p>
                    </div>
                    <a href="{{ url('/artworks') }}" class="panel-text-link">View store <span aria-hidden="true">→</span></a>
                </div>
                @if ($recentArtworks->isNotEmpty())
                    <div class="admin-table-scroll">
                        <table class="admin-table">
                            <thead><tr><th>ARTWORK</th><th>ARTIST</th><th>CATEGORY</th><th>PRICE</th></tr></thead>
                            <tbody>
                                @foreach ($recentArtworks as $artwork)
                                    <tr>
                                        <td>
                                            <span class="table-artwork">
                                                <img src="{{ asset($artwork->image_path) }}" alt="" loading="lazy">
                                                <span>{{ $artwork->title }}</span>
                                            </span>
                                        </td>
                                        <td>{{ $artwork->artist_name }}</td>
                                        <td><span class="category-chip">{{ $artwork->category }}</span></td>
                                        <td class="price-cell">${{ number_format((float) $artwork->price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="admin-table-empty">No artworks have been added to the catalog yet.</div>
                @endif
            </article>

            <div class="dashboard-side-stack">
                <article class="admin-panel metric-card">
                    <span class="metric-label">FEATURED ARTISTS</span>
                    <strong class="metric-value">{{ number_format($stats['artists']) }}</strong>
                    <span class="metric-note">Creative contributors in your catalog</span>
                </article>
                <article class="admin-panel metric-card">
                    <span class="metric-label">CLIENT ACCOUNTS</span>
                    <strong class="metric-value">{{ number_format($stats['clients']) }}</strong>
                    <span class="metric-note">Registered members of your store</span>
                </article>
            </div>
        </section>

        <section class="dashboard-support-grid" aria-label="Client activity">
            <article class="admin-panel signup-panel">
                <div class="admin-panel-heading">
                    <div>
                        <h2>Client signups</h2>
                        <p>New client accounts over the last six months.</p>
                    </div>
                    <span class="panel-tag">LAST 6 MONTHS</span>
                </div>
                @if ($stats['clients'] > 0)
                    <div class="signup-chart-wrap"><canvas id="signup-chart" aria-label="New client signups by month" role="img"></canvas></div>
                @else
                    <div class="support-empty">New client signups will appear here.</div>
                @endif
            </article>

            <article class="admin-panel clients-panel" id="clients">
                <div class="admin-panel-heading">
                    <div>
                        <h2>New clients</h2>
                        <p>Recently registered accounts.</p>
                    </div>
                </div>
                @if ($recentClients->isNotEmpty())
                    <ul class="member-list">
                        @foreach ($recentClients as $client)
                            <li class="member-list-item">
                                <span class="member-avatar">{{ strtoupper(substr($client->name, 0, 1)) }}</span>
                                <span class="member-copy"><strong>{{ $client->name }}</strong><small>{{ $client->email }}</small></span>
                                <time datetime="{{ $client->created_at->toDateString() }}">{{ $client->created_at->format('M j') }}</time>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="support-empty">New client accounts will appear here.</div>
                @endif
            </article>
        </section>

        <aside class="admin-coming-soon" aria-label="Sales reporting status">
            <span class="coming-soon-icon" aria-hidden="true">↗</span>
            <span><strong>Sales reporting is next.</strong> Orders and revenue will appear here once checkout is connected to the SQLite database.</span>
        </aside>

        <footer class="admin-footer">ARTFOLIO ADMIN <span aria-hidden="true">·</span> Catalog and client metrics update from SQLite.</footer>
    </main>
</div>

@if ($stats['artworks'] > 0 || $stats['clients'] > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
    <script>
        const categoryData = @json($categoryChart);
        const signupData = @json($clientSignupChart);
        const chartColors = ['#315b45', '#d2a06a', '#7996a3', '#a28db4', '#d17b67', '#8ca47b'];

        if (document.getElementById('category-chart')) {
            new Chart(document.getElementById('category-chart'), {
                type: 'doughnut',
                data: {
                    labels: categoryData.labels,
                    datasets: [{
                        data: categoryData.values,
                        backgroundColor: chartColors,
                        borderColor: '#fbfaf6',
                        borderWidth: 3,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '79%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (context) => ` ${context.label}: ${context.raw} artwork${context.raw === 1 ? '' : 's'}`
                            }
                        }
                    }
                }
            });

            const categoryLegend = document.getElementById('category-legend');
            categoryData.labels.forEach((label, index) => {
                const item = document.createElement('li');
                const marker = document.createElement('span');
                const name = document.createElement('span');
                const total = document.createElement('strong');
                marker.className = 'legend-marker';
                marker.style.backgroundColor = chartColors[index % chartColors.length];
                name.textContent = label;
                total.textContent = categoryData.values[index];
                item.append(marker, name, total);
                categoryLegend.append(item);
            });
        }

        if (document.getElementById('signup-chart')) {
            new Chart(document.getElementById('signup-chart'), {
                type: 'line',
                data: {
                    labels: signupData.labels,
                    datasets: [{
                        label: 'New clients',
                        data: signupData.values,
                        borderColor: '#315b45',
                        backgroundColor: 'rgba(49, 91, 69, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#315b45'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, border: { display: false }, ticks: { color: '#66695f', font: { family: 'DM Sans', size: 11 } } },
                        y: { beginAtZero: true, border: { display: false }, grid: { color: '#ecebe4' }, ticks: { precision: 0, color: '#66695f', font: { family: 'DM Sans', size: 11 } } }
                    }
                }
            });
        }
    </script>
@endif
</body>
</html>
