<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artworks | ART FOLIO</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff;
            color: #222;
        }

        .navbar {
            padding: 18px 0;
            border-bottom: 1px solid #eee;
        }

        .brand {
            font-size: 25px;
            font-weight: 800;
            color: #1d3928;
            text-decoration: none;
            letter-spacing: 1px;
        }

        .nav-link {
            color: #222;
            font-weight: 500;
        }

        .nav-link:hover {
            color: #56805e;
        }

        .search-box {
            background: #f5f5f5;
            border-radius: 25px;
            padding: 9px 15px;
            border: none;
        }

        .hero {
            background: #dcebdc;
            padding: 55px 20px;
            text-align: center;
        }

        .hero h1 {
            font-size: clamp(32px, 5vw, 48px);
            font-weight: 700;
            color: #203d2a;
        }

        .hero p {
            color: #536456;
            margin-top: 12px;
        }

        .page-content {
            padding-top: 45px;
            padding-bottom: 60px;
        }

        .sidebar h5 {
            font-weight: 700;
            margin-bottom: 18px;
        }

        .category-link {
            display: block;
            color: #555;
            text-decoration: none;
            padding: 9px 0;
        }

        .category-link:hover,
        .category-link.active {
            color: #285b38;
            font-weight: 700;
        }

        .section-heading {
            font-weight: 700;
        }

        .art-card {
            border: none;
            height: 100%;
            transition: transform .2s;
        }

        .art-card:hover {
            transform: translateY(-5px);
        }

        .art-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            background: #f1f1f1;
        }

        .art-title {
            font-size: 18px;
            font-weight: 700;
            margin-top: 14px;
            margin-bottom: 5px;
        }

        .art-category {
            color: #777;
            font-size: 14px;
        }

        .art-price {
            font-weight: 700;
            color: #284e34;
        }

        .btn-art {
            background: #284e34;
            color: white;
            border-radius: 5px;
            padding: 9px 14px;
        }

        .btn-art:hover {
            background: #1b3825;
            color: white;
        }

        footer {
            background: #203d2a;
            color: white;
            padding: 28px 0;
            text-align: center;
        }

        @media (max-width: 768px) {
            .art-image {
                height: 220px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVIGATION BAR -->
    <nav class="navbar navbar-expand-lg bg-white">
        <div class="container">
            <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none flex-shrink-0">
    <svg width="34" height="34" viewBox="0 0 32 32" fill="none"
         xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M5.33 27.55L14.22 4.44H17.78L11.56 22.22H18.67L20.44 27.55H5.33Z"
              fill="#1C1D1B"/>
        <path d="M19.56 11.56L25.78 27.55H21.33L16.89 16L19.56 11.56Z"
              fill="#1C1D1B"/>
        <circle cx="24" cy="8" r="2.22" fill="#1C1D1B"/>
    </svg>

    <div>
        <div style="color:#171717; font-size:17px; font-weight:700; letter-spacing:4px; line-height:1.1;">
            ARTFOLIO
        </div>
        <div style="color:#737373; font-size:8px; letter-spacing:3px; margin-top:5px;">
            ART LIVES HERE
        </div>
    </div>
</a>

            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto gap-lg-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ url('/artworks') }}">Artworks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#featured">Featured Arts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#bestsellers">Best Sellers</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                    <form action="{{ url('/artworks') }}" method="GET" class="d-flex">
                        <input
                            type="search"
                            name="search"
                            class="form-control search-box"
                            placeholder="Search artworks..."
                            value="{{ request('search') }}"
                        >
                    </form>
                    <a href="#" class="text-dark fs-5" aria-label="Account">
                        <i class="bi bi-person"></i>
                    </a>
                    <a href="#" class="text-dark fs-5" aria-label="Shopping cart">
                        <i class="bi bi-cart3"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="container">
            <h1>Find Your Next Masterpiece</h1>
            <p>Explore unique artworks created by talented artists.</p>
        </div>
    </section>

    <!-- ARTWORKS CONTENT -->
    <main class="container page-content">
        <div class="row g-5">

            <!-- SIDEBAR -->
            <aside class="col-lg-3 sidebar">
                <h5>Categories</h5>

                <a href="{{ url('/artworks') }}"
                   class="category-link active">All Artworks</a>
                <a href="{{ url('/abstract') }}"
   class="category-link">
    Abstract
