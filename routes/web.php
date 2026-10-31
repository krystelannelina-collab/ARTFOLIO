<?php

use App\Http\Controllers\Admin\ArtworkController as AdminArtworkController;
use App\Http\Controllers\Admin\ArtistController as AdminArtistController;
use App\Http\Controllers\Admin\DashboardController;
use App\Models\Artwork;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

Route::get('/', function () {
    return view('client-login');
})->name('welcome');

Route::get('/login', function () {
    return view('client-login');
})->name('login');

Route::get('/home', function () {
    return view('welcome');
})->middleware('auth')->name('home');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/artists', [AdminArtistController::class, 'index'])->name('artists.index');
    Route::resource('artworks', AdminArtworkController::class)->except('show');
});

Route::get('/artworks', function () {
    $catalogArtworks = Artwork::query()
        ->where('status', 'published')
        ->orderBy('title')
        ->get();

    return view('artworks', [
        'catalogArtworks' => $catalogArtworks,
        'catalogCategories' => $catalogArtworks->pluck('category')->unique()->values(),
    ]);
})->middleware('auth')->name('artworks');

Route::get('/artworks/{slug}', function (string $slug) {
    $artworks = [
        'fluid-geometric' => [
            'title' => 'Fluid Geometric',
            'author' => 'Cristine Joy Montes',
            'category' => 'Abstract',
            'price' => 28.56,
            'image' => asset('images/artwork-fluid-geometric.png'),
            'description' => 'A study in movement and color, Fluid Geometric brings flowing shapes together in a bold, contemporary composition.',
        ],
        'stranger-things-poster' => [
            'title' => 'Stranger Things Poster',
            'author' => 'Gian Gomez',
            'category' => 'Poster',
            'price' => 35.76,
            'image' => asset('images/artwork-stranger-things.png'),
            'description' => 'An atmospheric poster pairing a bold red palette with a cinematic composition and dramatic lighting.',
        ],
        'minimalist-organic' => [
            'title' => 'Minimalist Organic',
            'author' => 'Gian Gomez',
            'category' => 'Abstract',
            'price' => 15.67,
            'image' => asset('images/artwork-minimalist-organic.png'),
            'description' => 'Soft, organic shapes and a restrained palette create a calm, modern addition to any collection.',
        ],
        'geometric-contrast' => [
            'title' => 'Geometric Contrast',
            'author' => 'Kalvin Dave Ramos',
            'category' => 'Abstract',
            'price' => 20.43,
            'image' => asset('images/artwork-geometric-contrast.png'),
            'description' => 'Crisp geometric forms meet expressive color in a composition built around contrast and balance.',
        ],
        'colorful-abstract' => [
            'title' => 'Colorful Abstract',
            'author' => 'Kalvin Dave Ramos',
            'category' => 'Abstract',
            'price' => 24.50,
            'image' => asset('images/artwork-colorful-abstract.png'),
            'description' => 'A vibrant abstract composition that brings expressive color and movement into focus.',
        ],
        'the-one-poster' => [
            'title' => 'The One Poster',
            'author' => 'Kalvin Dave Ramos',
            'category' => 'Poster',
            'price' => 25.86,
            'image' => asset('images/artwork-the-one-poster.png'),
            'description' => 'A striking poster design with a memorable focal point and a clean, graphic sensibility.',
        ],
        'geometric-abstract' => [
            'title' => 'Geometric Abstract',
            'author' => 'Krystel Anne Lina',
            'category' => 'Abstract',
            'price' => 21.43,
            'image' => asset('images/artwork-geometric-abstract.png'),
            'description' => 'Layered geometric forms create depth and rhythm in this imaginative abstract artwork.',
        ],
        'goldmidnight-pixel-art' => [
            'title' => 'GoldMidnight Pixel Art',
            'author' => 'Kalvin Dave Ramos',
            'category' => 'Pixel',
            'price' => 18.50,
            'image' => asset('images/artwork-geometric-contrast.png'),
            'description' => 'A pixel-inspired composition that brings a graphic, digital edge to a warm and moody palette.',
        ],
        'golden-mood-typography' => [
            'title' => 'Golden Mood Typography',
            'author' => 'Gian Gomez',
            'category' => 'Typography',
            'price' => 24.50,
            'image' => asset('images/artwork-stranger-things.png'),
            'description' => 'Expressive type and cinematic color make this artwork a bold statement for your space.',
        ],
        'modern-muse-digital' => [
            'title' => 'Modern Muse Digital',
            'author' => 'Cristine Joy Montes',
            'category' => 'Digital',
            'price' => 27.00,
            'image' => asset('images/artwork-fluid-geometric.png'),
            'description' => 'A contemporary digital artwork shaped by fluid forms, color, and a playful sense of movement.',
        ],
    ];

    $catalogArtwork = Artwork::query()->where('slug', $slug)->first();
    if ($catalogArtwork !== null) {
        abort_unless($catalogArtwork->status === 'published' || Auth::user()?->role === 'admin', 404);
        $artworks[$slug] = [
            'title' => $catalogArtwork->title,
            'author' => $catalogArtwork->artist_name,
            'category' => $catalogArtwork->category,
            'price' => (float) $catalogArtwork->price,
            'image' => asset($catalogArtwork->image_path),
            'description' => $catalogArtwork->description,
        ];
    } else {
        abort_unless(isset($artworks[$slug]), 404);
    }

    $artwork = ['slug' => $slug, 'rating' => '5.0', ...$artworks[$slug]];
    $relatedArtworks = collect($artworks)
        ->except($slug)
        ->take(3)
        ->map(fn (array $relatedArtwork, string $relatedSlug): array => [
            'slug' => $relatedSlug,
            ...$relatedArtwork,
        ])
        ->all();

    return view('artwork-details', compact('artwork', 'relatedArtworks'));
})->middleware('auth')->name('artworks.show');

Route::get('/artists', function () {
    return view('artists');
})->middleware('auth')->name('artists');

Route::get('/about', function () {
    return view('about');
})->middleware('auth')->name('about');

Route::get('/favorites', function () {
    return view('favorites');
})->middleware('auth')->name('favorites');

Route::get('/cart', function () {
    return view('cart');
})->middleware('auth')->name('cart');

Route::get('/checkout', function () {
    return view('checkout');
})->middleware('auth')->name('checkout');

Route::get('/order-success', function () {
    return view('order-success');
})->middleware('auth')->name('order.success');

Route::get('/orders', function () {
    return view('order-success');
})->middleware('auth')->name('orders');

Route::get('/abstract', function () {
    return view('abstract');
})->middleware('auth')->name('abstract');

Route::get('/admin-signup', function () {
    return redirect()->route('login')
        ->with('status', 'Admin access must be granted by an existing system administrator.');
});

Route::get('/client-signup', function () {
    return view('client-signup');
});

Route::get('/client-login', function () {
    return view('client-login');
});

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
        'password' => ['required', 'string', 'min:8'],
    ]);

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'client',
    ]);

    return redirect()->route('login')->with('success', 'Account created successfully. Please log in.');
})->name('register');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    $user = User::where('email', $credentials['email'])->first();

    if (! $user) {
        return back()->withErrors([
            'email' => 'No account found. Please create an account first.',
        ])->onlyInput('email');
    }

    if (! Auth::attempt($credentials, $request->boolean('remember'))) {
        return back()->withErrors([
            'password' => 'Incorrect password. Please try again.',
        ])->onlyInput('email');
    }

    $request->session()->regenerate();

    if (Auth::user()?->role === 'admin') {
        return redirect()->intended(route('admin.dashboard'));
    }

    return redirect()->intended(route('home'));
})->name('login');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');
