<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Tenant;
use App\Models\Complaint;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ExecutiveDashboardController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        $days = $request->input('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $tenantId = $request->input('tenant_id');

        $orderQuery = Order::whereBetween('ordered_at', [$startDate, $endDate]);
        if ($tenantId) {
            $orderQuery->where('tenant_id', $tenantId);
        }

        // 1. Volume Pesanan per Hari (Line Chart)
        $volumePerDay = (clone $orderQuery)
            ->select(DB::raw('DATE(ordered_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 2. Distribusi Status Pesanan (Doughnut)
        $statusDistribution = (clone $orderQuery)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // 3. Performa Tenant (Volume)
        $tenantPerformance = (clone $orderQuery)
            ->join('tenants', 'orders.tenant_id', '=', 'tenants.id')
            ->select('tenants.id', 'tenants.name', DB::raw('count(orders.id) as total'))
            ->groupBy('tenants.id', 'tenants.name')
            ->orderByDesc('total')
            ->take(20)
            ->get();

        // 4. Produk Terlaris
        $orderItemQuery = OrderItem::whereHas('order', function($q) use ($startDate, $endDate, $tenantId) {
            $q->whereBetween('ordered_at', [$startDate, $endDate]);
            if ($tenantId) {
                $q->where('tenant_id', $tenantId);
            }
        });
        
        $topProducts = $orderItemQuery
            ->select('product_name_snapshot', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_name_snapshot')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // 5. SLA Rata-rata per Tenant (Top 20 Paling Lambat)
        $slaPerformance = (clone $orderQuery)
            ->join('tenants', 'orders.tenant_id', '=', 'tenants.id')
            ->whereNotNull('ready_at')
            ->select(
                'tenants.id',
                'tenants.name',
                DB::raw('AVG(TIMESTAMPDIFF(MINUTE, ordered_at, ready_at)) as avg_minutes')
            )
            ->groupBy('tenants.id', 'tenants.name')
            ->orderByDesc('avg_minutes')
            ->take(20)
            ->get();

        // 6. Komplain
        $complaintQuery = Complaint::whereBetween('created_at', [$startDate, $endDate]);
        if ($tenantId) {
            $complaintQuery->whereHas('order', function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            });
        }
        $openComplaints = (clone $complaintQuery)->whereIn('status', ['open', 'in_progress'])->count();
        $resolvedComplaints = (clone $complaintQuery)->whereIn('status', ['resolved', 'closed'])->count();

        // 7. Customer Segmentation (CRM)
        $customerSegmentation = DB::table('customers')
            ->selectRaw("
                SUM(CASE WHEN total_orders = 1 THEN 1 ELSE 0 END) as new_customers,
                SUM(CASE WHEN total_orders BETWEEN 2 AND 5 THEN 1 ELSE 0 END) as regular_customers,
                SUM(CASE WHEN total_orders > 5 THEN 1 ELSE 0 END) as frequent_customers,
                COUNT(id) as total_customers
            ")->first();

        $tenants = Tenant::where('is_active', true)->get();

        return view('admin.executive-dashboard', compact(
            'days',
            'tenantId',
            'tenants',
            'volumePerDay',
            'statusDistribution',
            'tenantPerformance',
            'topProducts',
            'slaPerformance',
            'openComplaints',
            'resolvedComplaints',
            'customerSegmentation'
        ));
    }

    public function export(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        $days = $request->input('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        $tenantId = $request->input('tenant_id');

        $tenant = $tenantId ? Tenant::find($tenantId) : null;

        // Base Query
        $orderQuery = Order::whereBetween('ordered_at', [$startDate, $endDate]);
        if ($tenantId) {
            $orderQuery->where('tenant_id', $tenantId);
        }

        // 1. Total Pendapatan (Gross Revenue)
        $totalRevenue = (clone $orderQuery)->where('is_paid', true)->sum('total_amount');

        // 2. Volume per hari
        $volumePerDay = (clone $orderQuery)
            ->select(DB::raw('DATE(ordered_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 3. Distribusi Status
        $statusDistribution = (clone $orderQuery)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // 4. Kinerja SLA
        $slaQuery = Order::join('tenants', 'orders.tenant_id', '=', 'tenants.id')
            ->whereBetween('ordered_at', [$startDate, $endDate])
            ->whereNotNull('ready_at');
        if ($tenantId) {
            $slaQuery->where('orders.tenant_id', $tenantId);
        }
        $slaPerformance = $slaQuery->select(
                'tenants.name',
                DB::raw('count(orders.id) as total_completed'),
                DB::raw('AVG(TIMESTAMPDIFF(MINUTE, ordered_at, ready_at)) as avg_minutes')
            )
            ->groupBy('tenants.id', 'tenants.name')
            ->orderBy('avg_minutes')
            ->get();

        // 5. Performa Tenant (Volume Terlaris)
        $tenantPerformance = (clone $orderQuery)
            ->join('tenants', 'orders.tenant_id', '=', 'tenants.id')
            ->select('tenants.name', DB::raw('count(orders.id) as total_orders'), DB::raw('sum(orders.total_amount) as total_omset'))
            ->groupBy('tenants.id', 'tenants.name')
            ->orderByDesc('total_orders')
            ->take(5)
            ->get();

        // 6. Produk Terlaris
        $orderItemQuery = OrderItem::whereHas('order', function($q) use ($startDate, $endDate, $tenantId) {
            $q->whereBetween('ordered_at', [$startDate, $endDate]);
            if ($tenantId) {
                $q->where('tenant_id', $tenantId);
            }
        });
        $topProducts = $orderItemQuery
            ->select('product_name_snapshot', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->groupBy('product_name_snapshot')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // 7. Komplain
        $complaintQuery = Complaint::whereBetween('created_at', [$startDate, $endDate]);
        if ($tenantId) {
            $complaintQuery->whereHas('order', function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            });
        }
        $openComplaints = (clone $complaintQuery)->whereIn('status', ['open', 'in_progress'])->count();
        $resolvedComplaints = (clone $complaintQuery)->whereIn('status', ['resolved', 'closed'])->count();

        // 8. CRM Segmentasi
        $customerSegmentation = DB::table('customers')
            ->selectRaw("
                SUM(CASE WHEN total_orders = 1 THEN 1 ELSE 0 END) as new_customers,
                SUM(CASE WHEN total_orders BETWEEN 2 AND 5 THEN 1 ELSE 0 END) as regular_customers,
                SUM(CASE WHEN total_orders > 5 THEN 1 ELSE 0 END) as frequent_customers,
                COUNT(id) as total_customers
            ")->first();

        // Base64 Logos (Hanya jika GD extension tersedia agar anti-crash)
        $hasGd = extension_loaded('gd');
        $logoFlydine = ($hasGd && file_exists(public_path('images/logo-flydine.png'))) 
            ? 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/logo-flydine.png'))) 
            : null;
        $logoAngkasaPura = ($hasGd && file_exists(public_path('images/angkasa-pura.png'))) 
            ? 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/angkasa-pura.png'))) 
            : null;

        $pdf = Pdf::loadView('admin.exports.executive-dashboard', compact(
            'days', 'startDate', 'endDate', 'tenant', 'totalRevenue', 'volumePerDay',
            'statusDistribution', 'slaPerformance', 'tenantPerformance', 'topProducts',
            'openComplaints', 'resolvedComplaints', 'customerSegmentation',
            'logoFlydine', 'logoAngkasaPura'
        ))->setPaper('a4', 'portrait');

        $filename = 'laporan-eksekutif-' . ($tenant ? \Illuminate\Support\Str::slug($tenant->name) . '-' : '') . $days . '-hari.pdf';

        return $pdf->download($filename);
    }
}
