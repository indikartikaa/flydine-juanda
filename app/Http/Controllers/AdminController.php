<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Order;
use App\Models\Complaint;
use App\Models\ActivityLog;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        // 1. Total Tenants & Sebaran Terminal
        $total_tenants = Tenant::count();
        $active_tenants = Tenant::where('is_active', true)->count();
        $t1_tenants = Tenant::where('terminal', 'like', '%1%')->count();
        $t2_tenants = Tenant::where('terminal', 'like', '%2%')->count();
        $t1_active = Tenant::where('is_active', true)->where('terminal', 'like', '%1%')->count();
        $t2_active = Tenant::where('is_active', true)->where('terminal', 'like', '%2%')->count();
        
        // 2. Komplain Pelanggan
        $open_complaints = Complaint::where('status', 'open')->count();
        $in_progress_complaints = Complaint::where('status', 'in_progress')->count();
        
        // 3. Pesanan & Omset Hari Ini
        $today_orders = Order::whereDate('ordered_at', Carbon::today())->count();
        $today_revenue = (float) Order::whereDate('ordered_at', Carbon::today())->where('is_paid', true)->sum('total_amount');
        
        // 4. Audit Trail & Log Aktivitas Sistem
        $order_logs = Order::with('tenant')->latest('ordered_at')->take(15)->get()->map(function ($order) {
            return [
                'id' => 'ord-' . $order->id,
                'type' => 'order',
                'category' => 'Pesanan',
                'title' => 'Pesanan Baru ' . $order->order_code,
                'time' => $order->ordered_at,
                'time_formatted' => Carbon::parse($order->ordered_at)->format('H:i') . ' WIB',
                'date_formatted' => Carbon::parse($order->ordered_at)->translatedFormat('d M Y, H:i'),
                'description' => 'Pesanan masuk ke ' . ($order->tenant->name ?? 'Tenant') . ' (Total: Rp ' . number_format($order->total_amount, 0, ',', '.') . ')',
                'badge' => 'ORD',
                'badge_color' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'icon_bg' => 'bg-indigo-600 text-white',
                'actor' => $order->customer_name ?? 'Pelanggan',
                'tenant_name' => $order->tenant->name ?? 'Tenant',
                'terminal' => $order->tenant->terminal ?? 'T1',
                'link' => route('admin.analytics.drilldown', ['search' => $order->order_code])
            ];
        });
        
        $complaint_logs = Complaint::with('order.tenant')->latest('created_at')->take(10)->get()->map(function ($comp) {
            $statusLabels = [
                'open' => 'Menunggu Respon',
                'in_progress' => 'Sedang Ditangani',
                'resolved' => 'Selesai',
                'closed' => 'Ditutup'
            ];
            return [
                'id' => 'cmp-' . $comp->id,
                'type' => 'complaint',
                'category' => 'Komplain',
                'title' => 'Komplain ' . $comp->complaint_code . ' (' . ($statusLabels[$comp->status] ?? $comp->status) . ')',
                'time' => $comp->created_at,
                'time_formatted' => Carbon::parse($comp->created_at)->format('H:i') . ' WIB',
                'date_formatted' => Carbon::parse($comp->created_at)->translatedFormat('d M Y, H:i'),
                'description' => 'Laporan dari ' . $comp->reporter_name . ': "' . \Illuminate\Support\Str::limit($comp->description, 70) . '" (' . ($comp->order->tenant->name ?? 'Sistem') . ')',
                'badge' => 'CMP',
                'badge_color' => 'bg-amber-50 text-amber-700 border-amber-200',
                'icon_bg' => 'bg-amber-500 text-white',
                'actor' => $comp->reporter_name,
                'tenant_name' => $comp->order->tenant->name ?? 'Umum',
                'terminal' => $comp->order->tenant->terminal ?? 'T1',
                'link' => route('admin.complaints')
            ];
        });

        $system_logs = ActivityLog::with('user')->latest('created_at')->take(15)->get()->map(function ($act) {
            return [
                'id' => 'act-' . $act->id,
                'type' => 'system',
                'category' => 'Sistem & Keamanan',
                'title' => 'Aktivitas ' . ($act->action ?? 'Sistem'),
                'time' => $act->created_at,
                'time_formatted' => Carbon::parse($act->created_at)->format('H:i') . ' WIB',
                'date_formatted' => Carbon::parse($act->created_at)->translatedFormat('d M Y, H:i'),
                'description' => ($act->description ?? 'Aksi tercatat di sistem') . ($act->user ? ' oleh ' . $act->user->name : ''),
                'badge' => 'SYS',
                'badge_color' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'icon_bg' => 'bg-emerald-600 text-white',
                'actor' => $act->user->name ?? 'Admin Ops',
                'tenant_name' => 'Pusat Operasional',
                'terminal' => 'Pusat Kontrol',
                'ip_address' => $act->ip_address,
                'link' => null
            ];
        });
        
        $all_logs = $order_logs->concat($complaint_logs)->concat($system_logs)
                               ->sortByDesc('time')
                               ->values();
        $recent_logs = $all_logs->take(6);

        return view('admin.dashboard', compact(
            'total_tenants',
            'active_tenants',
            't1_tenants',
            't2_tenants',
            't1_active',
            't2_active',
            'open_complaints',
            'in_progress_complaints',
            'today_orders',
            'today_revenue',
            'recent_logs',
            'all_logs'
        ));
    }

    public function tenantsManagement(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        $query = Tenant::query();

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('tenant_code', 'like', '%' . $request->search . '%')
                  ->orWhere('company_name', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('terminal')) {
            $term = $request->terminal;
            $termT = str_starts_with(strtoupper($term), 'T') ? strtoupper($term) : 'T' . $term;
            $termNum = preg_replace('/[^0-9]/', '', $term);

            $query->where(function($q) use ($term, $termT, $termNum) {
                $q->where('terminal', $term)
                  ->orWhere('terminal', $termT)
                  ->orWhere('terminal', $termNum)
                  ->orWhere('floor_location', 'like', '%Terminal ' . $termNum . '%')
                  ->orWhere('floor_location', 'like', '%' . $termT . '%');
            });
        }

        $tenants = $query->withCount(['products', 'orders'])->paginate(10)->withQueryString();
        
        // Count total for the stats since $tenants is now a LengthAwarePaginator
        $total_tenants = Tenant::count();
        $active_tenants = Tenant::where('is_active', true)->count();
        $t1_count = Tenant::where('terminal', 'like', '%1%')->count();
        $t2_count = Tenant::where('terminal', 'like', '%2%')->count();

        return view('admin.tenants-management', compact('tenants', 'total_tenants', 'active_tenants', 't1_count', 't2_count'));
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

        $newStatus = !$tenant->is_active;

        $tenant->update([
            'is_active' => $newStatus
        ]);

        // Sinkronkan status aktif akun staf (users) milik tenant terkait
        \App\Models\User::where('tenant_id', $tenant->id)->update([
            'is_active' => $newStatus
        ]);

        $statusText = $newStatus ? 'diaktifkan' : 'ditangguhkan (suspend)';
        return redirect()->back()->with('success', "Tenant berhasil $statusText!");
    }
}
