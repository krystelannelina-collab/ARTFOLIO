{{--
    Decorative cut-paper artwork for the client page banners.

    Params:
    - variant: 'home' (Dusk Garden, an arched window over rolling hills)
               or 'about' (Studio Still Life, a vase on a striped table).

    Drawn as inline SVG in the brand palette so it scales cleanly and needs no image
    downloads. Purely decorative: hidden from screen readers.
--}}
@php
    $variant = $variant ?? 'home';
@endphp

<div class="hero-art hero-art-{{ $variant }}" aria-hidden="true">
    @if ($variant === 'about')
        <svg class="hero-art-canvas" viewBox="0 0 640 400" preserveAspectRatio="xMaxYMax meet" focusable="false">
            <defs>
                <pattern id="hero-art-dots-about" width="14" height="14" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.6" fill="#a8d1b3" opacity="0.5"/>
                </pattern>
                <pattern id="hero-art-stripes-about" width="18" height="18" patternUnits="userSpaceOnUse">
                    <rect width="9" height="18" fill="#ECE9E0" opacity="0.22"/>
                </pattern>
            </defs>

            <rect x="268" y="56" width="98" height="126" fill="url(#hero-art-dots-about)"/>
            <circle cx="500" cy="176" r="118" fill="#ECE9E0"/>
            <circle class="hero-art-float" cx="392" cy="262" r="24" fill="#e9b765"/>
            <circle cx="352" cy="96" r="30" fill="none" stroke="#e9b765" stroke-width="2" stroke-dasharray="2 7" stroke-linecap="round"/>

            <g class="hero-art-sway">
                <path d="M486 212C478 168 452 140 418 126" stroke="#315b45" stroke-width="3" fill="none" stroke-linecap="round"/>
                <path d="M418 126c-6 30 8 52 34 58-2-30-14-48-34-58z" fill="#a8d1b3"/>
                <path d="M492 212C496 160 520 126 556 108" stroke="#315b45" stroke-width="3" fill="none" stroke-linecap="round"/>
                <path d="M556 108c-34 4-50 24-50 52 30-6 46-24 50-52z" fill="#315b45"/>
                <path d="M489 212C488 170 490 132 498 92" stroke="#315b45" stroke-width="3" fill="none" stroke-linecap="round"/>
                <path d="M498 92c-20-16-20-40-2-58 16 18 18 40 2 58z" fill="#c9764f"/>
            </g>

            <path d="M474 320c-28-22-32-66-8-92 6-7 6-15 0-21h46c-6 6-6 14 0 21 24 26 20 70-8 92z" fill="#102b21"/>
            <path d="M470 252c10 8 34 8 44 0" stroke="#e9b765" stroke-width="3" fill="none" stroke-linecap="round"/>

            <rect x="300" y="318" width="360" height="90" fill="#c9764f"/>
            <rect x="300" y="318" width="360" height="90" fill="url(#hero-art-stripes-about)"/>
            <path d="M590 34a30 30 0 0 1 0 60z" fill="#c9764f"/>
            <path d="M282 360q14-18 28 0t28 0t28 0" stroke="#ECE9E0" stroke-width="3" fill="none" stroke-linecap="round" opacity="0.7"/>
        </svg>
    @else
        <svg class="hero-art-canvas" viewBox="0 0 640 400" preserveAspectRatio="xMaxYMax meet" focusable="false">
            <defs>
                <clipPath id="hero-art-arch-home">
                    <path d="M380 400V182a124 124 0 0 1 248 0V400z"/>
                </clipPath>
                <pattern id="hero-art-dots-home" width="14" height="14" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.6" fill="#a8d1b3" opacity="0.5"/>
                </pattern>
            </defs>

            <rect x="262" y="44" width="98" height="126" fill="url(#hero-art-dots-home)"/>
            <circle cx="372" cy="104" r="32" fill="none" stroke="#e9b765" stroke-width="2" stroke-dasharray="2 7" stroke-linecap="round"/>

            <path d="M380 400V182a124 124 0 0 1 248 0V400z" fill="#ECE9E0"/>
            <g clip-path="url(#hero-art-arch-home)">
                <circle class="hero-art-float" cx="528" cy="176" r="48" fill="#e9b765"/>
                <path d="M410 232c10-8 20-8 28 2 8-10 18-10 28-2" stroke="#102b21" stroke-width="3" fill="none" stroke-linecap="round"/>
                <path d="M452 206c6-5 12-5 17 1 5-6 11-6 17-1" stroke="#102b21" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                <path d="M370 300c50-46 104-44 150-16s86 24 120-6v122H370z" fill="#c9764f"/>
                <path d="M370 342c64-38 128-26 186 0 32 14 58 12 84-4v62H370z" fill="#315b45"/>
                <path d="M370 374c80-24 170-18 270-12v38H370z" fill="#102b21"/>
            </g>

            <g class="hero-art-sway">
                <path d="M326 400C330 360 326 330 318 300" stroke="#a8d1b3" stroke-width="3" fill="none" stroke-linecap="round"/>
                <path d="M318 300c-30-40-12-92 26-112 6 46-2 84-26 112z" fill="#a8d1b3"/>
                <path d="M324 352c-30-6-46-30-44-58 28 8 42 30 44 58z" fill="#e9b765"/>
            </g>
            <path d="M600 0a44 44 0 0 0 88 0z" fill="#c9764f"/>
            <path d="M250 330q14-18 28 0t28 0t28 0" stroke="#ECE9E0" stroke-width="3" fill="none" stroke-linecap="round" opacity="0.7"/>
        </svg>
    @endif
</div>
