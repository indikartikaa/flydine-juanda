<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerCatalogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RUTE PENUMPANG (PUBLIK / TANPA LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/', [CustomerCatalogController::class, 'index'])->name('catalog.index');
Route::get('/cart', [CustomerCatalogController::class, 'cart'])->name('customer.cart');
Route::get('/tracking', [CustomerCatalogController::class, 'tracking'])->name('customer.tracking');


/*
|--------------------------------------------------------------------------
| RUTE TENANT RESTORAN (DIKUNCI DENGAN LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/tenant/dashboard', function () {
        return view('tenant.dashboard');
    });
    
    Route::get('/tenant/orders', function () {
        return view('tenant.orders');
    });
    
    Route::get('/tenant/products', function () {
        return view('tenant.products');
    });
    
    Route::get('/tenant/products/create', function () {
        return view('tenant.create-product');
    });

});


/*
|--------------------------------------------------------------------------
| RUTE BAWAAN LARAVEL BREEZE (SISTEM PROFIL)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
    |--------------------------------------------------------------------------
    | RUTE ADMIN OPERASIONAL
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    });

    Route::get('/admin/tenants-management', function () {
        return view('admin.tenants-management');
    });

    Route::get('/admin/complaints', function () {
        return view('admin.complaints');
    });

require __DIR__.'/auth.php';