<?php

use App\Http\Controllers\GeminiAIController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OpenAPIController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = Product::get();
    return view('welcome', compact('products'));
})->name('welcome');

Auth::routes();



Route::middleware('guest')->group(function () {
    // Google OAuth
    Route::get('/auth/google', [GoogleController::class, 'redirect'])
        ->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
        ->name('google.callback');
});



Route::group(['middleware' => ['auth']], function() {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');


});


Route::group(['middleware' => ['auth','admin']], function() {
    Route::get('admin/dashboard', [HomeController::class, 'admindashboard'])->name('admin.dashboard');
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
});



Route::get('stripe', [StripeController::class, 'stripe'])->name('stripe');
Route::post('stripe', [StripeController::class, 'stripePost'])->name('stripe.post');




Route::get('paypal', [PaypalController::class, 'index'])->name('paypal');
Route::post('paypal/payment', [PayPalController::class, 'payment'])->name('paypal.payment');
Route::get('paypal/payment-success', [PayPalController::class, 'paymentSuccess'])->name('paypal.payment.success');
Route::get('paypal/payment-cancel', [PayPalController::class, 'paymentCancel'])->name('paypal.payment.cancel');


Route::get('geminiai', [GeminiAIController::class, 'index'])->name('geminiai');
Route::post('geminiai/answers', [GeminiAIController::class, 'generateAnawers'])->name('generateAnawers');
Route::post('geminiai/answers/ajax', [GeminiAIController::class, 'ajaxresponseanswers'])->name('ajaxresponseanswers');