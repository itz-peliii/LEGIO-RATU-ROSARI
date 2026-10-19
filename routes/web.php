<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentationController;
use App\Http\Controllers\Admin\LegioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Member\AbsensiController;
use App\Http\Controllers\Member\KegiatanController;
use App\Http\Controllers\Member\RenunganController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Setelah login (dan register) semua orang diarahkan ke 'dashboard',
// lalu dilempar ke area yang sesuai dengan rolenya.
Route::get('/dashboard', function () {
    return redirect()->route(auth()->user()->isAdmin() ? 'admin.dashboard' : 'member.absensi');
})->middleware(['auth', 'verified'])->name('dashboard');

// ===== AREA ANGGOTA =====
Route::middleware(['auth'])->prefix('member')->as('member.')->group(function () {
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi');

    Route::get('/jadwal', [KegiatanController::class, 'index'])->name('jadwal');
    Route::post('/jadwal/{kegiatan}/join', [KegiatanController::class, 'join'])->name('jadwal.join');
    Route::delete('/jadwal/{kegiatan}/join', [KegiatanController::class, 'leave'])->name('jadwal.leave');

    Route::get('/renungan', [RenunganController::class, 'index'])->name('renungan');
    Route::post('/renungan', [RenunganController::class, 'store'])->name('renungan.store');
});

// ===== AREA ADMIN (hanya role admin) =====
Route::middleware(['auth', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    // Dashboard Admin (Edit Konten)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::put('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');

    // Halaman Live Preview Website
    Route::get('/preview', [DashboardController::class, 'preview'])->name('preview');

    // Action Documentation
    Route::post('/documentations', [DocumentationController::class, 'store'])->name('documentations.store');
    Route::delete('/documentations/{documentation}', [DocumentationController::class, 'destroy'])->name('documentations.destroy');
    
    // Action Legio
    Route::post('/legios', [LegioController::class, 'store'])->name('legios.store');
    Route::put('/legios/{legio}', [LegioController::class, 'update'])->name('legios.update');
    Route::delete('/legios/{legio}', [LegioController::class, 'destroy'])->name('legios.destroy');
});

require __DIR__.'/auth.php';
