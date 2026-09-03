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
     * Menampilkan Halaman Manajemen Pesanan (Aktif Saja)
     */
    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $orders = Order::with('orderItems')
            ->where('tenant_id', $tenantId)
            ->where('is_paid', true)
            ->whereIn('status', ['menunggu', 'diproses'])
            ->orderBy('ordered_at', 'asc')
            ->get();

        return view('tenant.orders', compact('orders'));
    }

    /**
     * Menampilkan Halaman Riwayat Pesanan (Selesai/Ditolak)
     */
    public function history(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $query = Order::with('orderItems')
            ->where('tenant_id', $tenantId)
            ->where('is_paid', true)
            ->whereIn('status', ['selesai', 'ditolak', 'dibatalkan']);

        // Filter Tanggal
        if ($request->filled('date_filter')) {
            $filter = $request->date_filter;
            if ($filter === 'today') {
                $query->whereDate('ordered_at', today());
            } elseif ($filter === 'yesterday') {
                $query->whereDate('ordered_at', today()->subDay());
            } elseif ($filter === 'this_week') {
                $query->whereBetween('ordered_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($filter === 'this_month') {
                $query->whereMonth('ordered_at', now()->month)
                      ->whereYear('ordered_at', now()->year);
            }
        }

        $orders = $query->latest('ordered_at')->paginate(15)->withQueryString();

        return view('tenant.history', compact('orders'));
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
            'status' => 'required|in:diproses,siap,selesai,dibatalkan'
        ]);

        $order->status = $request->status;
        
        if ($request->status === 'siap' || $request->status === 'selesai') {
            if (!$order->ready_at) {
                $order->ready_at = now();
            }
        }

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

    /**
     * Update Jam Operasional Tenant
     */
    public function updateHours(Request $request)
    {
        $request->validate([
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i'
        ]);

        $tenant = auth()->user()->tenant;
        
        if ($tenant) {
            $tenant->opening_time = $request->opening_time;
            $tenant->closing_time = $request->closing_time;
            $tenant->save();

            return redirect()->back()->with('success', 'Jam operasional berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui jam operasional.');
    }
}
