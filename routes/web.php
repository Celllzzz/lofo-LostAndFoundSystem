<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClaimController;
use App\Models\Item;

// Halaman Depan (Bisa diakses tanpa login)
Route::get('/', function () {
    $stats = [
        'lost' => Item::where('type', 'lost')->count(),
        'found' => Item::where('type', 'found')->count(),
        'returned' => Item::where('status', 'returned')->count(),
    ];
    $latestItems = Item::where('status', 'open')->latest()->take(3)->get();

    return view('welcome', compact('stats', 'latestItems'));
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
    
    // Dashboard Utama (Laporan)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Route Verifikasi & Update Status
    Route::patch('/items/{item}/verify', [AdminController::class, 'verifyItem'])->name('items.verify');
    Route::patch('/items/{item}/update-status', [AdminController::class, 'updateItemStatus'])->name('items.update-status');
    Route::patch('/claims/{claim}/status', [ClaimController::class, 'updateStatus'])->name('claims.update');

    // KHUSUS ADMIN
    Route::middleware(['role:admin'])->group(function() {
        // DASHBOARD KLAIM (BARU)
        Route::get('/claims-dashboard', [AdminController::class, 'claimsDashboard'])->name('claims-dashboard');

        // Manajemen User & Kategori
        Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
        Route::post('/users', [AdminController::class, 'usersStore'])->name('users.store');
        Route::put('/users/{user}', [AdminController::class, 'usersUpdate'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'usersDestroy'])->name('users.destroy');

        Route::get('/categories', [AdminController::class, 'categoriesIndex'])->name('categories.index');
        Route::post('/categories', [AdminController::class, 'categoriesStore'])->name('categories.store');
        Route::put('/categories/{category}', [AdminController::class, 'categoriesUpdate'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'categoriesDestroy'])->name('categories.destroy');
    });
});

require __DIR__.'/auth.php';