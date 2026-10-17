<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\CapacityPlanner;

class AnalyticsController extends Controller
{
    /**
     * Helper to resolve common date range and tenant filter parameters.
     */
    protected function resolveFilters(Request $request): array
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            if ($startDate->gt($endDate)) {
                $temp = $startDate;
                $startDate = $endDate->copy()->startOfDay();
                $endDate = $temp->copy()->endOfDay();
            }
        } else {
            $daysInput = (int) $request->input('days', 30);
            $startDate = Carbon::now()->subDays($daysInput)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        }
        $days = max(1, (int) round($startDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay())) + 1);

        $startDateFormatted = $startDate->format('Y-m-d');
        $endDateFormatted = $endDate->format('Y-m-d');

        $tenantId = $request->input('tenant_id');
        $selectedTenant = $tenantId ? Tenant::find($tenantId) : null;
        $tenants = Tenant::where('is_active', true)->orderBy('name')->get();

        $isSqlite = DB::getDriverName() === 'sqlite';
        $diffMinutesSql = $isSqlite
            ? '((strftime(\'%s\', ready_at) - strftime(\'%s\', ordered_at)) / 60)'
            : 'TIMESTAMPDIFF(MINUTE, ordered_at, ready_at)';

        return compact(
            'startDate',
            'endDate',
            'days',
            'startDateFormatted',
            'endDateFormatted',
            'tenantId',
            'selectedTenant',
            'tenants',
            'diffMinutesSql'
        );
    }

    /**
     * Helper to build filtered query for orders drill-down.
     */
    protected function buildFilteredOrderQuery(Request $request): \Illuminate\Database\Eloquent\Builder
    {
        $filters = $this->resolveFilters($request);
        $startDate = $filters['startDate'];
        $endDate = $filters['endDate'];
        $tenantId = $filters['tenantId'];
        $diffMinutesSql = $filters['diffMinutesSql'];

        $query = Order::query();

        // 1. Date Range or Single Date Filter
        if ($request->filled('date')) {
            $query->whereDate('orders.ordered_at', $request->input('date'));
        } else {
            $query->whereBetween('orders.ordered_at', [$startDate, $endDate]);
        }

        // 2. Tenant Filter
        if ($tenantId) {
            $query->where('orders.tenant_id', $tenantId);
        }

        // 3. Status Filter
        if ($request->filled('status') && $request->input('status') !== 'semua') {
            $query->where('orders.status', $request->input('status'));
        }

        // 4. SLA Compliance Filter
        if ($request->filled('sla') && $request->input('sla') !== 'semua') {
            $sla = $request->input('sla');
            if ($sla === 'compliant') {
                $query->whereNotNull('orders.ready_at')
                      ->whereRaw('orders.ready_at >= orders.ordered_at')
                      ->whereRaw("{$diffMinutesSql} <= 15");
            } elseif ($sla === 'breached') {
                $query->whereNotNull('orders.ready_at')
                      ->where(function ($q) use ($diffMinutesSql) {
                          $q->whereRaw('orders.ready_at < orders.ordered_at')
                            ->orWhereRaw("{$diffMinutesSql} > 15");
                      });
            }
        }

        // 5. Hour Filter (0-23)
        if ($request->filled('hour') && $request->input('hour') !== 'semua') {
            $hour = (int) $request->input('hour');
            $isSqlite = DB::getDriverName() === 'sqlite';
            if ($isSqlite) {
                $query->whereRaw("CAST(strftime('%H', orders.ordered_at) AS INTEGER) = ?", [$hour]);
            } else {
                $query->whereRaw("HOUR(orders.ordered_at) = ?", [$hour]);
            }
        }

        // 6. Pickup Method Filter ('ambil_sendiri', 'diantar')
        if ($request->filled('pickup_method') && $request->input('pickup_method') !== 'semua') {
            $query->where('orders.pickup_method', $request->input('pickup_method'));
        }

        // 7. Search Filter (order_code, customer_name, flight_number, gate, product_name_snapshot)
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $term = "%{$search}%";
                $q->where('orders.order_code', 'like', $term)
                  ->orWhere('orders.customer_name', 'like', $term)
                  ->orWhere('orders.flight_number', 'like', $term)
                  ->orWhere('orders.gate', 'like', $term)
                  ->orWhereHas('orderItems', function ($itemQuery) use ($term) {
                      $itemQuery->where('product_name_snapshot', 'like', $term);
                  });
            });
        }

        // 8. Category Filter (tenants.category)
        if ($request->filled('category') && $request->input('category') !== 'semua') {
            $category = html_entity_decode((string)$request->input('category'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $query->whereHas('tenant', function ($tq) use ($category) {
                $tq->where('category', $category);
            });
        }

        return $query;
    }

    /**
     * Halaman Drill-Down Pesanan (View).
     */
    public function drilldown(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        $filters = $this->resolveFilters($request);
        extract($filters);

        $availableCategories = [
            'Beverages & Coffee',
            'Fast Food & Restaurant',
            'Bakery & Pastry',
            'Snacks & Convenience',
            'Indonesian Traditional',
            'Lounge',
        ];
        $dbCategories = Tenant::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category')->toArray();
        $categories = array_values(array_unique(array_merge($availableCategories, $dbCategories)));

        $initialStatus = $request->input('status', 'semua');
        $initialSla = $request->input('sla', 'semua');
        $initialHour = $request->input('hour', 'semua');
        $initialPickupMethod = $request->input('pickup_method', 'semua');
        $initialCategory = $request->filled('category')
            ? html_entity_decode((string)$request->input('category'), ENT_QUOTES | ENT_HTML5, 'UTF-8')
            : 'semua';
        $initialSearch = $request->input('search', '');
        $initialDate = $request->input('date', '');

        return view('admin.analytics.drilldown', compact(
            'days',
            'startDate',
            'endDate',
            'startDateFormatted',
            'endDateFormatted',
            'tenantId',
            'selectedTenant',
            'tenants',
            'categories',
            'initialStatus',
            'initialSla',
            'initialHour',
            'initialPickupMethod',
            'initialCategory',
            'initialSearch',
            'initialDate'
        ));
    }

    /**
     * Endpoint JSON: Data Pesanan Terpaginasi & Ringkasan Metrik.
     */
    public function drilldownOrders(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        // Sanitize string 'semua' to null so nullable validation passes
        $cleanInputs = [];
        foreach (['status', 'sla', 'hour', 'pickup_method', 'tenant_id', 'category'] as $key) {
            if ($request->has($key) && ($request->input($key) === 'semua' || $request->input($key) === '')) {
                $cleanInputs[$key] = null;
            }
        }
        if ($request->filled('category')) {
            $cleanInputs['category'] = html_entity_decode((string)$request->input('category'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        if (!empty($cleanInputs)) {
            $request->merge($cleanInputs);
        }

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'date' => 'nullable|date',
            'tenant_id' => 'nullable|exists:tenants,id',
            'category' => 'nullable|string|in:Beverages & Coffee,Fast Food & Restaurant,Bakery & Pastry,Snacks & Convenience,Indonesian Traditional,Lounge',
            'status' => 'nullable|in:menunggu,diproses,siap,selesai,dibatalkan',
            'sla' => 'nullable|in:compliant,breached',
            'hour' => 'nullable|integer|min:0|max:23',
            'pickup_method' => 'nullable|in:ambil_sendiri,diantar',
            'search' => 'nullable|string|max:100',
            'per_page' => 'nullable|integer|in:10,25,50',
            'sort_by' => 'nullable|in:ordered_at,total_amount,status,sla_minutes,order_code',
            'sort_dir' => 'nullable|in:asc,desc',
            'page' => 'nullable|integer|min:1',
        ]);

        $baseQuery = $this->buildFilteredOrderQuery($request);

        // Hitung 4 Kartu Ringkasan Hasil Filter
        $totalOrders = (clone $baseQuery)->count();
        $totalRevenue = (float) (clone $baseQuery)->where('orders.is_paid', true)->sum('orders.total_amount');

        $isSqlite = DB::getDriverName() === 'sqlite';
        $diffMinutesSql = $isSqlite
            ? '((strftime(\'%s\', orders.ready_at) - strftime(\'%s\', orders.ordered_at)) / 60)'
            : 'TIMESTAMPDIFF(MINUTE, orders.ordered_at, orders.ready_at)';

        $readyOrdersQuery = (clone $baseQuery)
            ->whereNotNull('orders.ready_at')
            ->whereRaw('orders.ready_at >= orders.ordered_at');

        $avgSlaMinutes = round(
            (float) ((clone $readyOrdersQuery)->selectRaw("AVG({$diffMinutesSql}) as avg_m")->value('avg_m') ?? 0),
            1
        );

        $readyOrdersCount = (clone $readyOrdersQuery)->count();
        $compliantCount = (clone $readyOrdersQuery)->whereRaw("{$diffMinutesSql} <= 15")->count();
        $slaCompliantPercentage = $readyOrdersCount > 0
            ? round(($compliantCount / $readyOrdersCount) * 100, 1)
            : 0;

        // Paginasi & Pengurutan Server
        $sortBy = $request->input('sort_by', 'ordered_at');
        $sortDir = strtolower($request->input('sort_dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50])) {
            $perPage = 10;
        }

        $orderQuery = clone $baseQuery;

        if ($sortBy === 'sla_minutes') {
            $orderQuery->orderByRaw("CASE WHEN orders.ready_at IS NULL THEN 1 ELSE 0 END ASC, {$diffMinutesSql} {$sortDir}");
        } elseif (in_array($sortBy, ['ordered_at', 'total_amount', 'status', 'order_code'])) {
            $orderQuery->orderBy("orders.{$sortBy}", $sortDir);
        } else {
            $orderQuery->orderBy('orders.ordered_at', 'desc');
        }

        // Eager loading agar tidak terjadi N+1
        $paginated = $orderQuery->with([
            'tenant:id,name,floor_location,zone',
            'orderItems:id,order_id,product_id,product_name_snapshot,quantity,unit_price,subtotal',
            'deliveryLocation:id,name,terminal'
        ])->paginate($perPage);

        $ordersData = $paginated->getCollection()->map(function ($o) {
            $slaMinutes = ($o->ready_at && $o->ordered_at && $o->ready_at >= $o->ordered_at)
                ? round(Carbon::parse($o->ordered_at)->diffInMinutes(Carbon::parse($o->ready_at)), 1)
                : null;

            return [
                'id' => $o->id,
                'order_code' => $o->order_code,
                'tenant_id' => $o->tenant_id,
                'tenant_name' => $o->tenant->name ?? 'N/A',
                'tenant_location' => $o->tenant->floor_location ?? '-',
                'tenant_zone' => $o->tenant->zone ?? '-',
                'customer_name' => $o->customer_name ?? 'Penumpang Juanda',
                'flight_number' => $o->flight_number ?? '-',
                'gate' => $o->gate ?? '-',
                'pickup_method' => $o->pickup_method ?? 'ambil_sendiri',
                'pickup_method_label' => ($o->pickup_method === 'diantar') ? 'Diantar ke Gate' : 'Ambil di Gerai',
                'delivery_target' => $o->deliveryLocation
                    ? ($o->deliveryLocation->name . ' (' . $o->deliveryLocation->terminal . ')')
                    : ($o->tenant->floor_location ?? '-'),
                'ordered_at_formatted' => Carbon::parse($o->ordered_at)->format('d M Y, H:i') . ' WIB',
                'time_formatted' => Carbon::parse($o->ordered_at)->format('H:i') . ' WIB',
                'ordered_hour' => (int) Carbon::parse($o->ordered_at)->format('H'),
                'status' => $o->status,
                'is_paid' => (bool) $o->is_paid,
                'payment_method' => strtoupper($o->payment_method ?? 'QRIS'),
                'total_amount' => (float) $o->total_amount,
                'total_amount_formatted' => 'Rp ' . number_format($o->total_amount, 0, ',', '.'),
                'sla_minutes' => $slaMinutes,
                'sla_compliant' => $slaMinutes !== null ? ($slaMinutes <= 15) : null,
                'items_count' => $o->orderItems->sum('quantity'),
                'items_summary' => $o->orderItems->map(fn($i) => $i->quantity . 'x ' . $i->product_name_snapshot)->implode(', '),
            ];
        });

        return response()->json([
            'data' => $ordersData,
            'pagination' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'from' => $paginated->firstItem(),
                'to' => $paginated->lastItem(),
            ],
            'summary' => [
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'total_revenue_formatted' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
                'avg_sla_minutes' => $avgSlaMinutes,
                'sla_compliant_percentage' => $slaCompliantPercentage,
                'ready_orders_count' => $readyOrdersCount,
            ]
        ]);
    }

    /**
     * Endpoint JSON: Data 5 Grafik Terhubung (Cross-Filtering).
     */
    public function drilldownCharts(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        // Sanitize string 'semua' to null
        $cleanInputs = [];
        foreach (['status', 'sla', 'hour', 'pickup_method', 'tenant_id', 'category'] as $key) {
            if ($request->has($key) && ($request->input($key) === 'semua' || $request->input($key) === '')) {
                $cleanInputs[$key] = null;
            }
        }
        if ($request->filled('category')) {
            $cleanInputs['category'] = html_entity_decode((string)$request->input('category'), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        if (!empty($cleanInputs)) {
            $request->merge($cleanInputs);
        }

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'date' => 'nullable|date',
            'tenant_id' => 'nullable|exists:tenants,id',
            'category' => 'nullable|string|in:Beverages & Coffee,Fast Food & Restaurant,Bakery & Pastry,Snacks & Convenience,Indonesian Traditional,Lounge',
            'status' => 'nullable|in:menunggu,diproses,siap,selesai,dibatalkan',
            'sla' => 'nullable|in:compliant,breached',
            'hour' => 'nullable|integer|min:0|max:23',
            'pickup_method' => 'nullable|in:ambil_sendiri,diantar',
            'search' => 'nullable|string|max:100',
        ]);

        $baseQuery = $this->buildFilteredOrderQuery($request);
        $isSqlite = DB::getDriverName() === 'sqlite';

        // 1. Pesanan per Hari (Klik = Filter Tanggal)
        $dateSql = $isSqlite ? 'date(orders.ordered_at)' : 'DATE(orders.ordered_at)';
        $dailyRows = (clone $baseQuery)
            ->selectRaw("{$dateSql} as order_date, COUNT(*) as count")
            ->groupBy('order_date')
            ->orderBy('order_date', 'asc')
            ->get();

        $ordersPerDay = [
            'labels' => $dailyRows->map(fn($r) => Carbon::parse($r->order_date)->format('d M'))->toArray(),
            'raw_dates' => $dailyRows->pluck('order_date')->toArray(),
            'values' => $dailyRows->pluck('count')->map(fn($v) => (int)$v)->toArray(),
        ];

        // 2. Pesanan per Jam (Klik = Filter Jam 0-23)
        $hourSql = $isSqlite ? "CAST(strftime('%H', orders.ordered_at) AS INTEGER)" : "HOUR(orders.ordered_at)";
        $hourlyMap = (clone $baseQuery)
            ->selectRaw("{$hourSql} as order_hour, COUNT(*) as count")
            ->groupBy('order_hour')
            ->orderBy('order_hour', 'asc')
            ->pluck('count', 'order_hour')
            ->toArray();

        $hourLabels = [];
        $hourRaw = [];
        $hourValues = [];
        for ($h = 0; $h < 24; $h++) {
            $hourLabels[] = sprintf('%02d:00', $h);
            $hourRaw[] = $h;
            $hourValues[] = (int) ($hourlyMap[$h] ?? 0);
        }
        $ordersPerHour = [
            'labels' => $hourLabels,
            'raw_hours' => $hourRaw,
            'values' => $hourValues,
        ];

        // 3. Distribusi Status (Klik Irisan = Filter Status)
        $statusCounts = (clone $baseQuery)
            ->selectRaw('orders.status, COUNT(*) as count')
            ->groupBy('orders.status')
            ->pluck('count', 'orders.status')
            ->toArray();

        $statusConfig = [
            'menunggu' => ['label' => 'Menunggu', 'color' => '#f59e0b'],
            'diproses' => ['label' => 'Diproses', 'color' => '#3b82f6'],
            'siap' => ['label' => 'Siap', 'color' => '#a855f7'],
            'selesai' => ['label' => 'Selesai', 'color' => '#10b981'],
            'dibatalkan' => ['label' => 'Dibatalkan', 'color' => '#f43f5e'],
        ];

        $statusLabels = [];
        $statusRaw = [];
        $statusValues = [];
        $statusColors = [];
        foreach ($statusConfig as $stKey => $stData) {
            $statusLabels[] = $stData['label'];
            $statusRaw[] = $stKey;
            $statusValues[] = (int) ($statusCounts[$stKey] ?? 0);
            $statusColors[] = $stData['color'];
        }

        $statusDistribution = [
            'labels' => $statusLabels,
            'raw_statuses' => $statusRaw,
            'values' => $statusValues,
            'colors' => $statusColors,
        ];

        // 4. Top Tenant (Klik Bar = Filter Tenant)
        $tenantRows = (clone $baseQuery)
            ->join('tenants', 'orders.tenant_id', '=', 'tenants.id')
            ->selectRaw('tenants.id as tenant_id, tenants.name as tenant_name, COUNT(orders.id) as count')
            ->groupBy('tenants.id', 'tenants.name')
            ->orderByDesc('count')
            ->limit(7)
            ->get();

        $topTenants = [
            'labels' => $tenantRows->pluck('tenant_name')->toArray(),
            'tenant_ids' => $tenantRows->pluck('tenant_id')->toArray(),
            'values' => $tenantRows->pluck('count')->map(fn($v) => (int)$v)->toArray(),
        ];

        // 5. Menu Terlaris (Klik Bar = Filter Menu)
        $menuRows = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.id', (clone $baseQuery)->select('orders.id'))
            ->selectRaw('order_items.product_name_snapshot as menu_name, SUM(order_items.quantity) as total_qty')
            ->groupBy('order_items.product_name_snapshot')
            ->orderByDesc('total_qty')
            ->limit(7)
            ->get();

        $topMenus = [
            'labels' => $menuRows->pluck('menu_name')->toArray(),
            'menu_names' => $menuRows->pluck('menu_name')->toArray(),
            'values' => $menuRows->pluck('total_qty')->map(fn($v) => (int)$v)->toArray(),
        ];

        // 6. Volume Pesanan dan Omset per Kategori (Klik Bar = Filter Kategori)
        $categoryRows = DB::table('orders')
            ->join('tenants', 'orders.tenant_id', '=', 'tenants.id')
            ->whereIn('orders.id', (clone $baseQuery)->select('orders.id'))
            ->whereNotNull('tenants.category')
            ->where('tenants.category', '!=', '')
            ->selectRaw('tenants.category as category_name, COUNT(orders.id) as order_count, SUM(CASE WHEN orders.is_paid = 1 THEN orders.total_amount ELSE 0 END) as total_revenue')
            ->groupBy('tenants.category')
            ->orderByDesc('order_count')
            ->get();

        $categoryDistribution = [
            'labels' => $categoryRows->pluck('category_name')->toArray(),
            'categories' => $categoryRows->pluck('category_name')->toArray(),
            'order_counts' => $categoryRows->pluck('order_count')->map(fn($v) => (int)$v)->toArray(),
            'revenues' => $categoryRows->pluck('total_revenue')->map(fn($v) => (float)$v)->toArray(),
            'revenues_formatted' => $categoryRows->map(fn($r) => 'Rp ' . number_format($r->total_revenue, 0, ',', '.'))->toArray(),
        ];

        return response()->json([
            'orders_per_day' => $ordersPerDay,
            'orders_per_hour' => $ordersPerHour,
            'status_distribution' => $statusDistribution,
            'top_tenants' => $topTenants,
            'top_menus' => $topMenus,
            'category_distribution' => $categoryDistribution,
        ]);
    }

    /**
     * Endpoint JSON: Detail Pesanan untuk Side Drawer Panel.
     */
    public function drilldownOrderDetail(Order $order)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        $order->load([
            'tenant:id,name,floor_location,zone',
            'orderItems.product:id,name,image_url',
            'customer:id,name,phone,email',
            'deliveryLocation:id,name,terminal',
            'complaints:id,order_id,category,status,description,created_at'
        ]);

        $slaMinutes = ($order->ready_at && $order->ordered_at && $order->ready_at >= $order->ordered_at)
            ? round(Carbon::parse($order->ordered_at)->diffInMinutes(Carbon::parse($order->ready_at)), 1)
            : null;

        $timeline = [];
        if ($order->ordered_at) {
            $timeline[] = [
                'stage' => 'Pesanan Diterima',
                'time' => Carbon::parse($order->ordered_at)->format('d M Y, H:i') . ' WIB',
                'desc' => 'Pesanan berhasil dibuat oleh pelanggan di sistem.',
                'done' => true,
                'status' => 'ordered',
            ];
        }
        if ($order->ready_at) {
            $timeline[] = [
                'stage' => 'Makanan / Minuman Siap',
                'time' => Carbon::parse($order->ready_at)->format('d M Y, H:i') . ' WIB',
                'desc' => $slaMinutes !== null
                    ? "Waktu saji: {$slaMinutes} menit " . ($slaMinutes <= 15 ? '(✅ Sesuai Standar SOP ≤ 15 mnt)' : '(⚠️ Melewati Batas SOP SLA)')
                    : 'Pesanan siap diserahkan.',
                'done' => true,
                'sla_compliant' => $slaMinutes !== null ? ($slaMinutes <= 15) : null,
                'status' => 'ready',
            ];
        } elseif ($order->status !== 'dibatalkan') {
            $timeline[] = [
                'stage' => 'Persiapan Dapur',
                'time' => 'Sedang berlangsung...',
                'desc' => 'Tenant sedang menyiapkan pesanan di gerai.',
                'done' => false,
                'status' => 'cooking',
            ];
        }
        if ($order->completed_at) {
            $timeline[] = [
                'stage' => 'Pesanan Selesai / Diserahkan',
                'time' => Carbon::parse($order->completed_at)->format('d M Y, H:i') . ' WIB',
                'desc' => 'Pesanan telah diterima oleh pelanggan / porter bandara.',
                'done' => true,
                'status' => 'completed',
            ];
        } elseif ($order->status === 'dibatalkan') {
            $timeline[] = [
                'stage' => 'Pesanan Dibatalkan',
                'time' => Carbon::parse($order->updated_at)->format('d M Y, H:i') . ' WIB',
                'desc' => 'Pesanan dibatalkan atau auto-cancel karena batas waktu pembayaran/konfirmasi.',
                'done' => true,
                'is_cancelled' => true,
                'status' => 'cancelled',
            ];
        }

        return response()->json([
            'id' => $order->id,
            'order_code' => $order->order_code,
            'status' => $order->status,
            'status_label' => ucfirst($order->status),
            'ordered_at_formatted' => Carbon::parse($order->ordered_at)->format('d M Y, H:i') . ' WIB',
            'is_paid' => (bool) $order->is_paid,
            'payment_method' => strtoupper($order->payment_method ?? 'QRIS'),
            'total_amount' => (float) $order->total_amount,
            'total_amount_formatted' => 'Rp ' . number_format($order->total_amount, 0, ',', '.'),
            'delivery_fee' => (float) ($order->delivery_fee ?? 0),
            'delivery_fee_formatted' => 'Rp ' . number_format($order->delivery_fee ?? 0, 0, ',', '.'),
            'pickup_method' => $order->pickup_method ?? 'ambil_sendiri',
            'pickup_method_label' => ($order->pickup_method === 'diantar') ? 'Diantar Porter ke Gate' : 'Ambil Sendiri di Gerai',
            'delivery_target' => $order->deliveryLocation
                ? ($order->deliveryLocation->name . ' • ' . $order->deliveryLocation->terminal)
                : ($order->tenant->floor_location ?? 'Counter Tenant'),
            'tenant' => [
                'id' => $order->tenant->id ?? null,
                'name' => $order->tenant->name ?? 'N/A',
                'location' => $order->tenant->floor_location ?? '-',
                'zone' => $order->tenant->zone ?? '-',
            ],
            'customer' => [
                'name' => $order->customer_name ?? ($order->customer->name ?? 'Penumpang Juanda'),
                'flight_number' => $order->flight_number ?? '-',
                'gate' => $order->gate ?? '-',
                'boarding_time' => $order->boarding_time ? Carbon::parse($order->boarding_time)->format('H:i') . ' WIB' : '-',
                'phone' => $order->customer->phone ?? '-',
            ],
            'sla' => [
                'minutes' => $slaMinutes,
                'compliant' => $slaMinutes !== null ? ($slaMinutes <= 15) : null,
                'badge' => $slaMinutes !== null
                    ? ($slaMinutes <= 15 ? "✅ {$slaMinutes} mnt (SOP)" : "⚠️ {$slaMinutes} mnt (Breached)")
                    : 'Belum siap',
            ],
            'items' => $order->orderItems->map(fn($item) => [
                'id' => $item->id,
                'name' => $item->product_name_snapshot,
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'unit_price_formatted' => 'Rp ' . number_format($item->unit_price, 0, ',', '.'),
                'subtotal' => (float) $item->subtotal,
                'subtotal_formatted' => 'Rp ' . number_format($item->subtotal, 0, ',', '.'),
            ]),
            'timeline' => $timeline,
            'complaints' => $order->complaints->map(fn($c) => [
                'id' => $c->id,
                'category' => $c->category,
                'status' => $c->status,
                'description' => $c->description,
            ]),
        ]);
    }

    /**
     * Halaman Simulasi What-If: Kapasitas Tenant & Skenario Omset.
     */
    public function whatif(Request $request, CapacityPlanner $planner)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        $filters = $this->resolveFilters($request);
        extract($filters);

        // Bagian 1: Inisialisasi simulasi Kapasitas Tenant awal (0% growth, 1 staf, baseline S0)
        $initialCapacity = $planner->calculate(
            $startDate,
            $endDate,
            $tenantId,
            0.0,
            1,
            null
        );

        // Bagian 2: Baseline Metrik untuk Skenario Omset (kalkulator lama)
        // Fallback revenue in date range
        $totalRevenue = (float) Order::whereBetween('ordered_at', [$startDate, $endDate])
            ->when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
            ->where('is_paid', true)
            ->sum('total_amount');

        // 1. Weekly Baseline Metrics (7 Hari Terakhir)
        $weekAgo = Carbon::now()->subDays(7)->startOfDay();
        $weeklyOrderQuery = Order::where('ordered_at', '>=', $weekAgo);
        if ($tenantId) {
            $weeklyOrderQuery->where('tenant_id', $tenantId);
        }
        $weeklyTotalRevenue = (float) (clone $weeklyOrderQuery)->where('is_paid', true)->sum('total_amount');
        $weeklyOrdersCount = (int) (clone $weeklyOrderQuery)->count();
        $weeklyAov = $weeklyOrdersCount > 0 ? round($weeklyTotalRevenue / $weeklyOrdersCount) : 30000;
        $weeklyVisitors = (int) (clone $weeklyOrderQuery)->distinct('customer_id')->count('customer_id');
        if ($weeklyVisitors === 0) {
            $weeklyVisitors = max(1, (int) round($weeklyOrdersCount * 1.5));
        }
        $weeklySlaQuery = (clone $weeklyOrderQuery)->whereNotNull('ready_at');
        $weeklyAvgSla = round((clone $weeklySlaQuery)->selectRaw("AVG({$diffMinutesSql}) as avg_m")->value('avg_m') ?? 11.5, 1);

        // 2. Monthly Baseline Metrics (30 Hari Terakhir)
        $monthAgo = Carbon::now()->subDays(30)->startOfDay();
        $monthlyOrderQuery = Order::where('ordered_at', '>=', $monthAgo);
        if ($tenantId) {
            $monthlyOrderQuery->where('tenant_id', $tenantId);
        }
        $monthlyTotalRevenue = (float) (clone $monthlyOrderQuery)->where('is_paid', true)->sum('total_amount');
        $monthlyOrdersCount = (int) (clone $monthlyOrderQuery)->count();
        $monthlyAov = $monthlyOrdersCount > 0 ? round($monthlyTotalRevenue / $monthlyOrdersCount) : 30000;
        $monthlyVisitors = (int) (clone $monthlyOrderQuery)->distinct('customer_id')->count('customer_id');
        if ($monthlyVisitors === 0) {
            $monthlyVisitors = max(1, (int) round($monthlyOrdersCount * 1.5));
        }
        $monthlySlaQuery = (clone $monthlyOrderQuery)->whereNotNull('ready_at');
        $monthlyAvgSla = round((clone $monthlySlaQuery)->selectRaw("AVG({$diffMinutesSql}) as avg_m")->value('avg_m') ?? 11.5, 1);

        return view('admin.analytics.whatif', compact(
            'days',
            'startDate',
            'endDate',
            'startDateFormatted',
            'endDateFormatted',
            'tenantId',
            'selectedTenant',
            'tenants',
            'initialCapacity',
            'totalRevenue',
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

    /**
     * Endpoint POST: Hitung Simulasi Kapasitas Tenant Real-Time.
     */
    public function calculateWhatIf(Request $request, CapacityPlanner $planner)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        if ($request->has('tenant_id') && ($request->input('tenant_id') === 'semua' || $request->input('tenant_id') === '')) {
            $request->merge(['tenant_id' => null]);
        }

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'tenant_id' => 'nullable|exists:tenants,id',
            'order_growth_pct' => 'nullable|numeric|min:-50|max:200',
            'staff_count' => 'nullable|integer|min:1|max:10',
            'target_sla_minutes' => 'nullable|numeric|min:1|max:120',
        ]);

        $filters = $this->resolveFilters($request);
        $startDate = $filters['startDate'];
        $endDate = $filters['endDate'];
        $tenantId = $request->input('tenant_id') ?: null;
        $orderGrowthPct = (float) $request->input('order_growth_pct', 0.0);
        $staffCount = (int) $request->input('staff_count', 1);
        $targetSlaMinutes = $request->filled('target_sla_minutes') ? (float) $request->input('target_sla_minutes') : null;

        $result = $planner->calculate(
            $startDate,
            $endDate,
            $tenantId,
            $orderGrowthPct,
            $staffCount,
            $targetSlaMinutes
        );

        return response()->json($result);
    }

    /**
     * Halaman Segmentasi Pelanggan: Enterprise CRM - Loyalitas & Frekuensi Belanja.
     */
    public function customers(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        $filters = $this->resolveFilters($request);
        extract($filters);

        $customerSegmentation = DB::table('customers')
            ->selectRaw("
                SUM(CASE WHEN total_orders = 1 THEN 1 ELSE 0 END) as new_customers,
                SUM(CASE WHEN total_orders BETWEEN 2 AND 5 THEN 1 ELSE 0 END) as regular_customers,
                SUM(CASE WHEN total_orders > 5 THEN 1 ELSE 0 END) as frequent_customers,
                COALESCE(SUM(CASE WHEN total_orders > 0 THEN 1 ELSE 0 END), 0) as total_customers
            ")->first();

        return view('admin.analytics.customers', compact(
            'days',
            'startDate',
            'endDate',
            'startDateFormatted',
            'endDateFormatted',
            'tenantId',
            'selectedTenant',
            'tenants',
            'customerSegmentation'
        ));
    }
}
