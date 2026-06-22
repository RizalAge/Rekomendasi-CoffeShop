<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\CoffeeShopController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AdminController; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ========== HALAMAN UTAMA ==========
Route::get('/', function () {
    // Jika sudah login
    if (Auth::check()) {
        $role = Auth::user()->role;
        
        // Admin ke dashboard admin
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } 
        // Selain admin (Pengguna biasa & Pemilik Cafe) ke Beranda Cari Cafe
        return redirect()->route('recommendation.form');
    }
    
    // Jika belum login, arahkan ke halaman login
    return redirect()->route('login');
});

// ========== AUTHENTICATION ROUTES ==========
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ========== ROUTES YANG MEMERLUKAN LOGIN ==========

// 1. Rute Khusus ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Rute Validasi (Pending)
    Route::get('/validasi-cafe', [AdminController::class, 'cekValidasi'])->name('validasi.index');
    Route::post('/validasi-cafe/{id}/{status}', [AdminController::class, 'prosesValidasi'])->name('validasi.process');
    
    // BARIS BARU: Rute Daftar Cafe Disetujui
    Route::get('/cafe-disetujui', [AdminController::class, 'cafeDisetujui'])->name('cafe_disetujui');
});

// 2. Rute GABUNGAN (User Biasa & Pemilik Cafe)
// Hanya menggunakan middleware 'auth' agar satu akun bisa mengakses kedua fitur
Route::middleware(['auth'])->group(function () {
    
    // --- FITUR PENGGUNA (CARI CAFE) ---
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/recommendation', [RecommendationController::class, 'index'])->name('recommendation.form');
    Route::post('/recommendation/process', [RecommendationController::class, 'process'])->name('recommendation.process');
    
    Route::get('/coffee-shops', [CoffeeShopController::class, 'index'])->name('coffee-shops.index');
    Route::get('/coffee-shops/search', [CoffeeShopController::class, 'search'])->name('coffee-shops.search');
    Route::get('/coffee-shops/nearby', [CoffeeShopController::class, 'nearby'])->name('coffee-shops.nearby');
    Route::get('/coffee-shops/{id}', [CoffeeShopController::class, 'show'])->name('coffee-shops.show');
    
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // --- FITUR PEMILIK CAFE (AJUKAN & KELOLA) ---
    // Tetap menggunakan prefix 'owner' agar URL-nya rapi (contoh: /owner/upload-persyaratan)
    Route::prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');
        
        // Upload & Ajukan Cafe
        Route::get('/upload-persyaratan', [OwnerController::class, 'showUploadForm'])->name('upload.form');
        Route::post('/upload-persyaratan', [OwnerController::class, 'processUpload'])->name('upload.process');
        
        // Kelola data cafe setelah disetujui
        Route::get('/kelola-cafe', [OwnerController::class, 'kelolaData'])->name('kelola');
        Route::post('/kelola-cafe', [OwnerController::class, 'updateData'])->name('kelola.update'); 
    });
});

// ========== FALLBACK ROUTE (404) ==========
Route::fallback(function () {
    return view('errors.404');
});