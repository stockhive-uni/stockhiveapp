<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WarehouseOrderController;
use App\Http\Controllers\StockSortController;
use App\Http\Middleware\CheckUserCategory;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Sales, Logistics, Inventory and Admin are currently files. When you want to start implementing them properly, create a controller and folder for them.

Route::middleware(['auth', 'verified', CheckUserCategory::class])->group(function () {
  
    // Stock Management

    Route::get('/stock-management', [ItemController::class, 'index'])
        ->name('stock-management');
  
    Route::get('/stock-management/sort', [StockSortController::class, 'sort'])
        ->name('stock-management.sort');

    Route::post('/stock-management/order', [ItemController::class, 'chosenItems'])
        ->name('stock-management.chosenItems');

    Route::post('/stock-management/store', [WarehouseOrderController::class, 'store'])
        ->name('stock-management.store');

    // Sales

    Route::get('/sales', function () {
        return view('sales');
    })->name('sales');

    // Logistics

    Route::get('/logistics', function () {
        return view('logistics');
    })->name('logistics');

    // Inventory

    Route::get('/inventory', function () {
        return view('inventory');
    })->name('inventory');

    // Admin

    Route::get('/admin', function () {
        return view('admin');
    })->name('admin');
});