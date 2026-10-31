<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - Artworks from our Artists</title>
    <meta name="description" content="Explore original artworks created by talented artists">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #ECE9E0;
            --main-bg: #FFFFFF;
            --active-pill: #D8E3D5;
            --active-pill-text: #1E2B20;
            --badge-bg: #C09875;
            --dark-btn-bg: #102B21;
            --dark-btn-hover: #193E30;
            --text-dark: #171717;
            --text-muted: #71717A;
            --border-subtle: rgba(0, 0, 0, 0.06);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--main-bg);
            color: var(--text-dark);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 245px;
            flex: 0 0 245px;
            background: var(--sidebar-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(0, 0, 0, 0.04);
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
        }

        .sidebar::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
        }

        .sidebar-brand-container {
            padding: 22px 20px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .brand-logo-mark {
            width: 44px;
            height: 44px;
            margin-bottom: 4px;
        }

        .brand-logo-title {
            font-size: 15px;
            font-weight: 800;
            letter-spacing: 4px;
            color: #171717;
            line-height: 1.1;
            margin-top: 4px;
        }

        .brand-logo-sub {
            font-size: 7.5px;
            font-weight: 600;
            letter-spacing: 2.8px;
            color: #787878;
            margin-top: 5px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            padding: 6px 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 2px;
            flex: 1;
        }

        .nav-item-link,
        .nav-item-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            min-height: 38px;
            padding: 7px 12px;
            border-radius: 9px;
            border: none;
            background: transparent;
            color: rgba(30, 30, 30, 0.85);
            font-size: 12.5px;
            font-weight: 600;
            letter-spacing: -0.2px;
            cursor: pointer;
            transition: all 0.15s ease;
            text-align: left;
            text-decoration: none;
        }

        .nav-item-link:hover,
        .nav-item-btn:hover {
            color: #000;
            background: rgba(0, 0, 0, 0.05);
        }

        .nav-item-link-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            min-height: 38px;
            padding: 7px 12px;
            border-radius: 9px;
            color: rgba(30, 30, 30, 0.85);
            font-size: 12.5px;
            font-weight: 600;
            letter-spacing: -0.2px;
            transition: all 0.15s ease;
        }

        .nav-item-link-group:hover {
            color: #000;
            background: rgba(0, 0, 0, 0.05);
        }

        .nav-item-link-group.active {
            background: var(--active-pill);
            color: var(--active-pill-text);
            font-weight: 700;
        }

        .nav-item-link-group.active .nav-icon {
            color: var(--active-pill-text);
        }

        .nav-item-main-link {
            display: flex;
            align-items: center;
            flex: 1;
            color: inherit;
            text-decoration: none;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-icon {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            color: rgba(30, 30, 30, 0.8);
        }

        .nav-right-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-badge {
            background: var(--badge-bg);
            color: #FFFFFF;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 12px;
            letter-spacing: 0.2px;
            line-height: 1.4;
        }

        .nav-chevron {
            color: rgba(60, 60, 60, 0.6);
            transition: transform 0.2s ease;
            flex-shrink: 0;
        }

        .nav-chevron-btn {
            background: transparent;
            border: none;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: inherit;
            border-radius: 4px;
            line-height: 1;
            transition: background 0.15s ease;
        }

        .nav-chevron-btn:hover {
            background: rgba(0, 0, 0, 0.08);
        }

        .dropdown-chevron {
            color: rgba(60, 60, 60, 0.7);
            transition: transform 0.2s ease;
            flex-shrink: 0;
        }

        .dropdown-chevron.is-open {
            transform: rotate(0deg);
        }

        .dropdown-chevron:not(.is-open) {
            transform: rotate(-90deg);
        }

        /* Submenu */
        .artworks-submenu {
            display: none;
            flex-direction: column;
            gap: 1px;
            padding-left: 24px;
            margin-top: 2px;
            margin-bottom: 4px;
        }

        .artworks-submenu.is-open {
            display: flex;
        }

        .submenu-link {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 30px;
            padding: 4px 10px;
            font-size: 11.5px;
            font-weight: 600;
            color: rgba(45, 45, 45, 0.85);
            border-radius: 6px;
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .submenu-link:hover {
            color: #000;
            background: rgba(0, 0, 0, 0.04);
        }

        .submenu-link.active {
            color: #102B21;
            font-weight: 700;
            background: rgba(16, 43, 33, 0.09);
        }

        .submenu-icon {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
            color: rgba(45, 45, 45, 0.85);
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 6px;
            border-top: 1px solid rgba(0, 0, 0, 0.08);
        }

        /* ================= MAIN CONTENT ================= */
        .main-content {
            flex: 1;
            min-width: 0;
            padding: 22px 42px 42px 42px;
            background: #FFFFFF;
        }

        /* Top Bar */
        .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }

        .search-pill-container {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #EDEDEB;
            border-radius: 12px;
            padding: 10px 18px;
            width: 440px;
            max-width: 100%;
        }

        .search-menu-btn {
            background: transparent;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
            color: #3F3F46;
            cursor: pointer;
        }

        .search-input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 13px;
            color: #171717;
            font-family: inherit;
        }

        .search-input::placeholder {
            color: #71717A;
            font-weight: 400;
        }

        .search-submit-btn {
            background: transparent;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
            color: #3F3F46;
            cursor: pointer;
        }

        .user-avatar-btn {
            background: transparent;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
            color: #171717;
            cursor: pointer;
        }

        /* Section Heading Row */
        .section-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 800;
            color: #171717;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .section-title .artists-sub {
            font-weight: 500;
            color: #52525B;
            font-size: 16px;
            margin-left: 4px;
            letter-spacing: 0;
        }

        .active-filter-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #D8E3D5;
            color: #1E2B20;
            font-size: 12px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 9999px;
            margin-left: 10px;
            cursor: pointer;
        }

        .active-filter-badge:hover {
            background: #C7D8C4;
        }

        /* Sort Dropdown Box */
        .sort-box {
            position: relative;
            display: inline-flex;
            align-items: center;
            border: 1px solid #D4D4D8;
            border-radius: 8px;
            padding: 6px 14px;
            background: #FFFFFF;
            gap: 10px;
        }

        .sort-label {
            font-size: 12.5px;
            font-weight: 600;
            color: #27272A;
            margin: 0;
            white-space: nowrap;
        }

        .sort-divider {
            width: 1px;
            height: 16px;
            background: #D4D4D8;
        }

        .sort-select {
            border: none;
            background: transparent;
            outline: none;
            font-size: 12.5px;
            font-weight: 600;
            color: #27272A;
            cursor: pointer;
            padding-right: 4px;
            font-family: inherit;
        }

        .sort-chevron {
            color: #52525B;
            pointer-events: none;
        }

        /* Artworks Grid */
        .artworks-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 28px 24px;
            transition: opacity 0.15s ease;
        }

        .artwork-item {
            display: flex;
            flex-direction: column;
        }

        .artwork-item.is-hidden {
            display: none !important;
        }

        .artwork-image-box {
            width: 100%;
            border-radius: 14px;
            overflow: hidden;
            aspect-ratio: 16 / 11;
            background: #EDEAE4;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .artwork-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.25s ease;
        }

        .artwork-item:hover .artwork-image {
            transform: scale(1.02);
        }

        .artwork-title-row {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 12px;
            margin-top: 12px;
        }

        .artwork-name {
            font-size: 16.5px;
            font-weight: 800;
            color: #171717;
            margin: 0;
            line-height: 1.25;
            letter-spacing: -0.3px;
        }

        .artwork-price {
            font-size: 16.5px;
            font-weight: 800;
            color: #171717;
            margin: 0;
            white-space: nowrap;
            letter-spacing: -0.2px;
        }

        .artwork-artist {
            font-size: 11px;
            color: #71717A;
            margin: 2px 0 0 0;
        }

        .artwork-rating-row {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 6px;
        }

        .artwork-star-icon {
            color: #E5A83B;
            flex-shrink: 0;
        }

        .artwork-rating-text {
            font-size: 11px;
            color: #52525B;
            font-weight: 500;
        }

        .artwork-actions-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
        }

        .btn-cart-outline {
            background: #FFFFFF;
            border: 1px solid #171717;
            border-radius: 6px;
            padding: 6px 14px;
            font-size: 10.5px;
            font-weight: 700;
            color: #171717;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .btn-cart-outline:hover {
            background: #F4F4F5;
        }

        .btn-buy-solid {
            background: var(--dark-btn-bg);
            border: 1px solid var(--dark-btn-bg);
            border-radius: 6px;
            padding: 6px 18px;
            font-size: 10.5px;
            font-weight: 700;
            color: #FFFFFF;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .btn-buy-solid:hover {
            background: var(--dark-btn-hover);
            border-color: var(--dark-btn-hover);
        }

        .btn-icon-square {
            background: transparent;
            border: none;
            padding: 4px 6px;
            color: #27272A;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: color 0.15s ease;
        }

        .btn-icon-square:hover {
            color: #000;
        }

        .no-results-msg {
            display: none;
            text-align: center;
            padding: 60px 20px;
            color: #71717A;
            font-size: 14px;
            grid-column: 1 / -1;
        }

        /* Mobile Header Toggle */
        .mobile-header {
            display: none;
            padding: 12px 18px;
            background: var(--sidebar-bg);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            align-items: center;
            justify-content: space-between;
        }

        .mobile-menu-toggle {
            background: transparent;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #171717;
            padding: 4px;
        }

        @media (max-width: 991.98px) {
            .app-layout {
                flex-direction: column;
            }

            .mobile-header {
                display: flex;
                position: sticky;
                top: 0;
                z-index: 100;
            }

            .sidebar {
                display: none;
                width: 100%;
                height: auto;
                position: relative;
                border-right: none;
                border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            }

            .sidebar.is-mobile-open {
                display: flex;
            }

            .main-content {
                padding: 20px 18px 30px;
            }

            .artworks-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .artworks-grid {
                grid-template-columns: 1fr;
            }

            .search-pill-container {
                width: 100%;
            }

            .top-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .section-header-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }
    </style>
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

    $allArtworks = [
        [
            'id' => 1,
            'title' => 'Fluid Geometric',
            'price' => 28.56,
            'author' => 'Cristine Joy Montes',
            'category' => 'Abstract',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-fluid-geometric.png'),
        ],
        [
            'id' => 2,
            'title' => 'Stranger Things Poster',
            'price' => 35.76,
            'author' => 'Gian Gomez',
            'category' => 'Poster',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-stranger-things.png'),
        ],
        [
            'id' => 3,
            'title' => 'Minimalist Organic',
            'price' => 15.67,
            'author' => 'Gian Gomez',
            'category' => 'Abstract',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-minimalist-organic.png'),
        ],
        [
            'id' => 4,
            'title' => 'Geometric Contrast',
            'price' => 20.43,
            'author' => 'Kalvin Dave Ramos',
            'category' => 'Abstract',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-geometric-contrast.png'),
        ],
        [
            'id' => 5,
            'title' => 'The One Poster',
            'price' => 25.86,
            'author' => 'Kalvin Dave Ramos',
            'category' => 'Poster',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-the-one-poster.png'),
        ],
        [
            'id' => 6,
            'title' => 'Geometric Abstract',
            'price' => 21.43,
            'author' => 'Krystel Anne Lina',
            'category' => 'Abstract',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-geometric-abstract.png'),
        ],
        [
            'id' => 7,
            'title' => 'GoldMidnight Pixel Art',
            'price' => 18.50,
            'author' => 'Kalvin Dave Ramos',
            'category' => 'Pixel',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-geometric-contrast.png'),
        ],
        [
            'id' => 8,
            'title' => 'Golden Mood Typography',
            'price' => 24.50,
            'author' => 'Gian Gomez',
            'category' => 'Typography',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-stranger-things.png'),
        ],
        [
            'id' => 9,
            'title' => 'Modern Muse Digital',
            'price' => 27.00,
            'author' => 'Cristine Joy Montes',
            'category' => 'Digital',
            'rating' => '5.0 (1k reviews)',
            'image' => asset('images/artwork-fluid-geometric.png'),
        ],
    ];

    $selectedCategory = trim((string) request('category'));
