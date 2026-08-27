<?php

use App\Http\Controllers\ProfileController;
<<<<<<< HEAD
use App\Http\Controllers\TenantProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/* Home */
Route::get('/', function () {
    return view('welcome');
});
=======
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
>>>>>>> 3a84dc870484311d5a410cd6e72d5d78faccab33


/* Preview Reset Password (Utility) */
Route::get('/preview/reset-password', function () {
    $request = request();
    $request->merge(['email' => 'admin@flydine.com']);
    return view('auth.reset-password', compact('request'));
});

<<<<<<< HEAD
/* Dashboard Redirect */
=======

/*
|--------------------------------------------------------------------------
| REDIRECT UTAMA SETELAH LOGIN
|--------------------------------------------------------------------------
*/
>>>>>>> 3a84dc870484311d5a410cd6e72d5d78faccab33
Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin_ops' => redirect('/admin/dashboard'),
        'tenant_staff' => redirect('/tenant/dashboard'),
<<<<<<< HEAD
        default => abort(403),
=======
        default => abort(403, 'Akses Tidak Diizinkan')
>>>>>>> 3a84dc870484311d5a410cd6e72d5d78faccab33
    };
})->middleware(['auth', 'verified'])->name('dashboard');

<<<<<<< HEAD
/* Admin */
Route::get('/admin/dashboard', function () {
    abort_unless(auth()->user()->role === 'admin_ops', 403);

    return view('admin.dashboard');
})->middleware('auth')->name('admin.dashboard');

/* Tenant */
Route::middleware('auth')
    ->prefix('tenant')
    ->name('tenant.')
    ->group(function () {

        Route::get('/dashboard', function () {
            abort_unless(auth()->user()->role === 'tenant_staff', 403);

            return view('tenant.dashboard');
        })->name('dashboard');

        Route::get('/orders', function () {
            abort_unless(auth()->user()->role === 'tenant_staff', 403);

            return view('tenant.orders');
        })->name('orders');

        /* Daftar Produk */
        Route::get('/products', function () {
            abort_unless(auth()->user()->role === 'tenant_staff', 403);

            $products = Product::where(
                'tenant_id',
                auth()->user()->tenant_id
            )->latest()->get();

            return view('tenant.products', compact('products'));
        })->name('products');

        /* Form Tambah Produk */
        Route::get(
            '/products/create',
            [TenantProductController::class, 'create']
        )->name('products.create');

        /* Simpan Produk */
        Route::post(
            '/products',
            [TenantProductController::class, 'store']
        )->name('products.store');
    });

/* Profile */
=======

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
>>>>>>> 3a84dc870484311d5a410cd6e72d5d78faccab33
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