</a>
                <a href="{{ url('/artworks') }}?category=Typography"
                   class="category-link">Typography</a>
                <a href="{{ url('/artworks') }}?category=Modern"
                   class="category-link">Modern</a>
                <a href="{{ url('/artworks') }}?category=Character Design"
                   class="category-link">Character Design</a>
                <a href="{{ url('/artworks') }}?category=Digital"
                   class="category-link">Digital Art</a>

                <hr class="my-4">

                <h5 id="featured">Featured Arts</h5>
                <p class="text-secondary small">
                    Discover selected artworks from our collection.
                </p>

                <h5 id="bestsellers" class="mt-4">Best Sellers</h5>
                <p class="text-secondary small">
                    Explore popular pieces loved by art enthusiasts.
                </p>
            </aside>

            <!-- PRODUCT GRID -->
            <section class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="section-heading mb-1">Our Artworks</h2>
                        <p class="text-secondary mb-0">
                            Discover art that matches your style.
                        </p>
                    </div>

                    <select class="form-select w-auto" aria-label="Sort artworks">
                        <option selected>Sort by</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Newest</option>
                    </select>
                </div>

                <div class="row g-4">

                    <!-- ARTWORK 1 -->
                    <div class="col-sm-6 col-xl-4">
                        <div class="card art-card">
                            <img
                                src="https://api.builder.io/api/v1/image/assets/TEMP/e4a687fc75572a77e06ffeed5632c47d39c88776?width=540"
                                class="art-image"
                                alt="Modern Muse artwork"
                            >
                            <div class="card-body px-0">
                                <h3 class="art-title">Modern Muse</h3>
                                <p class="art-category">Illustration</p>
                                <p class="small text-secondary">
                                    Bold colors and a modern portrait style.
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="art-price">$8.00</span>
                                    <button class="btn btn-art btn-sm" type="button">
                                        View Artwork
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ARTWORK 2 -->
                    <div class="col-sm-6 col-xl-4">
                        <div class="card art-card">
                            <img
                                src="https://api.builder.io/api/v1/image/assets/TEMP/c8c575b63430b0406c0062deba38c2c5461c77f3?width=540"
                                class="art-image"
                                alt="Golden Mood artwork"
                            >
                            <div class="card-body px-0">
                                <h3 class="art-title">Golden Mood</h3>
                                <p class="art-category">Pop Art</p>
                                <p class="small text-secondary">
                                    A vibrant artwork with a bold golden palette.
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="art-price">$7.50</span>
                                    <button class="btn btn-art btn-sm" type="button">
                                        View Artwork
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ARTWORK 3 -->
                    <div class="col-sm-6 col-xl-4">
                        <div class="card art-card">
                            <img
                                src="https://api.builder.io/api/v1/image/assets/TEMP/efcf1ef60a3f61d4f37368bd9008c035a50e3156?width=540"
                                class="art-image"
                                alt="GoldMidnight Cat artwork"
                            >
                            <div class="card-body px-0">
                                <h3 class="art-title">GoldMidnight Cat</h3>
                                <p class="art-category">Character Art</p>
                                <p class="small text-secondary">
                                    A distinctive character artwork for your collection.
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="art-price">$6.50</span>
                                    <button class="btn btn-art btn-sm" type="button">
                                        View Artwork
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ARTWORK 4 -->
                    <div class="col-sm-6 col-xl-4">
                        <div class="card art-card">
                            <img
                                src="https://api.builder.io/api/v1/image/assets/TEMP/e2cabf299aa9b9f8a4d7c2bee94460061e9da077?width=540"
                                class="art-image"
                                alt="Color Theory artwork"
                            >
                            <div class="card-body px-0">
                                <h3 class="art-title">Color Theory</h3>
                                <p class="art-category">Abstract Art</p>
                                <p class="small text-secondary">
                                   condense the core message into a single, memorable phrase
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="art-price">$9.00</span>
                                    <button class="btn btn-art btn-sm" type="button">
                                        View Artwork
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <h5 class="fw-bold">ART FOLIO</h5>
            <p class="mb-0">Discover art. Support creativity.</p>
            <small>&copy; {{ date('Y') }} ART FOLIO. All rights reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>