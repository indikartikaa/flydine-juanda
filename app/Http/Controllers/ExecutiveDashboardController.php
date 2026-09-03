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
            'resolvedComplaints'
        ));
    }

    public function export(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        $days = $request->input('days', 30);
        $startDate = Carbon::now()->subDays($days)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Ambil data (simplifikasi untuk PDF)
        $volumePerDay = Order::select(DB::raw('DATE(ordered_at) as date'), DB::raw('count(*) as total'))
            ->whereBetween('ordered_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $statusDistribution = Order::select('status', DB::raw('count(*) as total'))
            ->whereBetween('ordered_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();

        $slaPerformance = Order::join('tenants', 'orders.tenant_id', '=', 'tenants.id')
            ->whereBetween('ordered_at', [$startDate, $endDate])
            ->whereNotNull('ready_at')
            ->select('tenants.name', DB::raw('AVG(TIMESTAMPDIFF(MINUTE, ordered_at, ready_at)) as avg_minutes'))
            ->groupBy('tenants.id', 'tenants.name')
            ->get();
            
        $pdf = Pdf::loadView('admin.exports.executive-dashboard', compact(
            'days', 'startDate', 'endDate', 'volumePerDay', 'statusDistribution', 'slaPerformance'
        ));

        return $pdf->download('laporan-eksekutif-'.$days.'-hari.pdf');
    }
}
