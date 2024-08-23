<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\VegeProduct;
use App\Http\Controllers\VegeUserController;
use App\Models\VegeUser;
use App\Http\Middleware\checkAuth;

Route::get('/register', [VegeUserController::class, 'showRegisterForm'])->name('vege_register');
Route::post('/register', [VegeUserController::class, 'register'])->name('vege_register');
Route::get('/login', [VegeUserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [VegeUserController::class, 'login']);
Route::post('/logout', [VegeUserController::class, 'logout'])->name('logout');

Route::get('/products', [VegeUserController::class, 'showProducts'])->name('products');
Route::get('/product/{id}', [VegeUserController::class, 'showProductDetails'])->name('product.details');
Route::get('/products/create', [VegeUserController::class, 'create'])->name('add');
Route::post('/products', [VegeUserController::class, 'store']);

Route::get("/",function(){
    return response(123);
});
Route::get('/vege_home', [VegeUserController::class, 'showProducts'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [VegeUserController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [VegeUserController::class, 'updateProfile'])->name('profile.update');

    Route::post('/address/store', [VegeUserController::class, 'storeAddress'])->name('address.store');
    Route::post('/address/update/{id}', [VegeUserController::class, 'updateAddress'])->name('address.update');

    Route::post('/vegetables/add/{p_id}', [VegeUserController::class, 'addToCart'])->name('addcart');
    Route::get('/vegetables/cart', [VegeUserController::class, 'viewCart'])->name('view_cart');
});

Route::delete('/address/{id}', [VegeUserController::class, 'destroyAddress'])->name('address.delete');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [VegeUserController::class, 'showProfile'])->name('profile');
    Route::post('/profile/update-username', [VegeUserController::class, 'updateUsername2'])->name('profile.update.username');
    Route::post('/profile/update-email', [VegeUserController::class, 'updateEmail'])->name('profile.update.email');
    Route::post('/profile/update-password', [VegeUserController::class, 'updatePassword'])->name('profile.update.password');
    Route::post('/profile/verify-otp', [VegeUserController::class, 'verifyOtp3'])->name('profile.verify.otp');
});

Route::get('/forgot-password', [VegeUserController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [VegeUserController::class, 'sendResetLink'])->name('password.email');
Route::get('/verify-otp', [VegeUserController::class, 'showVerifyOtpForm2'])->name('password.verify');
Route::post('/verify-otp', [VegeUserController::class, 'verifyOtp2'])->name('password.otp');
Route::get('/reset-password', [VegeUserController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [VegeUserController::class, 'resetPassword'])->name('password.update');

Route::get('/verify_otp', [VegeUserController::class, 'showVerifyOtpForm'])->name('verify.otp');
Route::post('/verify_otp', [VegeUserController::class, 'verifyOtp'])->name('otp.verify');

Route::get('/cart/count', [VegeUserController::class, 'getCartCount'])->name('cart.count');

Route::post('/update-cart', [VegeUserController::class, 'updateCart'])->name('updateCart');
Route::post('/remove-from-cart', [VegeUserController::class, 'removeFromCart'])->name('removeFromCart');

Route::post('/cart/edit/{id}', [VegeUserController::class, 'editCartItem'])->name('editCartItem');