@endphp

<!-- Mobile Header Toggle -->
<div class="mobile-header">
    <div class="d-flex align-items-center gap-2">
        <svg class="brand-logo-mark" style="width:30px; height:30px;" viewBox="0 0 32 32" fill="none">
            <path d="M5.33325 27.5556L14.2221 4.44444H17.7777L11.5555 22.2222H18.6666L20.4444 27.5556H5.33325Z" fill="#1C1D1B"/>
            <path d="M19.5556 11.5556L25.7778 27.5556H21.3334L16.8889 16L19.5556 11.5556Z" fill="#1C1D1B"/>
            <circle cx="24" cy="8" r="2.22" fill="#1C1D1B"/>
        </svg>
        <span class="fw-bold" style="letter-spacing:2px; font-size:14px;">ARTFOLIO</span>
    </div>
    <button type="button" class="mobile-menu-toggle" id="mobile-toggle" aria-label="Toggle navigation menu">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>
</div>

<div class="app-layout">

    <!-- ================= SIDEBAR ================= -->
    <aside class="sidebar" id="sidebar">
        <!-- Logo -->
        <div class="sidebar-brand-container">
            <svg class="brand-logo-mark" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                <path d="M5.33325 27.5556L14.2221 4.44444H17.7777L11.5555 22.2222H18.6666L20.4444 27.5556H5.33325Z" fill="#1C1D1B"/>
                <path d="M19.5556 11.5556L25.7778 27.5556H21.3334L16.8889 16L19.5556 11.5556Z" fill="#1C1D1B"/>
                <circle cx="24" cy="8" r="2.22" fill="#1C1D1B"/>
            </svg>
            <div class="brand-logo-title">ARTFOLIO</div>
            <div class="brand-logo-sub">ART LIVES HERE</div>
        </div>

        <!-- Navigation Links -->
        <nav class="sidebar-nav">
            <!-- 1. Home Page (Inactive on this page, navigates to /home) -->
            <a href="{{ url('/home') }}" class="nav-item-link">
                <span class="nav-left">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 9.5L12 3L21 9.5V20C21 20.5523 20.5523 21 20 21H15V14H9V21H4C3.44772 21 3 20.5523 3 20V9.5Z"/>
                    </svg>
                    <span>Home Page</span>
                </span>
            </a>

            <!-- 2. Artworks (Active on this page, clicking text shows All Artworks, chevron toggles submenu) -->
            <div>
                <div class="nav-item-link-group active">
                    <a href="{{ url('/artworks') }}" class="nav-item-main-link" id="nav-artworks-all-link" title="Show all artworks">
                        <span class="nav-left">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.49 2 2 6.49 2 12C2 17.51 6.49 22 12 22C12.83 22 13.5 21.33 13.5 20.5C13.5 20.11 13.35 19.76 13.11 19.49C12.88 19.23 12.73 18.88 12.73 18.5C12.73 17.67 13.4 17 14.23 17H16C19.31 17 22 14.31 22 11C22 6.04 17.51 2 12 2ZM6.5 12C5.67 12 5 11.33 5 10.5C5 9.67 5.67 9 6.5 9C7.33 9 8 9.67 8 10.5C8 11.33 7.33 12 6.5 12ZM9.5 8C8.67 8 8 7.33 8 6.5C8 5.67 8.67 5 9.5 5C10.33 5 11 5.67 11 6.5C11 7.33 10.33 8 9.5 8ZM14.5 8C13.67 8 13 7.33 13 6.5C13 5.67 13.67 5 14.5 5C15.33 5 16 5.67 16 6.5C16 7.33 15.33 8 14.5 8ZM17.5 12C16.67 12 16 11.33 16 10.5C16 9.67 16.67 9 17.5 9C18.33 9 19 9.67 19 10.5C19 11.33 18.33 12 17.5 12Z"/>
                            </svg>
                            <span>Artworks</span>
                        </span>
                    </a>
                    <div class="nav-right-actions">
                        <span class="nav-badge">05</span>
                        <button type="button" class="nav-chevron-btn" id="artworks-toggle" aria-expanded="true" aria-controls="artworks-submenu" aria-label="Toggle artworks submenu">
                            <svg class="dropdown-chevron is-open" id="artworks-chevron" width="10" height="10" viewBox="0 0 16 16" fill="none">
                                <path d="M3 6L8 11L13 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submenu Categories -->
                <div id="artworks-submenu" class="artworks-submenu is-open">
                    @foreach ($categories as $cat)
                        @php
                            $isCatActive = strcasecmp($selectedCategory, $cat['label']) === 0;
                        @endphp
                        <a href="{{ $cat['url'] }}"
                           class="submenu-link {{ $isCatActive ? 'active' : '' }}"
                           data-category-filter="{{ $cat['label'] }}">
                            @if ($cat['type'] === 'abstract')
                                <svg class="submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14.93V17c0 .55-.45 1-1 1s-1-.45-1-1v-.07C8.06 16.48 6.5 14.44 6.5 12c0-3.03 2.47-5.5 5.5-5.5s5.5 2.47 5.5 5.5c0 2.44-1.56 4.48-3.5 4.93z" fill="currentColor"/>
                                </svg>
                            @elseif ($cat['type'] === 'typography')
                                <svg class="submenu-icon" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 3L5 20H8.5L10 16H14L15.5 20H19L12 3ZM10.8 13.5L12 9.5L13.2 13.5H10.8Z"/>
                                </svg>
                            @elseif ($cat['type'] === 'pixel')
                                <svg class="submenu-icon" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M4 4H10V10H4V4ZM14 4H20V10H14V4ZM4 14H10V20H4V14ZM14 14H20V20H14V14Z"/>
                                </svg>
                            @elseif ($cat['type'] === 'poster')
                                <svg class="submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                            @elseif ($cat['type'] === 'digital')
                                <svg class="submenu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                                    <line x1="12" y1="18" x2="12.01" y2="18"></line>
                                </svg>
                            @endif
                            <span>{{ $cat['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- 3. Artists -->
            <a href="{{ url('/artworks') }}" class="nav-item-link">
                <span class="nav-left">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                    </svg>
                    <span>Artists</span>
                </span>
                <svg class="nav-chevron" width="10" height="10" viewBox="0 0 16 16" fill="none">
                    <path d="M6 3L11 8L6 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>

            <!-- 4. Favorites -->
            <a href="#favorites" class="nav-item-link">
                <span class="nav-left">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17 3H7C5.9 3 5 3.9 5 5V21L12 18L19 21V5C19 3.9 18.1 3 17 3Z"/>
                    </svg>
                    <span>Favorites</span>
                </span>
                <svg class="nav-chevron" width="10" height="10" viewBox="0 0 16 16" fill="none">
                    <path d="M6 3L11 8L6 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>

            <!-- 5. My cart -->
            <a href="#cart" class="nav-item-link">
                <span class="nav-left">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M7 18C5.9 18 5.01 18.9 5.01 20C5.01 21.1 5.9 22 7 22C8.1 22 9 21.1 9 20C9 18.9 8.1 18 7 18ZM1 2V4H3L6.6 11.59L5.25 14.04C5.09 14.32 5 14.65 5 15C5 16.1 5.9 17 7 17H19V15H7.42C7.28 15 7.17 14.89 7.17 14.75L7.2 14.63L8.1 13H15.55C16.3 13 16.96 12.59 17.3 11.97L20.88 5.48C20.96 5.34 21 5.17 21 5C21 4.45 20.55 4 20 4H5.21L4.27 2H1ZM17 18C15.9 18 15.01 18.9 15.01 20C15.01 21.1 15.9 22 17 22C18.1 22 19 21.1 19 20C19 18.9 18.1 18 17 18Z"/>
                    </svg>
                    <span>My cart</span>
                </span>
                <svg class="nav-chevron" width="10" height="10" viewBox="0 0 16 16" fill="none">
                    <path d="M6 3L11 8L6 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>

            <!-- 6. My orders -->
            <a href="#orders" class="nav-item-link">
                <span class="nav-left">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M21 16.5C21 16.88 20.79 17.21 20.47 17.38L12.57 21.82C12.41 21.91 12.23 21.96 12.05 21.96C11.87 21.96 11.69 21.91 11.53 21.82L3.63 17.38C3.31 17.21 3.1 16.88 3.1 16.5V7.5C3.1 7.12 3.31 6.79 3.63 6.62L11.53 2.18C11.85 2 12.25 2 12.57 2.18L20.47 6.62C20.79 6.79 21 7.12 21 7.5V16.5ZM12 4.15L5.74 7.65L12 11.16L18.26 7.65L12 4.15ZM5.1 9.25V15.75L11 19.06V12.56L5.1 9.25ZM13 19.06L18.9 15.75V9.25L13 12.56V19.06Z"/>
                    </svg>
                    <span>My orders</span>
                </span>
                <svg class="nav-chevron" width="10" height="10" viewBox="0 0 16 16" fill="none">
                    <path d="M6 3L11 8L6 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>

            <!-- 7. About us -->
            <a href="#about" class="nav-item-link">
                <span class="nav-left">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 2H4C2.9 2 2 2.9 2 4V22L6 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2ZM13 14H11V10H13V14ZM13 8H11V6H13V8Z"/>
                    </svg>
                    <span>About us</span>
                </span>
                <svg class="nav-chevron" width="10" height="10" viewBox="0 0 16 16" fill="none">
                    <path d="M6 3L11 8L6 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>

            <!-- 8. Log out (Anchored at bottom) -->
            <div class="sidebar-bottom">
                <form method="POST" action="{{ url('/logout') }}">
                    @csrf
                    <button type="submit" class="nav-item-btn">
                        <span class="nav-left">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17 7L15.59 8.41L18.17 11H8V13H18.17L15.59 15.58L17 17L22 12L17 7ZM4 5H12V3H4C2.9 3 2 3.9 2 5V19C2 20.1 2.9 21 4 21H12V19H4V5Z"/>
                            </svg>
                            <span>Log out</span>
                        </span>
                        <svg class="nav-chevron" width="10" height="10" viewBox="0 0 16 16" fill="none">
                            <path d="M6 3L11 8L6 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="main-content">

        <!-- Top Bar -->
        <header class="top-bar">
            <!-- Search Pill with Hamburger/Filter + Magnifying Glass -->
            <form action="{{ url('/artworks') }}" method="GET" class="search-pill-container" role="search" id="artworks-search-form">
                <button type="button" class="search-menu-btn" aria-label="Menu filter" id="search-filter-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="4" y1="7" x2="20" y2="7"></line>
                        <line x1="4" y1="12" x2="20" y2="12"></line>
                        <line x1="4" y1="17" x2="20" y2="17"></line>
                    </svg>
                </button>
                <input
                    type="search"
                    name="search"
                    id="search-input"
                    class="search-input"
                    placeholder="Search Artworks..."
                    aria-label="Search Artworks"
                    value="{{ request('search') }}"
                >
                <button type="submit" class="search-submit-btn" aria-label="Search">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </form>

            <!-- User Avatar Circle -->
            <button type="button" class="user-avatar-btn" title="{{ Auth::user()->name ?? 'Account' }}" aria-label="User Account">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 5C13.66 5 15 6.34 15 8C15 9.66 13.66 11 12 11C10.34 11 9 9.66 9 8C9 6.34 10.34 5 12 5ZM12 19.2C9.5 19.2 7.29 17.92 6 15.98C6.03 13.99 10 12.9 12 12.9C13.99 12.9 17.97 13.99 18 15.98C16.71 17.92 14.5 19.2 12 19.2Z"/>
                </svg>
            </button>
        </header>

        <!-- Section Header Row -->
        <div class="section-header-row">
            <h1 class="section-title">
                Artworks <span class="artists-sub">from our Artists</span>
                <span id="active-category-indicator" class="active-filter-badge" style="{{ empty($selectedCategory) ? 'display: none;' : '' }}">
                    <span id="active-category-text">{{ $selectedCategory }}</span>
                    <span style="font-size: 14px; line-height: 1;" title="Clear filter">&times;</span>
                </span>
            </h1>

            <!-- Sort By Box -->
            <div class="sort-box">
                <span class="sort-label">Sort By</span>
                <div class="sort-divider"></div>
                <select id="sort-select" class="sort-select" aria-label="Sort artworks">
                    <option value="default">Default</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="name">Name (A-Z)</option>
                    <option value="filter-Abstract" {{ strcasecmp($selectedCategory, 'Abstract') === 0 ? 'selected' : '' }}>Category: Abstract</option>
                    <option value="filter-Poster" {{ strcasecmp($selectedCategory, 'Poster') === 0 ? 'selected' : '' }}>Category: Poster</option>
                    <option value="filter-Pixel" {{ strcasecmp($selectedCategory, 'Pixel') === 0 ? 'selected' : '' }}>Category: Pixel Art</option>
                    <option value="filter-Typography" {{ strcasecmp($selectedCategory, 'Typography') === 0 ? 'selected' : '' }}>Category: Typography</option>
                    <option value="filter-Digital" {{ strcasecmp($selectedCategory, 'Digital') === 0 ? 'selected' : '' }}>Category: Digital Art</option>
                </select>
                <svg class="sort-chevron" width="11" height="11" viewBox="0 0 16 16" fill="none">
                    <path d="M3 6L8 11L13 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        <!-- Artworks 3-Column Grid -->
        <div class="artworks-grid" id="artworks-grid">
            @foreach ($allArtworks as $art)
                @php
                    $isInitiallyHidden = false;
                    if (!empty($selectedCategory) && strcasecmp($art['category'], $selectedCategory) !== 0) {
                        $isInitiallyHidden = true;
                    }
                @endphp
                <article class="artwork-item {{ $isInitiallyHidden ? 'is-hidden' : '' }}"
                         data-category="{{ $art['category'] }}"
                         data-price="{{ $art['price'] }}"
                         data-name="{{ $art['title'] }}"
                         data-artist="{{ $art['author'] }}">
                    <div class="artwork-image-box">
                        <img src="{{ $art['image'] }}" alt="{{ $art['title'] }}" class="artwork-image" loading="lazy">
                    </div>

                    <div class="artwork-title-row">
                        <h2 class="artwork-name">{{ $art['title'] }}</h2>
                        <span class="artwork-price">${{ number_format($art['price'], 2) }}</span>
                    </div>

                    <p class="artwork-artist">by {{ $art['author'] }}</p>

                    <div class="artwork-rating-row">
                        <svg class="artwork-star-icon" width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 1.5l3.09 6.26L22 8.77l-5 4.87 1.18 6.86L12 17.27l-6.18 3.23L7 13.64l-5-4.87 6.91-1.01L12 1.5z"/>
                        </svg>
                        <span class="artwork-rating-text">{{ $art['rating'] }}</span>
                    </div>

                    <div class="artwork-actions-row">
                        <button type="button" class="btn-cart-outline">
                            Add to cart
                        </button>
                        <button type="button" class="btn-buy-solid">
                            Buy now
                        </button>
                        <button type="button" class="btn-icon-square ms-auto" aria-label="Share artwork">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="18" cy="5" r="3"></circle>
                                <circle cx="6" cy="12" r="3"></circle>
                                <circle cx="18" cy="19" r="3"></circle>
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                            </svg>
                        </button>
                        <button type="button" class="btn-icon-square" aria-label="Save to favorites">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                            </svg>
                        </button>
                    </div>
                </article>
            @endforeach

            <!-- Empty State message -->
            <div id="no-results" class="no-results-msg">
                No artworks found matching your selection.
            </div>
        </div>

    </main>
</div>

<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Artworks chevron dropdown toggle in sidebar
        const artworksToggle = document.getElementById('artworks-toggle');
        const artworksSubmenu = document.getElementById('artworks-submenu');
        const artworksChevron = document.getElementById('artworks-chevron');

        if (artworksToggle && artworksSubmenu) {
            artworksToggle.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const isOpen = artworksSubmenu.classList.toggle('is-open');
                if (artworksChevron) {
                    artworksChevron.classList.toggle('is-open', isOpen);
                }
                artworksToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        }

        // Mobile sidebar toggle
        const mobileToggle = document.getElementById('mobile-toggle');
        const sidebar = document.getElementById('sidebar');

        if (mobileToggle && sidebar) {
            mobileToggle.addEventListener('click', function () {
                sidebar.classList.toggle('is-mobile-open');
            });
        }

        // Category & Sort Management
        const grid = document.getElementById('artworks-grid');
        const items = Array.from(grid.querySelectorAll('.artwork-item'));
        const sortSelect = document.getElementById('sort-select');
        const searchInput = document.getElementById('search-input');
        const activeCategoryBadge = document.getElementById('active-category-indicator');
        const activeCategoryText = document.getElementById('active-category-text');
        const submenuLinks = Array.from(document.querySelectorAll('[data-category-filter]'));
        const allArtworksNav = document.getElementById('nav-artworks-all-link');
        const noResults = document.getElementById('no-results');

        let currentCategory = new URLSearchParams(window.location.search).get('category') || '';
        let currentSearch = '';

        function applyFilterAndSort() {
            let visibleCount = 0;

            items.forEach(item => {
                const itemCat = item.dataset.category || '';
                const itemName = (item.dataset.name || '').toLowerCase();
                const itemArtist = (item.dataset.artist || '').toLowerCase();

                let matchesCategory = true;
                if (currentCategory && currentCategory !== 'all') {
                    matchesCategory = itemCat.toLowerCase() === currentCategory.toLowerCase();
                }

                let matchesSearch = true;
                if (currentSearch) {
                    matchesSearch = itemName.includes(currentSearch) || itemArtist.includes(currentSearch) || itemCat.toLowerCase().includes(currentSearch);
                }

                if (matchesCategory && matchesSearch) {
                    item.classList.remove('is-hidden');
                    visibleCount++;
                } else {
                    item.classList.add('is-hidden');
                }
            });

            if (noResults) {
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            }

            // Update active state on sidebar links
            submenuLinks.forEach(link => {
                const linkCat = link.dataset.categoryFilter || '';
                if (currentCategory && linkCat.toLowerCase() === currentCategory.toLowerCase()) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });

            // Update badge indicator
            if (activeCategoryBadge && activeCategoryText) {
                if (currentCategory && currentCategory !== 'all') {
                    activeCategoryText.textContent = currentCategory;
                    activeCategoryBadge.style.display = 'inline-flex';
                } else {
                    activeCategoryBadge.style.display = 'none';
                }
            }

            // Sync sort dropdown if category selected
            if (sortSelect) {
                if (currentCategory && sortSelect.querySelector(`option[value="filter-${currentCategory}"]`)) {
                    sortSelect.value = `filter-${currentCategory}`;
                } else if (!currentCategory && sortSelect.value.startsWith('filter-')) {
                    sortSelect.value = 'default';
                }
            }
        }

        function sortItems(sortBy) {
            if (sortBy === 'price-low') {
                items.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
            } else if (sortBy === 'price-high') {
                items.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
            } else if (sortBy === 'name') {
                items.sort((a, b) => a.dataset.name.localeCompare(b.dataset.name));
            }

            items.forEach(item => grid.appendChild(item));
        }

        // Click on category in sidebar dropdown
        submenuLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const cat = this.dataset.categoryFilter;
                currentCategory = cat;

                // Update URL history without page reload
                const url = new URL(window.location);
                url.searchParams.set('category', cat);
                window.history.pushState({}, '', url);

                applyFilterAndSort();
            });
        });

        // Click on "Artworks" in sidebar -> Show all artworks
        if (allArtworksNav) {
            allArtworksNav.addEventListener('click', function (e) {
                e.preventDefault();
                currentCategory = '';
                const url = new URL(window.location);
                url.searchParams.delete('category');
                window.history.pushState({}, '', url);
                applyFilterAndSort();
            });
        }

        // Click badge to clear filter
        if (activeCategoryBadge) {
            activeCategoryBadge.addEventListener('click', function () {
                currentCategory = '';
                const url = new URL(window.location);
                url.searchParams.delete('category');
                window.history.pushState({}, '', url);
                applyFilterAndSort();
            });
        }

        // Sort select change
        if (sortSelect) {
            sortSelect.addEventListener('change', function () {
                const val = this.value;
                if (val.startsWith('filter-')) {
                    const cat = val.replace('filter-', '');
                    currentCategory = cat;
                    const url = new URL(window.location);
                    url.searchParams.set('category', cat);
                    window.history.pushState({}, '', url);
                    applyFilterAndSort();
                } else {
                    sortItems(val);
                }
            });
        }

        // Live search filter
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                currentSearch = this.value.trim().toLowerCase();
                applyFilterAndSort();
            });
        }

        // Initial setup
        applyFilterAndSort();
    });
</script>

</body>
</html>
