<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CustomerAuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AddressController;



Route::prefix('auth')->group(function () {
    Route::post('/register', [CustomerAuthController::class, 'register']);
    Route::post('/verify-register-otp', [CustomerAuthController::class, 'verifyRegisterOtp']);
    Route::post('/login', [CustomerAuthController::class, 'login']);
    Route::post('/verify-login-otp', [CustomerAuthController::class, 'verifyLoginOtp']);
    Route::post('/resend-otp', [CustomerAuthController::class, 'resendOtp']);

});

// Secure logout using Sanctum middleware
Route::middleware(['auth:sanctum', 'valid.sanctum'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile']);
    Route::post('/profile', [ProfileController::class, 'updateProfile']);
    Route::post('/logout', [ProfileController::class, 'logout']);

    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{product_id}', [CartController::class, 'update']);
    Route::delete('/cart/{product_id}', [CartController::class, 'destroy']);
    Route::delete('/cart', [CartController::class, 'clear']); // Clear all items

    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{product_id}', [WishlistController::class, 'destroy']);
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle']);

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);

    Route::get('/products', [ProductController::class, 'index']);     // List API with pagination
    Route::get('/products/{id}', [ProductController::class, 'show']); // Detail API
    Route::post('/orders', [OrderController::class, 'create']);

    Route::get('/orders', [OrderController::class, 'orderHistory']);     // Paginated order history
    Route::get('/orders/{id}', [OrderController::class, 'showOrder']);   // Order detail by ID


    Route::get('addresses/default', [AddressController::class, 'defaultAddress']);
    Route::post('addresses/{address}/set-default', [AddressController::class, 'setDefault']);
    Route::post('addresses/{address}', [AddressController::class, 'update']);
    Route::delete('addresses/{address}', [AddressController::class, 'destroy']);
    Route::apiResource('addresses', AddressController::class);
});
