<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\ArtistController as AdminArtistController;
use App\Http\Controllers\Admin\ArtworkController as AdminArtworkController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('welcome');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/client-login', [AuthController::class, 'showLogin']);
Route::get('/client-signup', [AuthController::class, 'showRegister'])->name('register.show');

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', HomeController::class)
    ->middleware('auth')
    ->name('home');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/artists', [AdminArtistController::class, 'index'])->name('artists.index');
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::patch('/categories/{category}/visibility', [AdminCategoryController::class, 'toggleVisibility'])->name('categories.visibility');
    Route::get('/orders/export', [AdminOrderController::class, 'export'])->name('orders.export');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::resource('artworks', AdminArtworkController::class)->except('show');
});

Route::middleware('auth')->group(function () {
    Route::get('/artworks', [ArtworkController::class, 'index'])->name('artworks');
    Route::get('/artworks/{slug}', [ArtworkController::class, 'show'])->name('artworks.show');
    Route::get('/artists', [ArtistController::class, 'index'])->name('artists');
    Route::get('/about', AboutController::class)->name('about');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
    Route::post('/favorites/{slug}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::delete('/favorites/{slug}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/{slug}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/checkout/{slug}', [CartController::class, 'buyNow'])->name('checkout.buy-now');
    Route::delete('/cart/items/{artwork}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/items/{artwork}/save-for-later', [CartController::class, 'saveForLater'])->name('cart.save-for-later');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/order-success/{order}', [OrderController::class, 'show'])->name('order.success');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/items/{item}/download', [OrderController::class, 'downloadItem'])->name('orders.items.download');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'downloadReceipt'])->name('orders.receipt');
});

Route::get('/abstract', function () {
    return view('abstract');
})->middleware('auth')->name('abstract');

Route::get('/admin-signup', function () {
    return redirect()->route('login')
        ->with('status', 'Admin access must be granted by an existing system administrator.');
});
