<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelacakan & Pembayaran Pesanan - {{ $order->order_code }} - FlyDine Juanda</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="text-slate-800 min-h-screen selection:bg-[#005ea2] selection:text-white bg-slate-50/80 antialiased flex flex-col" x-data="{ mobileMenuOpen: false, copyToast: false }">

    <!-- Header / Navigation (Consistent with Catalog, Cart, & History) -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs transition-all duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo & Airport Location -->
                <a href="{{ route('customer.menu') }}" class="group cursor-pointer">
                    <img src="{{ asset('images/logo-flydine.png') }}" alt="FlyDine Juanda Airport" class="w-40 sm:w-48 object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-xs">
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center space-x-6">
                    <div class="bg-slate-100/80 p-1 rounded-full flex items-center">
                        <button type="button" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all bg-white text-[#005ea2] shadow-xs">ID</button>
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

                    <a href="{{ route('customer.history') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-600 hover:text-[#005ea2] transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        <span>RIWAYAT</span>
                    </a>

                    <a href="{{ route('customer.tracking', ['order' => $order->order_code]) }}" class="flex items-center space-x-2 text-sm font-bold text-[#005ea2] bg-blue-50 px-3.5 py-1.5 rounded-full border border-blue-200 shadow-xs">
                        <div class="relative">
                            <span class="absolute -top-1 -right-1 w-2 h-2 bg-blue-500 rounded-full animate-ping"></span>
                            <span class="absolute -top-1 -right-1 w-2 h-2 bg-blue-500 rounded-full"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <span>PELACAKAN AKTIF</span>
                    </a>

                    <div class="h-6 w-px bg-slate-200"></div>
                    <a href="{{ route('login') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-600 hover:text-[#005ea2] transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        <span>PORTAL LOGIN</span>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer" aria-label="Buka Menu">
                    <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    <svg x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <!-- Mobile Menu Dropdown -->
            <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-slate-100 bg-white pb-3">
                <div class="py-3 space-y-2 px-2">
                    <a href="{{ route('customer.menu') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-700 bg-slate-50 py-3 px-4 rounded-xl hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        <span>KATALOG RESTORAN</span>
                    </a>

                    <a href="{{ route('customer.cart') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-700 bg-slate-50 py-3 px-4 rounded-xl hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        <span>PESANAN SAYA</span>
                    </a>

                    <a href="{{ route('customer.history') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-700 bg-slate-50 py-3 px-4 rounded-xl hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        <span>RIWAYAT TRANSAKSI</span>
                    </a>
                    
                    <a href="{{ route('login') }}" class="flex items-center justify-center space-x-2 text-sm font-bold text-white bg-[#005ea2] py-3 rounded-xl hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        <span>PORTAL LOGIN TENANT</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
        
        <!-- Flash Alerts -->
        @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl text-sm font-bold flex items-center justify-between shadow-xs animate-fade-in">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                </div>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('warning'))
        <div class="mb-6 bg-amber-50 border border-amber-200 text-amber-900 px-5 py-4 rounded-2xl text-sm font-bold flex items-center justify-between shadow-xs animate-fade-in">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <span>{{ session('warning') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-2xl text-sm font-bold flex items-center justify-between shadow-xs animate-fade-in">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </div>
                <span>{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Clean Header Title Bar (Matching Cart Style) -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-5 border-b border-slate-200/80 gap-3 mb-8">
            <div>
                <div class="flex items-center gap-2 text-xs font-extrabold text-[#005ea2] uppercase tracking-wider mb-1">
                    <span>✈️ Bandara Internasional Juanda</span>
                    <span class="text-slate-300">•</span>
                    <span>Terminal {{ $order->tenant->terminal ?? '1' }}</span>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Pelacakan & Pembayaran
                    </h1>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-xl bg-slate-100 text-slate-700 border border-slate-200 text-xs font-mono font-bold shadow-2xs">
                        <span>ID: {{ $order->order_code }}</span>
                        <button type="button" 
                                @click="navigator.clipboard.writeText('{{ $order->order_code }}'); copyToast = true; setTimeout(() => copyToast = false, 2500)"
                                class="text-slate-400 hover:text-[#005ea2] transition cursor-pointer" title="Salin ID Pesanan">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" /></svg>
                        </button>
                        <span x-show="copyToast" x-cloak class="text-emerald-600 font-sans text-[11px] font-bold">Tersalin!</span>
                    </div>
                </div>
            </div>

            <!-- Dynamic Live Status Pill -->
            <div>
                @if(!$order->is_paid && ($order->payment_method === 'qris' || str_starts_with((string)$order->payment_method, 'transfer')) && $order->status === 'menunggu')
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-50 border border-amber-300 text-amber-900 text-xs font-black shadow-xs">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                        </span>
                        <span>MENUNGGU PEMBAYARAN</span>
                    </span>
                @else
                    @php
                        $statusBadgeConfig = [
                            'menunggu' => ['bg-amber-50 border-amber-300 text-amber-900', 'bg-amber-500', 'PESANAN DITERIMA'],
                            'diproses' => ['bg-emerald-50 border-emerald-300 text-emerald-900', 'bg-emerald-500', 'SEDANG DIMASAK'],
                            'siap' => ['bg-blue-50 border-blue-300 text-blue-900', 'bg-blue-500', $order->pickup_method === 'diantar' ? 'SEDANG DIANTAR PORTER' : 'SIAP DIAMBIL'],
                            'selesai' => ['bg-emerald-100 border-emerald-400 text-emerald-950', 'bg-emerald-600', 'PESANAN SELESAI'],
                            'dibatalkan' => ['bg-rose-50 border-rose-300 text-rose-900', 'bg-rose-500', 'DIBATALKAN'],
                            'ditolak' => ['bg-rose-50 border-rose-300 text-rose-900', 'bg-rose-500', 'DITOLAK RESTORAN'],
                        ];
                        $currConfig = $statusBadgeConfig[$order->status] ?? ['bg-slate-50 border-slate-300 text-slate-800', 'bg-slate-400', strtoupper($order->status)];
                    @endphp
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full border text-xs font-black shadow-xs {{ $currConfig[0] }}">
                        <span class="w-2.5 h-2.5 rounded-full {{ $currConfig[1] }}"></span>
                        <span>{{ $currConfig[2] }}</span>
                    </span>
                @endif
            </div>
        </div>

        <!-- 2-COLUMN RESPONSIVE LAYOUT (Desktop 12-cols, Mobile 1-col) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- ========================================================================= -->
            <!-- KOLOM KIRI (7 Kolom): HERO STATUS, PEMBAYARAN QRIS / TRANSFER, & TIMELINE -->
            <!-- ========================================================================= -->
            <div class="lg:col-span-7 space-y-6">

                @if(!$order->is_paid && ($order->payment_method === 'qris' || str_starts_with((string)$order->payment_method, 'transfer')) && $order->status === 'menunggu')
                    <!-- ========================================== -->
                    <!-- TAMPILAN UNPAID: KARTU QRIS RESMI FINTECH  -->
                    <!-- ========================================== -->
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-md p-6 sm:p-8 relative overflow-hidden">
                        
                        <!-- Top Accent Banner -->
                        <div class="flex items-center justify-between pb-5 border-b border-slate-100 mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center shrink-0 shadow-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">Selesaikan Pembayaran</h2>
                                    <p class="text-xs text-slate-500 font-medium">Pesanan otomatis diproses oleh {{ $order->tenant->name }}</p>
                                </div>
                            </div>

                            <!-- Live Countdown Pill -->
                            @if($order->auto_cancel_at)
                            <div class="text-right" x-data="countdownTimer('{{ $order->auto_cancel_at }}')">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Batas Waktu:</span>
                                <span class="font-mono font-black text-sm text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200 inline-block mt-0.5" x-text="timeLeft">
                                    14:59
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- MODUL QRIS KARTU FINTECH -->
                        @if($order->payment_method === 'qris')
                            <div class="bg-gradient-to-b from-slate-50/80 via-white to-slate-50/50 p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm text-center max-w-sm mx-auto mb-6">
                                
                                <!-- QRIS Header Bar -->
                                <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-2xl font-black tracking-tighter text-rose-600">QRIS</span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">National Standard</span>
                                    </div>
                                    <span class="text-[10px] font-black px-2 py-0.5 bg-rose-50 text-rose-600 rounded-md border border-rose-200">GPN</span>
                                </div>

                                <h3 class="text-base font-black text-slate-800 tracking-tight mb-0.5">{{ strtoupper($order->tenant->name) }}</h3>
                                <p class="text-[10px] text-slate-400 font-mono mb-4">NMID: ID1024398109283 • Bandara Juanda</p>

                                <!-- QR Canvas Frame with Drop Shadow -->
                                <div class="relative mx-auto w-56 h-56 flex items-center justify-center bg-white p-3.5 rounded-2xl border border-slate-200 shadow-sm">
                                    <canvas id="qris-canvas" data-qr-code="{{ $order->order_code }}" data-qr-size="210" class="rounded-xl w-full h-full block"></canvas>
                                    <img id="qris-fallback-img" 
                                         src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ $order->order_code }}" 
                                         alt="QRIS {{ $order->order_code }}" 
                                         class="hidden rounded-xl w-52 h-52 mix-blend-multiply mx-auto">
                                </div>

                                <!-- Total Tagihan Box -->
                                <div class="mt-5 p-3.5 rounded-2xl bg-blue-50/80 border border-blue-100 flex items-center justify-between">
                                    <div class="text-left">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Tagihan:</span>
                                        <span class="text-xl sm:text-2xl font-black text-[#005ea2]">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <button type="button" 
                                            @click="navigator.clipboard.writeText('{{ $order->total_amount }}'); copyToast = true; setTimeout(() => copyToast = false, 2000)"
                                            class="text-xs font-bold text-[#005ea2] hover:underline bg-white px-3 py-1.5 rounded-xl border border-blue-200 shadow-2xs cursor-pointer">
                                        Salin
                                    </button>
                                </div>

                                <!-- Supported E-Wallets & Mobile Banking Pills -->
                                <div class="mt-4 pt-3.5 border-t border-dashed border-slate-200">
                                    <p class="text-[11px] font-bold text-slate-400 mb-2">Dukungan Semua Aplikasi Pembayaran QRIS:</p>
                                    <div class="flex flex-wrap items-center justify-center gap-1.5 text-[10px] font-extrabold text-slate-600">
                                        <span class="px-2 py-0.5 bg-white rounded-md border border-slate-200 shadow-2xs">BCA Mobile</span>
                                        <span class="px-2 py-0.5 bg-white rounded-md border border-slate-200 shadow-2xs">Livin</span>
                                        <span class="px-2 py-0.5 bg-white rounded-md border border-slate-200 shadow-2xs">BRImo</span>
                                        <span class="px-2 py-0.5 bg-white rounded-md border border-slate-200 shadow-2xs">GoPay</span>
                                        <span class="px-2 py-0.5 bg-white rounded-md border border-slate-200 shadow-2xs">OVO</span>
                                        <span class="px-2 py-0.5 bg-white rounded-md border border-slate-200 shadow-2xs">Dana</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Transfer Bank / Virtual Account Module -->
                            <div class="bg-gradient-to-b from-slate-50/80 via-white to-slate-50/50 p-6 sm:p-7 rounded-3xl border border-slate-200 shadow-sm max-w-md mx-auto mb-6"
                                 x-data="{
                                     activeBank: '{{ $order->selected_bank }}',
                                     copyToast: false,
                                     toastMsg: '',
                                     openGuide: false,
                                     banks: {
                                         bca: {
                                             name: 'BCA Virtual Account',
                                             code: 'BCA',
                                             va: '80012' + '{{ substr(preg_replace('/[^0-9]/', '', $order->customer->phone_number ?? '08123456789') ?: '8720192833', -8) }}',
                                             color: 'text-blue-700 bg-blue-50 border-blue-200',
                                             steps: [
                                                 'Buka m-BCA > pilih menu m-Transfer > BCA Virtual Account.',
                                                 'Masukkan Nomor Virtual Account di atas.',
                                                 'Periksa nominal Rp {{ number_format($order->total_amount, 0, ',', '.') }} dan nama penerima, lalu selesaikan dengan PIN.'
                                             ]
                                         },
                                         mandiri: {
                                             name: 'Mandiri Virtual Account',
                                             code: 'MANDIRI',
                                             va: '88908' + '{{ substr(preg_replace('/[^0-9]/', '', $order->customer->phone_number ?? '08123456789') ?: '8720192833', -8) }}',
                                             color: 'text-amber-700 bg-amber-50 border-amber-200',
                                             steps: [
                                                 'Buka Livin\' by Mandiri > pilih Bayar > Pembayaran Baru > Virtual Account.',
                                                 'Masukkan Nomor Virtual Account di atas.',
                                                 'Periksa rincian pesanan dan konfirmasi pembayaran dengan PIN Livin\'.'
                                             ]
                                         },
                                         bri: {
                                             name: 'BRI Virtual Account (BRIVA)',
                                             code: 'BRIVA',
                                             va: '12800' + '{{ substr(preg_replace('/[^0-9]/', '', $order->customer->phone_number ?? '08123456789') ?: '8720192833', -8) }}',
                                             color: 'text-sky-700 bg-sky-50 border-sky-200',
                                             steps: [
                                                 'Buka BRImo > pilih Pembayaran > BRIVA.',
                                                 'Masukkan Nomor BRIVA di atas.',
                                                 'Periksa jumlah tagihan dan masukkan PIN BRImo Anda.'
                                             ]
                                         },
                                         bni: {
                                             name: 'BNI Virtual Account',
                                             code: 'BNI',
                                             va: '98888' + '{{ substr(preg_replace('/[^0-9]/', '', $order->customer->phone_number ?? '08123456789') ?: '8720192833', -8) }}',
                                             color: 'text-teal-700 bg-teal-50 border-teal-200',
                                             steps: [
                                                 'Buka BNI Mobile Banking > pilih Transfer > Virtual Account Billing.',
                                                 'Masukkan Nomor Virtual Account di atas.',
                                                 'Periksa nominal pembayaran dan masukkan Password Transaksi Anda.'
                                             ]
                                         }
                                     },
                                     copy(text, msg) {
                                         if (navigator.clipboard) {
                                             navigator.clipboard.writeText(text);
                                         }
                                         this.toastMsg = msg;
                                         this.copyToast = true;
                                         setTimeout(() => this.copyToast = false, 2500);
                                     }
                                 }">
                                
                                <!-- Bank Switcher Tabs (Horizontal Segmented Control) -->
                                <div class="mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Pilih Bank Transfer:</span>
                                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Bebas Biaya Admin</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 p-1.5 bg-slate-100 rounded-2xl border border-slate-200">
                                        <button type="button" 
                                                @click="activeBank = 'bca'"
                                                class="flex-1 py-2 px-1 rounded-xl text-xs font-black transition-all duration-200 cursor-pointer text-center"
                                                :class="activeBank === 'bca' ? 'bg-white text-blue-700 shadow-sm border border-slate-200/90 ring-1 ring-blue-500/20' : 'text-slate-500 hover:text-slate-800 hover:bg-white/60'">
                                            BCA
                                        </button>
                                        <button type="button" 
                                                @click="activeBank = 'mandiri'"
                                                class="flex-1 py-2 px-1 rounded-xl text-xs font-black transition-all duration-200 cursor-pointer text-center"
                                                :class="activeBank === 'mandiri' ? 'bg-white text-amber-700 shadow-sm border border-slate-200/90 ring-1 ring-amber-500/20' : 'text-slate-500 hover:text-slate-800 hover:bg-white/60'">
                                            MANDIRI
                                        </button>
                                        <button type="button" 
                                                @click="activeBank = 'bri'"
                                                class="flex-1 py-2 px-1 rounded-xl text-xs font-black transition-all duration-200 cursor-pointer text-center"
                                                :class="activeBank === 'bri' ? 'bg-white text-sky-700 shadow-sm border border-slate-200/90 ring-1 ring-sky-500/20' : 'text-slate-500 hover:text-slate-800 hover:bg-white/60'">
                                            BRIVA
                                        </button>
                                        <button type="button" 
                                                @click="activeBank = 'bni'"
                                                class="flex-1 py-2 px-1 rounded-xl text-xs font-black transition-all duration-200 cursor-pointer text-center"
                                                :class="activeBank === 'bni' ? 'bg-white text-teal-700 shadow-sm border border-slate-200/90 ring-1 ring-teal-500/20' : 'text-slate-500 hover:text-slate-800 hover:bg-white/60'">
                                            BNI
                                        </button>
                                    </div>
                                </div>

                                <!-- Active VA Box -->
                                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs mb-3 text-left transition-all duration-300">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-xs font-extrabold text-slate-700" x-text="banks[activeBank].name"></span>
                                        <span class="text-[10px] font-black px-2 py-0.5 rounded-md border transition-colors duration-200" :class="banks[activeBank].color" x-text="banks[activeBank].code"></span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 font-medium mb-2.5">Nomor Virtual Account:</p>
                                    
                                    <div class="flex items-center justify-between bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-200/80 mb-2.5">
                                        <span class="font-mono font-black text-lg sm:text-xl text-slate-900 tracking-wider transition-all duration-200" x-text="banks[activeBank].va"></span>
                                        <button type="button" 
                                                @click="copy(banks[activeBank].va, 'Nomor ' + banks[activeBank].code + ' VA Berhasil Disalin!')"
                                                class="text-xs font-black text-[#005ea2] hover:text-[#003b66] px-3 py-1.5 bg-blue-50 hover:bg-blue-100 rounded-lg cursor-pointer transition-colors shrink-0">
                                            Salin
                                        </button>
                                    </div>
                                    
                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px]">
                                        <span class="text-slate-400">Penerima Resmi:</span>
                                        <span class="font-extrabold text-slate-700 text-right">FlyDine Juanda <span class="text-slate-400 font-normal">({{ $order->tenant->name }})</span></span>
                                    </div>
                                </div>

                                <!-- Total Tagihan Box -->
                                <div class="p-3.5 rounded-2xl bg-blue-50/70 border border-blue-100 flex items-center justify-between mb-3 text-left">
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Pembayaran:</span>
                                        <span class="text-xl font-black text-[#005ea2]">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <button type="button" 
                                            @click="copy('{{ $order->total_amount }}', 'Nominal Tagihan Berhasil Disalin!')"
                                            class="text-xs font-bold text-[#005ea2] bg-white px-3 py-1.5 rounded-xl border border-blue-200 shadow-2xs hover:bg-blue-50 cursor-pointer">
                                        Salin
                                    </button>
                                </div>

                                <!-- Toast Notification for Copy -->
                                <div x-show="copyToast" x-cloak x-transition class="p-2 mb-3 bg-emerald-500 text-white text-xs font-black rounded-xl text-center shadow-md animate-fade-in">
                                    <span x-text="toastMsg"></span>
                                </div>

                                <!-- Accordion Petunjuk Cara Bayar dengan Smooth Transition -->
                                <div class="text-left pt-2 border-t border-dashed border-slate-200">
                                    <button type="button" 
                                            @click="openGuide = !openGuide" 
                                            class="flex items-center justify-between w-full text-xs font-black text-slate-700 hover:text-[#005ea2] cursor-pointer py-1.5 transition-colors">
                                        <span>Petunjuk Pembayaran M-Banking</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-300" :class="openGuide ? 'rotate-180 text-[#005ea2]' : 'text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div x-show="openGuide" 
                                         x-cloak 
                                         x-transition:enter="transition ease-out duration-250" 
                                         x-transition:enter-start="opacity-0 -translate-y-2" 
                                         x-transition:enter-end="opacity-100 translate-y-0" 
                                         x-transition:leave="transition ease-in duration-150" 
                                         x-transition:leave-start="opacity-100 translate-y-0" 
                                         x-transition:leave-end="opacity-0 -translate-y-2"
                                         class="mt-2 text-[11px] text-slate-600 space-y-2 bg-slate-50 p-3.5 rounded-xl border border-slate-200/90">
                                        <template x-for="(step, idx) in banks[activeBank].steps" :key="idx">
                                            <div class="flex items-start gap-2">
                                                <span class="font-black text-[#005ea2] bg-blue-100/70 w-4 h-4 rounded-full flex items-center justify-center text-[10px] shrink-0 mt-0.5" x-text="idx + 1"></span>
                                                <span class="leading-relaxed" x-text="step"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <form action="{{ route('customer.simulate_payment') }}" method="POST">
                                @csrf
                                <input type="hidden" name="order_code" value="{{ $order->order_code }}">
                                <button type="submit" 
                                        class="w-full bg-gradient-to-r from-[#003b66] to-[#005ea2] hover:from-[#002f54] hover:to-[#004a80] text-white font-extrabold py-4 rounded-2xl text-base shadow-lg shadow-blue-500/25 hover:shadow-blue-500/35 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2.5 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                    <span>Konfirmasi Saya Sudah Bayar</span>
                                </button>
                            </form>

                            <form action="{{ route('customer.tracking.cancel', $order->order_code) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');" class="text-center pt-2">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-slate-400 hover:text-rose-600 transition-colors cursor-pointer py-1">
                                    Batalkan Pesanan Ini
                                </button>
                            </form>
                        </div>

                    </div>

                @else
                    <!-- ========================================== -->
                    <!-- TAMPILAN PAID / IN-PROGRESS / SELESAI      -->
                    <!-- ========================================== -->
                    @php
                        $statusHead = [
                            'menunggu' => ['bg-amber-50 text-amber-900 border-amber-200', 'Pesanan Diterima di Sistem', 'Pesanan Anda telah dikonfirmasi dan sedang menunggu antrean staf restoran.'],
                            'diproses' => ['bg-emerald-50 text-emerald-950 border-emerald-200', 'Sedang Dipersiapkan di Dapur', 'Dapur restoran sedang memasak hidangan lezat pesanan Anda.'],
                            'siap' => ['bg-blue-50 text-blue-950 border-blue-200', $order->pickup_method === 'diantar' ? 'Sedang Diantar Porter' : 'Makanan Siap Diserahkan', $order->pickup_method === 'diantar' ? 'Porter FlyDine sedang dalam perjalanan menuju lokasi Anda.' : 'Silakan menuju konter kasir tenant dengan menunjukkan ID pesanan ini.'],
                            'selesai' => ['bg-emerald-50 text-emerald-950 border-emerald-200', 'Pesanan Anda Selesai', 'Pesanan telah selesai diserahkan. Selamat menikmati hidangan Anda dan terima kasih telah menggunakan FlyDine Juanda!'],
                            'dibatalkan' => ['bg-rose-50 text-rose-950 border-rose-200', 'Pesanan Dibatalkan', 'Pesanan ini telah dibatalkan.'],
                            'ditolak' => ['bg-rose-50 text-rose-950 border-rose-200', 'Pesanan Ditolak', 'Mohon maaf, restoran tidak dapat memproses pesanan ini (misal stok habis).'],
                        ];
                        $sh = $statusHead[$order->status] ?? ['bg-slate-50 text-slate-800 border-slate-200', ucfirst($order->status), ''];
                    @endphp

                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm relative overflow-hidden">
                        <div class="flex items-start gap-4 mb-3">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-xs border {{ $sh[0] }}">
                                @if($order->status === 'selesai')
                                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                @elseif(in_array($order->status, ['dibatalkan', 'ditolak']))
                                    <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                @else
                                    <svg class="w-7 h-7 text-[#005ea2] animate-bounce" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                @endif
                            </div>
                            <div>
                                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">
                                    {{ $sh[1] }}
                                </h2>
                                <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1 leading-relaxed">
                                    {{ $sh[2] }}
                                </p>
                            </div>
                        </div>

                        <!-- Refund Alert Jika Dibatalkan & Sudah Bayar -->
                        @if(in_array($order->status, ['dibatalkan', 'ditolak']) && $order->is_paid)
                        <div class="mt-5 p-5 rounded-2xl bg-rose-50 border border-rose-200">
                            <h4 class="font-black text-rose-900 text-sm mb-1 flex items-center gap-1.5">
                                <span>💸</span>
                                <span>Informasi Pengembalian Dana (Refund 100%)</span>
                            </h4>
                            <p class="text-xs text-rose-700 font-medium leading-relaxed mb-3">
                                Karena pesanan telah dibayar, dana Anda akan dikembalikan utuh. Tim Customer Service kami siap membantu proses transfer balik secara instan.
                            </p>
                            <a href="https://wa.me/6281234567890?text=Halo%20Admin%20FlyDine%2C%20saya%20ingin%20mengajukan%20Refund%20untuk%20pesanan%20{{ $order->order_code }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                <span>Hubungi CS FlyDine WhatsApp</span>
                            </a>
                        </div>
                        @endif
                    </div>

                    <!-- TIMELINE STEPPER -->
                    @if(!in_array($order->status, ['dibatalkan', 'ditolak']))
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                                <span>🛫 Progres Pelayanan Bandara</span>
                            </h3>
                            <span class="text-xs font-extrabold text-[#005ea2] bg-blue-50 px-2.5 py-1 rounded-lg">
                                FlyDine FastTrack
                            </span>
                        </div>

                        @php
                            $stepMap = ['menunggu' => 1, 'diproses' => 2, 'siap' => 3, 'selesai' => 4];
                            $currStep = $stepMap[$order->status] ?? 1;
                            $trackHeight = ($currStep === 1) ? '0%' : (($currStep === 2) ? '33%' : (($currStep === 3) ? '66%' : '100%'));
                        @endphp

                        <!-- Stepper Vertical -->
                        <div class="relative pl-3 space-y-7">
                            
                            <!-- Connecting Line Track -->
                            <div class="absolute left-[19px] top-4 bottom-4 w-1 bg-slate-100 rounded-full overflow-hidden">
                                <div class="w-full bg-gradient-to-b from-[#005ea2] via-[#8dc63f] to-emerald-500 transition-all duration-700" style="height: {{ $trackHeight }};"></div>
                            </div>

                            <!-- Step 1: Pesanan Diterima & Lunas -->
                            <div class="flex items-start gap-4 relative">
                                <div class="w-10 h-10 rounded-2xl {{ $currStep >= 1 ? 'bg-[#005ea2] text-white shadow-md shadow-blue-500/20 ring-4 ring-white' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center shrink-0 z-10 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <div class="pt-0.5">
                                    <h4 class="text-sm font-black {{ $currStep >= 1 ? 'text-slate-900' : 'text-slate-400' }}">Pesanan Diterima & Lunas</h4>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5">Pesanan masuk ke sistem antrean restoran {{ $order->tenant->name }}.</p>
                                </div>
                            </div>

                            <!-- Step 2: Sedang Dimasak -->
                            <div class="flex items-start gap-4 relative">
                                <div class="w-10 h-10 rounded-2xl {{ $currStep >= 2 ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20 ring-4 ring-white' : 'bg-slate-100 border border-slate-200 text-slate-400 ring-4 ring-white' }} flex items-center justify-center shrink-0 z-10 transition">
                                    @if($currStep === 2)
                                        <span class="w-3 h-3 bg-white rounded-full animate-ping"></span>
                                    @elseif($currStep > 2)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                    @else
                                        <span class="w-2.5 h-2.5 bg-slate-300 rounded-full"></span>
                                    @endif
                                </div>
                                <div class="pt-0.5">
                                    <h4 class="text-sm font-black {{ $currStep >= 2 ? 'text-emerald-700' : 'text-slate-400' }}">Sedang Dipersiapkan di Dapur</h4>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5">Koki gerai sedang mengolah dan membungkus hidangan Anda sesuai pesanan.</p>
                                </div>
                            </div>

                            <!-- Step 3: Siap Diambil / Diantar -->
                            <div class="flex items-start gap-4 relative">
                                <div class="w-10 h-10 rounded-2xl {{ $currStep >= 3 ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20 ring-4 ring-white' : 'bg-slate-100 border border-slate-200 text-slate-400 ring-4 ring-white' }} flex items-center justify-center shrink-0 z-10 transition">
                                    @if($currStep === 3)
                                        <span class="w-3 h-3 bg-white rounded-full animate-ping"></span>
                                    @elseif($currStep > 3)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                    @else
                                        <span class="w-2.5 h-2.5 bg-slate-300 rounded-full"></span>
                                    @endif
                                </div>
                                <div class="pt-0.5">
                                    <h4 class="text-sm font-black {{ $currStep >= 3 ? 'text-blue-700' : 'text-slate-400' }}">
                                        {{ $order->pickup_method === 'diantar' ? 'Sedang Diantar Porter ke Gate' : 'Makanan Siap Diambil di Konter' }}
                                    </h4>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5">
                                        @if($order->pickup_method === 'diantar')
                                            Porter resmi bandara sedang bergerak mengantar ke titik lokasi Anda.
                                        @else
                                            Silakan menuju konter {{ $order->tenant->name }} ({{ $order->tenant->floor_location }}) untuk pengambilan.
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Step 4: Selesai -->
                            <div class="flex items-start gap-4 relative">
                                <div class="w-10 h-10 rounded-2xl {{ $currStep >= 4 ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/20 ring-4 ring-white' : 'bg-slate-100 border border-slate-200 text-slate-400 ring-4 ring-white' }} flex items-center justify-center shrink-0 z-10 transition">
                                    @if($currStep >= 4)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                    @else
                                        <span class="w-2.5 h-2.5 bg-slate-300 rounded-full"></span>
                                    @endif
                                </div>
                                <div class="pt-0.5">
                                    <h4 class="text-sm font-black {{ $currStep >= 4 ? 'text-emerald-800' : 'text-slate-400' }}">Pesanan Selesai Diserahkan</h4>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5">Terima kasih atas pesanan Anda. Semoga penerbangan Anda menyenangkan!</p>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif

                @endif

            </div>

            <!-- ========================================================================= -->
            <!-- KOLOM KANAN (5 Kolom): BOARDING PASS & RINCIAN PESANAN                    -->
            <!-- ========================================================================= -->
            <div class="lg:col-span-5 space-y-6">

                @php
                    $hasFlight = !empty($order->flight_number) || !empty($order->boarding_time);
                @endphp

                <!-- 1. AIRPORT DIGITAL BOARDING PASS / ORDER INFO CARD -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-md overflow-hidden transition-all duration-300">
                    
                    <!-- Top Airline / Pickup Accent Bar -->
                    <div class="bg-gradient-to-r from-[#002b49] via-[#00436d] to-[#005ea2] px-6 py-4 text-white flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="text-lg">{{ $hasFlight ? '✈️' : '🏪' }}</span>
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-blue-200 block">
                                    {{ $hasFlight ? 'Boarding Pass & Penerbangan' : 'Informasi Pengambilan' }}
                                </span>
                                <span class="text-xs font-extrabold text-white">Bandara Internasional Juanda (SUB)</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full bg-white/15 text-white border border-white/25">
                            {{ $order->pickup_method === 'diantar' ? 'Layanan Porter' : 'Ambil di Gerai' }}
                        </span>
                    </div>

                    <!-- Ticket Information Body -->
                    <div class="p-6">
                        @if($hasFlight)
                            <!-- Passenger & Flight Details -->
                            <div class="grid grid-cols-2 gap-4 pb-4">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Nama Pemesan</span>
                                    <span class="text-sm font-black text-slate-800 truncate block">
                                        {{ $order->customer_name ?: 'Tamu Bandara' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">No. Penerbangan</span>
                                    <span class="text-sm font-black text-[#005ea2] block">
                                        {{ $order->flight_number }}
                                    </span>
                                </div>
                            </div>

                            @if($order->gate || $order->boarding_time || $order->deliveryLocation || $order->pickup_method)
                            <div class="grid grid-cols-2 gap-4 pb-2">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">
                                        {{ $order->pickup_method === 'diantar' ? 'Titik Antar / Gate' : 'Pintu / Gate' }}
                                    </span>
                                    <span class="text-sm font-black text-slate-800 block truncate">
                                        @if($order->pickup_method === 'diantar' && $order->deliveryLocation)
                                            {{ $order->deliveryLocation->name }}
                                        @elseif($order->gate)
                                            {{ preg_match('/^gate\s*/i', trim($order->gate)) ? trim($order->gate) : 'Gate ' . trim($order->gate) }}
                                        @else
                                            Ambil di Gerai Resto
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Waktu Boarding</span>
                                    <span class="text-sm font-black text-amber-600 block">
                                        {{ $order->boarding_time ? $order->boarding_time->format('H:i') . ' WIB' : '-' }}
                                    </span>
                                </div>
                            </div>
                            @endif
                        @else
                            <!-- General Customer / Staff Info (No Flight Info) -->
                            <div class="grid grid-cols-2 gap-4 pb-2">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Nama Pemesan</span>
                                    <span class="text-sm font-black text-slate-800 truncate block">
                                        {{ $order->customer_name ?: 'Tamu Bandara' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">No. WhatsApp / HP</span>
                                    <span class="text-sm font-mono font-bold text-slate-800 block">
                                        {{ $order->customer->phone_number ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        <!-- Ticket Perforated Cutout Notch -->
                        <div class="relative py-2 my-2">
                            <div class="absolute -left-9 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-slate-50 border-r border-slate-200"></div>
                            <div class="border-b-2 border-dashed border-slate-200 w-full"></div>
                            <div class="absolute -right-9 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-slate-50 border-l border-slate-200"></div>
                        </div>

                        <!-- Pickup / Delivery Info -->
                        <div class="pt-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1">
                                {{ $order->pickup_method === 'diantar' ? '🛵 Titik Pengantaran Porter' : '🏪 Gerai Restoran Pengambilan' }}
                            </span>
                            @if($order->pickup_method === 'diantar' && $order->deliveryLocation)
                                <p class="text-sm font-extrabold text-slate-800 leading-snug">
                                    {{ $order->deliveryLocation->name }}
                                </p>
                                <p class="text-xs font-semibold text-emerald-600 mt-0.5">
                                    Terminal {{ $order->deliveryLocation->terminal }} • Diantar petugas ke lokasi
                                </p>
                            @else
                                <p class="text-sm font-extrabold text-slate-800 leading-snug">
                                    {{ $order->tenant->name }}
                                </p>
                                <p class="text-xs font-semibold text-slate-500 mt-0.5">
                                    {{ $order->tenant->terminal ?? 'Terminal 1' }} • {{ $order->tenant->floor_location ?? 'Area Komersial' }}
                                </p>
                            @endif
                        </div>

                        @if($order->notes)
                        <div class="mt-3 pt-3 border-t border-slate-100">
                            <span class="text-[10px] font-bold text-amber-700 uppercase tracking-widest block mb-1">
                                📝 Catatan Khusus Pesanan
                            </span>
                            <p class="text-xs font-bold text-slate-800 bg-amber-50/80 p-2.5 rounded-xl border border-amber-200/70 italic">
                                "{{ $order->notes }}"
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- 2. KARTU RINCIAN MENU & BILLING -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6">
                    <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 mb-4">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Rincian Menu Pesanan</h3>
                        <span class="text-xs font-bold text-slate-400">{{ count($order->orderItems) }} Item</span>
                    </div>

                    <!-- Items List -->
                    <div class="space-y-3 pb-4 border-b border-dashed border-slate-200">
                        @foreach($order->orderItems as $item)
                        <div class="flex justify-between items-start text-xs">
                            <div class="pr-2 flex-grow">
                                <p class="font-extrabold text-slate-800 text-sm leading-snug">
                                    {{ $item->product_name_snapshot ?? ($item->product->name ?? 'Menu') }}
                                </p>
                                <p class="text-[11px] text-slate-400 font-semibold mt-0.5">
                                    {{ $item->quantity }}x @ Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                </p>
                            </div>
                            <span class="font-mono font-black text-slate-800 text-sm shrink-0">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Total Calculation -->
                    <div class="pt-4 space-y-2 text-xs">
                        @if($order->delivery_fee && $order->delivery_fee > 0)
                        <div class="flex justify-between text-slate-500 font-medium">
                            <span>Biaya Layanan Porter</span>
                            <span class="font-mono font-bold text-slate-700">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between items-baseline pt-2">
                            <span class="font-extrabold text-slate-700 text-sm">Total Belanja</span>
                            <span class="font-mono font-black text-2xl text-[#005ea2]">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Quick Actions Buttons -->
                    <div class="mt-6 pt-5 border-t border-slate-100 space-y-2.5">
                        <a href="{{ route('customer.receipt', $order->order_code) }}" target="_blank" 
                           class="flex items-center justify-center w-full bg-slate-50 hover:bg-[#005ea2] hover:text-white text-slate-700 font-extrabold py-3.5 rounded-2xl text-xs uppercase tracking-wider transition-all border border-slate-200 hover:border-[#005ea2] shadow-2xs active:scale-[0.99] cursor-pointer group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-slate-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                            <span>Buka / Cetak Struk Digital</span>
                        </a>

                        <div class="flex gap-2">
                            <a href="{{ route('page.faq', ['order' => $order->order_code]) }}" 
                               class="flex-1 flex items-center justify-center bg-white hover:bg-rose-50 text-rose-600 font-extrabold py-2.5 rounded-xl text-[11px] uppercase tracking-wider transition-all border border-rose-200 active:scale-[0.99] cursor-pointer">
                                Laporkan Kendala
                            </a>
                            <a href="{{ route('customer.menu') }}" 
                               class="flex-1 flex items-center justify-center bg-white hover:bg-slate-50 text-slate-600 font-extrabold py-2.5 rounded-xl text-[11px] uppercase tracking-wider transition-all border border-slate-200 active:scale-[0.99] cursor-pointer">
                                Katalog Menu
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- Official FlyDine Corporate Footer -->
    <x-footer />

    <!-- Auto Refresh Polling Script & QR Renderer Fallback -->
    <script nonce="{{ $cspNonce ?? '' }}">
        // Countdown Timer Logic
        function countdownTimer(targetTimeStr) {
            return {
                target: new Date(targetTimeStr).getTime(),
                timeLeft: '14:59',
                interval: null,
                init() {
                    this.updateTime();
                    this.interval = setInterval(() => this.updateTime(), 1000);
                },
                updateTime() {
                    const now = new Date().getTime();
                    const diff = this.target - now;
                    if (diff <= 0) {
                        this.timeLeft = '00:00 (Waktu Habis)';
                        clearInterval(this.interval);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1200);
                        return;
                    }
                    const m = Math.floor(diff / 60000);
                    const s = Math.floor((diff % 60000) / 1000);
                    this.timeLeft = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                }
            };
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Render QR Code immediately
            if (typeof window.initQRCodes === 'function') {
                window.initQRCodes();
            }

            // Fallback for QR code: if canvas is blank or fails, show image
            const qrisCanvas = document.getElementById('qris-canvas');
            const qrisImg = document.getElementById('qris-fallback-img');
            if (qrisCanvas && qrisImg) {
                setTimeout(() => {
                    try {
                        const ctx = qrisCanvas.getContext('2d');
                        const pixel = ctx.getImageData(10, 10, 1, 1).data;
                        if (pixel[3] === 0) {
                            qrisCanvas.classList.add('hidden');
                            qrisImg.classList.remove('hidden');
                        }
                    } catch (e) {
                        qrisCanvas.classList.add('hidden');
                        qrisImg.classList.remove('hidden');
                    }
                }, 400);
            }

            const currentStatus = '{{ $order->status }}';
            const currentIsPaid = {{ $order->is_paid ? 'true' : 'false' }};
            const orderCode = '{{ $order->order_code }}';
            
            // Poll for live status update every 5 seconds
            if (!['selesai', 'ditolak', 'dibatalkan'].includes(currentStatus)) {
                setInterval(() => {
                    fetch(`/tracking/${orderCode}/status`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status && (data.status !== currentStatus || data.is_paid !== currentIsPaid)) {
                                window.location.reload();
                            }
                        })
                        .catch(err => console.error('Error polling status:', err));
                }, 5000);
            }
        });
    </script>
</body>
</html>