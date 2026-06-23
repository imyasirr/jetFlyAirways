<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/modules', [HomeController::class, 'modules']);
    Route::get('/modules/{module}', [ModuleController::class, 'index']);
    Route::get('/modules/{module}/{item}', [ModuleController::class, 'show']);
    Route::post('/modules/{module}/{item}/book', [BookingController::class, 'store']);
    Route::post('/coupons/validate', [BookingController::class, 'validateCoupon']);

    Route::get('/faqs', [ContentController::class, 'faqs']);
    Route::get('/blogs', [ContentController::class, 'blogs']);
    Route::get('/blogs/{slug}', [ContentController::class, 'blogShow']);
    Route::get('/pages/{slug}', [ContentController::class, 'page']);

    // Auth
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/otp/send', [AuthController::class, 'sendOtp']);
    Route::post('/auth/otp/verify', [AuthController::class, 'verifyOtp']);

    // Payments (guest or auth)
    Route::post('/bookings/{booking}/payment/order', [PaymentController::class, 'createOrder']);
    Route::post('/payments/verify', [PaymentController::class, 'verify']);

    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/user', [AuthController::class, 'user']);

        Route::get('/account/bookings', [AccountController::class, 'bookings']);
        Route::get('/account/bookings/{booking}', [AccountController::class, 'showBooking']);
        Route::post('/account/bookings/{booking}/cancel', [AccountController::class, 'cancelBooking']);
        Route::put('/account/profile', [AccountController::class, 'updateProfile']);
        Route::put('/account/password', [AccountController::class, 'updatePassword']);

        Route::get('/account/wishlist', [AccountController::class, 'wishlist']);
        Route::post('/account/wishlist/{module}/{id}', [AccountController::class, 'addWishlist']);
        Route::delete('/account/wishlist/{module}/{id}', [AccountController::class, 'removeWishlist']);

        Route::get('/account/saved-travellers', [AccountController::class, 'savedTravellers']);
        Route::post('/account/saved-travellers', [AccountController::class, 'storeSavedTraveller']);
        Route::delete('/account/saved-travellers/{savedTraveller}', [AccountController::class, 'deleteSavedTraveller']);

        Route::get('/account/announcements', [AccountController::class, 'announcements']);
        Route::post('/account/announcements/{announcement}/read', [AccountController::class, 'markAnnouncementRead']);
        Route::get('/account/refunds', [AccountController::class, 'refunds']);
    });
});
