<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - Home</title>
    <meta name="description" content="Discover original art and the artists behind it">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #ECE9E0;
            --main-bg: #F5F6F3;
            --active-pill: #D8E3D5;
            --active-pill-text: #1E2B20;
            --badge-bg: #C09875;
            --details-btn-bg: #D8E6DD;
            --details-btn-text: #1E3326;
            --details-btn-hover: #CADED1;
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

        .nav-item-link.active {
            background: var(--active-pill);
            color: var(--active-pill-text);
            font-weight: 700;
        }

        .nav-item-link.active .nav-icon {
            color: var(--active-pill-text);
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

        .nav-item-main-link {
            display: flex;
            align-items: center;
            flex: 1;
            color: inherit;
            text-decoration: none;
        }

        .nav-right-actions {
            display: flex;
            align-items: center;
            gap: 6px;
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
        }

        .submenu-link:hover {
            color: #000;
            background: rgba(0, 0, 0, 0.04);
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
            padding: 20px 36px 36px 36px;
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
            gap: 10px;
            background: #EDEDEB;
            border-radius: 9999px;
            padding: 8px 18px;
            width: 440px;
            max-width: 100%;
        }

        .search-icon {
            width: 15px;
            height: 15px;
            color: #71717A;
            flex-shrink: 0;
        }

        .search-input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 12.5px;
            color: #171717;
            font-family: inherit;
        }

        .search-input::placeholder {
            color: #8E8E93;
            font-weight: 400;
        }

        .top-right-actions {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .notif-btn {
            background: transparent;
            border: none;
            padding: 4px;
            color: #27272A;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: color 0.15s ease;
        }

        .notif-btn:hover {
            color: #000;
        }

        .user-avatar-pill {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #DCE5DB;
            color: #203527;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            letter-spacing: -0.3px;
        }

        /* Hero Banner */
        .hero-banner {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            min-height: 255px;
            display: flex;
            align-items: center;
            background: #152219;
            margin-bottom: 28px;
        }

        .hero-banner-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: right center;
            opacity: 0.9;
        }

        .hero-banner-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(12, 28, 20, 0.96) 0%, rgba(12, 28, 20, 0.85) 36%, rgba(12, 28, 20, 0.42) 65%, rgba(12, 28, 20, 0.12) 100%);
            z-index: 1;
        }

        .hero-banner-content {
            position: relative;
            z-index: 2;
            padding: 34px 44px;
            color: #FFFFFF;
        }

        .hero-badge-tag {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: #CCD7CE;
            margin-bottom: 12px;
            display: inline-block;
        }

        .hero-heading {
            font-size: 38px;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.9px;
            color: #FFFFFF;
            margin: 0 0 12px 0;
        }

        .hero-desc {
            font-size: 13px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.82);
            margin: 0 0 20px 0;
        }

        .hero-explore-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #FFFFFF;
            color: #17241A;
            padding: 10px 22px;
            border-radius: 9999px;
            font-size: 12.5px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.2s ease;
        }

        .hero-explore-btn:hover {
            background: #F3F6F3;
            color: #0E1A11;
            transform: translateX(2px);
        }

        /* Featured Artworks Section */
        .featured-header-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .featured-title {
            font-size: 22px;
            font-weight: 700;
            color: #171717;
            margin: 0;
            letter-spacing: -0.4px;
        }

        .featured-subtitle {
            font-size: 12px;
            color: #71717A;
            margin: 3px 0 0 0;
        }

        .view-all-pill-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #FFFFFF;
            border: 1px solid rgba(0, 0, 0, 0.07);
            border-radius: 9999px;
            padding: 8px 18px;
            font-size: 12px;
            font-weight: 600;
            color: #1E2D22;
            text-decoration: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            transition: all 0.15s ease;
        }

        .view-all-pill-btn:hover {
            background: #FAFAFA;
            color: #0E1C12;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        }

        /* Artworks Grid */
        .artworks-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .art-card {
            background: #FFFFFF;
            border-radius: 18px;
            padding: 10px 10px 14px 10px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .art-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
        }

        .art-img-container {
            width: 100%;
            border-radius: 14px;
            overflow: hidden;
            aspect-ratio: 16 / 10.3;
            background: #EDEAE4;
        }

        .art-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .art-content {
            padding: 14px 8px 2px 8px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .art-tag-label {
            font-size: 9.5px;
            font-weight: 700;
            color: #2F6A4F;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .art-title-text {
            font-size: 15.5px;
            font-weight: 700;
            color: #171717;
            margin: 0 0 3px 0;
            line-height: 1.25;
            letter-spacing: -0.3px;
        }

        .art-author-text {
            font-size: 11.5px;
            color: #71717A;
            margin-bottom: 18px;
        }

        .art-footer-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            padding-top: 4px;
        }

        .art-rating-box {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 700;
            color: #27272A;
        }

        .art-details-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: var(--details-btn-bg);
            color: var(--details-btn-text);
            padding: 7px 16px;
            border-radius: 8px;
            font-size: 11.5px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .art-details-btn:hover {
            background: var(--details-btn-hover);
            color: #14241B;
        }

        /* Footer Tagline */
        .bottom-tagline {
            margin-top: 36px;
            padding-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 10px;
            flex-wrap: wrap;
        }

        .tagline-primary {
            font-weight: 700;
            color: #525252;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .tagline-secondary {
            color: #71717A;
            font-weight: 400;
        }

        /* Mobile / Responsive */
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

            .hero-heading {
                font-size: 30px;
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

            .hero-banner-content {
                padding: 24px 20px;
            }

            .hero-heading {
                font-size: 26px;
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

    $featuredArtworks = [
        [
            'title' => 'Fluid Geometric',
            'author' => 'Cristine Joy Montes',
            'category' => 'ABSTRACT',
            'rating' => '5.0',
            'image' => asset('images/artwork-fluid-geometric.png'),
            'url' => url('/abstract'),
        ],
        [
            'title' => 'Stranger Things Poster',
            'author' => 'Gian Gomez',
            'category' => 'POSTER',
            'rating' => '5.0',
            'image' => asset('images/artwork-stranger-things.png'),
            'url' => url('/artworks?category=Poster'),
        ],
        [
            'title' => 'Minimalist Organic',
            'author' => 'Gian Gomez',
            'category' => 'ABSTRACT',
            'rating' => '5.0',
            'image' => asset('images/artwork-minimalist-organic.png'),
            'url' => url('/abstract'),
        ],
    ];

    $userInitials = 'CJ';
    if (Auth::check() && !empty(Auth::user()->name)) {
        $nameParts = explode(' ', trim(Auth::user()->name));
        if (count($nameParts) >= 2) {
            $userInitials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
        } else {
            $userInitials = strtoupper(substr($nameParts[0], 0, 2));
        }
    }
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
            <!-- 1. Home Page (Active, no dropdown / arrow) -->
            <a href="{{ url('/home') }}" class="nav-item-link active">
                <span class="nav-left">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 9.5L12 3L21 9.5V20C21 20.5523 20.5523 21 20 21H15V14H9V21H4C3.44772 21 3 20.5523 3 20V9.5Z"/>
                    </svg>
                    <span>Home Page</span>
                </span>
            </a>

            <!-- 2. Artworks (Navigates to /artworks, with chevron toggle for submenu) -->
            <div>
                <div class="nav-item-link-group">
                    <a href="{{ url('/artworks') }}" class="nav-item-main-link">
                        <span class="nav-left">
                            <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.49 2 2 6.49 2 12C2 17.51 6.49 22 12 22C12.83 22 13.5 21.33 13.5 20.5C13.5 20.11 13.35 19.76 13.11 19.49C12.88 19.23 12.73 18.88 12.73 18.5C12.73 17.67 13.4 17 14.23 17H16C19.31 17 22 14.31 22 11C22 6.04 17.51 2 12 2ZM6.5 12C5.67 12 5 11.33 5 10.5C5 9.67 5.67 9 6.5 9C7.33 9 8 9.67 8 10.5C8 11.33 7.33 12 6.5 12ZM9.5 8C8.67 8 8 7.33 8 6.5C8 5.67 8.67 5 9.5 5C10.33 5 11 5.67 11 6.5C11 7.33 10.33 8 9.5 8ZM14.5 8C13.67 8 13 7.33 13 6.5C13 5.67 13.67 5 14.5 5C15.33 5 16 5.67 16 6.5C16 7.33 15.33 8 14.5 8ZM17.5 12C16.67 12 16 11.33 16 10.5C16 9.67 16.67 9 17.5 9C18.33 9 19 9.67 19 10.5C19 11.33 18.33 12 17.5 12Z"/>
                            </svg>
                            <span>Artworks</span>
                        </span>
                    </a>
                    <div class="nav-right-actions">
                        <span class="nav-badge">05</span>
                        <button type="button" class="nav-chevron-btn" id="artworks-toggle" aria-expanded="true" aria-controls="artworks-submenu" aria-label="Toggle artworks categories">
                            <svg class="dropdown-chevron is-open" id="artworks-chevron" width="10" height="10" viewBox="0 0 16 16" fill="none">
                                <path d="M3 6L8 11L13 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submenu Categories -->
                <div id="artworks-submenu" class="artworks-submenu is-open">
                    @foreach ($categories as $cat)
                        <a href="{{ $cat['url'] }}" class="submenu-link">
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
            <!-- Search Pill -->
            <form action="{{ url('/artworks') }}" method="GET" class="search-pill-container" role="search">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input
                    type="search"
                    name="search"
                    class="search-input"
                    placeholder="Search artworks, artists, and inspiration"
                    aria-label="Search artworks, artists, and inspiration"
                    value="{{ request('search') }}"
                >
            </form>

            <!-- Actions (Notification Bell & Profile Avatar) -->
            <div class="top-right-actions">
                <button type="button" class="notif-btn" aria-label="Notifications">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </button>

                <div class="user-avatar-pill" title="{{ Auth::user()->name ?? 'User' }}">
                    {{ $userInitials }}
                </div>
            </div>
        </header>

        <!-- Hero Card -->
        <section class="hero-banner">
            <!-- Painter studio image background -->
            <img
                src="https://api.builder.io/api/v1/image/assets/TEMP/66782d815052068e1b1dfe82e874ea27b8e9eb69?width=2382"
                alt=""
                class="hero-banner-img"
                onerror="this.src='{{ asset('images/hero-banner.png') }}';"
            >
            <div class="hero-banner-overlay"></div>

            <div class="hero-banner-content">
                <span class="hero-badge-tag">A SPACE FOR ORIGINAL IDEAS</span>
                <h1 class="hero-heading">Find your next<br>masterpiece.</h1>
                <p class="hero-desc">Discover original art and the artists behind it.</p>
                <a href="{{ url('/artworks') }}" class="hero-explore-btn">
                    <span>Explore collection</span>
                    <svg width="13" height="13" viewBox="0 0 16 16" fill="none">
                        <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </section>

        <!-- Featured Artworks Section -->
        <section aria-labelledby="featured-artworks-title">
            <div class="featured-header-row">
                <div>
                    <h2 id="featured-artworks-title" class="featured-title">Featured artworks</h2>
                    <p class="featured-subtitle">Handpicked pieces. Independent voices.</p>
                </div>
                <a href="{{ url('/artworks') }}" class="view-all-pill-btn">
                    <span>View all artworks</span>
                    <svg width="11" height="11" viewBox="0 0 16 16" fill="none">
                        <path d="M4 12L12 4M12 4H6M12 4V10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            <!-- Artworks 3-Column Grid -->
            <div class="artworks-grid">
                @foreach ($featuredArtworks as $art)
                    <article class="art-card">
                        <div class="art-img-container">
                            <img src="{{ $art['image'] }}" alt="{{ $art['title'] }}" class="art-img" loading="lazy">
                        </div>
                        <div class="art-content">
                            <span class="art-tag-label">{{ $art['category'] }}</span>
                            <h3 class="art-title-text">{{ $art['title'] }}</h3>
                            <span class="art-author-text">by {{ $art['author'] }}</span>

                            <div class="art-footer-actions">
                                <div class="art-rating-box">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="#E5A83B">
                                        <path d="M12 1.5l3.09 6.26L22 8.77l-5 4.87 1.18 6.86L12 17.27l-6.18 3.23L7 13.64l-5-4.87 6.91-1.01L12 1.5z"/>
                                    </svg>
                                    <span>{{ $art['rating'] }}</span>
                                </div>
                                <a href="{{ $art['url'] }}" class="art-details-btn">
                                    <span>View details</span>
                                    <svg width="9" height="9" viewBox="0 0 16 16" fill="none">
                                        <path d="M4 12L12 4M12 4H6M12 4V10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Footer Tagline -->
            <div class="bottom-tagline">
                <span class="tagline-primary">ORIGINAL ART. ENDLESS POSSIBILITIES.</span>
                <span class="tagline-secondary">Discover something that feels like you.</span>
            </div>
        </section>

    </main>
</div>

<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Artworks dropdown toggle
        const artworksToggle = document.getElementById('artworks-toggle');
        const artworksSubmenu = document.getElementById('artworks-submenu');
        const artworksChevron = document.getElementById('artworks-chevron');

        if (artworksToggle && artworksSubmenu) {
            artworksToggle.addEventListener('click', function () {
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
    });
</script>

</body>
</html>
