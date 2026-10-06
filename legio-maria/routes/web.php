<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\AnnouncementController;
use Illuminate\Support\Facades\Route;

// Kalau memakai Laravel Breeze, bungkus semua route ini dengan middleware('auth')
// contoh: Route::middleware('auth')->group(function () { ...isi di bawah... });

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('members', MemberController::class)->except('show');

Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
Route::get('attendance/{meeting}/mark', [AttendanceController::class, 'mark'])->name('attendance.mark');
Route::post('attendance/{meeting}/mark', [AttendanceController::class, 'saveMark'])->name('attendance.saveMark');

Route::get('visits', [VisitController::class, 'index'])->name('visits.index');
Route::get('visits/create', [VisitController::class, 'create'])->name('visits.create');
Route::post('visits', [VisitController::class, 'store'])->name('visits.store');
Route::get('visits/{visitRequest}/schedule', [VisitController::class, 'schedule'])->name('visits.schedule');
Route::post('visits/{visitRequest}/schedule', [VisitController::class, 'storeSchedule'])->name('visits.storeSchedule');

Route::get('cash', [CashController::class, 'index'])->name('cash.index');
Route::get('cash/create', [CashController::class, 'create'])->name('cash.create');
Route::post('cash', [CashController::class, 'store'])->name('cash.store');
Route::delete('cash/{cash}', [CashController::class, 'destroy'])->name('cash.destroy');

Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
Route::post('announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
Route::delete('announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
