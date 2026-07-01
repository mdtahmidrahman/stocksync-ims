<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PlatformController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

// Authenticated API / Web Actions
Route::middleware('auth')->group(function () {
    Route::get('/api/user', function (Request $request) {
        return response()->json($request->user());
    });

    // Team Management API
    Route::get('/api/team', [TeamController::class, 'index']);
    Route::post('/api/team', [TeamController::class, 'store'])->middleware('role:admin');
    Route::put('/api/team/{id}', [TeamController::class, 'update'])->middleware('role:admin');
    Route::delete('/api/team/{id}', [TeamController::class, 'destroy'])->middleware('role:admin');
    
    // Core Inventory APIs (Secured by TenantScope)
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

// Fallback route for all frontend routes (Vue Router takes over client-side)
Route::fallback(function () {
    return view('welcome');
});
