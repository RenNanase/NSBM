<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WardController;
use App\Http\Controllers\CensusController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\EmergencyDashboardController;
use App\Http\Controllers\InfectiousDiseaseController;
use App\Http\Controllers\EmergencyRoomBORController;

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

    // Apply Emergency Department middleware to check and redirect to the proper dashboard
    Route::middleware('emergency.department')->group(function () {
        // Regular dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    // Emergency Department Dashboard
    Route::get('/emergency/dashboard', [EmergencyDashboardController::class, 'index'])->name('emergency.dashboard');

    // Infectious Disease routes - Only for Emergency Department staff
    Route::middleware('ward.access')->group(function () {
        // Infectious Disease routes
        Route::get('/infectious-diseases', [InfectiousDiseaseController::class, 'index'])->name('infectious-diseases.index');
        Route::get('/infectious-diseases/create', [InfectiousDiseaseController::class, 'create'])->name('infectious-diseases.create');
        Route::post('/infectious-diseases', [InfectiousDiseaseController::class, 'store'])->name('infectious-diseases.store');
        Route::get('/infectious-diseases/{infectiousDisease}', [InfectiousDiseaseController::class, 'show'])->name('infectious-diseases.show');
        Route::get('/infectious-diseases/{infectiousDisease}/edit', [InfectiousDiseaseController::class, 'edit'])->name('infectious-diseases.edit');
        Route::put('/infectious-diseases/{infectiousDisease}', [InfectiousDiseaseController::class, 'update'])->name('infectious-diseases.update');
        Route::delete('/infectious-diseases/{infectiousDisease}', [InfectiousDiseaseController::class, 'destroy'])->name('infectious-diseases.destroy');
        Route::get('/infectious-diseases-report', [InfectiousDiseaseController::class, 'report'])->name('infectious-diseases.report');

        // Emergency Room BOR routes - Only for Emergency Department staff
        Route::get('/emergency/bor', [EmergencyRoomBORController::class, 'index'])->name('emergency.bor.index');
        Route::get('/emergency/bor/create', [EmergencyRoomBORController::class, 'create'])->name('emergency.bor.create');
        Route::post('/emergency/bor', [EmergencyRoomBORController::class, 'store'])->name('emergency.bor.store');
        Route::get('/emergency/bor/{id}', [EmergencyRoomBORController::class, 'show'])->name('emergency.bor.show');
        Route::get('/emergency/bor/{id}/edit', [EmergencyRoomBORController::class, 'edit'])->name('emergency.bor.edit');
        Route::put('/emergency/bor/{id}', [EmergencyRoomBORController::class, 'update'])->name('emergency.bor.update');
        Route::delete('/emergency/bor/{id}', [EmergencyRoomBORController::class, 'destroy'])->name('emergency.bor.destroy');
        Route::get('/emergency/bor/history', [EmergencyRoomBORController::class, 'history'])->name('emergency.bor.history');
        Route::get('/emergency/bor/report', [EmergencyRoomBORController::class, 'report'])->name('emergency.bor.report');
    });

    // Non-Emergency Department routes (Ward Entry and Census)
    Route::middleware('ward.access')->group(function () {
        // Ward entries and Census - Only for non-emergency departments
        Route::middleware('non.emergency')->group(function() {
            // Ward entries
            Route::get('/ward/entry/create', [WardController::class, 'createEntry'])->name('ward.entry.create');
            Route::post('/ward/entry/store', [WardController::class, 'storeEntry'])->name('ward.entry.store');

            // Census entries
            Route::get('/census/create', [CensusController::class, 'create'])->name('census.create');
            Route::post('/census/store', [CensusController::class, 'store'])->name('census.store');
        });

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
