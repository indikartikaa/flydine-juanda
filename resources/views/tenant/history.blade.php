@extends('layouts.tenant')

@section('title', 'Riwayat Pesanan')

@section('content')
<div x-data="{ 
    showModal: false, 
    selectedOrder: null,
    openReceipt(order) {
        this.selectedOrder = order;
        this.showModal = true;
    }
}" class="space-y-6">

    <!-- ========================================================================= -->
    <!-- 1. HEADER HALAMAN                                                         -->
    <!-- ========================================================================= -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Riwayat Pesanan</h2>
            <p class="text-xs sm:text-sm font-semibold text-slate-500 mt-0.5">
                Daftar seluruh transaksi yang telah selesai disajikan atau dibatalkan.
            </p>
        </div>
        
        <div class="flex items-center gap-3 shrink-0">
            <!-- Tombol Pintasan ke Antrean Pesanan Aktif -->
            <a href="{{ url('/tenant/orders') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#005ea2] hover:bg-[#004b82] active:scale-95 text-white text-xs sm:text-sm font-bold shadow-sm shadow-blue-600/20 transition-all cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>Antrean Pesanan Aktif</span>
            </a>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. RINGKASAN METRIK PERFORMA (RESPONSIF MOBILE TANPA TRUNCATE ANGKA)       -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        
        <!-- Kartu 1: Total Omzet Selesai -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Total Omzet Bersih</span>
                <span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-emerald-500"></span>
            </div>
            <h3 class="text-base sm:text-xl md:text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
            </h3>
            <span class="text-[10px] sm:text-xs font-semibold text-emerald-600 block mt-1">Akumulasi Pesanan Sukses</span>
        </div>

        <!-- Kartu 2: Pesanan Sukses Selesai -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Pesanan Selesai</span>
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
            </div>
            <h3 class="text-base sm:text-xl md:text-2xl font-black text-slate-900 tracking-tight leading-tight">
                {{ number_format($totalCompleted ?? 0, 0, ',', '.') }}
            </h3>
            <span class="text-[10px] sm:text-xs font-semibold text-slate-500 block mt-1">Total Diserahkan</span>
        </div>

        <!-- Kartu 3: Selesai Hari Ini -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Selesai Hari Ini</span>
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
            </div>
            <h3 class="text-base sm:text-xl md:text-2xl font-black text-slate-900 tracking-tight leading-tight">
                {{ number_format($todayCompleted ?? 0, 0, ',', '.') }}
            </h3>
            <span class="text-[10px] sm:text-xs font-semibold text-indigo-600 block mt-1">Transaksi Hari Ini</span>
        </div>

        <!-- Kartu 4: Pesanan Dibatalkan -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Dibatalkan / Tolak</span>
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
            </div>
            <h3 class="text-base sm:text-xl md:text-2xl font-black text-slate-900 tracking-tight leading-tight">
                {{ number_format($totalCancelled ?? 0, 0, ',', '.') }}
            </h3>
            <span class="text-[10px] sm:text-xs font-semibold text-rose-600 block mt-1">Tidak Terselesaikan</span>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- 3. BAR FILTER PENCARIAN (RAPI & JARAK PROPORSIONAL)                       -->
    <!-- ========================================================================= -->
    <div class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
        <form action="{{ route('tenant.orders.history') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2.5 sm:gap-3">
            
            <!-- Input Pencarian -->
            <div class="relative flex-1 min-w-[200px]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 absolute left-3.5 top-3 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari ID, pelanggan, flight..." 
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/15 outline-none transition-all">
            </div>

            <!-- Filter Status -->
            <div class="w-full sm:w-44">
                <select name="status_filter" 
                        onchange="this.form.submit()" 
                        class="w-full bg-slate-50 border border-slate-200 text-slate-700 font-semibold text-sm rounded-xl pl-3 pr-8 py-2 focus:bg-white focus:outline-none focus:border-[#005ea2] cursor-pointer hover:bg-slate-100 transition-colors">
                    <option value="all" {{ request('status_filter') == 'all' || !request('status_filter') ? 'selected' : '' }}>Semua Status</option>
                    <option value="selesai" {{ request('status_filter') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ in_array(request('status_filter'), ['dibatalkan', 'ditolak']) ? 'selected' : '' }}>Dibatalkan / Tolak</option>
                </select>
            </div>

            <!-- Filter Periode Tanggal -->
            <div class="w-full sm:w-40">
                <select name="date_filter" 
                        onchange="this.form.submit()" 
                        class="w-full bg-slate-50 border border-slate-200 text-slate-700 font-semibold text-sm rounded-xl pl-3 pr-8 py-2 focus:bg-white focus:outline-none focus:border-[#005ea2] cursor-pointer hover:bg-slate-100 transition-colors">
                    <option value="all" {{ request('date_filter') == 'all' || !request('date_filter') ? 'selected' : '' }}>Semua Periode</option>
                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="yesterday" {{ request('date_filter') == 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                    <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <!-- Tombol Filter -->
                <button type="submit" 
                        class="flex-1 sm:flex-none bg-[#005ea2] hover:bg-[#004b82] active:scale-95 text-white px-5 py-2 rounded-xl text-sm font-bold transition-all cursor-pointer shadow-2xs">
                    Filter
                </button>

                <!-- Tombol Reset Filter -->
                @if(request('search') || (request('date_filter') && request('date_filter') !== 'all') || (request('status_filter') && request('status_filter') !== 'all'))
                    <a href="{{ route('tenant.orders.history') }}" 
                       class="text-sm font-semibold text-slate-500 hover:text-rose-600 px-3 py-2 transition-colors flex items-center gap-1.5"
                       title="Reset Semua Filter">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>Reset</span>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- ========================================================================= -->
    <!-- 4. ARSIP RIWAYAT PESANAN (MOBILE CARD LIST & DESKTOP PROPORTIONAL TABLE)   -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        
        <!-- A. TAMPILAN MOBILE: LIST KARTU ADAPTIF (< md) -->
        <div class="block md:hidden divide-y divide-slate-100">
            @forelse($orders as $order)
                @php
                    $orderJson = [
                        'code' => $order->order_code,
                        'customer' => $order->customer_name ?: 'Pelanggan Bandara',
                        'phone' => $order->customer?->phone_number ?: '-',
                        'flight' => $order->flight_number ?: '-',
                        'gate' => $order->gate ?: '-',
                        'pickup' => $order->pickup_method === 'diantar' ? 'Porter Bandara (Diantar)' : 'Ambil di Gerai',
                        'payment' => $order->payment_method ?: 'QRIS',
                        'notes' => $order->notes ?: null,
                        'ordered_at' => \Carbon\Carbon::parse($order->ordered_at)->format('d M Y, H:i') . ' WIB',
                        'completed_at' => $order->completed_at ? \Carbon\Carbon::parse($order->completed_at)->format('d M Y, H:i') . ' WIB' : '-',
                        'status' => $order->status,
                        'total' => 'Rp ' . number_format($order->total_amount, 0, ',', '.'),
                        'items' => $order->orderItems->map(fn($item) => [
                            'name' => $item->product_name_snapshot,
                            'qty' => $item->quantity,
                            'price' => 'Rp ' . number_format($item->unit_price, 0, ',', '.'),
                            'subtotal' => 'Rp ' . number_format($item->subtotal, 0, ',', '.')
                        ])->values()->all()
                    ];

                    $itemSummary = $order->orderItems->map(fn($i) => $i->quantity . 'x ' . $i->product_name_snapshot)->join(', ');
                    $firstItem = $order->orderItems->first();
                    $otherCount = $order->orderItems->count() - 1;
                    $totalQty = $order->orderItems->sum('quantity');
                    $dur = $order->completed_at ? \Carbon\Carbon::parse($order->ordered_at)->diffInMinutes(\Carbon\Carbon::parse($order->completed_at)) : 0;
                @endphp
                <div class="p-4 space-y-2.5">
                    <!-- Baris Atas: ID Pesanan & Status Badge -->
                    <div class="flex items-center justify-between">
                        <button type="button" 
                                @click="openReceipt(@js($orderJson))" 
                                class="font-mono font-bold text-[#005ea2] hover:underline inline-flex items-center gap-1.5 text-sm cursor-pointer"
                                title="Buka struk digital transaksi">
                            <span>{{ $order->order_code }}</span>
                            <svg class="w-3.5 h-3.5 text-[#005ea2] opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </button>
                        
                        <!-- Status Badge -->
                        @if($order->status === 'selesai')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Selesai
                            </span>
                        @elseif($order->status === 'ditolak')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                Dibatalkan
                            </span>
                        @endif
                    </div>

                    <!-- Pelanggan & Info Penerbangan / HP -->
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <span class="font-bold text-slate-800 block text-sm truncate">
                                {{ $order->customer_name ?: 'Pelanggan Bandara' }}
                            </span>
                            @if($order->flight_number)
                                <span class="text-xs font-semibold text-slate-500 flex items-center gap-1.5 mt-0.5 whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                    </svg>
                                    <span>{{ $order->flight_number }}</span>
                                    @if($order->gate)
                                        <span class="text-slate-300">•</span>
                                        <span>{{ \Illuminate\Support\Str::startsWith(strtolower(trim($order->gate)), 'gate') ? trim($order->gate) : 'Gate ' . trim($order->gate) }}</span>
                                    @endif
                                </span>
                            @elseif($order->customer && $order->customer->phone_number)
                                <span class="text-xs font-semibold text-slate-500 flex items-center gap-1.5 mt-0.5 whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span>{{ $order->customer->phone_number }}</span>
                                    <span class="text-slate-300">•</span>
                                    <span class="text-slate-400 font-medium">Umum</span>
                                </span>
                            @endif
                        </div>

                        <!-- Total & Waktu Pesan -->
                        <div class="text-right shrink-0">
                            <span class="font-black text-slate-900 block text-sm">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                            <span class="text-[11px] font-medium text-slate-400 block mt-0.5">
                                {{ \Carbon\Carbon::parse($order->ordered_at)->format('d M, H:i') }} WIB
                            </span>
                        </div>
                    </div>

                    <!-- Ringkasan Menu & Cara Ambil -->
                    <div class="bg-slate-50/80 p-2.5 rounded-xl text-xs space-y-1 border border-slate-100">
                        <div class="flex items-center gap-2">
                            @if($order->pickup_method === 'diantar')
                                <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-200">
                                    🛵 Porter
                                </span>
                            @else
                                <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-md bg-white text-slate-600 border border-slate-200">
                                    🏃 Gerai
                                </span>
                            @endif
                            <span class="font-bold text-slate-700">{{ $totalQty }} porsi</span>
                        </div>
                        <div class="text-slate-600 font-medium truncate" title="{{ $itemSummary }}">
                            @if($firstItem)
                                <span>{{ $firstItem->quantity }}x {{ $firstItem->product_name_snapshot }}</span>@if($otherCount > 0)<span class="text-slate-400 font-semibold">, +{{ $otherCount }} lainnya...</span>@endif
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Kartu: Selesai & Durasi SLA + Tombol Buka Struk -->
                    <div class="flex items-center justify-between pt-1">
                        <div>
                            @if($order->completed_at)
                                <div class="inline-flex items-center gap-1.5">
                                    <span class="text-xs font-bold text-slate-700">
                                        {{ \Carbon\Carbon::parse($order->completed_at)->format('H:i') }} WIB
                                    </span>
                                    @if($dur > 0)
                                        @if($dur <= 15)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-1.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200" title="Sesuai SLA (≤ 15 mnt)">
                                                <svg class="w-2.5 h-2.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>{{ $dur }} mnt</span>
                                            </span>
                                        @elseif($dur <= 20)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-1.5 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200" title="Toleransi (16 - 20 mnt)">
                                                <svg class="w-2.5 h-2.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>{{ $dur }} mnt</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-1.5 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200" title="Lewat SLA (> 20 mnt)">
                                                <svg class="w-2.5 h-2.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                <span>{{ $dur }} mnt</span>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-slate-400 font-semibold">-</span>
                            @endif
                        </div>

                        <button type="button" 
                                @click="openReceipt(@js($orderJson))" 
                                class="inline-flex items-center gap-1 text-xs font-bold text-[#005ea2] hover:text-[#004b82] px-2.5 py-1 rounded-lg bg-blue-50/70 hover:bg-blue-100/70 border border-blue-100 transition cursor-pointer">
                            <span>Struk</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-black text-slate-900">Belum Ada Data Riwayat</h4>
                    <p class="text-xs text-slate-500 font-medium mt-1">
                        Transaksi yang selesai atau dibatalkan akan otomatis muncul di sini.
                    </p>
                </div>
            @endforelse
        </div>

        <!-- B. TAMPILAN DESKTOP: TABEL LENGKAP & PROPORSIONAL (>= md) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[760px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 text-xs uppercase tracking-wider font-bold">
                        <th class="px-5 py-3.5 w-[19%]">ID & Waktu Pesan</th>
                        <th class="px-5 py-3.5 w-[21%]">Pelanggan & Penerbangan</th>
                        <th class="px-5 py-3.5 w-[24%]">Pengambilan & Menu</th>
                        <th class="px-5 py-3.5 w-[21%]">Selesai & Durasi</th>
                        <th class="px-5 py-3.5 w-[15%] text-right">Total & Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        @php
                            $orderJson = [
                                'code' => $order->order_code,
                                'customer' => $order->customer_name ?: 'Pelanggan Bandara',
                                'phone' => $order->customer?->phone_number ?: '-',
                                'flight' => $order->flight_number ?: '-',
                                'gate' => $order->gate ?: '-',
                                'pickup' => $order->pickup_method === 'diantar' ? 'Porter Bandara (Diantar)' : 'Ambil di Gerai',
                                'payment' => $order->payment_method ?: 'QRIS',
                                'notes' => $order->notes ?: null,
                                'ordered_at' => \Carbon\Carbon::parse($order->ordered_at)->format('d M Y, H:i') . ' WIB',
                                'completed_at' => $order->completed_at ? \Carbon\Carbon::parse($order->completed_at)->format('d M Y, H:i') . ' WIB' : '-',
                                'status' => $order->status,
                                'total' => 'Rp ' . number_format($order->total_amount, 0, ',', '.'),
                                'items' => $order->orderItems->map(fn($item) => [
                                    'name' => $item->product_name_snapshot,
                                    'qty' => $item->quantity,
                                    'price' => 'Rp ' . number_format($item->unit_price, 0, ',', '.'),
                                    'subtotal' => 'Rp ' . number_format($item->subtotal, 0, ',', '.')
                                ])->values()->all()
                            ];

                            $itemSummary = $order->orderItems->map(fn($i) => $i->quantity . 'x ' . $i->product_name_snapshot)->join(', ');
                            $firstItem = $order->orderItems->first();
                            $otherCount = $order->orderItems->count() - 1;
                            $totalQty = $order->orderItems->sum('quantity');
                            $dur = $order->completed_at ? \Carbon\Carbon::parse($order->ordered_at)->diffInMinutes(\Carbon\Carbon::parse($order->completed_at)) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            
                            <!-- 1. ID & Waktu Pesan (Font Standar Jelas & Nyaman) -->
                            <td class="px-5 py-4 align-middle">
                                <button type="button" 
                                        @click="openReceipt(@js($orderJson))" 
                                        class="font-mono font-bold text-[#005ea2] hover:text-[#004b82] hover:underline inline-flex items-center gap-1.5 text-sm cursor-pointer group"
                                        title="Buka struk digital transaksi">
                                    <span>{{ $order->order_code }}</span>
                                    <svg class="w-3.5 h-3.5 text-[#005ea2] opacity-60 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </button>
                                <span class="text-xs font-medium text-slate-400 block mt-1 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($order->ordered_at)->format('d M Y, H:i') }} WIB
                                </span>
                            </td>

                            <!-- 2. Pelanggan & Penerbangan / Kontak -->
                            <td class="px-5 py-4 align-middle">
                                <span class="font-bold text-slate-800 block text-sm truncate max-w-[200px]">
                                    {{ $order->customer_name ?: 'Pelanggan Bandara' }}
                                </span>
                                @if($order->flight_number)
                                    <span class="text-xs font-semibold text-slate-500 flex items-center gap-1.5 mt-1 whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                        </svg>
                                        <span>{{ $order->flight_number }}</span>
                                        @if($order->gate)
                                            <span class="text-slate-300">•</span>
                                            <span>{{ \Illuminate\Support\Str::startsWith(strtolower(trim($order->gate)), 'gate') ? trim($order->gate) : 'Gate ' . trim($order->gate) }}</span>
                                        @endif
                                    </span>
                                @elseif($order->customer && $order->customer->phone_number)
                                    <span class="text-xs font-semibold text-slate-500 flex items-center gap-1.5 mt-1 whitespace-nowrap" title="Pelanggan Umum / Non-Penerbangan">
                                        <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <span>{{ $order->customer->phone_number }}</span>
                                        <span class="text-slate-300">•</span>
                                        <span class="text-slate-400 font-medium">Umum</span>
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 mt-1 block">Pelanggan Umum</span>
                                @endif
                            </td>

                            <!-- 3. Pengambilan & Menu (Proporsional & Truncate Rapi) -->
                            <td class="px-5 py-4 align-middle">
                                <div class="flex items-center gap-2 mb-1">
                                    @if($order->pickup_method === 'diantar')
                                        <span class="inline-flex items-center text-xs font-bold px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-200 shrink-0">
                                            🛵 Porter
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-xs font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 border border-slate-200 shrink-0">
                                            🏃 Gerai
                                        </span>
                                    @endif
                                    <span class="text-xs font-bold text-slate-700 shrink-0">{{ $totalQty }} porsi</span>
                                </div>
                                <div class="text-xs font-medium text-slate-600 truncate max-w-[280px]" title="{{ $itemSummary }}">
                                    @if($firstItem)
                                        <span>{{ $firstItem->quantity }}x {{ $firstItem->product_name_snapshot }}</span>@if($otherCount > 0)<span class="text-slate-400 font-semibold">, +{{ $otherCount }} lainnya...</span>@endif
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </div>
                            </td>

                            <!-- 4. Waktu Selesai & Durasi SLA (Ditampilkan Sebelahan Rapi) -->
                            <td class="px-5 py-4 align-middle whitespace-nowrap">
                                @if($order->completed_at)
                                    <div class="inline-flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-700">
                                            {{ \Carbon\Carbon::parse($order->completed_at)->format('H:i') }} WIB
                                        </span>
                                        @if($dur > 0)
                                            @if($dur <= 15)
                                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200" title="Sesuai Target SLA (≤ 15 mnt)">
                                                    <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>{{ $dur }} mnt</span>
                                                </span>
                                            @elseif($dur <= 20)
                                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200" title="Batas Toleransi SLA (16 - 20 mnt)">
                                                    <svg class="w-3 h-3 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>{{ $dur }} mnt</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200" title="Melebihi Batas Waktu SLA (> 20 mnt)">
                                                    <svg class="w-3 h-3 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                    <span>{{ $dur }} mnt</span>
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-300 font-bold text-xs">-</span>
                                @endif
                            </td>

                            <!-- 5. Total & Status -->
                            <td class="px-5 py-4 align-middle text-right whitespace-nowrap">
                                <span class="font-black text-slate-900 block text-sm">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                                <div class="mt-1">
                                    @if($order->status === 'selesai')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Selesai
                                        </span>
                                    @elseif($order->status === 'ditolak')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            Dibatalkan
                                        </span>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-14 text-center">
                                <div class="max-w-md mx-auto flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-sm font-black text-slate-900">Belum Ada Data Riwayat Pesanan</h4>
                                    <p class="text-xs text-slate-500 font-medium mt-1">
                                        Seluruh transaksi yang telah selesai disajikan atau dibatalkan akan otomatis terarsip pada daftar ini.
                                    </p>
                                    @if(request('search') || (request('date_filter') && request('date_filter') !== 'all') || (request('status_filter') && request('status_filter') !== 'all'))
                                        <a href="{{ route('tenant.orders.history') }}" 
                                           class="mt-3.5 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-xs font-bold text-[#005ea2] transition shadow-2xs">
                                            <span>Reset Filter & Pencarian</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- C. Paginasi & Keterangan Total Data (Terintegrasi Rapi Tanpa Teks Duplikat) -->
        <div class="px-4 sm:px-6 py-3 border-t border-slate-200/80 bg-slate-50/50">
            @if($orders->hasPages())
                {{ $orders->links('components.pagination', ['itemName' => 'pesanan', 'itemNameEn' => 'orders']) }}
            @else
                <div class="text-xs text-slate-500 font-semibold py-1">
                    Menampilkan <span class="font-bold text-slate-700">{{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }}</span> dari <span class="font-bold text-[#005ea2]">{{ $orders->total() }}</span> pesanan
                </div>
            @endif
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 5. MODAL STRUK DIGITAL TRANSAKSI (TELEPORT KE BODY: BACKDROP 100% FULL)   -->
    <!-- ========================================================================= -->
    <template x-teleport="body">
        <div x-show="showModal" 
             x-cloak
             class="fixed inset-0 z-[100] overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
             @keydown.escape.window="showModal = false">
            
            <div id="printable-receipt"
                 class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-200 relative transform transition-all"
                 @click.away="showModal = false">
                
                <!-- Header Struk -->
                <div class="text-center pb-4 border-b border-dashed border-slate-200">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">STRUK DIGITAL FLYDINE</h3>
                    <p class="text-xs font-bold text-slate-500 mt-0.5">Bandara Internasional Juanda</p>
                    <p class="text-xs font-semibold text-slate-400 mt-0.5">Gerai: {{ auth()->user()->tenant?->name ?? 'Mitra FlyDine' }}</p>
                </div>

                <!-- Detail Pesanan -->
                <template x-if="selectedOrder">
                    <div class="py-4 space-y-2.5 text-xs">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 font-medium">No. Transaksi</span>
                            <span class="font-mono font-black text-slate-800 text-sm" x-text="selectedOrder.code"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 font-medium">Waktu Pesan</span>
                            <span class="font-semibold text-slate-700" x-text="selectedOrder.ordered_at"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 font-medium">Waktu Selesai</span>
                            <span class="font-semibold text-slate-700" x-text="selectedOrder.completed_at"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 font-medium">Pelanggan</span>
                            <span class="font-bold text-slate-800" x-text="selectedOrder.customer"></span>
                        </div>
                        <template x-if="selectedOrder.phone && selectedOrder.phone !== '-'">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 font-medium">No. Telepon / WA</span>
                                <span class="font-semibold text-slate-700" x-text="selectedOrder.phone"></span>
                            </div>
                        </template>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 font-medium">Penerbangan / Gate</span>
                            <span class="font-semibold text-slate-700" x-text="selectedOrder.flight !== '-' ? (selectedOrder.flight + (selectedOrder.gate !== '-' ? ' • Gate ' + selectedOrder.gate : '')) : 'Pelanggan Umum (Tanpa Tiket)'"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 font-medium">Metode Penyerahan</span>
                            <span class="font-bold text-purple-700" x-text="selectedOrder.pickup"></span>
                        </div>

                        <!-- Catatan Pesanan Khusus -->
                        <template x-if="selectedOrder.notes">
                            <div class="p-2.5 bg-amber-50 rounded-xl border border-amber-200/80 text-xs text-amber-900">
                                <span class="text-[10px] font-black uppercase tracking-wider text-amber-800 block mb-0.5">Catatan Pesanan:</span>
                                <span class="font-bold italic text-amber-950" x-text="selectedOrder.notes"></span>
                            </div>
                        </template>

                        <!-- Daftar Item Menu -->
                        <div class="pt-3 border-t border-dashed border-slate-200">
                            <span class="text-xs uppercase tracking-wider font-black text-slate-400 block mb-2">Rincian Menu</span>
                            <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                                <template x-for="(item, idx) in selectedOrder.items" :key="idx">
                                    <div class="flex justify-between items-center text-xs">
                                        <div class="min-w-0 pr-2">
                                            <span class="font-bold text-slate-800" x-text="item.qty + 'x '"></span>
                                            <span class="text-slate-600 font-medium" x-text="item.name"></span>
                                        </div>
                                        <span class="font-bold text-slate-800 shrink-0" x-text="item.subtotal"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Total Tagihan -->
                        <div class="pt-3 border-t border-dashed border-slate-200 flex justify-between items-center">
                            <div>
                                <span class="text-xs uppercase tracking-wider font-black text-slate-400 block">Total Transaksi</span>
                                <span class="text-xs font-bold text-emerald-600">Lunas • <span x-text="selectedOrder.payment"></span></span>
                            </div>
                            <span class="text-lg font-black text-slate-900" x-text="selectedOrder.total"></span>
                        </div>
                    </div>
                </template>

                <!-- Footer Tombol Aksi (Otomatis Hilang Saat Print) -->
                <div id="receipt-modal-actions" class="pt-4 border-t border-slate-100 flex items-center gap-2">
                    <button type="button" 
                            onclick="window.print()" 
                            class="flex-1 py-2.5 px-3 rounded-xl bg-[#005ea2] hover:bg-[#004b82] active:scale-95 text-white font-bold text-xs shadow-xs transition-all cursor-pointer flex items-center justify-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span>Cetak Struk</span>
                    </button>
                    <button type="button" 
                            @click="showModal = false" 
                            class="py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-bold text-xs transition-all cursor-pointer">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </template>

</div>

<!-- CSS Khusus Cetak Struk Kasir Termal -->
<style nonce="{{ $cspNonce ?? '' }}">
@media print {
    body * {
        visibility: hidden !important;
    }
    #printable-receipt, #printable-receipt * {
        visibility: visible !important;
    }
    #receipt-modal-actions, #receipt-modal-actions * {
        display: none !important;
        visibility: hidden !important;
    }
    #printable-receipt {
        position: absolute !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        top: 20px !important;
        width: 75mm !important;
        max-width: 75mm !important;
        box-shadow: none !important;
        border: 1px dashed #94a3b8 !important;
        padding: 12px !important;
        margin: 0 !important;
        border-radius: 8px !important;
    }
}
</style>
@endsection
