@extends('layouts.tenant')

@section('title', 'Dashboard')

@section('content')

@php
    $user = auth()->user();
    $tenant = $user ? $user->tenant : null;
    $todayRevenue = $todayRevenue ?? 0;
    $recentOrders = $recentOrders ?? collect();
    $countMenunggu = $countMenunggu ?? 0;
    $countDiproses = $countDiproses ?? 0;
    $countSelesai = $countSelesai ?? 0;
    $countProduk = $countProduk ?? 0;
@endphp

<!-- ========================================================================= -->
<!-- 1. HERO BANNER: BERSIH, LEGA, EKSEKUTIF (BEBAS DARI PILL & BADGE BERLEBIH) -->
<!-- ========================================================================= -->
<section class="relative bg-gradient-to-r from-slate-900 via-[#004f8c] to-[#005ea2] rounded-3xl p-6 sm:p-8 text-white mb-8 shadow-xl overflow-hidden border border-white/10 group">
    <!-- Ambient Glow Efek Elegan -->
    <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-400/15 rounded-full blur-3xl pointer-events-none group-hover:scale-110 transition-transform duration-1000"></div>
    <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-[#8dc63f]/15 rounded-full blur-3xl pointer-events-none group-hover:scale-110 transition-transform duration-1000"></div>
    
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        
        <!-- Sisi Kiri: Ucapan Selamat Datang & Nama Gerai Bersih -->
        <div class="flex-1 min-w-0">
            <p class="text-blue-200 font-bold text-xs uppercase tracking-wider mb-1 flex items-center gap-2">
                <span>Selamat Datang Kembali</span>
                <span class="text-xl sm:text-2xl select-none inline-block -mt-1 hover:rotate-12 transition-transform">👋</span>
            </p>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-white">
                {{ $tenant?->name ?? $user?->name ?? 'Mitra Gerai FlyDine' }}
            </h1>
            <p class="text-blue-100/80 text-xs sm:text-sm font-medium mt-2 max-w-2xl leading-relaxed">
                Pantau antrean pesanan pelanggan, proses masakan tepat waktu, dan kelola operasional gerai Anda dari satu dashboard terpadu.
            </p>

            <!-- Tombol Aksi Cepat pada Banner -->
            <div class="flex flex-wrap items-center gap-3 mt-6 pt-5 border-t border-white/15">
                <a href="{{ url('/tenant/orders') }}" 
                   class="inline-flex items-center gap-2 bg-[#8dc63f] hover:bg-[#7cb333] active:scale-95 text-slate-900 font-extrabold px-5 py-2.5 rounded-xl text-xs shadow-lg shadow-black/20 transition-all cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span>Buka Antrean Pesanan</span>
                    @if($countMenunggu > 0)
                        <span class="ml-1 px-2 py-0.5 rounded-full bg-rose-600 text-white text-[10px] font-black animate-pulse">
                            {{ $countMenunggu }} Baru
                        </span>
                    @endif
                </a>

                <a href="{{ url('/tenant/products') }}" 
                   class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 active:scale-95 text-white font-bold px-4 py-2.5 rounded-xl text-xs border border-white/20 transition-all cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span>Kelola Menu & Stok</span>
                </a>
            </div>
        </div>

        <!-- Sisi Kanan: Foto Resmi Gerai / Logo Terverifikasi Bandara -->
        <div class="hidden lg:flex flex-col items-center shrink-0">
            <div class="w-32 h-32 bg-white rounded-2xl p-2 shadow-2xl border-2 border-white/30 flex items-center justify-center overflow-hidden">
                @if($tenant?->logo)
                    <img src="{{ asset($tenant->logo) }}" alt="{{ $tenant->name }}" class="w-full h-full object-cover rounded-xl">
                @else
                    <div class="w-full h-full bg-slate-100 rounded-xl flex flex-col items-center justify-center text-[#005ea2]">
                        <span class="text-3xl font-black">{{ strtoupper(substr(str_replace([' ', "'"], '', $tenant?->name ?? 'FD'), 0, 2)) }}</span>
                        <span class="text-[9px] font-extrabold text-slate-400 mt-1">FLY DINE</span>
                    </div>
                @endif
            </div>
        </div>

    </div>
</section>

<!-- ========================================================================= -->
<!-- 2. KPI OPERASIONAL: 5 KARTU STATISTIK RINGKAS                            -->
<!-- ========================================================================= -->
<section class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-5 mb-8">
    
    <!-- KPI 1: Pesanan Masuk (Perlu Konfirmasi) -->
    <a href="{{ url('/tenant/orders') }}" 
       class="bg-white rounded-2xl p-5 shadow-xs border transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 group flex flex-col justify-between cursor-pointer {{ $countMenunggu > 0 ? 'border-amber-300 bg-amber-50/20 ring-2 ring-amber-400/20' : 'border-slate-200/80 hover:border-slate-300' }}">
        <div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Pesanan Masuk</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110 {{ $countMenunggu > 0 ? 'bg-amber-100 text-amber-700 animate-bounce' : 'bg-blue-50 text-[#005ea2]' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
            </div>
            <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $countMenunggu }}</h3>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold">
            <span class="{{ $countMenunggu > 0 ? 'text-amber-600 font-extrabold' : 'text-slate-500' }}">
                {{ $countMenunggu > 0 ? 'Perlu Segera Diproses' : 'Tidak Ada Antrean' }}
            </span>
            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </a>

    <!-- KPI 2: Sedang Dimasak -->
    <a href="{{ url('/tenant/orders') }}" 
       class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200/80 hover:border-slate-300 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 group flex flex-col justify-between cursor-pointer">
        <div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Sedang Dimasak</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center transition-transform group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $countDiproses }}</h3>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold">
            <span class="text-blue-600 font-bold">Proses Dapur/Bar</span>
            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </a>

    <!-- KPI 3: Pesanan Selesai Hari Ini -->
    <a href="{{ url('/tenant/orders/history?date_filter=today') }}" 
       class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200/80 hover:border-slate-300 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 group flex flex-col justify-between cursor-pointer">
        <div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Selesai Hari Ini</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center transition-transform group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $countSelesai }}</h3>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold">
            <span class="text-emerald-600 font-bold">Sukses Disajikan</span>
            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </a>

    <!-- KPI 4: Estimasi Omzet Hari Ini -->
    <div class="bg-white rounded-2xl p-5 shadow-xs border border-slate-200/80 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 group flex flex-col justify-between cursor-default">
        <div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Omzet Hari Ini</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center transition-transform group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight truncate">
                Rp {{ number_format($todayRevenue, 0, ',', '.') }}
            </h3>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-500">
            <span class="text-indigo-600 font-bold">Total Transaksi Selesai</span>
        </div>
    </div>

    <!-- KPI 5: Produk Menu Aktif -->
    <a href="{{ url('/tenant/products') }}" 
       class="col-span-2 sm:col-span-1 bg-white rounded-2xl p-5 shadow-xs border border-slate-200/80 hover:border-slate-300 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 group flex flex-col justify-between cursor-pointer">
        <div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Menu Siap Jual</span>
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center transition-transform group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $countProduk }}</h3>
        </div>
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold">
            <span class="text-purple-600 font-bold">Aktif di Katalog</span>
            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </a>

