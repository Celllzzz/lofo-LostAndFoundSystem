<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClaimController;

// Halaman Depan (Bisa diakses tanpa login)
Route::get('/', function () {
    return view('welcome'); // Landing page
});

// ROUTE UNTUK SEMUA USER YANG LOGIN (Mahasiswa, Admin, Security)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard redirect (Simple logic)
    Route::get('/dashboard', function () {
        if (auth()->user()->role !== 'mahasiswa') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('items.index');
    })->name('dashboard');

    // Profile (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- AREA MAHASISWA / UMUM ---
    // List Barang & Detail
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
    
    // Lapor Barang (Create & Store)
    Route::get('/report', [ItemController::class, 'create'])->name('items.create');
    Route::post('/report', [ItemController::class, 'store'])->name('items.store');

    // Ajukan Klaim
    Route::post('/items/{item}/claim', [ClaimController::class, 'store'])->name('claims.store');
    Route::get('/my-claims', [ClaimController::class, 'index'])->name('claims.index');
});

// --- AREA ADMIN & SECURITY ---
// Middleware: role:admin,security (Artinya user harus punya role admin ATAU security)
Route::middleware(['auth', 'role:admin,security'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Verifikasi Barang Temuan
    Route::patch('/items/{item}/verify', [AdminController::class, 'verifyItem'])->name('items.verify');

    // Proses Klaim (Terima/Tolak)
    Route::patch('/claims/{claim}/status', [ClaimController::class, 'updateStatus'])->name('claims.update');
});

require __DIR__.'/auth.php';