<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\reportController;
use App\Http\Controllers\searchController;
use App\Http\Middleware\CheckUserCategory;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LogisticsController;
use App\Http\Controllers\StockSortController;
use App\Http\Controllers\UsersSortController;
use App\Http\Controllers\WarehouseOrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard')
->middleware(['auth', 'verified']);



Route::middleware('auth')->group(function () {
    Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified', CheckUserCategory::class])->group(function () {
  
    // Stock Management

    Route::get('/stock-management', [ItemController::class, 'index'])
        ->name('stock-management');
  
    Route::get('/stock-management/sort', [StockSortController::class, 'sort'])
        ->name('stock-management.sort');

    Route::get('/stock-management/order/sort', [StockSortController::class, 'sortOrder'])
        ->name('stock-management.sortOrder');

    Route::get('/stock-management/order', [ItemController::class, 'chosenItems'])
        ->name('stock-management.chosenItems');

    Route::post('/stock-management/store', [WarehouseOrderController::class, 'store'])
        ->name('stock-management.store');

    Route::get('/stock-management/overview', [WarehouseOrderController::class, 'toOverview'])
        ->name('stock-management.toOverview');
 
    Route::any('/stock-management/order-history', [dashboardController::class, 'ShowOrderHistory'])
        ->name('stock-management.ShowOrderHistory');

    Route::get('/stock-management/search', [searchController::class, 'search'])
        ->name('stock-management.search');

    // Sales

    Route::get('/sales', [SalesController::class, 'index'])
    ->name('sales');

    // Logistics

    Route::get('/logistics', [LogisticsController::class, 'index'])
    ->name('logistics');

    // Inventory

    Route::get('/inventory', [InventoryController::class, 'index'])
    ->name('inventory');
    // Admin

    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin');

    Route::get('/admin/sort', [UsersSortController::class, 'sort'])
        ->name('admin.sort');

    Route::post('/admin/user', [AdminController::class, 'selectedUser'])
        ->name('admin.selectedUser');

    Route::post('/admin/update-settings', [AdminController::class, 'updateSettings'])
        ->name('admin.updateSettings');

    Route::any('/admin/update-permissions', [AdminController::class, 'updatePermissions'])
        ->name('admin.updatePermissions');

    Route::any('/admin/toggle-activation', [AdminController::class, 'toggleAccountActivation'])
        ->name('admin.toggleAccountActivation');

    Route::get('/admin/new-user', [AdminController::class, 'createNewUser'])
        ->name('admin.createNewUser');

    Route::any('/admin/add-new-user', [AdminController::class, 'addNewUser'])
        ->name('admin.addNewUser');
});