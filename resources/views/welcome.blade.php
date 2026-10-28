<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - Home</title>
    <meta name="description" content="Discover original art and creative essentials">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        
        :root {
            --artfolio-cream: #F7F5EF;
                --artfolio-charcoal: #303C32;
                    --artfolio-sage: #758C71;
                        --artfolio-text: #303C32;
                        }

                        body {
                            margin: 0;
                                background: var(--artfolio-cream);
                                    color: var(--artfolio-text);
                                        font-family: Arial, Helvetica, sans-serif;
                                            overflow-x: hidden;
                                            }

        .app-layout {
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .sidebar {
            width: 286px;
            flex: 0 0 286px;
            background: #D9D9D9;
            border: 1px solid #000;
            min-height: 100vh;
        }

        .sidebar-logo {
            display: block;
            width: 200px;
            height: 200px;
            object-fit: contain;
            margin: 7px auto 8px;
        }

        .sidebar-nav {
            padding: 0 25px 0 39px;
        }

        .sidebar-nav a {
            position: relative;
            display: flex;
            align-items: center;
            gap: 20px;
            min-height: 48px;
            padding: 8px 28px 8px 16px;
            color: rgba(64, 64, 64, .8);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: -0.5px;
            border-radius: 6px;
        }

        .sidebar-nav a:hover {
            color: #000;
            background: rgba(0, 0, 0, .08);
        }

        .sidebar-nav a.active {
            min-height: 42px;
            background: rgba(0, 0, 0, .25);
            font-weight: 700;
        }

        .home-nav-item {
            position: relative;
        }

        .home-nav-link {
            display: flex;
            align-items: center;
            width: 100%;
            padding-right: 36px;
        }

        .home-nav-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 26px;
            height: 26px;
            border: 0;
            background: transparent;
            color: rgba(64, 64, 64, .8);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .home-nav-toggle svg {
            transition: transform .2s ease;
        }

        .home-nav-toggle.is-open svg {
            transform: rotate(180deg);
        }

        .home-submenu {
            display: none;
            position: relative;
            margin: 4px 0 0 20px;
            padding-left: 12px;
            border-left: 1px solid rgba(0, 0, 0, .8);
        }

        .home-submenu.is-open {
            display: block;
        }

        .home-submenu a {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 40px;
            padding-left: 12px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: -0.5px;
            color: rgba(64, 64, 64, .8);
            text-decoration: none;
        }

        .home-submenu a::before {
            content: "";
            position: absolute;
            left: -12px;
            top: 50%;
            width: 12px;
            height: 1px;
            background: rgba(0, 0, 0, .8);
        }

        .home-submenu .submenu-icon {
            width: 22px;
            height: 22px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .home-submenu a:hover {
            color: #000;
        }

        .sidebar-nav .nav-icon {
            width: 24px;
            height: 24px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .sidebar-nav .chevron {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 10px;
            height: 5px;
            opacity: .8;
        }

        .nav-badge {
            margin-left: auto;
            margin-right: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 23px;
            background: #FF4343;
            color: #fff;
            border-radius: 4px;
            font-size: 11px;
        }

        .main-content {
            flex: 1;
            min-width: 0;
        }

        .hero {
            position: relative;
            display: flex;
            align-items: center;
            min-height: 345px;
            overflow: visible;
            background: #171717;
        }

        .hero-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: right center;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            padding: 48px 64px;
            color: #f5f5f4;
        }

        .hero-line {
            width: 40px;
            height: 2px;
            background: #78716c;
            margin-bottom: 24px;
        }

        .hero h1 {
            max-width: 375px;
            margin: 0;
            font-size: 45px;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -1.35px;
        }

        .hero p {
            max-width: 350px;
            margin-top: 20px;
            color: rgba(245, 245, 244, .65);
            font-size: 14px;
            line-height: 24px;
        }

        .hero-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 18px;
            padding: 14px 24px;
            border-radius: 2px;
            background: #FFF9EE;
            color: #171717;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
        }

        .hero-button:hover {
            background: #e9e1d4;
            color: #171717;
        }

        .featured-section {
            padding: 60px 64px;
            overflow: visible;
        }

        .featured-heading {
            margin-bottom: 16px;
            font-size: 20px;
            letter-spacing: -1px;
        }

        .featured-heading strong {
            font-size: 30px;
            font-weight: 700;
        }

        .art-strip {
            display: block;
            width: 100%;
            max-width: 100%;
            height: auto;
            object-fit: contain;
            margin-top: 16px;
        }

        .artwork-card {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .artwork-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 4px 12px;
        }

        .artwork-title {
            margin: 0;
            font-size: 23px;
            font-weight: 700;
            line-height: 1.5;
            letter-spacing: -1px;
            color: rgba(23, 23, 23, .9);
        }

        .artwork-author {
            font-size: 9px;
            color: rgba(0, 0, 0, .8);
        }

        .rating-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .rating-star {
            width: 17px;
            height: 16px;
            object-fit: contain;
        }

        .rating-text {
            font-size: 11px;
            color: rgba(23, 23, 23, .6);
        }

        .artwork-description {
            max-width: 264px;
            margin-top: 16px;
            margin-bottom: 0;
            font-size: 8px;
            line-height: 10px;
            color: rgba(0, 0, 0, .8);
        }

        .artwork-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
        }

        .details-button {
            width: 94px;
            height: 27px;
            padding: 0;
            border: 0;
            border-radius: 6px;
            background: #112B21;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
        }

        .details-button:hover {
            background: #234638;
        }

        .action-icon {
            width: 24px;
            height: 20px;
            object-fit: contain;
            opacity: .8;
        }

        .share-icon {
            width: 29px;
            margin-left: 4px;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                display: none;
            }

            .hero-content {
                padding: 40px 32px;
            }

            .featured-section {
                padding: 45px 32px;
            }
        }

        @media (max-width: 575.98px) {
            .hero {
                min-height: 360px;
            }

            .hero-content {
                padding: 36px 24px;
            }

            .hero h1 {
                font-size: 38px;
            }

            .featured-section {
                padding: 36px 24px;
            }

            .featured-heading strong {
                font-size: 25px;
            }

            .artwork-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

@php
    $imageBase = 'https://api.builder.io/api/v1/image/assets/TEMP/';

    $homeCategories = [
        ['label' => 'Abstract', 'icon' => '337de285e65c20611d5b816609b67137421bf2a4?width=44', 'url' => '#abstract'],
        ['label' => 'Typography', 'icon' => 'da301936dba9c14c1d2cea674eb1d10117e30de6?width=38', 'url' => '#typography'],
        ['label' => 'Pixel', 'icon' => '2e7a0e8a50132f5a41d0e12f147e6432390431cc?width=32', 'url' => '#pixel'],
        ['label' => 'Poster', 'icon' => '59b1c2c74d62b4265953eac40305866b5500789e?width=44', 'url' => '#poster'],
        ['label' => 'Digital', 'icon' => '93a574e3da9921d9b3ae5bcac5cc9528095aeb59?width=46', 'url' => '#digital'],
    ];

    $navItems = [
        [
            'label' => 'Artworks',
            'icon' => 'f5ab3587d7b97919bb1c6372365dc255cc649d38?width=42',
            'badge' => '05',
            'url' => url('/artworks')
        ],
        [
            'label' => 'Artists',
            'icon' => '64fe3c927c7abb9add87eab59d4756b86c84d285?width=44',
            'url' => '#artists'
        ],
        [
            'label' => 'Favorites',
            'icon' => '8936e6ecf576ca970955067bdaa36ef2e1d09b15?width=50',
            'url' => '#favorites'
        ],
        [
            'label' => 'My cart',
            'icon' => '2ac823dead631470ef353137c7c4db0dddc12154?width=50',
            'url' => '#cart'
        ],
        [
            'label' => 'My orders',
            'icon' => '2deb49be6e27adf18b4dd278c5beb482ea6e0e3c?width=52',
            'url' => '#orders'
        ],
        [
            'label' => 'About us',
            'icon' => 'db3c5fc068d0dd1da61213d950ec3473270da513?width=52',
            'url' => '#about'
        ],
    ];

    $artworks = [
        [
            'title' => 'Fluid Geometric',
            'author' => 'Cristine Joy Montes',
            'desc' => 'Fluid-Geometric Abstract Art combines flowing shapes and sharp forms to express movement and creativity.',
        ],
        [
            'title' => 'Stranger Things Poster',
            'author' => 'Gian Gomez',
            'desc' => 'The poster uses dark red tones, glowing text, and a mysterious creature to create a suspenseful, supernatural atmosphere.',
        ],
        [
            'title' => 'Minimalist Organic',
            'author' => 'Gian Gomez',
            'desc' => 'This minimalist abstract art uses curved lines, organic shapes, and neutral colors to create a sense of balance, simplicity, and movement.',
        ],
    ];

    $strips = [
        $imageBase . 'bbde1b8c7dd41d8852294760d031d16ab9021058?width=2470',
        $imageBase . 'cbeb94899e8967001df417f32f10e20c3290e547?width=2470',
    ];

    $stars = [
        $imageBase . '67a1f5c07f1a2761f64da2e66f1cdbea5c3d1dda?width=34',
        $imageBase . 'a0aec7389b59c267fe9e6cb147a75e605ac97963?width=34',
    ];
@endphp

<div class="app-layout">

    <aside class="sidebar">
        <div class="sticky-top" style="height: 100vh; overflow-y: auto;">
            <img
                src="{{ $imageBase }}a8b81c0bdfc0f8221be62a3791175c249ee41e9d?width=400"
                alt="ARTFOLIO Logo"
                class="sidebar-logo"
            >

            <nav class="sidebar-nav d-flex flex-column gap-1">
                <div class="home-nav-item">
                    <a href="{{ url('/home') }}" class="active home-nav-link">
                        <img
                            src="{{ $imageBase }}843fcd8234795c83a88059d8be4696c561e264cd?width=48"
                            alt=""
                            class="nav-icon"
                        >
                        <span>Home Page</span>
                    </a>
                    <button type="button" class="home-nav-toggle is-open" aria-expanded="true" aria-controls="home-submenu" data-home-toggle>
                        <svg class="chevron" viewBox="0 0 10 3" fill="none">
                            <path d="M0 0L5 3L10 0" stroke="#404040" stroke-width="1.5"/>
                        </svg>
                    </button>
                </div>

                <div id="home-submenu" class="home-submenu is-open">
                    @foreach ($homeCategories as $category)
                        <a href="{{ $category['url'] }}">
                            <img src="{{ $imageBase . $category['icon'] }}" alt="" class="submenu-icon">
                            <span>{{ $category['label'] }}</span>
                        </a>
                    @endforeach
                </div>

                @foreach ($navItems as $item)
                    <a href="{{ $item['url'] }}">
                        <img
                            src="{{ $imageBase . $item['icon'] }}"
                            alt=""
                            class="nav-icon"
                        >
                        <span>{{ $item['label'] }}</span>

                        @if (isset($item['badge']))
                            <span class="nav-badge">{{ $item['badge'] }}</span>
                        @endif

                        <svg class="chevron" viewBox="0 0 10 3" fill="none">
                            <path d="M0 0L5 3L10 0" stroke="#404040" stroke-width="1.5"/>
                        </svg>
                    </a>
                @endforeach

                <form method="POST" action="{{ url('/logout') }}" style="margin-top: 4px;">
                    @csrf
                    <button type="submit" style="display: flex; align-items: center; gap: 20px; width: 100%; min-height: 48px; padding: 8px 28px 8px 16px; border: 0; background: transparent; color: rgba(64, 64, 64, .8); font-size: 13px; font-weight: 600; letter-spacing: -0.5px; text-align: left; cursor: pointer;">
                        <img
                            src="{{ $imageBase }}ddac490a2c98d12c0c73de5c664014b60d6a0167?width=82"
                            alt=""
                            class="nav-icon"
                        >
                        <span>Log out</span>
                        <svg class="chevron" viewBox="0 0 10 3" fill="none">
                            <path d="M0 0L5 3L10 0" stroke="#404040" stroke-width="1.5"/>
                        </svg>
                    </button>
                </form>
            </nav>
        </div>
    </aside>

    <main class="main-content">

        <section class="hero">
            <img
                src="{{ $imageBase }}66782d815052068e1b1dfe82e874ea27b8e9eb69?width=2382"
                alt=""
                class="hero-image"
            >

            <div class="hero-content">
                <div class="hero-line"></div>

                <h1>Find your next masterpiece.</h1>

                <p>
                    Discover original art, creative essentials, and pieces made to inspire.
                </p>

                <a href="#featured" class="hero-button">
                    Explore Collection
                    <svg width="8" height="3" viewBox="0 0 8 3" fill="none">
                        <path
                            d="M5.79492 1.1543C5.57227 0.892578 5.33984 0.507812 5.09766 0H5.51953C6.01562 0.582031 6.54297 1.01562 7.10156 1.30078V1.51172C6.54297 1.79688 6.01562 2.23047 5.51953 2.8125H5.09766C5.33984 2.30469 5.57227 1.91992 5.79492 1.6582H0V1.1543H5.79492V1.1543"
                            fill="#171717"
                        />
                    </svg>
                </a>
            </div>
        </section>

        <section id="featured" class="featured-section">
            <h2 class="featured-heading">
                <strong>Featured Artworks</strong> from our Artists
            </h2>

            @foreach ($strips as $row => $strip)
                <div class="mt-3">
                    <img
                        src="{{ $strip }}"
                        alt="Featured artwork frame {{ $row + 1 }}"
                        class="art-strip"
                    >

                    <div class="row g-4 mt-1">
                        @foreach ($artworks as $artwork)
                            <div class="col-12 col-md-6 col-lg-4">
                                <article class="artwork-card">
                                    <div class="artwork-title-row">
                                        <h3 class="artwork-title">
                                            {{ $artwork['title'] }}
                                        </h3>

                                        <span class="artwork-author">
                                            by {{ $artwork['author'] }}
                                        </span>
                                    </div>

                                    <div class="rating-row">
                                        <img
                                            src="{{ $stars[$row] }}"
                                            alt=""
                                            class="rating-star"
                                        >
                                        <span class="rating-text">5.0 (1k reviews)</span>
                                    </div>

                                    <p class="artwork-description">
                                        {{ $artwork['desc'] }}
                                    </p>

                                    <div class="artwork-actions">
                                        <button type="button" class="details-button">
                                            View Details
                                        </button>

                                        <img
                                            src="{{ $imageBase }}3dc2b6b48bfebb92d84790a2b6515d37ac3337f9?width=58"
                                            alt="Share"
                                            class="action-icon share-icon"
                                        >

                                        <img
                                            src="{{ $imageBase }}e1b8a910d94b3199e310804ba60889a88397b9af?width=38"
                                            alt="Bookmark"
                                            class="action-icon"
                                        >
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </section>

    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.querySelector('[data-home-toggle]');
        const submenu = document.getElementById('home-submenu');

        if (!toggle || !submenu) return;

        toggle.addEventListener('click', function () {
            const isOpen = submenu.classList.toggle('is-open');
            toggle.classList.toggle('is-open', isOpen);
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    });
</script>
</body>
</html>