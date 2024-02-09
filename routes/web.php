<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
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


Route::get('/', [ProductController::class, 'index'])->name('products.index');

Route::post('{product}/add-stock', [ProductController::class, 'addStock'])->name('products.addStock');
Route::get('/products/add-stock', [ProductController::class, 'showAddStockForm'])->name('products.showAddStockForm');
Route::post('/products/add-stock', [ProductController::class, 'addStock'])->name('products.addStock');
Route::post('/products/report', [ProductController::class, 'report'])->name('products.report');
Route::post('//{product}/purchase', [ProductController::class, 'purchase'])->name('product.purchase');
Route::post('/products/purchase', [ProductController::class, 'purchase'])->name('product.purchase');



Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    
Route::get('/products/', [ProductController::class, 'crud'])->name('products.crud');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::get('/categories/', [CategoryController::class, 'crud'])->name('categories.crud');
Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    
});
