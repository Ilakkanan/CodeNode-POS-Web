<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockEntryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LowStockNotification;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';



//dashboard
Route::get('/home', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');


//============================ Categories 2025.06.27 ========================================
// Categories routes
Route::middleware(['auth', 'admin'])->group(function () {
        Route::resource('categories', CategoryController::class);
        // Trashed categories routes
        Route::get('categoriestrashed', [CategoryController::class, 'trashed'])
        ->name('categories.trashed');
        Route::prefix('categories')->group(function () {
        //Route::get('/trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');
        Route::post('/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
        Route::delete('/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');
    });
});

//============================ brand 2025.06.28 ========================================
Route::middleware(['auth', 'admin'])->group(function () {
    // Brands Routes
    Route::resource('brands', BrandController::class);
    Route::get('brandstrashed', [BrandController::class, 'trashed'])->name('brands.trashed');
    Route::prefix('brands')->group(function () {
       
        Route::post('/{id}/restore', [BrandController::class, 'restore'])->name('brands.restore');
        Route::delete('/{id}/force-delete', [BrandController::class, 'forceDelete'])->name('brands.force-delete');
    });
});

//============================ Vendors 2025.06.29 ========================================
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('vendors', VendorController::class);
    Route::get('vendorstrashed', [VendorController::class, 'trashed'])->name('vendors.trashed');
    Route::prefix('vendors')->group(function () {
        Route::post('/{id}/restore', [VendorController::class, 'restore'])->name('vendors.restore');
        Route::delete('/{id}/force-delete', [VendorController::class, 'forceDelete'])->name('vendors.force-delete');
    });
});

//============================ products 2025.07.03 ========================================
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::get('Productstrashed', [ProductController::class, 'trashed'])->name('products.trashed');
    
    Route::prefix('products')->group(function () {
        
        Route::post('/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
        Route::delete('/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');
        Route::get('/search', [ProductController::class, 'search'])->name('products.search');
    });
});

//============================ Stock Entries ========================================
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('stock-entries', StockEntryController::class);
    Route::get('stock-entriestrashed', [StockEntryController::class, 'trashed'])->name('stock-entries.trashed');
    Route::prefix('stock-entries')->group(function () {
        
        Route::post('/trashed/{id}/restore', [StockEntryController::class, 'restore'])->name('stock-entries.restore');
        Route::delete('/trashed/{id}/force-delete', [StockEntryController::class, 'forceDelete'])->name('stock-entries.force-delete');
    });
});

//============================ Inventory ========================================
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('inventory', InventoryController::class)->only(['index', 'edit', 'update']);
});

// Notification routes
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');