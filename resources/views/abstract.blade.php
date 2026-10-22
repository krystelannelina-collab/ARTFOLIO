<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Abstract Art | ARTFOLIO</title>
    <meta name="description" content="Discover abstract art at Artfolio.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --dark-green: #102b21;
            --soft-gray: #d9d9d9;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: white;
            color: var(--dark-green);
            font-family: Arial, Helvetica, sans-serif;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

        .site-header {
            background: #fbfbfa;
            border-bottom: 1px solid #f5f5f4;
        }

        .brand-logo {
            color: #171717;
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 4px;
            line-height: 1.1;
        }

        .brand-tagline {
            margin-top: 5px;
            color: #737373;
            font-size: 8px;
            letter-spacing: 3px;
        }

        .brand-mark {
            width: 32px;
            height: 32px;
            flex-shrink: 0;
        }

        .nav-link-custom {
            color: #404040;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
        }

        .nav-link-custom:hover {
            color: #7d927b;
        }

        .search-box {
            position: relative;
            width: 100%;
            max-width: 448px;
        }

        .search-box input {
            width: 100%;
            padding: 13px 16px 13px 40px;
            border: none;
            border-radius: 50px;
            outline: none;
            background: #eeeeee;
            font-size: 13px;
        }

        .search-box i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #a3a3a3;
        }

        .icon-button {
            position: relative;
            padding: 4px;
            color: #262626;
            font-size: 21px;
        }

        .cart-count {
            position: absolute;
            top: -4px;
            right: -7px;
            display: flex;
            width: 16px;
            height: 16px;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #a3a3a3;
            color: white;
            font-size: 9px;
            font-weight: bold;
        }

        .abstract-hero {
            position: relative;
            display: flex;
            min-height: 386px;
            align-items: center;
            margin-top: 50px;
            overflow: hidden;
            background: #d9d9d9;
        }

        .hero-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                90deg,
                #d9d9d9 31.74%,
                rgba(153,153,153,.37) 49.47%,
                rgba(115,115,115,0) 70%
            );
        }

        .hero-content {
            position: relative;
            max-width: 480px;
            padding: 40px 0;
        }

        .hero-content h1 {
            color: #102b21;
            font-size: clamp(40px, 5vw, 54px);
            font-weight: 700;
            letter-spacing: -1.35px;
            line-height: 1;
        }

        .hero-content p {
            margin-top: 10px;
            color: #171717;
            font-size: 16px;
            line-height: 24px;
            opacity: .65;
        }

        .category-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 22px;
            padding-top: 12px;
            padding-bottom: 4px;
        }

        .category-pill {
            width: 140px;
            min-height: 47px;
            border: 1px solid #102b21;
            border-radius: 30px;
            background: #d9d9d9;
            color: rgba(16,43,33,.8);
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -1px;
            transition: .2s;
        }

        .category-pill:hover {
            background: #e9eeeb;
        }

        .category-pill.active {
            background: #102b21;
            color: white;
        }

        .art-image-box {
            width: 100%;
            aspect-ratio: 315 / 174;
            overflow: hidden;
            background: #d9d9d9;
        }

        .art-image-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s;
        }

        .art-card:hover img {
            transform: scale(1.04);
        }

        .art-title {
            margin-top: 8px;
            color: #000;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: -.5px;
        }

        @media (max-width: 575px) {
            .abstract-hero {
                min-height: 360px;
                margin-top: 20px;
            }

            .hero-image {
                opacity: .55;
            }

            .hero-overlay {
                background: linear-gradient(
                    90deg,
                    rgba(217,217,217,.96),
                    rgba(217,217,217,.65)
                );
            }

            .hero-content {
                padding: 30px 8px;
            }

            .category-buttons {
                gap: 10px;
            }

            .category-pill {
                width: auto;
                min-width: 120px;
                padding: 5px 18px;
            }
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header class="site-header">
        <div class="container-fluid px-3 px-lg-5">
            <div class="d-flex align-items-center gap-3 gap-lg-4 py-3">

                <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 flex-shrink-0">
                    <svg class="brand-mark" viewBox="0 0 32 32" fill="none"
                         xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M5.33325 27.5556L14.2221 4.44444H17.7777L11.5555 22.2222H18.6666L20.4444 27.5556H5.33325Z" fill="#1C1D1B"/>
                        <path d="M19.5556 11.5556L25.7778 27.5556H21.3334L16.8889 16L19.5556 11.5556Z" fill="#1C1D1B"/>
                        <path d="M21.7778 8C21.7778 9.22648 22.7736 10.2222 24.0001 10.2222C25.2265 10.2222 26.2223 9.22648 26.2223 8C26.2223 6.77352 25.2265 5.77778 24.0001 5.77778C22.7736 5.77778 21.7778 6.77352 21.7778 8Z" fill="#1C1D1B"/>
                    </svg>

                    <div>
                        <div class="brand-logo">ARTFOLIO</div>
                        <div class="brand-tagline">ART LIVES HERE</div>
                    </div>
                </a>

                <nav class="d-none d-xl-flex align-items-center gap-4 ms-3">
                    <a href="{{ url('/artworks') }}" class="nav-link-custom">
                        Shop <i class="bi bi-chevron-down"></i>
                    </a>
                    <a href="{{ url('/artworks') }}" class="nav-link-custom">Original Art</a>
                    <a href="{{ url('/artworks') }}" class="nav-link-custom">Art Supplies</a>
                    <a href="{{ url('/artworks') }}" class="nav-link-custom">Collections</a>
                </nav>

                <div class="search-box ms-auto d-none d-md-block">
                    <input type="search" placeholder="Search artworks and supplies" aria-label="Search">
                    <i class="bi bi-search"></i>
                </div>

                <div class="d-flex align-items-center gap-3 ms-auto ms-md-3">
                    <a href="#" class="icon-button" aria-label="Account">
                        <i class="bi bi-person"></i>
                    </a>
                    <a href="#" class="icon-button" aria-label="Shopping cart">
                        <i class="bi bi-cart3"></i>
                        <span class="cart-count">0</span>
                    </a>
                </div>

            </div>
        </div>
    </header>

    <!-- ABSTRACT HERO -->
    <main>
        <section class="abstract-hero">
            <img
                src="https://api.builder.io/api/v1/image/assets/TEMP/d30059d20820ca765715772b6fc8d538a37236d6?width=2880"
                alt="Abstract art collection"
                class="hero-image"
            >

            <div class="hero-overlay"></div>

            <div class="container-fluid px-4 px-lg-5">
                <div class="hero-content">
                    <h1>Abstract Art</h1>
                    <p>
                        Discover the beauty of abstract art, where colors,
                        shapes, and imagination come together to create
                        extraordinary masterpieces that inspire creativity
                        and express emotions.
                    </p>
                </div>
            </div>
        </section>

        <!-- CATEGORY BUTTONS -->
        <section class="container-fluid px-4 px-lg-5">
            <div class="category-buttons">
                <button type="button" class="category-pill active">Abstract</button>
                <button type="button" class="category-pill">Typography</button>
                <button type="button" class="category-pill">Modem</button>
                <button type="button" class="category-pill">Character</button>
                <button type="button" class="category-pill">Digital</button>
            </div>

            <!-- ARTWORK GRID -->
            <div class="row g-4 py-4">

                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="#" class="art-card d-block">
                        <div class="art-image-box">
                            <img src="https://api.builder.io/api/v1/image/assets/TEMP/a41e1796fe10e97eb14070298bf0aa29792aa70e?width=630" alt="Geometric Painting" loading="lazy">
                        </div>
                        <div class="art-title">Geometric Painting</div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="#" class="art-card d-block">
                        <div class="art-image-box">
                            <img src="https://api.builder.io/api/v1/image/assets/TEMP/cced601eb0707011569e2e0d88d7c42c65d8ed0e?width=632" alt="Autumn Landscape" loading="lazy">
                        </div>
                        <div class="art-title">Autumn Landscape</div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="#" class="art-card d-block">
                        <div class="art-image-box">
                            <img src="https://api.builder.io/api/v1/image/assets/TEMP/3401bdecf82affaafb17850d0cd6aed39f36ba9f?width=616" alt="Sunset Landscape" loading="lazy">
                        </div>
                        <div class="art-title">Sunset Landscape</div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="#" class="art-card d-block">
                        <div class="art-image-box">
                            <img src="https://api.builder.io/api/v1/image/assets/TEMP/23d710ed419aa14436e580a7d1a958af9f216c0f?width=638" alt="Modern Abstract" loading="lazy">
                        </div>
                        <div class="art-title">Modern Abstract</div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="#" class="art-card d-block">
                        <div class="art-image-box">
                            <img src="https://api.builder.io/api/v1/image/assets/TEMP/d85b1fa35147c0ee08b102efa8d65c9646edef8a?width=634" alt="Ocean Landscape" loading="lazy">
                        </div>
                        <div class="art-title">Ocean Landscape</div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="#" class="art-card d-block">
                        <div class="art-image-box">
                            <img src="https://api.builder.io/api/v1/image/assets/TEMP/a376502eda95a7368aa22694ba8ec74007dc7872?width=324" alt="Colorful Abstract" loading="lazy">
                        </div>
                        <div class="art-title">Colorful Abstract</div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="#" class="art-card d-block">
                        <div class="art-image-box">
                            <img src="https://api.builder.io/api/v1/image/assets/TEMP/c6fb13d4a48d38af66225f3f319266dda8562a46?width=329" alt="Abstract Painting" loading="lazy">
                        </div>
                        <div class="art-title">Abstract Painting</div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="#" class="art-card d-block">
                        <div class="art-image-box">
                            <img src="https://api.builder.io/api/v1/image/assets/TEMP/bc62596afc8e5008aae6ec4671950c9c7858bc92?width=638" alt="Contemporary Abstract" loading="lazy">
                        </div>
                        <div class="art-title">Contemporary Abstract</div>
                    </a>
                </div>

            </div>
        </section>
    </main>

    <footer class="py-4 mt-3 border-top bg-light">
        <div class="container-fluid px-4 px-lg-5 d-flex flex-column flex-sm-row justify-content-between gap-2">
            <span class="fw-semibold">ARTFOLIO — ART LIVES HERE</span>
            <span class="text-secondary small">&copy; {{ date('Y') }} ART FOLIO. All rights reserved.</span>
        </div>
    </footer>

</body>
</html>