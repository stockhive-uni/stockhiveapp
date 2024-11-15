<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WarehouseOrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/stock-management', function() {
    return view('stock-management');
})->middleware(['auth', 'verified'])->name('stock-management');

Route::get('/sales', function () {
    return view('sales');
})->middleware(['auth', 'verified'])->name('sales');

Route::get('/logistics', function () {
    return view('logistics');
})->middleware(['auth', 'verified'])->name('logistics');

Route::get('/inventory', function () {
    return view('inventory');
})->middleware(['auth', 'verified'])->name('inventory');

Route::get('/admin', function () {
    return view('admin');
})->middleware(['auth', 'verified'])->name('admin');

Route::middleware('auth')->group(function () {
    Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/stock', [ItemController::class, 'index'])->name('stock.index');
Route::post('/stock/order', [ItemController::class, 'chosenItems'])->name('stock.chosenItems');

Route::post('/stock/store', [WarehouseOrderController::class, 'store'])->name('WarehouseOrder.store');