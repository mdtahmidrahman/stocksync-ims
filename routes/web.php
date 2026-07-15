<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\InvoiceController;
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

    Route::get('/platform', [PlatformController::class, 'dashboardMetrics'])->middleware('role:super_admin');

    // Products (Web / Inertia)
    Route::get('/products/export', [ProductController::class, 'export'])->middleware('role:admin|manager');
    Route::post('/products/import', [ProductController::class, 'import'])->middleware('role:admin|manager');
    Route::get('/products', [ProductController::class, 'index'])->middleware('role:admin|manager');
    Route::post('/products', [ProductController::class, 'store'])->middleware('role:admin|manager');
    Route::put('/products/{product}', [ProductController::class, 'update'])->middleware('role:admin|manager');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->middleware('role:admin|manager');

    // Categories (Web / Inertia)
    Route::get('/categories/export', [CategoryController::class, 'export'])->middleware('role:admin|manager');
    Route::post('/categories/import', [CategoryController::class, 'import'])->middleware('role:admin|manager');
    Route::get('/categories', [CategoryController::class, 'index'])->middleware('role:admin|manager');
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('role:admin|manager');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->middleware('role:admin|manager');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->middleware('role:admin|manager');

    // Warehouses (Web / Inertia)
    Route::get('/warehouses/export', [WarehouseController::class, 'export'])->middleware('role:admin|manager');
    Route::post('/warehouses/import', [WarehouseController::class, 'import'])->middleware('role:admin|manager');
    Route::get('/warehouses', [WarehouseController::class, 'index'])->middleware('role:admin|manager');
    Route::post('/warehouses', [WarehouseController::class, 'store'])->middleware('role:admin|manager');
    Route::put('/warehouses/{warehouse}', [WarehouseController::class, 'update'])->middleware('role:admin|manager');
    Route::delete('/warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->middleware('role:admin|manager');

    // Suppliers
    Route::get('/suppliers/export', [SupplierController::class, 'export'])->middleware('role:admin|manager');
    Route::get('/suppliers', [SupplierController::class, 'index'])->middleware('role:admin|manager');
    Route::post('/suppliers', [SupplierController::class, 'store'])->middleware('role:admin|manager');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->middleware('role:admin|manager');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->middleware('role:admin|manager');

    Route::get('/orders', [OrderController::class, 'index'])->middleware('role:admin|manager|staff');
    Route::post('/orders', [OrderController::class, 'store'])->middleware('role:admin|manager|staff');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->middleware('role:admin|manager|staff');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->middleware('role:admin|manager|staff');

    Route::get('/invoices/sale/{sale}', [InvoiceController::class, 'saleInvoice'])->name('invoice.sale');
    Route::get('/invoices/order/{order}', [InvoiceController::class, 'orderInvoice'])->name('invoice.order');

    Route::get('/inventory', function () {
        return Inertia::render('Inventory');
    })->middleware('role:admin|manager|staff');

    Route::get('/purchases', [PurchaseController::class, 'index'])->middleware('role:admin|manager');
    Route::post('/purchases', [PurchaseController::class, 'store'])->middleware('role:admin|manager');
    Route::delete('/purchases/{purchase}', [PurchaseController::class, 'destroy'])->middleware('role:admin|manager');

    Route::get('/reports', function () {
        return Inertia::render('Reports');
    })->middleware('role:admin|manager');

    Route::get('/payments', [PaymentController::class, 'index'])->middleware('role:admin|manager');
    Route::post('/payments', [PaymentController::class, 'store'])->middleware('role:admin|manager|staff');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index')->middleware('role:admin|manager');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update')->middleware('role:admin|manager');

    Route::get('/support', function () {
        return Inertia::render('Support');
    });

    Route::get('/sales', [SaleController::class, 'index'])->middleware('role:admin|manager|staff');
    Route::post('/sales', [SaleController::class, 'store'])->middleware('role:admin|manager|staff');
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->middleware('role:admin|manager|staff');

    // Customers
    Route::get('/customers/export', [CustomerController::class, 'export'])->middleware('role:admin|manager|staff');
    Route::get('/customers', [CustomerController::class, 'index'])->middleware('role:admin|manager|staff');
    Route::post('/customers', [CustomerController::class, 'store'])->middleware('role:admin|manager|staff');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->middleware('role:admin|manager|staff');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->middleware('role:admin|manager|staff');

    Route::get('/audit-log', function () {
        return Inertia::render('AuditLog');
    })->middleware('role:admin|manager');

    // Team & Roles Management
    Route::get('/roles', [TeamController::class, 'index'])->name('roles.index')->middleware('role:admin');
    Route::post('/team', [TeamController::class, 'store'])->middleware('role:admin');
    Route::put('/team/{id}', [TeamController::class, 'update'])->middleware('role:admin');
    Route::delete('/team/{id}', [TeamController::class, 'destroy'])->middleware('role:admin');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
