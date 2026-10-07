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

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            if ($startDate->gt($endDate)) {
                $temp = $startDate;
                $startDate = $endDate->copy()->startOfDay();
                $endDate = $temp->copy()->endOfDay();
            }
            $days = max(1, (int) round($startDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay())) + 1);
        } else {
            $days = (int) $request->input('days', 30);
            $startDate = Carbon::now()->subDays($days)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        }
        $startDateFormatted = $startDate->format('Y-m-d');
        $endDateFormatted = $endDate->format('Y-m-d');

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

        // 8. Baseline Metrics for Drill-Down & What-If DSS
        $selectedTenant = $tenantId ? Tenant::find($tenantId) : null;
        $totalOrdersCount = (clone $orderQuery)->count();
        $totalRevenue = (clone $orderQuery)->where('is_paid', true)->sum('total_amount');
        $avgOrderValue = $totalOrdersCount > 0 ? round($totalRevenue / $totalOrdersCount) : 0;

        $slaReadyQuery = (clone $orderQuery)->whereNotNull('ready_at');
        $slaTotalReadyCount = (clone $slaReadyQuery)->count();
        $slaCompliantCount = (clone $slaReadyQuery)->whereRaw('TIMESTAMPDIFF(MINUTE, ordered_at, ready_at) <= 15')->count();
        $slaComplianceRate = $slaTotalReadyCount > 0 ? round(($slaCompliantCount / $slaTotalReadyCount) * 100, 1) : 100;
        $avgSlaMinutes = round((clone $slaReadyQuery)->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, ordered_at, ready_at)) as avg_m')->value('avg_m') ?? 0, 1);

        $tenants = Tenant::where('is_active', true)->get();

        // 9. Real Weekly & Monthly Metrics for Target Planning & Predictive Simulator
        $weekAgo = Carbon::now()->subDays(7)->startOfDay();
        $weeklyOrderQuery = Order::where('ordered_at', '>=', $weekAgo);
        if ($tenantId) {
            $weeklyOrderQuery->where('tenant_id', $tenantId);
        }
        $weeklyTotalRevenue = (float) (clone $weeklyOrderQuery)->where('is_paid', true)->sum('total_amount');
        $weeklyOrdersCount = (int) (clone $weeklyOrderQuery)->count();
        $weeklyAov = $weeklyOrdersCount > 0 ? round($weeklyTotalRevenue / $weeklyOrdersCount) : ($avgOrderValue > 0 ? round($avgOrderValue) : 30000);
        $weeklyVisitors = (int) (clone $weeklyOrderQuery)->distinct('customer_id')->count('customer_id');
        if ($weeklyVisitors === 0) {
            $weeklyVisitors = max(1, (int) round($weeklyOrdersCount * 1.5));
        }
        $weeklySlaQuery = (clone $weeklyOrderQuery)->whereNotNull('ready_at');
        $weeklyAvgSla = round((clone $weeklySlaQuery)->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, ordered_at, ready_at)) as avg_m')->value('avg_m') ?? ($avgSlaMinutes > 0 ? $avgSlaMinutes : 11.5), 1);

        $monthAgo = Carbon::now()->subDays(30)->startOfDay();
        $monthlyOrderQuery = Order::where('ordered_at', '>=', $monthAgo);
        if ($tenantId) {
            $monthlyOrderQuery->where('tenant_id', $tenantId);
        }
        $monthlyTotalRevenue = (float) (clone $monthlyOrderQuery)->where('is_paid', true)->sum('total_amount');
        $monthlyOrdersCount = (int) (clone $monthlyOrderQuery)->count();
        $monthlyAov = $monthlyOrdersCount > 0 ? round($monthlyTotalRevenue / $monthlyOrdersCount) : ($avgOrderValue > 0 ? round($avgOrderValue) : 30000);
        $monthlyVisitors = (int) (clone $monthlyOrderQuery)->distinct('customer_id')->count('customer_id');
        if ($monthlyVisitors === 0) {
            $monthlyVisitors = max(1, (int) round($monthlyOrdersCount * 1.5));
        }
        $monthlySlaQuery = (clone $monthlyOrderQuery)->whereNotNull('ready_at');
        $monthlyAvgSla = round((clone $monthlySlaQuery)->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, ordered_at, ready_at)) as avg_m')->value('avg_m') ?? ($avgSlaMinutes > 0 ? $avgSlaMinutes : 11.5), 1);

        return view('admin.executive-dashboard', compact(
            'days',
            'startDate',
            'endDate',
            'startDateFormatted',
            'endDateFormatted',
            'tenantId',
            'selectedTenant',
            'tenants',
            'volumePerDay',
            'statusDistribution',
            'tenantPerformance',
            'topProducts',
            'slaPerformance',
            'openComplaints',
            'resolvedComplaints',
            'customerSegmentation',
            'totalRevenue',
            'avgOrderValue',
            'slaComplianceRate',
            'avgSlaMinutes',
            'totalOrdersCount',
            'weeklyTotalRevenue',
            'weeklyOrdersCount',
            'weeklyAov',
            'weeklyVisitors',
            'weeklyAvgSla',
            'monthlyTotalRevenue',
            'monthlyOrdersCount',
            'monthlyAov',
            'monthlyVisitors',
            'monthlyAvgSla'
        ));
    }

    public function export(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            if ($startDate->gt($endDate)) {
                $temp = $startDate;
                $startDate = $endDate->copy()->startOfDay();
                $endDate = $temp->copy()->endOfDay();
            }
            $days = max(1, (int) round($startDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay())) + 1);
        } else {
            $days = (int) $request->input('days', 30);
            $startDate = Carbon::now()->subDays($days)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        }
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

        $periodLabel = ($request->filled('start_date') && $request->filled('end_date'))
            ? $startDate->format('Ymd') . '-sd-' . $endDate->format('Ymd')
            : $days . '-hari';
        $filename = 'laporan-eksekutif-' . ($tenant ? \Illuminate\Support\Str::slug($tenant->name) . '-' : '') . $periodLabel . '.pdf';

        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            if ($startDate->gt($endDate)) {
                $temp = $startDate;
                $startDate = $endDate->copy()->startOfDay();
                $endDate = $temp->copy()->endOfDay();
            }
            $days = max(1, (int) round($startDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay())) + 1);
        } else {
            $days = (int) $request->input('days', 30);
            $startDate = Carbon::now()->subDays($days)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        }
        $tenantId = $request->input('tenant_id');

        $tenant = $tenantId ? Tenant::find($tenantId) : null;

        // Base Query
        $orderQuery = Order::whereBetween('ordered_at', [$startDate, $endDate]);
        if ($tenantId) {
            $orderQuery->where('tenant_id', $tenantId);
        }

        // 1. Total Pendapatan & Volume
        $totalRevenue = (clone $orderQuery)->where('is_paid', true)->sum('total_amount');
        $totalOrders = (clone $orderQuery)->count();

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

        // 5. Performa Tenant
        $tenantPerformance = (clone $orderQuery)
            ->join('tenants', 'orders.tenant_id', '=', 'tenants.id')
            ->select('tenants.name', DB::raw('count(orders.id) as total_orders'), DB::raw('sum(orders.total_amount) as total_omset'))
            ->groupBy('tenants.id', 'tenants.name')
            ->orderByDesc('total_orders')
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
            ->take(15)
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

        $periodLabel = ($request->filled('start_date') && $request->filled('end_date'))
            ? $startDate->format('Ymd') . '-sd-' . $endDate->format('Ymd')
            : $days . '-hari';
        $filename = 'laporan-eksekutif-' . ($tenant ? \Illuminate\Support\Str::slug($tenant->name) . '-' : '') . $periodLabel . '.xls';

        $content = view('admin.exports.executive-dashboard-excel', compact(
            'days', 'startDate', 'endDate', 'tenant', 'totalRevenue', 'totalOrders', 'volumePerDay',
            'statusDistribution', 'slaPerformance', 'tenantPerformance', 'topProducts',
            'openComplaints', 'resolvedComplaints', 'customerSegmentation'
        ))->render();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function exportSimulationPdf(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        $horizon = $request->input('horizon', 'weekly');
        $tenantId = $request->input('tenant_id');
        $tenant = $tenantId ? Tenant::find($tenantId) : null;

        // Baseline Metrics
        $weekAgo = Carbon::now()->subDays(7)->startOfDay();
        $weeklyOrderQuery = Order::where('ordered_at', '>=', $weekAgo);
        if ($tenantId) {
            $weeklyOrderQuery->where('tenant_id', $tenantId);
        }
        $weeklyTotalRevenue = (float) (clone $weeklyOrderQuery)->where('is_paid', true)->sum('total_amount');
        $weeklyOrdersCount = (int) (clone $weeklyOrderQuery)->count();
        $weeklyAov = $weeklyOrdersCount > 0 ? round($weeklyTotalRevenue / $weeklyOrdersCount) : 35000;
        $weeklyVisitors = (int) (clone $weeklyOrderQuery)->distinct('customer_id')->count('customer_id');
        if ($weeklyVisitors === 0) {
            $weeklyVisitors = max(1, (int) round($weeklyOrdersCount * 1.5));
        }
        $weeklySlaQuery = (clone $weeklyOrderQuery)->whereNotNull('ready_at');
        $weeklyAvgSla = round((clone $weeklySlaQuery)->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, ordered_at, ready_at)) as avg_m')->value('avg_m') ?? 11.5, 1);

        $monthAgo = Carbon::now()->subDays(30)->startOfDay();
        $monthlyOrderQuery = Order::where('ordered_at', '>=', $monthAgo);
        if ($tenantId) {
            $monthlyOrderQuery->where('tenant_id', $tenantId);
        }
        $monthlyTotalRevenue = (float) (clone $monthlyOrderQuery)->where('is_paid', true)->sum('total_amount');
        $monthlyOrdersCount = (int) (clone $monthlyOrderQuery)->count();
        $monthlyAov = $monthlyOrdersCount > 0 ? round($monthlyTotalRevenue / $monthlyOrdersCount) : 35000;
        $monthlyVisitors = (int) (clone $monthlyOrderQuery)->distinct('customer_id')->count('customer_id');
        if ($monthlyVisitors === 0) {
            $monthlyVisitors = max(1, (int) round($monthlyOrdersCount * 1.5));
        }
        $monthlySlaQuery = (clone $monthlyOrderQuery)->whereNotNull('ready_at');
        $monthlyAvgSla = round((clone $monthlySlaQuery)->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, ordered_at, ready_at)) as avg_m')->value('avg_m') ?? 11.5, 1);

        // Parameters for this simulation
        $baseRevenue = (float) $request->input('revenue', ($horizon === 'weekly' ? ($weeklyTotalRevenue > 0 ? $weeklyTotalRevenue : 15000000) : ($monthlyTotalRevenue > 0 ? $monthlyTotalRevenue : 60000000)));
        $baseVisitors = (int) $request->input('visitors', ($horizon === 'weekly' ? ($weeklyVisitors > 0 ? $weeklyVisitors : 450) : ($monthlyVisitors > 0 ? $monthlyVisitors : 1800)));
        $baseSla = (float) $request->input('sla', ($horizon === 'weekly' ? ($weeklyAvgSla > 0 ? $weeklyAvgSla : 12.0) : ($monthlyAvgSla > 0 ? $monthlyAvgSla : 12.0)));
        $goodGrowthPct = (int) $request->input('good_growth', 20);
        $badDropPct = (int) $request->input('bad_drop', 20);

        // Calculations
        $estBuyers = max(1, (int) round($baseVisitors * 0.5));
        $aov = max(1, (int) round($baseRevenue / $estBuyers));

        // Skenario Baik
        $goodRevenue = round($baseRevenue * (1 + ($goodGrowthPct / 100)));
        $goodRevenueDelta = $goodRevenue - $baseRevenue;
        $goodVisitors = round($baseVisitors * (1 + ($goodGrowthPct * 0.75 / 100)));
        $goodVisitorsDelta = $goodVisitors - $baseVisitors;
        $goodOrders = round($goodVisitors * 0.55);
        $goodSla = max(6.0, min(9.5, round($baseSla - 2.5, 1)));
        $goodCancelRate = 0.8;
        $goodSavedRevenue = round($goodRevenue * 0.08);

        // Skenario Buruk
        $badRevenue = round($baseRevenue * (1 - ($badDropPct / 100)));
        $badRevenueDelta = $baseRevenue - $badRevenue;
        $badVisitors = round($baseVisitors * (1 - ($badDropPct * 0.6 / 100)));
        $badVisitorsDelta = $baseVisitors - $badVisitors;
        $badOrders = round($badVisitors * 0.40);
        $badSla = min(22.0, max(16.0, round($baseSla + 4.5, 1)));
        $badCancelRate = 14.5;
        $badCompliance = max(30, round(85 - max(0, $badSla - 15) * 12, 1));
        $badLostRevenue = round($baseRevenue * ($badCancelRate / 100));
        $badComplaints = max(3, round($baseVisitors * 0.02));

        // Logos
        $hasGd = extension_loaded('gd');
        $logoFlydine = ($hasGd && file_exists(public_path('images/logo-flydine.png'))) 
            ? 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/logo-flydine.png'))) 
            : null;
        $logoAngkasaPura = ($hasGd && file_exists(public_path('images/angkasa-pura.png'))) 
            ? 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/angkasa-pura.png'))) 
            : null;

        $pdf = Pdf::loadView('admin.exports.simulation-guide-pdf', compact(
            'horizon', 'tenant', 'baseRevenue', 'baseVisitors', 'baseSla', 'estBuyers', 'aov',
            'goodGrowthPct', 'badDropPct',
            'goodRevenue', 'goodRevenueDelta', 'goodVisitors', 'goodVisitorsDelta', 'goodOrders', 'goodSla', 'goodCancelRate', 'goodSavedRevenue',
            'badRevenue', 'badRevenueDelta', 'badVisitors', 'badVisitorsDelta', 'badOrders', 'badSla', 'badCancelRate', 'badCompliance', 'badLostRevenue', 'badComplaints',
            'weeklyTotalRevenue', 'weeklyOrdersCount', 'weeklyAvgSla', 'monthlyTotalRevenue', 'monthlyOrdersCount', 'monthlyAvgSla',
            'logoFlydine', 'logoAngkasaPura'
        ))->setPaper('a4', 'portrait');

        $filename = 'panduan-dan-simulasi-target-juanda-' . Carbon::now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }
}
