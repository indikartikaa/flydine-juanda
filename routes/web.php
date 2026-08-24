<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerCatalogController;

// Rute untuk halaman utama (Katalog Penumpang)
Route::get('/', [CustomerCatalogController::class, 'index'])->name('catalog.index');
Route::get('/cart', [CustomerCatalogController::class, 'cart'])->name('customer.cart');
Route::get('/tracking', [CustomerCatalogController::class, 'tracking'])->name('customer.tracking');