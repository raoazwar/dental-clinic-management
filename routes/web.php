<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard')->with('success', 'Email verified successfully!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Search Routes
Route::post('/search', [SearchController::class, 'globalSearch'])->name('search.global');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['verified', 'first.login.email']);
    
    // Categories Management
    Route::resource('categories', CategoryController::class)->middleware('verified');
    
    // Products (Inventory Management)
    Route::resource('products', ProductController::class)->middleware('verified');
    
    // Sales
    Route::resource('sales', SaleController::class)->middleware('verified');
    
    // User Management
    Route::resource('users', UserController::class)->middleware('verified');
    Route::post('/users/{user}/resend-verification', [UserController::class, 'resendVerification'])->name('users.resend-verification')->middleware('verified');
    
    // Additional sales routes
    Route::get('/sales/create/new', [SaleController::class, 'createNew'])->name('sales.create.new')->middleware('verified');
    Route::post('/sales/store', [SaleController::class, 'store'])->name('sales.store')->middleware('verified');
});
