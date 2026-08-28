<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class TenantOrderController extends Controller
{
    /**
     * Menampilkan Dasbor Tenant dengan Statistik.
     */
    public function dashboard()
    {
        $tenantId = auth()->user()->tenant_id;

        // Statistik Pesanan (Hari Ini atau yang masih aktif)
        $today = now()->startOfDay();

        $countMenunggu = Order::where('tenant_id', $tenantId)
            ->where('is_paid', true)
            ->where('status', 'menunggu')
            ->count();

        $countDiproses = Order::where('tenant_id', $tenantId)
            ->where('is_paid', true)
            ->where('status', 'diproses')
            ->count();

        $countSelesai = Order::where('tenant_id', $tenantId)
            ->where('is_paid', true)
            ->where('status', 'selesai')
            ->where('updated_at', '>=', $today)
            ->count();

        // Produk Aktif
        $countProduk = Product::where('tenant_id', $tenantId)
            ->where('is_available', true)
            ->count();

        // 5 Pesanan Terbaru
        $recentOrders = Order::with('orderItems')
            ->where('tenant_id', $tenantId)
            ->where('is_paid', true)
            ->latest('ordered_at')
            ->take(5)
            ->get();

        return view('tenant.dashboard', compact(
            'countMenunggu', 
            'countDiproses', 
            'countSelesai', 
            'countProduk', 
            'recentOrders'
        ));
    }

    /**
     * Menampilkan Halaman Manajemen Pesanan
     */
    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $orders = Order::with('orderItems')
            ->where('tenant_id', $tenantId)
            ->where('is_paid', true)
            ->latest('ordered_at')
            ->get();

        return view('tenant.orders', compact('orders'));
    }

    /**
     * Update Status Pesanan via AJAX
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Pastikan order milik tenant ini
        if ($order->tenant_id != auth()->user()->tenant_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:diproses,selesai,ditolak'
        ]);

        $order->status = $request->status;
        
        if ($request->status === 'selesai') {
            $order->completed_at = now();
        }

        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui',
            'order' => $order
        ]);
    }
}
