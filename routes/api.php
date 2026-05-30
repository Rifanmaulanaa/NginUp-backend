<?php

use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\AturanController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FasilitasController;
use App\Http\Controllers\Api\GambarController;
use App\Http\Controllers\Api\KetersediaanPropertiController;
use App\Http\Controllers\Api\NotifikasiController;
use App\Http\Controllers\Api\PembayaranController;
use App\Http\Controllers\Api\PemesananController;
use App\Http\Controllers\Api\PropertiController;
use App\Http\Controllers\Api\RevenueSplitController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Support\Facades\Route;

// ===========================
// AUTH
// ===========================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // ===========================
    // PROPERTI (owner only)
    // ===========================
    Route::middleware('role:owner')->group(function () {
        Route::apiResource('properti', PropertiController::class)->except(['index', 'show']);
        Route::post('properti/{id}/fasilitas/sync', [PropertiController::class, 'syncFasilitas']);
        Route::post('properti/{id}/aturan/sync', [PropertiController::class, 'syncAturan']);

        Route::post('properti/{id_properti}/gambar', [GambarController::class, 'store']);
        Route::put('properti/{id_properti}/gambar/{id_gambar}', [GambarController::class, 'update']);
        Route::delete('properti/{id_properti}/gambar/{id_gambar}', [GambarController::class, 'destroy']);

        Route::post('properti/{id_properti}/ketersediaan', [KetersediaanPropertiController::class, 'store']);
        Route::delete('properti/{id_properti}/ketersediaan/{id_ketersediaan}', [KetersediaanPropertiController::class, 'destroy']);

        Route::get('revenue-split', [RevenueSplitController::class, 'index']);
    });

    // ===========================
    // PEMESANAN
    // ===========================
    Route::get('pemesanan', [PemesananController::class, 'index']);
    Route::post('pemesanan', [PemesananController::class, 'store']);
    Route::get('pemesanan/{id}', [PemesananController::class, 'show']);
    Route::put('pemesanan/{id}/status', [PemesananController::class, 'updateStatus']);

    // ===========================
    // PEMBAYARAN (nested di pemesanan)
    // ===========================
    Route::get('pemesanan/{id_pesanan}/pembayaran', [PembayaranController::class, 'index']);
    Route::post('pemesanan/{id_pesanan}/pembayaran', [PembayaranController::class, 'store']);
    Route::get('pemesanan/{id_pesanan}/pembayaran/{id_pembayaran}', [PembayaranController::class, 'show']);
    Route::put('pemesanan/{id_pesanan}/pembayaran/{id_pembayaran}/konfirmasi', [PembayaranController::class, 'konfirmasi']);

    // ===========================
    // REVIEW
    // ===========================
    Route::post('review', [ReviewController::class, 'store']);
    Route::put('review/{id}', [ReviewController::class, 'update']);
    Route::delete('review/{id}', [ReviewController::class, 'destroy']);

    // ===========================
    // FASILITAS & ATURAN (admin only)
    // ===========================
    Route::middleware('role:admin')->group(function () {
        Route::post('fasilitas', [FasilitasController::class, 'store']);
        Route::delete('fasilitas/{id}', [FasilitasController::class, 'destroy']);
        Route::post('aturan', [AturanController::class, 'store']);
        Route::delete('aturan/{id}', [AturanController::class, 'destroy']);
    });

    // ===========================
    // NOTIFIKASI
    // ===========================
    Route::get('notifikasi', [NotifikasiController::class, 'index']);
    Route::put('notifikasi/{id}/read', [NotifikasiController::class, 'markAsRead']);
    Route::put('notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead']);

    // ===========================
    // ADMIN (admin only)
    // ===========================
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Manajemen User
        Route::get('users', [AdminUserController::class, 'index']);
        Route::get('users/{id}', [AdminUserController::class, 'show']);
        Route::put('users/{id}/status', [AdminUserController::class, 'updateStatus']);
        Route::delete('users/{id}', [AdminUserController::class, 'destroy']);

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index']);

        // Verifikasi & Hapus Properti
        Route::put('properti/{id}/verify', [PropertiController::class, 'verify']);
        Route::delete('properti/{id}', [PropertiController::class, 'destroy']);

        // Revenue Split (admin view all)
        Route::get('revenue-split', [RevenueSplitController::class, 'index']);
    });
});

// ===========================
// PUBLIC ROUTES
// ===========================
Route::get('properti', [PropertiController::class, 'index']);
Route::get('properti/{id}', [PropertiController::class, 'show']);
Route::get('properti/{id_properti}/gambar', [GambarController::class, 'index']);
Route::get('properti/{id_properti}/ketersediaan', [KetersediaanPropertiController::class, 'index']);
Route::get('properti/{id_properti}/review', [ReviewController::class, 'index']);
Route::get('fasilitas', [FasilitasController::class, 'index']);
Route::get('aturan', [AturanController::class, 'index']);
