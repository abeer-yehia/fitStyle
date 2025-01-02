<?php

use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MoveToCartController;
use App\Http\Controllers\SaveForLaterController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardCategoryController;
use App\Http\Controllers\DashboardSettingsProfileController;
use App\Http\Controllers\DashboardSettingsPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardOrderController;
use App\Http\Controllers\DashboardProductController;
use App\Http\Controllers\DashboardSectionController;
use App\Http\Controllers\WhishlistController;
use Illuminate\Support\Facades\Route;

// SHOP
Route::get('/shop', ShopController::class)->name('shop');
Route::get('/', function () {
    return redirect()->route('shop');
});

// PRODUCTS
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('product.show');

Route::middleware(['auth'])->group(function () {
    // CART
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/{product:id}', [CartController::class, 'store'])->name('cart.store');
        Route::patch('/{product:id}/increase', [CartController::class, 'increase'])->name('cart.increase');
        Route::patch('/{product:id}/decrease', [CartController::class, 'decrease'])->name('cart.decrease');
        Route::delete('/empty', [CartController::class, 'empty'])->name('cart.empty');
        Route::delete('/{product:id}', [CartController::class, 'destroy'])->name('cart.destroy');

        // SAVEFORLATER
        Route::post('/{id}/save-for-later', SaveForLaterController::class)->name('cart.saveForLater');
        // MOVETOCART
        Route::post('/{id}/move-to-cart', MoveToCartController::class)->name('cart.moveToCart');
    });

    // WHISHLIST
    Route::get('/whishlist', [WhishlistController::class, 'index'])->name('whishlist.index');
    Route::post('/whishlist/{product:id}', [WhishlistController::class, 'toggle'])->name('whishlist.toggle');
    Route::post('/whishlist/{product:id}/move-to-cart', [WhishlistController::class, 'moveToCart'])->name('whishlist.moveToCart');
    Route::delete('/whishlist', [WhishlistController::class, 'destroy'])->name('whishlist.destroy');

    // CHECKOUT
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout');

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/orders', [DashboardOrderController::class, 'index'])->name('order.index');
    Route::resource('/dashboard/sections', DashboardSectionController::class)->except(['show']);
    Route::resource('/dashboard/categories', DashboardCategoryController::class)->except(['show']);
    Route::resource('/dashboard/products', DashboardProductController::class)->except(['show']);
    Route::post('/dashboard/products/{product}/update', [DashboardProductController::class, 'update'])->name('product.post.update');


    // DASHBOARD SETTINGS
    Route::get('/dashboard/settings/profile', [DashboardSettingsProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::patch('/dashboard/settings/profile', [DashboardSettingsProfileController::class, 'update'])->name('settings.profile.update');

    Route::get('/dashboard/settings/password', [DashboardSettingsPasswordController::class, 'edit'])->name('settings.password.edit');
    Route::patch('/dashboard/settings/password', [DashboardSettingsPasswordController::class, 'update'])->name('settings.password.update');
});


require __DIR__ . '/auth.php';
