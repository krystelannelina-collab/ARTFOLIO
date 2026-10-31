<?php

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

Route::get('/artworks', function () {
    return view('artworks');
})->middleware('auth')->name('artworks');

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
    return view('admin-signup');
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
        'role' => ['nullable', Rule::in(['client', 'admin'])],
    ]);

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'] ?? 'client',
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

    return redirect()->intended(route('home'));
})->name('login');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');
