@extends('layouts.tenant')

@section('title', 'Dashboard')

@section('content')

@php
    $user = auth()->user();
    $tenant = $user ? $user->tenant : null;
@endphp

<!-- Welcome Hero Banner -->
<section class="relative bg-gradient-to-br from-[#005ea2] to-blue-700 rounded-2xl p-8 text-white mb-8 shadow-lg overflow-hidden group">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
    <div class="absolute bottom-0 right-20 -mb-4 w-24 h-24 bg-[#8dc63f] opacity-20 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700 delay-100"></div>
    
    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <p class="text-blue-100 font-medium mb-1 text-sm tracking-wide">Selamat datang kembali,</p>
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                {{ $tenant?->name ?? $user?->name ?? 'Tenant Partner' }}
            </h1>
            <p class="text-blue-100/80 mt-2 text-sm max-w-lg">
                Pantau pesanan, kelola produk, dan kembangkan bisnis Anda bersama FlyDine dari satu dashboard yang terpadu.
            </p>

            <div class="flex flex-wrap gap-3 mt-6">
                <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-xl text-xs font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>{{ $tenant?->floor_location ?? 'Lokasi belum diatur' }}</span>
                </div>
                
                <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-xl text-xs font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $tenant?->opening_time ? substr($tenant->opening_time, 0, 5) : '--:--' }} - {{ $tenant?->closing_time ? substr($tenant->closing_time, 0, 5) : '--:--' }}</span>
                </div>

                <div class="flex items-center space-x-2 bg-[#8dc63f]/20 backdrop-blur-md border border-[#8dc63f]/30 text-[#e6fcd2] px-4 py-2 rounded-xl text-xs font-bold">
                    <span class="h-2 w-2 rounded-full bg-[#8dc63f] animate-pulse"></span>
                    <span>{{ $tenant?->is_active ? 'Tenant Aktif' : 'Tenant Nonaktif' }}</span>
                </div>
            </div>
        </div>
        
        <div class="hidden lg:block shrink-0">
            <div class="h-32 w-32 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center border border-white/20 shadow-xl">
                <span class="text-5xl">🏪</span>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Grid -->
<section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:border-slate-200 transition-all duration-300 group cursor-default relative overflow-hidden">
        <div class="absolute right-0 top-0 mt-6 mr-6 h-12 w-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
        </div>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pesanan Masuk</p>
        <div class="flex items-baseline space-x-2">
            <h3 class="text-4xl font-extrabold text-slate-800">{{ $countMenunggu ?? 0 }}</h3>
        </div>
        <div class="mt-3 flex items-center text-xs font-medium text-slate-500">
            <span class="text-[#005ea2]">Perlu Dikonfirmasi</span>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:border-slate-200 transition-all duration-300 group cursor-default relative overflow-hidden">
        <div class="absolute right-0 top-0 mt-6 mr-6 h-12 w-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Diproses</p>
        <div class="flex items-baseline space-x-2">
            <h3 class="text-4xl font-extrabold text-slate-800">{{ $countDiproses ?? 0 }}</h3>
        </div>
        <div class="mt-3 flex items-center text-xs font-medium text-slate-500">
            <span class="text-amber-500">Sedang Dimasak</span>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:border-slate-200 transition-all duration-300 group cursor-default relative overflow-hidden">
        <div class="absolute right-0 top-0 mt-6 mr-6 h-12 w-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Selesai</p>
        <div class="flex items-baseline space-x-2">
            <h3 class="text-4xl font-extrabold text-slate-800">{{ $countSelesai ?? 0 }}</h3>
        </div>
        <div class="mt-3 flex items-center text-xs font-medium text-slate-500">
            <span class="text-[#8dc63f]">Hari Ini</span>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md hover:border-slate-200 transition-all duration-300 group cursor-default relative overflow-hidden">
        <div class="absolute right-0 top-0 mt-6 mr-6 h-12 w-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-500 group-hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
        </div>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Produk Aktif</p>
        <div class="flex items-baseline space-x-2">
            <h3 class="text-4xl font-extrabold text-slate-800">{{ $countProduk ?? 0 }}</h3>
        </div>
        <div class="mt-3 flex items-center text-xs font-medium text-slate-500">
            <span class="text-indigo-500">Dalam katalog</span>
        </div>
    </div>
