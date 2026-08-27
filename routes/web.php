<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
        default => abort(403)
    };
})->middleware('auth')->name('dashboard');

/* Admin */
Route::get('/admin/dashboard', function () {
    abort_unless(auth()->user()->role === 'admin_ops', 403);
    return view('admin.dashboard');
})->middleware('auth')->name('admin.dashboard');

/* Profile */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
