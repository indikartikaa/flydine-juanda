<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerCatalogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RUTE PENUMPANG (PUBLIK)
|--------------------------------------------------------------------------
*/
// Menggunakan Controller untuk halaman depan pelanggan
Route::get('/', [CustomerCatalogController::class, 'index'])->name('customer.menu');
Route::get('/cart', [CustomerCatalogController::class, 'cart'])->name('customer.cart');
Route::get('/tracking', [CustomerCatalogController::class, 'tracking'])->name('customer.tracking');


/* Preview Reset Password (Utility) */
Route::get('/preview/reset-password', function () {
    $request = request();
    $request->merge(['email' => 'admin@flydine.com']);
    return view('auth.reset-password', compact('request'));
});


/*
|--------------------------------------------------------------------------
| REDIRECT UTAMA SETELAH LOGIN
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin_ops' => redirect('/admin/dashboard'),
        'tenant_staff' => redirect('/tenant/dashboard'),
        default => abort(403, 'Akses Tidak Diizinkan')
    };
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| RUTE ADMIN OPERASIONAL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    
    Route::get('/dashboard', function () {
        abort_unless(auth()->user()->role === 'admin_ops', 403);
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/tenants-management', function () {
        abort_unless(auth()->user()->role === 'admin_ops', 403);
        return view('admin.tenants-management');
    });

    Route::get('/complaints', function () {
        abort_unless(auth()->user()->role === 'admin_ops', 403);
        return view('admin.complaints');
    });
    
});


/*
|--------------------------------------------------------------------------
| RUTE TENANT RESTORAN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('tenant')->group(function () {
    
    Route::get('/dashboard', function () {
        abort_unless(auth()->user()->role === 'tenant_staff', 403);
        return view('tenant.dashboard');
    });

    Route::get('/orders', function () {
        abort_unless(auth()->user()->role === 'tenant_staff', 403);
        return view('tenant.orders');
    });

    Route::get('/products', function () {
        abort_unless(auth()->user()->role === 'tenant_staff', 403);
        return view('tenant.products');
    });

    Route::get('/products/create', function () {
        abort_unless(auth()->user()->role === 'tenant_staff', 403);
        return view('tenant.create-product');
    });
    
});


/*
|--------------------------------------------------------------------------
| RUTE PROFIL & AUTH BAWAAN BREEZE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';