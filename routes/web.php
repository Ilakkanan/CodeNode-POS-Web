<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

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
    return view('welcome');
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
     Route::get('trashed', [CategoryController::class, 'trashed'])
     ->name('categories.trashed');
    Route::prefix('categories')->group(function () {
        //Route::get('/trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');
        Route::post('/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
        Route::delete('/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');
    });
});