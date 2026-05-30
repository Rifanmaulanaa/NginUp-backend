<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\BookingController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\HostController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\PropertiController;
use App\Http\Controllers\Web\ReviewController;
use App\Http\Controllers\Web\SearchController;
use Illuminate\Support\Facades\Route;

// ===========================
// AUTH
// ===========================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/register/success', fn () => view('auth.register-success'));
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// ===========================
// AUTHENTICATED (traveler)
// ===========================
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/search', [SearchController::class, 'index']);
    Route::get('/filter', [SearchController::class, 'filter']);
    Route::get('/checkout', [BookingController::class, 'checkout']);
    Route::post('/checkout', [BookingController::class, 'store']);
    Route::get('/detail/{id}', [PropertiController::class, 'show']);
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/booking-detail/{id}', [BookingController::class, 'show']);
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
    Route::get('/rewards', [ProfileController::class, 'rewards']);
    Route::get('/payment/{id}', [PaymentController::class, 'create']);
    Route::post('/payment/{id}', [PaymentController::class, 'store']);
    Route::post('/review', [ReviewController::class, 'store']);
    Route::put('/review/{id}', [ReviewController::class, 'update']);
});

// ===========================
// HOST (owner)
// ===========================
Route::middleware(['auth', 'role:owner'])->prefix('host')->group(function () {
    Route::get('/welcome', [HostController::class, 'welcome']);
    Route::get('/verification', [HostController::class, 'verification']);
    Route::get('/dashboard', [HostController::class, 'dashboard']);
    Route::get('/properties', [HostController::class, 'properties']);
    Route::get('/add-new', [HostController::class, 'addNew']);
    Route::post('/add-new', [HostController::class, 'storeProperty']);
    Route::get('/reports', [HostController::class, 'reports']);
    Route::put('/properties/{id}', [HostController::class, 'updateProperty']);
    Route::get('/properties/{id}/rooms', [HostController::class, 'rooms']);
    Route::post('/properties/{id}/rooms', [HostController::class, 'storeRoom']);
    Route::delete('/properties/{id}/rooms/{roomId}', [HostController::class, 'destroyRoom']);
    Route::get('/account', [HostController::class, 'account']);
});

// ===========================
// ADMIN
// ===========================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/verification', [AdminController::class, 'verification']);
    Route::put('/verification/{id}', [AdminController::class, 'verifyProperty']);
    Route::get('/users', [AdminController::class, 'users']);
    Route::put('/users/{id}/status', [AdminController::class, 'updateUserStatus']);
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
    Route::get('/properties', [AdminController::class, 'properties']);
    Route::delete('/properties/{id}', [AdminController::class, 'deleteProperty']);
    Route::get('/payments', [AdminController::class, 'payments']);
    Route::post('/payments/{id}/confirm', [AdminController::class, 'confirmPayment']);
    Route::get('/messages', [AdminController::class, 'messages']);
    Route::get('/monitor', [AdminController::class, 'monitor']);
    Route::get('/settings', [AdminController::class, 'settings']);
    Route::get('/reports', [AdminController::class, 'reports']);
});

// ===========================
// ROOT
// ===========================
Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'admin' => redirect('/admin/dashboard'),
            'owner' => redirect('/host/dashboard'),
            default => redirect('/home'),
        };
    }
    return redirect('/login');
});
