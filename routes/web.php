<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AnakController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\SekolahController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// --- Guest (belum login) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// --- Sudah login ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Khusus Admin Kecamatan (CRUD Desa, Faskes, Manajemen User)
    Route::middleware('role:admin_kecamatan')->group(function () {
        Route::resource('desa', DesaController::class)->except('show');
        // Route::resource('faskes', FaskesController::class);
        // Route::resource('users', UserController::class);
        // -> ditambahkan di tahap berikutnya
    });

    // Admin & User Sekolah sama-sama boleh akses (query & otorisasi dibatasi di controller)
    Route::middleware('role:admin_kecamatan,user_sekolah')->group(function () {
        Route::resource('sekolah', SekolahController::class)->except('show');
        Route::resource('anak', AnakController::class)->except('show');
    });
});
