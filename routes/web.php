<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
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
// Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    
//     // Trang tổng quan admin
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//     // Quản lý sản phẩm, danh mục, biến thể
//     Route::resource('products', ProductController::class);
//     Route::resource('categories', ProductCategoryController::class);
    
// });
    
