<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WardController;
use App\Http\Controllers\CensusController;
use App\Http\Controllers\DeliveryController;

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

        // Delivery entries - Only accessible to maternity ward staff
        Route::middleware('maternity.access')->group(function () {
            Route::get('/delivery', [DeliveryController::class, 'index'])->name('delivery.index');
            Route::get('/delivery/create', [DeliveryController::class, 'create'])->name('delivery.create');
            Route::post('/delivery', [DeliveryController::class, 'store'])->name('delivery.store');
            Route::get('/delivery/{delivery}', [DeliveryController::class, 'show'])->name('delivery.show');
            Route::get('/delivery/{delivery}/edit', [DeliveryController::class, 'edit'])->name('delivery.edit');
            Route::put('/delivery/{delivery}', [DeliveryController::class, 'update'])->name('delivery.update');

            // Admin only delivery route
            Route::middleware(['auth', 'admin'])->group(function () {
                Route::delete('/delivery/{delivery}', [DeliveryController::class, 'destroy'])->name('delivery.destroy');
            });
        });

        // Admin only routes
        Route::middleware(['auth', 'admin'])->group(function () {
            Route::get('/ward/entry/{entry}/edit', [WardController::class, 'editEntry'])->name('ward.entry.edit');
            Route::put('/ward/entry/{entry}', [WardController::class, 'updateEntry'])->name('ward.entry.update');

            Route::get('/census/{entry}/edit', [CensusController::class, 'edit'])->name('census.edit');
            Route::put('/census/{entry}', [CensusController::class, 'update'])->name('census.update');
        });
    });

    // Support & Documentation Routes
    Route::get('/access-control-guide', function() {
        return view('support.access-control');
    })->name('support.access-control');
});

// Access Denied Routes
Route::get('/delivery-access-denied', function() {
    return view('errors.delivery-access-denied');
})->name('delivery.access.denied');

Route::get('/admin-access-denied', function() {
    return view('errors.admin-access-denied');
})->name('admin.access.denied');

Route::get('/ward-access-denied', function() {
    return view('errors.ward-access-denied');
})->name('ward.access.denied');