</section>

<!-- ========================================================================= -->
<!-- 3. KONTEN DUA KOLOM: OPERASIONAL PESANAN & PUSAT KENDALI GERAI             -->
<!-- ========================================================================= -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

    <!-- ===================================================================== -->
    <!-- KOLOM KIRI (8 KOLOM): PESANAN TERBARU & PANDUAN OPERASIONAL BANDARA   -->
    <!-- ===================================================================== -->
    <div class="lg:col-span-7 xl:col-span-8 space-y-8">
        
        <!-- CARD 1: PESANAN TERBARU -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-black text-slate-900 text-lg tracking-tight flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Pesanan Terbaru Masuk</span>
                    </h3>
                    <p class="text-xs font-semibold text-slate-400 mt-0.5">Daftar transaksi pelanggan terkini dari terminal bandara</p>
                </div>

                <a href="{{ url('/tenant/orders') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-[#005ea2] hover:bg-[#005ea2] hover:text-white transition-all text-xs font-black group cursor-pointer shadow-2xs">
                    <span>Lihat Semua Pesanan</span>
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            @if(isset($recentOrders) && $recentOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 text-[10px] font-black text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                <th class="px-5 py-3">Kode & Waktu</th>
                                <th class="px-5 py-3">Penerbangan & Gate</th>
                                <th class="px-5 py-3">Rincian Menu</th>
                                <th class="px-5 py-3 text-right">Total Bayar</th>
                                <th class="px-5 py-3 text-center">Status</th>
                                <th class="px-5 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs divide-y divide-slate-100">
                            @foreach($recentOrders as $order)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-5 py-4">
                                        <span class="font-mono font-black text-[#005ea2]">{{ $order->order_code }}</span>
                                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5">
                                            {{ $order->ordered_at ? $order->ordered_at->format('H:i') : '--:--' }} WIB
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-black text-slate-800 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                            </svg>
                                            <span>{{ $order->flight_number ?: 'Umum' }}</span>
                                        </div>
                                        <div class="text-[11px] font-semibold text-slate-500 mt-0.5">
                                            Gate {{ $order->gate ?: '-' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="font-bold text-slate-700">
                                            {{ $order->orderItems->sum('quantity') }} porsi
                                        </span>
                                        <p class="text-[11px] text-slate-400 truncate max-w-[180px] mt-0.5">
                                            {{ $order->orderItems->pluck('product.name')->filter()->join(', ') ?: 'Menu dipesan' }}
                                        </p>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <span class="font-black text-slate-900">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </span>
                                        <div class="text-[10px] font-bold text-emerald-600 mt-0.5 flex items-center justify-end gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Lunas</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        @if($order->status === 'menunggu')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse mr-1"></span>
                                                Menunggu
                                            </span>
                                        @elseif($order->status === 'diproses')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black bg-blue-50 text-blue-700 border border-blue-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1"></span>
                                                Diproses
                                            </span>
                                        @elseif($order->status === 'selesai')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1"></span>
                                                Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black bg-rose-50 text-rose-700 border border-rose-200">
                                                Dibatalkan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <a href="{{ url('/tenant/orders') }}" 
                                           class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-100 hover:bg-[#005ea2] hover:text-white text-slate-700 text-xs font-bold transition-all">
                                            Buka
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Empty State Formal & Elegan -->
                <div class="p-10 text-center flex flex-col items-center justify-center bg-slate-50/50">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-[#005ea2] flex items-center justify-center mb-3 shadow-2xs">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h4 class="text-base font-black text-slate-800">Belum Ada Antrean Pesanan Aktif</h4>
                    <p class="text-xs text-slate-500 font-semibold mt-1 max-w-md leading-relaxed">
                        Daftar pesanan baru yang telah diverifikasi pembayarannya oleh sistem akan otomatis ditampilkan pada panel ini secara <em>real-time</em>.
                    </p>
                    <a href="{{ url('/tenant/orders') }}" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-xs font-black text-[#005ea2] shadow-2xs transition">
                        <span>Buka Manajemen Pesanan</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>

        <!-- CARD 2: PEDOMAN OPERASIONAL LAYANAN GERAI (FORMAL & SIMETRIS) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden p-6">
            <div class="mb-5">
                <h3 class="font-black text-slate-900 text-base sm:text-lg tracking-tight flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Pedoman Operasional Layanan Gerai</span>
                </h3>
                <p class="text-xs font-semibold text-slate-400 mt-0.5">Ketentuan standar penyiapan dan pelayanan bagi mitra usaha Bandara Internasional Juanda</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <!-- Kotak 1: Ketepatan Waktu Penyajian -->
                <div class="p-5 rounded-2xl bg-slate-50/70 border border-slate-200/80 flex flex-col justify-between hover:bg-slate-50 hover:border-slate-300 transition-colors">
                    <div>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center mb-3 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider">Ketepatan Waktu Penyajian</h4>
                        <p class="text-xs text-slate-600 font-medium mt-2 leading-relaxed">
                            Estimasi penyiapan hidangan dianjurkan dalam rentang <strong>10–15 menit</strong> guna memastikan kenyamanan penumpang sebelum memasuki waktu keberangkatan (<em>boarding</em>).
                        </p>
                    </div>
                </div>

                <!-- Kotak 2: Standar Pengemasan -->
                <div class="p-5 rounded-2xl bg-slate-50/70 border border-slate-200/80 flex flex-col justify-between hover:bg-slate-50 hover:border-slate-300 transition-colors">
                    <div>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center mb-3 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider">Standar Pengemasan</h4>
                        <p class="text-xs text-slate-600 font-medium mt-2 leading-relaxed">
                            Seluruh produk wajib dikemas secara higienis, tertutup rapat, dan aman untuk dibawa ke ruang tunggu keberangkatan maupun kabin pesawat.
                        </p>
                    </div>
                </div>

                <!-- Kotak 3: Bantuan & Dukungan Teknis -->
                <div class="p-5 rounded-2xl bg-slate-50/70 border border-slate-200/80 flex flex-col justify-between hover:bg-slate-50 hover:border-slate-300 transition-colors">
                    <div>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center mb-3 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider">Bantuan & Dukungan Teknis</h4>
                        <p class="text-xs text-slate-600 font-medium mt-2 leading-relaxed">
                            Apabila terdapat kendala operasional atau transaksi sistem, silakan menghubungi layanan pusat bantuan pengelola bandara di nomor <strong>031-8667500</strong>.
                        </p>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- ===================================================================== -->
    <!-- KOLOM KANAN (4 KOLOM): PUSAT KENDALI OPERASIONAL GERAI (TABBED PANEL) -->
    <!-- ===================================================================== -->
    <div class="lg:col-span-5 xl:col-span-4" x-data="{ activeTab: 'operasional' }">
        
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            
            <!-- Header Kartu & Tab Switcher -->
            <div class="p-5 border-b border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-black text-slate-900 text-base tracking-tight flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-blue-50 text-[#005ea2] flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span>Pusat Kendali Gerai</span>
                        </h3>
                        <p class="text-[11px] font-semibold text-slate-400 mt-0.5">Atur jam layanan, porter, dan kontak gerai.</p>
                    </div>
                </div>

                <!-- Tab Buttons: Bersih & Modern -->
                <div class="grid grid-cols-2 gap-2 bg-slate-100/80 p-1 rounded-xl">
                    <button type="button" 
                            @click="activeTab = 'operasional'" 
                            :class="activeTab === 'operasional' ? 'bg-white text-[#005ea2] font-black shadow-xs' : 'text-slate-600 font-bold hover:text-slate-900'"
                            class="py-2 text-xs rounded-lg transition-all text-center cursor-pointer">
                        Jam & Porter
                    </button>
                    <button type="button" 
                            @click="activeTab = 'profile'" 
                            :class="activeTab === 'profile' ? 'bg-white text-[#005ea2] font-black shadow-xs' : 'text-slate-600 font-bold hover:text-slate-900'"
                            class="py-2 text-xs rounded-lg transition-all text-center cursor-pointer">
                        Kontak & Logo
                    </button>
                </div>
            </div>

            <!-- TAB 1: OPERASIONAL & LAYANAN (JAM + PORTER) -->
            <div x-show="activeTab === 'operasional'" class="p-5 space-y-6">
                
                <!-- 1. Form Jam Operasional -->
                <form action="{{ route('tenant.settings.hours') }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Jam Buka - Tutup (WIB)</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 mb-1">Mulai Buka</span>
                            <input type="time" 
                                   name="opening_time" 
                                   value="{{ $tenant?->opening_time ? substr($tenant->opening_time, 0, 5) : '05:00' }}" 
                                   class="w-full text-xs font-bold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/15 outline-none transition">
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 mb-1">Tutup Gerai</span>
                            <input type="time" 
                                   name="closing_time" 
                                   value="{{ $tenant?->closing_time ? substr($tenant->closing_time, 0, 5) : '23:00' }}" 
                                   class="w-full text-xs font-bold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/15 outline-none transition">
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 leading-tight">*Kosongkan jika restoran melayani 24 jam nonstop.</p>
                    
                    <button type="submit" 
                            class="w-full bg-slate-100 hover:bg-[#005ea2] hover:text-white text-slate-700 py-2 rounded-xl text-xs font-black transition-all cursor-pointer shadow-2xs">
                        Simpan Jam Operasional
                    </button>
                </form>

                <hr class="border-slate-100">

                <!-- 2. Form Layanan Antar (Porter) -->
                <form action="{{ route('tenant.settings.delivery') }}" 
                      method="POST" 
                      x-data="{ deliveryActive: {{ !is_null($tenant?->delivery_fee) ? 'true' : 'false' }} }" 
                      class="space-y-3">
                    @csrf
                    
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span>Layanan Antar (Porter)</span>
                        </label>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full" :class="deliveryActive ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'">
                            <span x-text="deliveryActive ? 'Aktif' : 'Nonaktif'"></span>
                        </span>
                    </div>

                    <!-- Toggle Switch -->
                    <label class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80 cursor-pointer hover:bg-slate-100/60 transition">
                        <div class="pr-2">
                            <span class="block text-xs font-black text-slate-800">Antar ke Gate Penumpang</span>
                            <span class="block text-[10px] text-slate-400">Porter mengantar pesanan ke ruang tunggu</span>
                        </div>
                        <div class="relative shrink-0">
                            <input type="checkbox" name="delivery_active" value="1" class="sr-only" x-model="deliveryActive">
                            <div class="block w-11 h-6 rounded-full transition-colors duration-300" :class="deliveryActive ? 'bg-[#005ea2]' : 'bg-slate-300'"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-300" :class="deliveryActive ? 'translate-x-5' : ''"></div>
                        </div>
                    </label>

                    <!-- Input Tarif Pengantaran Porter -->
                    <div x-show="deliveryActive" x-transition class="space-y-1.5 pt-1">
                        <label class="block text-[11px] font-bold text-slate-500">Tarif Ongkir Porter (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-xs font-black text-slate-400">Rp</span>
                            <input type="number" 
                                   name="delivery_fee" 
                                   min="0" 
                                   step="500" 
                                   value="{{ $tenant?->delivery_fee ? (int)$tenant->delivery_fee : 10000 }}" 
                                   class="w-full text-xs font-bold pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/15 outline-none transition">
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full bg-[#005ea2] hover:bg-[#004b82] text-white py-2 rounded-xl text-xs font-black shadow-md shadow-blue-600/20 transition-all cursor-pointer">
                        Simpan Pengaturan Porter
                    </button>
                </form>

            </div>

            <!-- TAB 2: KONTAK & FOTO LOGO GERAI -->
            <div x-show="activeTab === 'profile'" x-cloak class="p-5 space-y-4">
                
                <form action="{{ route('tenant.settings.profile') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Ganti Foto Logo Gerai -->
                    <div>
                        <label class="block text-[11px] font-black text-slate-700 uppercase tracking-wider mb-2">Foto / Logo Gerai</label>
                        
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200/80">
                            <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                                @if($tenant?->logo)
                                    <img src="{{ asset($tenant->logo) }}" alt="Logo Gerai" class="w-full h-full object-cover">
                                @else
                                    <span class="text-xs font-bold text-slate-400">No Logo</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <input type="file" 
                                       name="logo" 
                                       accept="image/png,image/jpeg,image/jpg,image/webp" 
                                       class="w-full text-[11px] file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-[#005ea2] file:text-white hover:file:bg-[#004b82] cursor-pointer">
                                <span class="block text-[9px] text-slate-400 mt-1">Format: JPG, PNG, WebP (Maks. 20MB)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Nomor Telepon Outlet -->
                    <div>
                        <label class="block text-[11px] font-black text-slate-700 uppercase tracking-wider mb-1">No. Telepon Gerai</label>
                        <input type="text" 
                               name="phone" 
                               value="{{ $tenant?->phone }}" 
                               placeholder="Contoh: 031-8667500 atau 08123456789" 
                               class="w-full text-xs font-bold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/15 outline-none transition">
                    </div>

                    <!-- PIC Info -->
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="block text-[11px] font-black text-slate-700 uppercase tracking-wider mb-1">Nama PIC</label>
                            <input type="text" 
                                   name="pic_name" 
                                   value="{{ $tenant?->pic_name }}" 
                                   placeholder="Supervisor gerai" 
                                   class="w-full text-xs font-bold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/15 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-700 uppercase tracking-wider mb-1">No. WA PIC</label>
                            <input type="text" 
                                   name="pic_phone" 
                                   value="{{ $tenant?->pic_phone }}" 
                                   placeholder="0812xxxxxxxx" 
                                   class="w-full text-xs font-bold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/15 outline-none transition">
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full bg-[#005ea2] hover:bg-[#004b82] text-white py-2 rounded-xl text-xs font-black shadow-md shadow-blue-600/20 transition-all cursor-pointer">
                        Simpan Data Kontak
                    </button>
                </form>

                <div class="pt-3 border-t border-slate-100 text-center">
                    <a href="{{ route('profile.edit') }}" class="text-[11px] font-bold text-[#005ea2] hover:underline flex items-center justify-center gap-1">
                        <span>Buka Pengaturan Akun & Sandi di Profil</span>
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection
