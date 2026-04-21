<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\OrderHistoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->middleware('check.account.status')->group(function () {


    Route::get('/account/orders', [OrderHistoryController::class, 'index'])->name('account.orders');
    Route::post('/account/orders/{order}/cancel', [OrderHistoryController::class, 'cancel'])->name('account.orders.cancel');
});

// ==========================================
// 1. TRANG CHỦ (Cho khách)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');

//Product
Route::get('/product', [HomeController::class, 'products'])->name('product');
Route::get('/product/filter', [HomeController::class, 'filter_product'])->name('product.filter_product');
Route::get('/product/{slug}', [HomeController::class, 'productDetail'])->name('product.detail');

//Brand
Route::get('/brand', [HomeController::class, 'brands'])->name('brand');

//cart
Route::get('/cart', [CartController::class, 'index'])->middleware('auth')->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->middleware('auth')->name('cart.add');
Route::delete('/cart/delete/{cartItem}', [CartController::class, 'destroy'])->name('cart.delete');

//checkout
Route::post('/checkout', [CheckoutController::class, 'process'])->middleware('auth')->name('checkout.process');
// ==========================================
// 2. TRANG ADMIN (Cho quản trị)
// ==========================================
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    
    // Trang tổng quan admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Quản lý danh mục
    Route::resource('categories', CategoryController::class);

    Route::post('sizes/store', [SizeController::class, 'store'])->name('sizes.store');
    Route::delete('sizes/delete/{id}', [SizeController::class, 'destroy'])->name('sizes.delete');

    //Quan ly thuong hieu
    Route::resource('brands', BrandController::class);

    //Quan ly san pham
    Route::resource('products', ProductController::class);
    Route::post('/products/check-slug', [ProductController::class, 'checkSlug'])->name('products.checkSlug');

    //Quan ly khach hang
    Route::resource('customers', CustomerController::class);

    //QUan ly don hang
    Route::resource('orders', OrderController::class);
});
    
