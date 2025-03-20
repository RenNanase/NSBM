<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WardController;
use App\Http\Controllers\CensusController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Authentication required routes
Route::middleware('auth')->group(function () {
    // Ward selection
    Route::get('/ward/select', [AuthController::class, 'showWardSelection'])->name('ward.select');
    Route::post('/ward/select', [AuthController::class, 'selectWard'])->name('ward.select.post');

    // Ward access required routes
    Route::middleware('ward.access')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Ward entries
        Route::get('/ward/entry/create', [WardController::class, 'createEntry'])->name('ward.entry.create');
        Route::post('/ward/entry/store', [WardController::class, 'storeEntry'])->name('ward.entry.store');

        // Census entries
        Route::get('/census/create', [CensusController::class, 'create'])->name('census.create');
        Route::post('/census/store', [CensusController::class, 'store'])->name('census.store');

        // Admin only routes
        Route::middleware(['auth', 'admin'])->group(function () {
            Route::get('/ward/entry/{entry}/edit', [WardController::class, 'editEntry'])->name('ward.entry.edit');
            Route::put('/ward/entry/{entry}', [WardController::class, 'updateEntry'])->name('ward.entry.update');

            Route::get('/census/{entry}/edit', [CensusController::class, 'edit'])->name('census.edit');
            Route::put('/census/{entry}', [CensusController::class, 'update'])->name('census.update');
        });
    });
});
