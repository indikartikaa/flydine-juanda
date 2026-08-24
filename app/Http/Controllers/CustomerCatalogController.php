<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;

class CustomerCatalogController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with('products')->where('is_active', true)->get();
        return view('customer.catalog', compact('tenants'));
    }

    // Fungsi untuk menampilkan halaman Keranjang
    public function cart()
    {
        return view('customer.cart');
    }

    // Fungsi untuk menampilkan halaman Pelacakan Pesanan
    public function tracking()
    {
        return view('customer.tracking');
    }
}