<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat & Pelacakan Pesanan - FlyDine Juanda</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="text-slate-800 min-h-screen selection:bg-[#005ea2] selection:text-white bg-slate-50/60 antialiased flex flex-col" x-data="{ mobileMenuOpen: false }">
    
    <!-- Header / Navigation (Matching Catalog Style & Prominence) -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm transition-all duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo & Location -->
                <a href="{{ route('customer.menu') }}" class="group cursor-pointer">
                    <img src="{{ asset('images/logo-flydine.png') }}" alt="FlyDine Juanda Airport" class="w-40 sm:w-48 object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-sm">
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center space-x-6">
                    <div class="bg-slate-100/80 p-1 rounded-full flex items-center">
                        <button type="button" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-white text-[#005ea2] shadow-sm">ID</button>
                        <button type="button" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all text-slate-500 hover:text-slate-700">EN</button>
                    </div>
                    
                    <a href="{{ route('customer.cart') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-600 hover:text-[#005ea2] transition-colors group">
                        <div class="relative">
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-rose-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">{{ count(session('cart')) }}</span>
                            @endif
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        </div>
                        <span>PESANAN</span>
                    </a>

                    <a href="{{ route('customer.history') }}" class="flex items-center space-x-2 text-sm font-bold text-[#005ea2] transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        <span>RIWAYAT</span>
                    </a>

                    @if(session('order_code'))
                    <a href="{{ route('customer.tracking', ['order' => session('order_code')]) }}" class="flex items-center space-x-2 text-sm font-bold text-amber-500 hover:text-amber-600 transition-colors group bg-amber-50 px-3 py-1.5 rounded-full border border-amber-200 shadow-sm">
                        <div class="relative">
                            <span class="absolute -top-1 -right-1 w-2 h-2 bg-amber-500 rounded-full animate-ping"></span>
                            <span class="absolute -top-1 -right-1 w-2 h-2 bg-amber-500 rounded-full"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <span>LACAK</span>
                    </a>
                    @endif

                    <div class="h-6 w-px bg-slate-200"></div>
                    <a href="{{ route('login') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-600 hover:text-[#005ea2] transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        <span>PORTAL LOGIN</span>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                    <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    <svg x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <!-- Mobile Menu Dropdown -->
            <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-slate-100 bg-white">
                <div class="py-4 space-y-3 px-2">
                    <a href="{{ route('customer.menu') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-700 bg-slate-50 py-3 px-4 rounded-xl hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        <span>KATALOG RESTORAN</span>
                    </a>

                    <a href="{{ route('customer.cart') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-700 bg-slate-50 py-3 px-4 rounded-xl hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        <span>PESANAN SAYA</span>
                    </a>

                    <a href="{{ route('customer.history') }}" class="flex items-center space-x-2 text-sm font-bold text-[#005ea2] bg-blue-50 py-3 px-4 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        <span>RIWAYAT & LACAK</span>
                    </a>
                    
                    <a href="{{ route('login') }}" class="flex items-center justify-center space-x-2 text-sm font-bold text-white bg-[#005ea2] py-3 rounded-xl hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        <span>PORTAL LOGIN TENANT</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Wide Premium Hero Area (Desktop & Mobile) -->
    <div class="relative bg-gradient-to-br from-[#002f54] via-[#004a80] to-[#005ea2] py-12 sm:py-16 overflow-hidden shadow-inner" style="background: linear-gradient(135deg, #002f54 0%, #004a80 50%, #005ea2 100%); background-color: #003b66;">
        <!-- Ambient Glow Orbs -->
        <div class="absolute -top-16 -right-16 w-80 h-80 bg-sky-400 rounded-full mix-blend-screen filter blur-3xl opacity-25 pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 w-80 h-80 bg-[#8dc63f] rounded-full mix-blend-screen filter blur-3xl opacity-20 pointer-events-none"></div>
        
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 animate-fade-up">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-black uppercase tracking-wider mb-4 shadow-sm">
                <span>✈️ Bandara Internasional Juanda Tracker</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-white drop-shadow-sm mb-3 tracking-tight">
                Lacak & Riwayat Pesanan
            </h1>
            <p class="text-blue-100/90 text-sm sm:text-base font-medium max-w-xl mx-auto leading-relaxed">
                Pantau status persiapan makanan dan pengantaran porter di Terminal 1 & 2 Juanda secara real-time.
            </p>
        </div>
    </div>

    <!-- Main Container -->
    <main class="flex-grow max-w-5xl w-full mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 pb-16">
        
        <!-- Search Card (Floating & Responsive on Desktop) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl shadow-slate-200/80 border border-slate-100 mb-10 max-w-2xl mx-auto animate-fade-up">
            <form action="{{ route('customer.history') }}" method="GET" class="space-y-4">
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-600 mb-2 ml-1">Nomor WhatsApp / Telepon Pemesan</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#005ea2] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        </div>
                        <input type="tel" name="phone_number" value="{{ !empty($customer) ? $cleanPhone : (request('phone_number') ?? '') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-12 pr-4 py-3.5 sm:py-4 text-sm sm:text-base font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] outline-none transition-all placeholder:text-slate-400 placeholder:font-normal" placeholder="Contoh: 08123456789">
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-gradient-to-r from-[#003b66] to-[#005ea2] hover:from-[#002f54] hover:to-[#004a80] text-white font-black py-4 px-6 rounded-2xl text-xs sm:text-sm uppercase tracking-wider transition-all shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center space-x-2 relative overflow-hidden group" style="background: linear-gradient(to right, #003b66, #005ea2); background-color: #005ea2;">
                    <span class="relative z-10 flex items-center justify-center">
                        Cari Riwayat Pesanan
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </span>
                    <div class="shine-effect"></div>
                </button>
            </form>
        </div>

        @if($searched)
            <!-- Searched Results Section -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between pb-3 border-b border-slate-200 gap-2 animate-fade-up">
                <div class="flex items-center space-x-2.5">
                    <span class="w-2 h-5 bg-[#8dc63f] rounded-full"></span>
                    <h2 class="text-base sm:text-lg font-black text-slate-900 uppercase tracking-wider">Hasil Pencarian Pesanan</h2>
                </div>
                
                @if($customer)
                    <div class="inline-flex items-center space-x-2 bg-blue-50 text-[#005ea2] font-black px-4 py-1.5 rounded-full border border-blue-200/80 shadow-sm self-start sm:self-auto">
                        <span class="w-2 h-2 bg-[#005ea2] rounded-full animate-pulse"></span>
                        <span class="text-xs sm:text-sm">Pelanggan: {{ $customer->name }}</span>
                    </div>
                @endif
            </div>

            @if($customer && $orders->count() > 0)
                <!-- 2-Column Responsive Order Cards Grid on Laptop -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    @foreach($orders as $index => $order)
                    <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-slate-200/80 group animate-fade-up flex flex-col justify-between" style="animation-delay: {{ 0.1 + ($index * 0.05) }}s;">
                        
                        <div>
                            <!-- Header Card: Restoran & Waktu -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200/60 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                        <span class="text-base font-black text-[#005ea2]">
                                            {{ strtoupper(substr(str_replace([' ', "'"], '', $order->tenant->name ?? 'RS'), 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <h3 class="font-extrabold text-slate-900 text-base leading-tight">{{ $order->tenant->name ?? 'Restoran' }}</h3>
                                            @if(!empty($order->tenant->terminal))
                                            <span class="px-2 py-0.5 rounded text-[10px] font-black bg-slate-100 text-slate-700 uppercase">{{ $order->tenant->terminal }}</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-400 font-medium mt-0.5">{{ \Carbon\Carbon::parse($order->ordered_at)->translatedFormat('d M Y, H:i') }} WIB</p>
                                    </div>
                                </div>
                                <span class="bg-slate-50 text-slate-600 text-xs font-black uppercase tracking-wider px-3 py-1 rounded-xl border border-slate-200">
                                    {{ $order->order_code }}
                                </span>
                            </div>

                            <!-- Item Breakdown Preview -->
                            @if($order->orderItems && $order->orderItems->count() > 0)
                            <div class="my-4 bg-slate-50/80 rounded-2xl p-4 border border-slate-100 space-y-2 text-xs sm:text-sm">
                                @foreach($order->orderItems->take(3) as $item)
                                <div class="flex justify-between items-center text-slate-600">
                                    <span class="truncate max-w-[240px] font-medium">{{ $item->quantity }}x {{ $item->product_name_snapshot ?? ($item->product->name ?? 'Menu') }}</span>
                                    <span class="font-bold text-slate-800 shrink-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                                @endforeach
                                @if($order->orderItems->count() > 3)
                                <p class="text-[11px] text-slate-400 font-bold italic pt-1">+{{ $order->orderItems->count() - 3 }} menu lainnya</p>
                                @endif
                            </div>
                            @endif

                            <!-- Divider -->
                            <div class="w-full border-t border-dashed border-slate-200 my-4"></div>

                            <!-- Body Card: Total & Status -->
                            <div class="flex justify-between items-end mb-5">
                                <div>
                                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-wider mb-0.5">Total Tagihan</p>
                                    <p class="font-black text-xl text-slate-900 tracking-tight">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>
                                
                                @php
                                    $statusData = [
                                        'menunggu' => ['bg-amber-50 text-amber-700 border-amber-200/80', 'Menunggu Bayar', 'bg-amber-500 animate-pulse'],
                                        'diproses' => ['bg-emerald-50 text-emerald-700 border-emerald-200/80', 'Sedang Dimasak', 'bg-emerald-500 animate-pulse'],
                                        'siap' => ['bg-blue-50 text-[#005ea2] border-blue-200/80', 'Siap Diambil', 'bg-[#005ea2] animate-pulse'],
                                        'selesai' => ['bg-slate-50 text-slate-600 border-slate-200', 'Selesai', 'bg-slate-400'],
                                        'ditolak' => ['bg-rose-50 text-rose-700 border-rose-200', 'Ditolak', 'bg-rose-500'],
                                        'dibatalkan' => ['bg-rose-50 text-rose-700 border-rose-200', 'Dibatalkan', 'bg-rose-500'],
                                    ];
                                    $s = $statusData[$order->status] ?? $statusData['menunggu'];
                                @endphp
                                
                                <div class="flex items-center space-x-1.5 {{ $s[0] }} px-3.5 py-1.5 rounded-full border">
                                    <span class="w-2 h-2 rounded-full {{ $s[2] }}"></span>
                                    <span class="text-xs font-black tracking-wide uppercase">{{ $s[1] }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="space-y-2.5 pt-2">
                            <a href="{{ route('customer.receipt', ['order' => $order->order_code]) }}" class="w-full text-center bg-slate-50 hover:bg-slate-100 text-slate-700 font-black py-3 rounded-xl text-xs transition-colors border border-slate-200 shadow-sm flex items-center justify-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <span>Lihat Struk Digital Resmi</span>
                            </a>

                            @if(!in_array($order->status, ['selesai', 'dibatalkan', 'ditolak']))
                            <a href="{{ route('customer.tracking', ['order' => $order->order_code]) }}" class="w-full text-center bg-gradient-to-r from-[#003b66] to-[#005ea2] hover:from-[#002f54] hover:to-[#004a80] text-white font-black py-3 rounded-xl text-xs transition-all shadow-md shadow-blue-500/20 flex items-center justify-center space-x-2" style="background: linear-gradient(to right, #003b66, #005ea2); background-color: #005ea2;">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                <span>Pantau Live Status Pesanan &rarr;</span>
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- Not Found State -->
                <div class="max-w-xl mx-auto bg-white rounded-3xl p-8 sm:p-12 text-center border border-slate-200/80 shadow-sm mt-4 animate-fade-up">
                    <div class="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-rose-100 text-rose-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                    </div>
                    <h3 class="font-black text-slate-800 text-lg mb-2">Pesanan Tidak Ditemukan</h3>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed max-w-sm mx-auto mb-6">
                        Tidak ada riwayat pesanan dengan nomor <span class="font-bold text-slate-800">{{ $cleanPhone }}</span>. Mohon pastikan nomor yang dimasukkan sama dengan yang Anda isi saat checkout.
                    </p>
                    <a href="{{ route('customer.menu') }}" class="inline-flex items-center space-x-2 px-6 py-3 rounded-full bg-[#005ea2] hover:bg-blue-700 text-white text-xs sm:text-sm font-black transition-colors shadow-sm">
                        <span>Pesan Makanan Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            @endif
        @else
            <!-- Informational Pre-Search Guide (Wide on Laptop) -->
            <div class="space-y-8 animate-fade-up">
                <div>
                    <div class="flex items-center space-x-2.5 mb-5">
                        <span class="w-1.5 h-5 bg-[#005ea2] rounded-full"></span>
                        <h2 class="text-sm sm:text-base font-black text-slate-900 uppercase tracking-wider">3 Langkah Mudah Cek Status Pesanan</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-lg mb-4 border border-blue-100">
                                    1
                                </div>
                                <h3 class="text-sm font-black text-slate-900 leading-snug">Masukkan Nomor WhatsApp</h3>
                                <p class="text-xs text-slate-500 font-medium mt-1.5 leading-relaxed">
                                    Gunakan nomor telepon yang Anda cantumkan pada formulir checkout saat pemesanan menu makanan.
                                </p>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-lg mb-4 border border-emerald-100">
                                    2
                                </div>
                                <h3 class="text-sm font-black text-slate-900 leading-snug">Pantau Live Status Pembuatan</h3>
                                <p class="text-xs text-slate-500 font-medium mt-1.5 leading-relaxed">
                                    Ketahui secara real-time kapan tenant mulai memasak dan estimasi waktu selesai sebelum panggilan boarding Anda.
                                </p>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-black text-lg mb-4 border border-amber-100">
                                    3
                                </div>
                                <h3 class="text-sm font-black text-slate-900 leading-snug">Akses Struk & Porter Gate</h3>
                                <p class="text-xs text-slate-500 font-medium mt-1.5 leading-relaxed">
                                    Unduh struk digital resmi transaksi atau lacak porter bandara saat mengantarkan pesanan ke gate ruang tunggu.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Callout Banner Pesan Sekarang -->
                <div class="bg-gradient-to-r from-blue-50 via-indigo-50 to-slate-50 p-6 sm:p-8 rounded-3xl border border-blue-100 flex flex-col sm:flex-row sm:items-center justify-between gap-5 shadow-sm">
                    <div class="max-w-xl">
                        <h3 class="text-base sm:text-lg font-black text-slate-900 leading-snug">Belum Memesan Makanan di Bandara Juanda?</h3>
                        <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1 leading-relaxed">
                            Nikmati sajian kuliner terbaik dari 76 tenant F&B di Terminal 1 & Terminal 2 Juanda tanpa perlu mengantre di kasir.
                        </p>
                    </div>
                    <a href="{{ route('customer.menu') }}" class="px-6 py-3.5 rounded-full bg-[#005ea2] hover:bg-blue-700 text-white text-xs sm:text-sm font-black transition-colors shadow-sm shrink-0 text-center" style="background-color: #005ea2;">
                        Jelajahi Menu Restoran &rarr;
                    </a>
                </div>
            </div>
        @endif

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-slate-500 text-xs mt-auto">
        <div class="container mx-auto px-4">
            <p class="font-medium">Copyright &copy; 2026 FlyDine Juanda. All rights reserved</p>
        </div>
    </footer>
</body>
</html>