</section>

<!-- Content Sections -->
<section class="grid lg:grid-cols-3 gap-6">

    <!-- Recent Orders -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Pesanan Terbaru</h3>
                <p class="text-xs font-medium text-slate-400 mt-1">Daftar pesanan customer yang masuk</p>
            </div>
            <a href="{{ url('/tenant/orders') }}" class="text-sm font-bold text-[#005ea2] hover:text-blue-700 hover:underline transition-colors flex items-center">
                Lihat Semua
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        @if(isset($recentOrders) && $recentOrders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <tbody class="text-sm divide-y divide-slate-100">
                    @foreach($recentOrders as $order)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-extrabold text-[#005ea2]">{{ $order->order_code }}</span>
                            <div class="text-xs text-slate-400 mt-1">{{ $order->ordered_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $order->flight_number }}</div>
                            <div class="text-xs text-slate-500 mt-1">Gate {{ $order->gate }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            <div class="text-xs text-slate-500 mt-1">{{ $order->orderItems->sum('quantity') }} items</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($order->status === 'menunggu')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">Menunggu</span>
                            @elseif($order->status === 'diproses')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-600 border border-blue-200">Diproses</span>
                            @elseif($order->status === 'selesai')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-green-50 text-green-600 border border-green-200">Selesai</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-600 border border-rose-200">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="flex-1 p-8 flex flex-col items-center justify-center text-center bg-slate-50/50">
            <div class="h-20 w-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                <span class="text-4xl text-slate-300">🛍️</span>
            </div>
            <p class="font-bold text-slate-600 text-lg">Belum ada pesanan</p>
            <p class="text-sm text-slate-400 mt-2 max-w-sm">
                Pesanan dari customer akan otomatis muncul di sini. Pastikan produk Anda tersedia!
            </p>
        </div>
        @endif
    </div>

    <!-- Tenant Info Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-slate-800 text-lg">Info Tenant</h3>
            <span class="h-8 w-8 bg-[#005ea2]/10 rounded-full flex items-center justify-center text-[#005ea2]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
        </div>

        <div class="space-y-4">
            <div class="flex items-start">
                <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center mr-4 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kode Tenant</p>
                    <p class="font-semibold text-slate-800 mt-0.5">{{ $tenant?->tenant_code ?? '-' }}</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center mr-4 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Tenant</p>
                    <p class="font-semibold text-slate-800 mt-0.5">{{ $tenant?->name ?? '-' }}</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center mr-4 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Lokasi</p>
                    <p class="font-semibold text-slate-800 mt-0.5">{{ $tenant?->floor_location ?? '-' }}</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center mr-4 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">No. Telepon</p>
                    <p class="font-semibold text-slate-800 mt-0.5">{{ $tenant?->phone ?? '-' }}</p>
                </div>
            </div>

            <hr class="border-slate-100 my-2">

            <form action="{{ route('tenant.settings.hours') }}" method="POST">
                @csrf
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Jam Operasional (WIB)</p>
                <div class="flex items-center space-x-2">
                    <input type="time" name="opening_time" value="{{ $tenant?->opening_time ? substr($tenant->opening_time, 0, 5) : '' }}" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500">
                    <span class="text-xs text-slate-400 font-bold">-</span>
                    <input type="time" name="closing_time" value="{{ $tenant?->closing_time ? substr($tenant->closing_time, 0, 5) : '' }}" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500">
                </div>
                <p class="text-[10px] text-slate-400 mt-1.5">*Kosongkan jam jika tenant buka 24 Jam</p>
                <button type="submit" class="mt-3 w-full bg-slate-100 hover:bg-slate-200 text-slate-700 py-2 rounded-xl text-xs font-bold transition-colors">
                    Simpan Jam Buka/Tutup
                </button>
            </form>
        </div>

        <div class="mt-8">
            <a href="{{ url('/tenant/products') }}" class="w-full flex items-center justify-center bg-[#005ea2] hover:bg-blue-700 text-white py-3 px-4 rounded-xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all hover:shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Kelola Produk Saya
            </a>
        </div>
    </div>
</section>

@endsection
