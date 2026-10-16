@extends('layouts.admin')

@section('title', 'Segmentasi Pelanggan')

@section('content')
<div class="space-y-6 sm:space-y-8 pb-12">

    <!-- Page Header & Identity -->
    <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-sm border border-slate-200">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <!-- Breadcrumb -->
                <nav class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-2">
                    <a href="{{ route('admin.executive-dashboard') }}" class="hover:text-slate-600 transition">Analitik Operasional</a>
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-[#005ea2] font-extrabold">Segmentasi Pelanggan</span>
                </nav>

                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Segmentasi & Loyalitas Pelanggan
                </h1>
                <p class="text-sm font-semibold text-slate-600 mt-1 max-w-3xl">
                    Distribusi loyalitas frekuensi belanja penumpang bandara untuk evaluasi retensi dan program promosi terarah.
                </p>

                <div class="mt-3 inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-xs font-bold text-slate-800">
                    <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Periode Terpilih: <strong class="text-[#005ea2]">{{ \Carbon\Carbon::parse($startDateFormatted)->format('d M Y') }}</strong> s/d <strong class="text-[#005ea2]">{{ \Carbon\Carbon::parse($endDateFormatted)->format('d M Y') }}</strong> ({{ (int)$days }} Hari)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar Component -->
    <x-analytics.filter-bar 
        :action="route('admin.analytics.customers')" 
        :startDateFormatted="$startDateFormatted" 
        :endDateFormatted="$endDateFormatted" 
        :tenantId="$tenantId" 
        :tenants="$tenants" 
    />

    <!-- Customer Loyalty Segmentation Card -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-100">
            <div>
                <div class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-700 uppercase tracking-wider mb-1">
                    <span>👑 Customer Loyalty Intel</span>
                </div>
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Peta Distribusi Segmen Penumpang</h2>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">Analisis frekuensi pemesanan per pelanggan unik pada periode terpilih</p>
            </div>
        </div>

        @php
            $newCust = $customerSegmentation->new_customers ?? 0;
            $regCust = $customerSegmentation->regular_customers ?? 0;
            $freqCust = $customerSegmentation->frequent_customers ?? 0;
            $totalCust = $customerSegmentation->total_customers ?? 0;
            
            $newPct = $totalCust > 0 ? round(($newCust / $totalCust) * 100) : 0;
            $regPct = $totalCust > 0 ? round(($regCust / $totalCust) * 100) : 0;
            $freqPct = $totalCust > 0 ? round(($freqCust / $totalCust) * 100) : 0;
        @endphp

        <!-- Segmen Bar -->
        <div class="mb-8">
            <div class="flex flex-wrap items-center justify-between mb-2.5 text-xs font-bold text-slate-700">
                <span>Rasio Pengguna Berdasarkan Loyalitas</span>
                <span class="tabular-nums">Total Teranalisis: <strong class="text-slate-900">{{ number_format($totalCust) }}</strong> Pelanggan</span>
            </div>
            <div class="w-full flex h-7 rounded-xl overflow-hidden bg-slate-100 p-1 gap-1 border border-slate-200/80">
                <div class="bg-sky-500 rounded-lg flex items-center justify-center text-[11px] font-black text-white transition-all duration-1000 shadow-xs tabular-nums" style="width: {{ max(4, $newPct) }}%" title="Pelanggan Baru ({{ $newPct }}%)">
                    @if($newPct > 5) {{ $newPct }}% @endif
                </div>
                <div class="bg-amber-500 rounded-lg flex items-center justify-center text-[11px] font-black text-white transition-all duration-1000 shadow-xs tabular-nums" style="width: {{ max(4, $regPct) }}%" title="Pelanggan Reguler ({{ $regPct }}%)">
                    @if($regPct > 5) {{ $regPct }}% @endif
                </div>
                <div class="bg-emerald-600 rounded-lg flex items-center justify-center text-[11px] font-black text-white transition-all duration-1000 shadow-xs tabular-nums" style="width: {{ max(4, $freqPct) }}%" title="Pelanggan Setia ({{ $freqPct }}%)">
                    @if($freqPct > 5) {{ $freqPct }}% @endif
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-3 mt-4 text-xs font-bold text-slate-600">
                <div class="flex items-center"><span class="w-3.5 h-3.5 rounded-full bg-sky-500 mr-2 shadow-xs"></span> Pelanggan Baru (1x transaksi)</div>
                <div class="flex items-center"><span class="w-3.5 h-3.5 rounded-full bg-amber-500 mr-2 shadow-xs"></span> Pelanggan Reguler (2-5x transaksi)</div>
                <div class="flex items-center"><span class="w-3.5 h-3.5 rounded-full bg-emerald-600 mr-2 shadow-xs"></span> Pelanggan Setia VIP (> 5x transaksi)</div>
            </div>
        </div>

        <!-- Kartu Rincian 3 Segmen -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-sky-50/70 p-6 rounded-2xl border border-sky-200 flex items-center justify-between transition-all hover:border-sky-300">
                <div>
                    <span class="text-xs font-black uppercase tracking-wider text-sky-800 block">Pelanggan Baru</span>
                    <span class="text-3xl font-black text-slate-900 mt-1 block tabular-nums">{{ number_format($newCust) }}</span>
                    <span class="text-xs text-sky-700 font-semibold block mt-1">1 Kali Pemesanan</span>
                </div>
                <div class="h-12 w-12 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-black text-base shadow-xs shrink-0">
                    1x
                </div>
            </div>

            <div class="bg-amber-50/70 p-6 rounded-2xl border border-amber-200 flex items-center justify-between transition-all hover:border-amber-300">
                <div>
                    <span class="text-xs font-black uppercase tracking-wider text-amber-800 block">Pelanggan Reguler</span>
                    <span class="text-3xl font-black text-slate-900 mt-1 block tabular-nums">{{ number_format($regCust) }}</span>
                    <span class="text-xs text-amber-700 font-semibold block mt-1">2 - 5 Kali Pemesanan</span>
                </div>
                <div class="h-12 w-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-black text-base shadow-xs shrink-0">
                    2-5x
                </div>
            </div>

            <div class="bg-emerald-50/70 p-6 rounded-2xl border border-emerald-300 flex items-center justify-between transition-all hover:border-emerald-400">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-black uppercase tracking-wider text-emerald-900">Pelanggan Setia</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-600 text-white uppercase">VIP</span>
                    </div>
                    <span class="text-3xl font-black text-slate-900 mt-1 block tabular-nums">{{ number_format($freqCust) }}</span>
                    <span class="text-xs text-emerald-800 font-semibold block mt-1">&gt; 5 Kali Pemesanan</span>
                </div>
                <div class="h-12 w-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black shadow-xs text-base shrink-0">
                    VIP
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
