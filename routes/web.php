<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PlatformController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;

// Public Pages
Route::get('/', function () {
    return Inertia::render('Landing');
});
Route::get('/features', function () {
    return Inertia::render('Features');
});
Route::get('/pricing', function () {
    return Inertia::render('Pricing');
});

// Authenticated Actions
Route::middleware(['auth'])->group(function () {
    // Current User API (Spatie shared auth prop fallback)
    Route::get('/api/user', function (Request $request) {
        return response()->json($request->user());
    });

    // Page Routes
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware('role:admin|manager|staff')->name('dashboard');

    Route::get('/platform', function () {
        return Inertia::render('SuperAdminDashboard');
    })->middleware('role:super_admin');

    // Products (Web / Inertia)
    Route::get('/products', [ProductController::class, 'index'])->middleware('role:admin|manager');
    Route::post('/products', [ProductController::class, 'store'])->middleware('role:admin|manager');
    Route::put('/products/{product}', [ProductController::class, 'update'])->middleware('role:admin|manager');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->middleware('role:admin|manager');

    // Categories (Web / Inertia)
    Route::get('/categories', [CategoryController::class, 'index'])->middleware('role:admin|manager');
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('role:admin|manager');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->middleware('role:admin|manager');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('role:admin|manager');

    // Warehouses (Web / Inertia)
    Route::get('/warehouses', [WarehouseController::class, 'index'])->middleware('role:admin|manager');
    Route::post('/warehouses', [WarehouseController::class, 'store'])->middleware('role:admin|manager');
    Route::put('/warehouses/{warehouse}', [WarehouseController::class, 'update'])->middleware('role:admin|manager');
    Route::delete('/warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->middleware('role:admin|manager');

    // Simple Render Pages
    Route::get('/suppliers', function () {
        return Inertia::render('Suppliers');
    })->middleware('role:admin|manager');

    Route::get('/orders', function () {
        return Inertia::render('Orders');
    })->middleware('role:admin|manager|staff');

    Route::get('/inventory', function () {
        return Inertia::render('Inventory');
    })->middleware('role:admin|manager|staff');

    Route::get('/purchases', function () {
        return Inertia::render('Purchases');
    })->middleware('role:admin|manager');

    Route::get('/reports', function () {
        return Inertia::render('Reports');
    })->middleware('role:admin|manager');

    Route::get('/payments', function () {
        return Inertia::render('Payments');
    })->middleware('role:admin|manager');

    Route::get('/settings', function () {
        return Inertia::render('Settings');
    })->middleware('role:admin');

    Route::get('/support', function () {
        return Inertia::render('Support');
    });

    Route::get('/sales', function () {
        return Inertia::render('Sales');
    })->middleware('role:admin|manager|staff');

    Route::get('/customers', function () {
        return Inertia::render('Customers');
    })->middleware('role:admin|manager|staff');

    Route::get('/audit-log', function () {
        return Inertia::render('AuditLog');
    })->middleware('role:admin|manager');

    // Team & Roles Management
    Route::get('/roles', [TeamController::class, 'index'])->name('roles.index')->middleware('role:admin');
    Route::post('/api/team', [TeamController::class, 'store'])->middleware('role:admin');
    Route::put('/api/team/{id}', [TeamController::class, 'update'])->middleware('role:admin');
    Route::delete('/api/team/{id}', [TeamController::class, 'destroy'])->middleware('role:admin');
    
    // Core Inventory API Endpoints (Fallback / REST compatibility)
    Route::apiResource('/api/categories', CategoryController::class);
    Route::apiResource('/api/warehouses', WarehouseController::class);
    Route::apiResource('/api/products', ProductController::class);
    
    // Super Admin API
    Route::get('/api/platform/metrics', [PlatformController::class, 'dashboardMetrics']);
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
