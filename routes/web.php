<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerCatalogController;
use App\Http\Controllers\TenantProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/* Customer */
Route::get('/', [CustomerCatalogController::class, 'index'])->name('customer.menu');
Route::get('/cart', [CustomerCatalogController::class, 'cart'])->name('customer.cart');
Route::get('/tracking', [CustomerCatalogController::class, 'tracking'])->name('customer.tracking');

/* Preview Reset */
Route::get('/preview/reset-password', function () {
    $request = request();
    $request->merge(['email' => 'admin@flydine.com']);
    return view('auth.reset-password', compact('request'));
});

/* Redirect Dashboard */
Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin_ops' => redirect('/admin/dashboard'),
        'tenant_staff' => redirect('/tenant/dashboard'),
        default => abort(403),
    };
})->middleware('auth')->name('dashboard');

/* Admin */
Route::middleware('auth')->prefix('admin')->group(function () {

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

        Route::get('/products', function () {
            abort_unless(auth()->user()->role === 'tenant_staff', 403);

            $products = Product::where(
                'tenant_id',
                auth()->user()->tenant_id
            )->latest()->get();

            return view('tenant.products', compact('products'));
        })->name('products');

        Route::get('/products/create',
            [TenantProductController::class, 'create']
        )->name('products.create');

        Route::post('/products',
            [TenantProductController::class, 'store']
        )->name('products.store');
    });

/* Profile */
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
