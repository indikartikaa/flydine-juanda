<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerCatalogController;
use App\Http\Controllers\TenantProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/* Customer */
Route::get('/', [CustomerCatalogController::class, 'index'])->name('customer.menu');
Route::get('/menu/{tenant}', [CustomerCatalogController::class, 'show'])->name('customer.tenant.show');
Route::get('/cart', [CustomerCatalogController::class, 'cart'])->name('customer.cart');
Route::post('/cart/add', [CustomerCatalogController::class, 'addToCart'])->name('customer.cart.add');
Route::post('/cart/update', [CustomerCatalogController::class, 'updateCart'])->name('customer.cart.update');
Route::post('/cart/clear', [CustomerCatalogController::class, 'clearCart'])->name('customer.cart.clear');
Route::post('/checkout', [CustomerCatalogController::class, 'checkout'])->name('customer.checkout');
Route::get('/tracking', [CustomerCatalogController::class, 'tracking'])->name('customer.tracking');
Route::get('/tracking/{order}/status', [CustomerCatalogController::class, 'checkStatus'])->name('customer.tracking.status');
Route::post('/tracking/pay', [CustomerCatalogController::class, 'simulatePayment'])->name('customer.simulate_payment');
Route::get('/history', [CustomerCatalogController::class, 'history'])->name('customer.history');
Route::post('/faq/complaint', [CustomerCatalogController::class, 'storeComplaint'])->name('customer.complaint');

/* Static Pages */
Route::view('/cara-pesan', 'customer.pages.cara-pesan')->name('page.cara-pesan');
Route::view('/faq', 'customer.pages.faq')->name('page.faq');
Route::view('/syarat-ketentuan', 'customer.pages.terms')->name('page.terms');
Route::view('/kebijakan-privasi', 'customer.pages.privacy')->name('page.privacy');
Route::view('/daftar-tenant', 'customer.pages.daftar-tenant')->name('page.daftar-tenant');
Route::view('/promosi', 'customer.pages.promosi')->name('page.promosi');

/* Preview Reset Password */
Route::get('/preview/reset-password', function () {
    $request = request();
    $request->merge(['email' => 'admin@flydine.com']);

    return view('auth.reset-password', compact('request'));
});

/* Dashboard */
Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin_ops' => redirect('/admin/dashboard'),
        'tenant_staff' => redirect('/tenant/dashboard'),
        default => abort(403),
    };
})->middleware('auth')->name('dashboard');

/* Admin */
Route::middleware('auth')->prefix('admin')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/tenants-management', [\App\Http\Controllers\AdminController::class, 'tenantsManagement']);

    Route::get('/complaints', function () {
        abort_unless(auth()->user()->role === 'admin_ops', 403);
        
        $complaints = \App\Models\Complaint::with(['order.tenant'])->latest()->get();
        return view('admin.complaints', compact('complaints'));
    })->name('admin.complaints');

    Route::post('/complaints/{complaint}/status', function (Illuminate\Http\Request $request, \App\Models\Complaint $complaint) {
        abort_unless(auth()->user()->role === 'admin_ops', 403);
        
        $request->validate(['status' => 'required|in:in_progress,resolved,closed']);
        
        $complaint->status = $request->status;
        if ($request->status === 'resolved' || $request->status === 'closed') {
            $complaint->resolved_at = now();
        }
        $complaint->handled_by_user_id = auth()->id();
        $complaint->save();
        
        return redirect()->back()->with('success', 'Status komplain berhasil diperbarui!');
    })->name('admin.complaints.status');
});

/* Tenant */
Route::middleware('auth')
    ->prefix('tenant')
    ->name('tenant.')
    ->group(function () {

        Route::get('/dashboard', [\App\Http\Controllers\TenantOrderController::class, 'dashboard'])->name('dashboard');
        Route::post('/settings/hours', [\App\Http\Controllers\TenantOrderController::class, 'updateHours'])->name('settings.hours');

        Route::get('/orders', [\App\Http\Controllers\TenantOrderController::class, 'index'])->name('orders');
        Route::get('/orders/history', [\App\Http\Controllers\TenantOrderController::class, 'history'])->name('orders.history');
        Route::post('/orders/{order}/status', [\App\Http\Controllers\TenantOrderController::class, 'updateStatus'])->name('orders.status');

        /* Produk */
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

        Route::get('/products/{product}',
            [TenantProductController::class, 'show']
        )->name('products.show');

        Route::get('/products/{product}/edit',
            [TenantProductController::class, 'edit']
        )->name('products.edit');

        Route::put('/products/{product}',
            [TenantProductController::class, 'update']
        )->name('products.update');

        Route::delete('/products/{product}',
            [TenantProductController::class, 'destroy']
        )->name('products.destroy');
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

Route::get('/test-products', function () {
    $products = \App\Models\Product::take(3)->get();
    return view('tenant.products', compact('products'));
});
