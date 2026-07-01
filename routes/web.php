<?php

use App\Http\Controllers\ProfileController;
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
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Fallback route for all frontend routes (Vue Router takes over client-side)
Route::fallback(function () {
    return view('welcome');
});
