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

    public function storeTenant(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'company_name' => 'nullable|string|max:255',
            'tenant_code' => 'required|string|max:30|unique:tenants,tenant_code',
            'terminal' => 'required|in:T1,T2',
            'zone' => 'required|in:Landside,Airside',
            'floor_location' => 'required|string|max:50',
            'category' => 'required|string|max:255',
            'contract_start' => 'nullable|date',
            'contract_end' => 'nullable|date|after_or_equal:contract_start',
            'email' => 'required|email|unique:users,email',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            $tenant = Tenant::create([
                'name' => $validated['name'],
                'company_name' => $validated['company_name'],
                'tenant_code' => $validated['tenant_code'],
                'terminal' => $validated['terminal'],
                'zone' => $validated['zone'],
                'floor_location' => $validated['floor_location'],
                'category' => $validated['category'],
                'contract_start' => $validated['contract_start'],
                'contract_end' => $validated['contract_end'],
                'is_active' => true,
            ]);

            \App\Models\User::create([
                'name' => 'PIC ' . $validated['name'],
                'email' => $validated['email'],
                'password' => \Illuminate\Support\Facades\Hash::make('juanda123'),
                'role' => 'tenant_staff',
                'tenant_id' => $tenant->id,
                'is_active' => true,
            ]);
        });

        return redirect()->back()->with('success', 'Mitra tenant baru dan akun akses berhasil ditambahkan!');
    }

    public function updateTenant(Request $request, Tenant $tenant)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'company_name' => 'nullable|string|max:255',
            'tenant_code' => 'required|string|max:30|unique:tenants,tenant_code,'.$tenant->id,
            'terminal' => 'required|in:T1,T2',
            'zone' => 'required|in:Landside,Airside',
            'floor_location' => 'required|string|max:50',
            'category' => 'required|string|max:255',
            'contract_start' => 'nullable|date',
            'contract_end' => 'nullable|date|after_or_equal:contract_start',
        ]);

        $tenant->update($validated);

        return redirect()->back()->with('success', 'Data mitra tenant berhasil diperbarui!');
    }

    public function toggleTenantStatus(Tenant $tenant)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        $tenant->update([
            'is_active' => !$tenant->is_active
        ]);

        $statusText = $tenant->is_active ? 'diaktifkan' : 'ditangguhkan (suspend)';
        return redirect()->back()->with('success', "Tenant berhasil $statusText!");
    }
}
