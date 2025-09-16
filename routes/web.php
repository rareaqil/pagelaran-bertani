<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;

Route::get('/', function () {
    // return view('frontend.welcome');
    return view('frontend.home');
});

Route::get('/dashboard', function () {
    return view('frontend.welcome');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/orders/{order}', [OrderController::class, 'showView'])->name('orders.showView');
    // Route::get('/cart', [CartController::class, 'index']);
    // Route::post('/cart/add', [CartController::class, 'addItem']);
    // Route::post('/cart/coupon', [CartController::class, 'applyCoupon']);
    // Route::delete('/cart/clear', [CartController::class, 'clear']);

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'showPage'])->name('cart.show');
    Route::post('/add', [CartController::class, 'addItem'])->name('cart.add');
    Route::post('/coupon', [CartController::class, 'applyVoucher'])->name('cart.coupon');
    Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::delete('/item/remove/{id}', [CartController::class, 'removeItem'])->name('cart.item.remove');

    Route::patch('/item/{id}', [CartController::class, 'updateItemQty'])->name('cart.item.update');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});


});
// Route::group(['middleware'=>'role:super admin,admin','prefix'=>'car', 'as'=>'car.'],function () {
//     Route::group(['prefix'=>'car-type', 'as'=>'car-type.'],function () {
//          return view('dashboard');
//     });
// });



// Super Admin
// Super Admin
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/superadmin/dashboard', function () {
        return 'Super Admin Dashboard';
    })->name('superadmin.dashboard');
});

// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// User
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('dashboard');
    })->name('user.dashboard');
});


Route::middleware(['auth', 'role:super_admin,admin'])->prefix('backend')->group(function() {
    Route::resource('users', UserController::class);
    Route::resource('posts', PostController::class);
    
});


use UniSharp\LaravelFilemanager\Lfm;

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web','auth']], function () {
    Lfm::routes();
});


require __DIR__.'/auth.php';

require __DIR__.'/api.php';


