<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTFOLIO - About Us</title>
    <meta name="description" content="Discover how ARTFOLIO brings digital art and creative voices together.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
</head>
<body>

@php
    $userName = Auth::user()->name ?? 'User';
    $nameParts = preg_split('/\s+/', trim($userName));
    $userInitials = strtoupper(substr($nameParts[0] ?? 'U', 0, 1));
    if (count($nameParts) > 1) {
        $userInitials .= strtoupper(substr($nameParts[1], 0, 1));
    }
@endphp


<div class="app-layout">
    @include('partials.site-header', ['active' => 'about'])

    <main class="main-content about-content">
        <section class="about-hero" aria-labelledby="about-title">
            <div class="about-hero-overlay"></div>
            <div class="about-hero-copy">
                <span class="about-eyebrow">ART LIVES HERE <span aria-hidden="true">·</span> MADE TO BE DISCOVERED</span>
                <h1 id="about-title">Find art that<br>feels like <em>you.</em></h1>
                <p>Meet original digital art and the independent voices behind it. Your next favorite is waiting to be found.</p>
                <a href="{{ url('/artworks') }}" class="about-button about-button-primary">
                    Explore the collection
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                        <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
            <span class="about-hero-index" aria-hidden="true">01 / 04</span>
        </section>

        <section class="about-intro" aria-labelledby="why-artfolio-title">
            <div class="about-intro-heading">
                <span class="about-eyebrow">A DIFFERENT KIND OF ART SPACE</span>
                <h2 id="why-artfolio-title">Creativity deserves to be <em>discovered.</em></h2>
            </div>
            <div class="about-intro-copy">
                <p>We bring digital art and creative voices together in one place. Wander through expressive abstracts, bold typography, pixel art, and cinematic posters—each with a person and a perspective behind it.</p>
                <a href="{{ url('/artists') }}" class="about-text-link">
                    Get to know the artists
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                        <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </section>

        <section class="about-gallery" aria-labelledby="about-gallery-title">
            <div class="about-section-heading">
                <div>
                    <span class="about-eyebrow">A FEW PLACES TO BEGIN</span>
                    <h2 id="about-gallery-title">Art with its own point of view.</h2>
                </div>
                <a href="{{ url('/artworks') }}" class="about-text-link">
                    Browse all artworks
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                        <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            <div class="about-artwork-grid">
                @foreach ($featuredArtworks ?? [] as $index => $artwork)
                    <a href="{{ $artwork['url'] }}" class="about-artwork-card {{ $index === 0 ? 'about-artwork-card-featured' : '' }}">
                        <img src="{{ $artwork['image'] }}" alt="{{ $artwork['title'] }} by {{ $artwork['author'] }}" loading="lazy">
                        <span class="about-artwork-caption">
                            <span class="about-artwork-category">{{ $artwork['category'] }}</span>
                            <span class="about-artwork-title">{{ $artwork['title'] }}</span>
                            <span class="about-artwork-artist">{{ $artwork['author'] }}</span>
                        </span>
                        <span class="about-artwork-arrow" aria-hidden="true">↗</span>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="about-values" aria-labelledby="about-values-title">
            <div class="about-section-heading about-values-heading">
                <div>
                    <span class="about-eyebrow">YOUR ART JOURNEY</span>
                    <h2 id="about-values-title">Follow what moves you.</h2>
                </div>
            </div>
            <div class="about-values-grid">
                <article class="about-value-card">
                    <span class="about-value-number">01</span>
                    <h3>Find your style</h3>
                    <p>Explore abstract, typography, pixel, poster, and digital art.</p>
                </article>
                <article class="about-value-card">
                    <span class="about-value-number">02</span>
                    <h3>Meet the makers</h3>
                    <p>Discover the artists and perspectives behind every piece.</p>
                </article>
                <article class="about-value-card">
                    <span class="about-value-number">03</span>
                    <h3>Keep what you love</h3>
                    <p>Save your favorites and revisit the art that speaks to you.</p>
                </article>
            </div>
        </section>

        @php
            $contact = config('artfolio.contact');
        @endphp
        <section class="about-contact" aria-labelledby="about-contact-title">
            <div class="about-contact-info">
                <span class="about-eyebrow">GET IN TOUCH</span>
                <h2 id="about-contact-title">Contact ARTFOLIO.</h2>
                <p>Questions about an order, a payment, or an artist? Reach the team here.</p>
                <dl class="about-contact-list">
                    <div>
                        <dt>Email</dt>
                        <dd>
                            @if (! empty($contact['email']))
                                <a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a>
                            @else
                                <span class="about-contact-placeholder">Contact email coming soon</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt>Phone</dt>
                        <dd>
                            @if (! empty($contact['phone']))
                                <a href="tel:{{ preg_replace('/[^\d+]/', '', $contact['phone']) }}">{{ $contact['phone'] }}</a>
                            @else
                                <span class="about-contact-placeholder">Contact number coming soon</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt>Facebook</dt>
                        <dd>
                            @if (! empty($contact['facebook_url']))
                                <a href="{{ $contact['facebook_url'] }}" target="_blank" rel="noopener noreferrer">Visit our official Facebook page</a>
                            @else
                                <span class="about-contact-placeholder">Official page coming soon</span>
                            @endif
                        </dd>
                    </div>
                    @if (! empty($contact['address']))
                        <div>
                            <dt>Address</dt>
                            <dd>{{ $contact['address'] }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            @guest
                <div class="about-feedback-form" id="feedback" aria-labelledby="about-feedback-title">
                    <h3 id="about-feedback-title">Send us feedback</h3>
                    <p>Log in to tell us what you love or what we can improve.</p>
                    <a href="{{ route('login') }}" class="about-button about-button-primary">Log in to send feedback</a>
                </div>
            @endguest
            @auth
            <form method="POST" action="{{ route('feedback.store') }}" class="about-feedback-form" id="feedback" aria-labelledby="about-feedback-title">
                @csrf
                <h3 id="about-feedback-title">Send us feedback</h3>
                <p>Tell us what you love or what we can improve.</p>

                @if (session('feedback_status'))
                    <p class="about-feedback-success" role="status">{{ session('feedback_status') }}</p>
                @endif

                <label class="about-field">
                    <span>Name</span>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" maxlength="255" required>
                    @error('name')<small class="field-error" role="alert">{{ $message }}</small>@enderror
                </label>
                <label class="about-field">
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" maxlength="255" required>
                    @error('email')<small class="field-error" role="alert">{{ $message }}</small>@enderror
                </label>
                <label class="about-field">
                    <span>Rating <small>(optional)</small></span>
                    <select name="rating">
                        <option value="">No rating</option>
                        @foreach ([5 => 'Excellent', 4 => 'Good', 3 => 'Okay', 2 => 'Poor', 1 => 'Very poor'] as $ratingValue => $ratingLabel)
                            <option value="{{ $ratingValue }}" @selected((string) old('rating') === (string) $ratingValue)>{{ $ratingValue }} – {{ $ratingLabel }}</option>
                        @endforeach
                    </select>
                    @error('rating')<small class="field-error" role="alert">{{ $message }}</small>@enderror
                </label>
                <label class="about-field">
                    <span>Message</span>
                    <textarea name="message" rows="4" minlength="10" maxlength="2000" required>{{ old('message') }}</textarea>
                    @error('message')<small class="field-error" role="alert">{{ $message }}</small>@enderror
                </label>
                <button type="submit" class="about-button about-button-primary">Send feedback</button>
            </form>
            @endauth
        </section>

        <section class="about-cta" aria-labelledby="about-cta-title">
            <span class="about-cta-orbit about-cta-orbit-one" aria-hidden="true"></span>
            <span class="about-cta-orbit about-cta-orbit-two" aria-hidden="true"></span>
            <div class="about-cta-copy">
                <span class="about-eyebrow">YOUR NEXT FAVORITE IS OUT THERE</span>
                <h2 id="about-cta-title">Bring a little more art into your day.</h2>
                <p>Take a look around. You might find something that feels like it was made for you.</p>
                <div class="about-cta-actions">
                    <a href="{{ url('/artworks') }}" class="about-button about-button-primary">
                        Explore artworks
                        <svg width="13" height="13" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                            <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a href="{{ url('/artists') }}" class="about-button about-button-secondary">Meet the artists</a>
                </div>
            </div>
        </section>

        <footer class="about-footer">
            <span>ARTFOLIO</span>
            <span aria-hidden="true">·</span>
            <span>Art lives here.</span>
        </footer>
    </main>
</div>

</body>
</html>
