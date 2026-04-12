<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SizeController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('frontend.dashboard.index');
    })->name('dashboard');
});

// ==========================================
// 1. TRANG CHỦ (Cho khách)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product', [HomeController::class, 'products'])->name('product');
Route::get('/product/{slug}', [HomeController::class, 'productDetail'])->name('product.detail');


// ==========================================
// 2. TRANG ADMIN (Cho quản trị)
// ==========================================
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    
    // Trang tổng quan admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý sản phẩm, danh mục
    Route::resource('products', ProductController::class);
    Route::delete('products/gallery/{id}', [ProductController::class, 'deleteImage'])->name('products.deleteImage');
    Route::get('get-sizes-by-category/{id}', [ProductController::class, 'getSizesByCategory'])->name('products.getSizes');

    Route::resource('categories', CategoryController::class);
    Route::post('sizes/store', [SizeController::class, 'store'])->name('sizes.store');

});
    
