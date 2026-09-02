@extends('layouts.admin')

@section('title', 'Ringkasan Sistem')

@section('content')
    <!-- Hero Banner Admin (Gojek / Traveloka Style) -->
    <div class="relative bg-gradient-to-r from-slate-900 via-[#005ea2] to-blue-700 rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-blue-900/10 mb-8 overflow-hidden">
        <!-- Abstract Pattern Background Decor -->
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <svg class="w-80 h-80 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
            </svg>
        </div>
        <div class="absolute top-0 right-1/4 w-64 h-64 bg-white/5 rounded-full filter blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md border border-white/20 px-3.5 py-1 rounded-full text-xs font-semibold text-blue-100 mb-3">
                    <span class="w-2 h-2 rounded-full bg-[#8dc63f] animate-pulse"></span>
                    <span>Sistem Operasional FlyDine Juanda Online</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Selamat Datang, Admin Ops! 👋</h1>
                <p class="text-blue-100 text-sm mt-1.5 max-w-xl leading-relaxed">
                    Pantau kinerja tenant, pesanan makanan penumpang, dan keluhan layanan bandara secara real-time.
                </p>
            </div>
            
            <div class="flex items-center space-x-3 shrink-0">
                <a href="{{ url('/admin/tenants-management') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/20 font-bold text-xs px-4 py-3 rounded-2xl transition-all shadow-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Kelola Tenant
                </a>
                <a href="{{ url('/admin/complaints') }}" class="bg-[#8dc63f] hover:bg-[#7cb335] text-slate-900 font-extrabold text-xs px-5 py-3 rounded-2xl transition-all shadow-md shadow-[#8dc63f]/30 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Resolusi Komplain (2)
                </a>
            </div>
        </div>
    </div>

    <!-- Statistic Cards (Premium rounded-2xl & Hover Elevation) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Card 1: Total Tenant (Royal Blue Gradient) -->
        <div class="bg-gradient-to-br from-[#005ea2] to-blue-800 rounded-2xl p-6 text-white shadow-md shadow-blue-900/10 relative overflow-hidden group hover:shadow-xl hover:shadow-blue-900/20 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
            <div class="absolute -right-4 -bottom-4 opacity-15 text-white group-hover:scale-110 transition-transform duration-500 pointer-events-none">
                <svg class="w-28 h-28" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="flex items-center justify-between mb-3 relative z-10">
                <span class="text-blue-100 text-xs font-bold uppercase tracking-wider">Mitra Terdaftar</span>
                <span class="h-8 w-8 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white text-xs font-bold">T1/T2</span>
            </div>
            <h3 class="text-3xl md:text-4xl font-extrabold relative z-10 tracking-tight">{{ $total_tenants }} <span class="text-sm font-medium opacity-80">Tenant</span></h3>
            <p class="text-xs text-blue-200 mt-2 relative z-10 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7 7 7M5 19l7-7 7 7" />
                </svg>
                Terdaftar di database
            </p>
        </div>

        <!-- Card 2: Tenant Aktif (Clean White with Emerald Accent) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex justify-between items-center group hover:shadow-xl hover:shadow-slate-200/60 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Mitra Beroperasi</p>
                <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $active_tenants }}</h3>
                <p class="text-xs text-emerald-600 font-semibold mt-2 flex items-center">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                    Tenant saat ini buka
                </p>
            </div>
            <div class="h-14 w-14 bg-emerald-50 text-[#8dc63f] rounded-2xl flex items-center justify-center group-hover:bg-[#8dc63f] group-hover:text-white transition-all duration-300 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Card 3: Komplain Baru (Clean White with Amber Alert Bar) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex justify-between items-center group hover:shadow-xl hover:shadow-slate-200/60 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-amber-500"></div>
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Menunggu Respon</p>
                <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $open_complaints }} @if($open_complaints > 0)<span class="text-xs font-extrabold text-rose-500 bg-rose-50 px-2 py-0.5 rounded-full ml-1 animate-pulse">Baru!</span>@endif</h3>
                <p class="text-xs text-amber-600 font-semibold mt-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Butuh penanganan cepat
                </p>
            </div>
            <div class="h-14 w-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
        </div>

        <!-- Card 4: Transaksi Hari Ini (Clean White with Indigo Accent) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex justify-between items-center group hover:shadow-xl hover:shadow-slate-200/60 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Pesanan Hari Ini</p>
                <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">{{ $today_orders }}</h3>
                <p class="text-xs text-indigo-600 font-semibold mt-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    Transaksi aktif hari ini
                </p>
            </div>
            <div class="h-14 w-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
        </div>

    </div>

    <!-- Audit Trail & Aktivitas Terbaru (Card rounded-2xl) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 rounded-xl bg-[#005ea2]/10 text-[#005ea2]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-extrabold text-slate-900 text-base">Audit Trail & Aktivitas Sistem</h2>
                    <p class="text-xs text-slate-500 font-medium">Catatan aktivitas terkini di seluruh terminal bandara.</p>
                </div>
            </div>
            <a href="#" class="text-xs font-bold text-[#005ea2] hover:text-blue-800 transition-colors bg-blue-50 px-3 py-1.5 rounded-xl hover:bg-blue-100">
                Lihat Semua
            </a>
        </div>
        
        <div class="p-6 md:p-8">
            <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-slate-200">
                
                @forelse($recent_logs as $log)
                <!-- Log Item -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                    <div class="flex items-center justify-center w-10 h-10 rounded-2xl border-4 border-white {{ $log['color'] }} text-white font-extrabold text-xs shadow-md shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 relative z-10">
                        {{ $log['icon'] }}
                    </div>
                    <div class="w-[calc(100%-3.5rem)] md:w-[calc(50%-2.5rem)] bg-white p-5 rounded-2xl border border-slate-100 shadow-sm group-hover:shadow-md hover:border-slate-300 transition-all">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-bold text-slate-900 text-sm">{{ $log['title'] }}</span>
                            <span class="text-[11px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">{{ \Carbon\Carbon::parse($log['time'])->format('H:i') }} WIB</span>
                        </div>
                        <p class="text-xs leading-relaxed text-slate-500">
                            {{ $log['description'] }}
                        </p>
                        @if(isset($log['link']))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ $log['link'] }}" class="bg-[#005ea2] hover:bg-blue-800 text-white text-[11px] font-bold px-3 py-1.5 rounded-xl transition-all shadow-sm flex items-center gap-1">
                                Lihat Detail
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-6 text-slate-500 font-medium text-sm">
                    Belum ada aktivitas hari ini.
                </div>
                @endforelse

            </div>
        </div>
    </div>
@endsection