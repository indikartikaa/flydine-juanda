<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Order;
use App\Models\Complaint;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        // 1. Total Tenants
        $total_tenants = Tenant::count();
        
        // 2. Active Tenants (Based on is_active status)
        $active_tenants = Tenant::where('is_active', true)->count();
        
        // 3. Open Complaints
        $open_complaints = Complaint::where('status', 'open')->count();
        
        // 4. Today's Orders
        $today_orders = Order::whereDate('ordered_at', Carbon::today())->count();
        
        // 5. Recent Logs (Combining recent orders and recent complaints)
        $recent_orders = Order::with('tenant')->latest('ordered_at')->take(3)->get()->map(function ($order) {
            return [
                'type' => 'order',
                'title' => 'Pesanan Baru',
                'time' => $order->ordered_at,
                'description' => 'Pesanan baru ' . $order->order_code . ' masuk ke ' . ($order->tenant->name ?? 'Tenant'),
                'icon' => 'ORD',
                'color' => 'bg-indigo-500'
            ];
        });
        
        $recent_complaints = Complaint::with('order.tenant')->latest('created_at')->take(3)->get()->map(function ($comp) {
            return [
                'type' => 'complaint',
                'title' => 'Komplain Masuk',
                'time' => $comp->created_at,
                'description' => 'Komplain (' . $comp->complaint_code . ') dari ' . $comp->reporter_name . ' terkait ' . ($comp->order->tenant->name ?? 'Sistem'),
                'icon' => 'CMP',
                'color' => 'bg-amber-500',
                'link' => route('admin.complaints')
            ];
        });
        
        $recent_logs = $recent_orders->concat($recent_complaints)
                                     ->sortByDesc('time')
                                     ->take(5)
                                     ->values();

        return view('admin.dashboard', compact(
            'total_tenants',
            'active_tenants',
            'open_complaints',
            'today_orders',
            'recent_logs'
        ));
    }

    public function tenantsManagement(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        $query = Tenant::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('tenant_code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('terminal')) {
            // Asumsikan floor_location menyimpan format seperti "Terminal 1" atau "T1" atau "T2"
            $query->where('floor_location', 'like', '%Terminal ' . $request->terminal . '%')
                  ->orWhere('floor_location', 'like', '%T' . $request->terminal . '%');
        }

        $tenants = $query->withCount(['products', 'orders'])->paginate(10)->withQueryString();
        
        // Count total for the stats since $tenants is now a LengthAwarePaginator
        $total_tenants = Tenant::count();
        $active_tenants = Tenant::where('is_active', true)->count();

        return view('admin.tenants-management', compact('tenants', 'total_tenants', 'active_tenants'));
    }
}
