<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->prefix('admin')->as('admin.')->group(function () {
    // Halaman Edit Website (Dashboard Utama)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::put('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');

    // Action Documentation
    Route::post('/documentations', [DocumentationController::class, 'store'])->name('documentations.store');
    Route::delete('/documentations/{documentation}', [DocumentationController::class, 'destroy'])->name('documentations.destroy');
});

require __DIR__.'/auth.php';
