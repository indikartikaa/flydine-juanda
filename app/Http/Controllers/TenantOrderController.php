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

        // Total Omzet Penjualan Selesai Hari Ini
        $todayRevenue = (float) Order::where('tenant_id', $tenantId)
            ->where('is_paid', true)
            ->where('status', 'selesai')
            ->where('updated_at', '>=', $today)
            ->sum('total_amount');

        // Ringkasan Menu Produk Aktif
        $activeProducts = Product::where('tenant_id', $tenantId)
            ->where('is_available', true)
            ->latest()
            ->take(4)
            ->get();

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
            'todayRevenue',
            'activeProducts',
            'recentOrders'
        ));
    }

    /**
     * Menampilkan Halaman Manajemen Pesanan (Aktif Saja)
     */
    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $orders = Order::with(['orderItems', 'deliveryLocation', 'customer'])
            ->where('tenant_id', $tenantId)
            ->where('is_paid', true)
            ->whereIn('status', ['menunggu', 'diproses', 'siap'])
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

        $query = Order::with(['orderItems', 'deliveryLocation', 'customer'])
            ->where('tenant_id', $tenantId)
            ->where('is_paid', true)
            ->whereIn('status', ['selesai', 'ditolak', 'dibatalkan']);

        // Filter Tanggal
        if ($request->filled('date_filter') && $request->date_filter !== 'all') {
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

        // Filter Pencarian (ID Pesanan, Customer, Flight)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('flight_number', 'like', "%{$search}%");
            });
        }

        // Filter Status Khusus (Selesai / Dibatalkan)
        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            if ($request->status_filter === 'selesai') {
                $query->where('status', 'selesai');
            } elseif (in_array($request->status_filter, ['dibatalkan', 'ditolak'])) {
                $query->whereIn('status', ['dibatalkan', 'ditolak']);
            }
        }

        // Ringkasan Statistik Riwayat
        $baseStatsQuery = Order::where('tenant_id', $tenantId)->where('is_paid', true);
        $totalCompleted = (clone $baseStatsQuery)->where('status', 'selesai')->count();
        $totalRevenue = (float) (clone $baseStatsQuery)->where('status', 'selesai')->sum('total_amount');
        $totalCancelled = (clone $baseStatsQuery)->whereIn('status', ['ditolak', 'dibatalkan'])->count();
        $todayCompleted = (clone $baseStatsQuery)->where('status', 'selesai')->whereDate('ordered_at', today())->count();

        $orders = $query->latest('ordered_at')->paginate(10)->withQueryString();

        return view('tenant.history', compact(
            'orders',
            'totalCompleted',
            'totalRevenue',
            'totalCancelled',
            'todayCompleted'
        ));
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
            'status' => 'required|in:diproses,siap,selesai,dibatalkan,ditolak'
        ]);

        $oldStatus = $order->status;
        $status = in_array($request->status, ['dibatalkan', 'ditolak']) ? 'dibatalkan' : $request->status;
        $order->status = $status;
        
        // Kembalikan kuota stok jika pesanan ditolak/dibatalkan oleh tenant dan DB trigger tidak aktif (SQLite test)
        if (in_array($request->status, ['dibatalkan', 'ditolak']) && !in_array($oldStatus, ['dibatalkan', 'ditolak'])) {
            if (!in_array(\Illuminate\Support\Facades\DB::getDriverName(), ['mysql', 'mariadb'])) {
                foreach ($order->orderItems as $item) {
                    if ($item->product_id) {
                        Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    }
                }
            }
        }
        
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

    public function updateDelivery(Request $request)
    {
        $tenant = auth()->user()->tenant;
        
        if (!$tenant) {
            return redirect()->back()->with('error', 'Data tenant tidak ditemukan.');
        }

        if ($request->has('delivery_active')) {
            $request->validate([
                'delivery_fee' => 'nullable|numeric|min:0',
            ]);
            $fee = $request->filled('delivery_fee') ? (float)$request->delivery_fee : 15000;
            $tenant->update(['delivery_fee' => $fee]);

            return redirect()->back()->with('success', 'Layanan antar (porter) berhasil diaktifkan (Tarif: Rp ' . number_format($fee, 0, ',', '.') . ').');
        } else {
            $tenant->update(['delivery_fee' => null]);

            return redirect()->back()->with('success', 'Layanan antar berhasil dinonaktifkan.');
        }
    }

    /**
     * Update Profil Lengkap Tenant (Logo & Kontak PIC)
     */
    public function updateProfile(Request $request)
    {
        $tenant = auth()->user()->tenant;
        
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'pic_name' => 'nullable|string|max:100',
            'pic_phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480' // max 20MB
        ]);

        $tenant->phone = $request->phone;
        $tenant->pic_name = $request->pic_name;
        $tenant->pic_phone = $request->pic_phone;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . \Str::slug($tenant->name) . '.' . $file->getClientOriginalExtension();
            
            // Simpan langsung ke folder public/images/logos agar lebih aman di Windows tanpa perlu symlink
            $destinationPath = public_path('images/logos');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file->move($destinationPath, $filename);
            
            // Hapus logo lama jika ada dan bukan bawaan
            if ($tenant->logo && file_exists(public_path($tenant->logo))) {
                @unlink(public_path($tenant->logo));
            }

            $tenant->logo = 'images/logos/' . $filename;
        }

        $tenant->save();

        return redirect()->back()->with('success', 'Profil restoran berhasil diperbarui!');
    }
}
