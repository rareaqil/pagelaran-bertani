<?php

use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\LearnController;
use App\Http\Controllers\ContactController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\FruitTypeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\VoucherController;

/*
|--------------------------------------------------------------------------
| Public Routes (Frontend)
|--------------------------------------------------------------------------
*/
Route::get('/', [ProductController::class, 'home'])->name('home');
Route::get('/order-product', [OrderProductController::class, 'index'])->name('order.product');
Route::get('/contact-us', [FrontendController::class, 'contact'])->name('contact.us');
Route::get('/order-history', [OrderController::class, 'OrderHistory'])->name('order.history');
Route::get('/learn', [LearnController::class, 'index'])->name('learn.index');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Stock Management
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/', [StockMovementController::class, 'index'])->name('index');
        Route::post('/hold', [StockMovementController::class, 'hold'])->name('hold');
        Route::post('/confirm-payment/{holdId}', [StockMovementController::class, 'confirmPayment'])->name('confirmPayment');
        Route::post('/cancel-hold/{holdId}', [StockMovementController::class, 'cancelHold'])->name('cancelHold');
        Route::post('/add', [StockMovementController::class, 'addStock'])->name('add');
        Route::post('/min', [StockMovementController::class, 'minStock'])->name('min');
    });

    // Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'showPage'])->name('show');
        Route::post('/add', [CartController::class, 'addItem'])->name('add');
        Route::post('/coupon', [CartController::class, 'applyVoucher'])->name('coupon');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::delete('/item/remove/{id}', [CartController::class, 'removeItem'])->name('item.remove');
        Route::patch('/item/{id}', [CartController::class, 'updateItemQty'])->name('item.update');
        Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
    });
});

/*
|--------------------------------------------------------------------------
| Role-based Dashboards
|--------------------------------------------------------------------------
*/
// Super Admin
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/superadmin/dashboard', fn () => 'Super Admin Dashboard')
        ->name('superadmin.dashboard');
});

// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/backend/dashboard', fn () => view('dashboard'))->name('dashboard');
});

// User
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', fn () => view('dashboard'))->name('user.dashboard');
    Route::get('/orders/{order}', [OrderController::class, 'showView'])->name('orders.showUserView');
});

/*
|--------------------------------------------------------------------------
| Backend (Super Admin & Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin,admin'])
    ->prefix('backend')
    ->group(function () {

        // Users, Posts, Products (resource routes)
        Route::resource('users', UserController::class);
        Route::resource('posts', PostController::class);
        Route::resource('products', ProductController::class);

        // Fruit Types
        Route::prefix('fruit-types')->name('fruit-types.')->group(function () {
            Route::get('/', [FruitTypeController::class, 'index'])->name('index');
            Route::post('/store', [FruitTypeController::class, 'store'])->name('store');
            Route::post('/{fruitType}/toggle', [FruitTypeController::class, 'toggle'])->name('toggle');
            Route::delete('/{fruitType}', [FruitTypeController::class, 'destroy'])->name('destroy');
        });

        // Orders
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'indexView'])->name('indexView');
            Route::get('/{order}', [OrderController::class, 'showView'])->name('showView');
            Route::post('/{order}/set-shipment', [OrderController::class, 'setShipment'])->name('setShipment');
            Route::post('/{order}/confirm-received', [OrderController::class, 'confirmReceived'])->name('confirmReceived');
            Route::patch('/{order}/cancel', [OrderController::class, 'orderReversal'])->name('orderReversal');
        });

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

        // Vouchers
        Route::prefix('vouchers')->name('vouchers.')->group(function () {
            Route::get('/', [VoucherController::class, 'index'])->name('index');
            Route::post('/store', [VoucherController::class, 'store'])->name('store');
            Route::post('/{voucher}/toggle', [VoucherController::class, 'toggle'])->name('toggle');
            Route::delete('/{voucher}', [VoucherController::class, 'destroy'])->name('destroy');
        });
    });

/*
|--------------------------------------------------------------------------
| File Manager
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web','auth']], function () {
    Lfm::routes();
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
require __DIR__ . '/api.php';
