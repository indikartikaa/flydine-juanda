<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Digital Resmi - {{ $order->order_code }} - FlyDine Juanda</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        
        /* Specialized Print Stylesheet */
        @media print {
            @page {
                size: A4 portrait;
                margin: 4mm 6mm;
            }
            html, body {
                background: #ffffff !important;
                color: #0f172a !important;
                margin: 0 !important;
                padding: 0 !important;
                min-height: 0 !important;
                height: auto !important;
                font-size: 8.5pt !important;
                line-height: 1.25 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            main {
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
            .print-receipt-container {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 auto !important;
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            .print-receipt-card {
                border: 1px solid #cbd5e1 !important;
                border-radius: 8px !important;
                box-shadow: none !important;
                padding: 0 !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                page-break-after: avoid !important;
                break-after: avoid !important;
                margin: 0 auto !important;
                max-width: 155mm !important;
            }
            .print-inner-content {
                padding: 8px 16px !important;
            }
            .print-compact,
            .print-receipt-card .py-4,
            .print-receipt-card .py-3 {
                padding-top: 2.5px !important;
                padding-bottom: 2.5px !important;
            }
            .print-receipt-card .pb-5 {
                padding-bottom: 4px !important;
            }
            .print-receipt-card .pt-5 {
                padding-top: 4px !important;
            }
            .print-receipt-card .mt-4 {
                margin-top: 3px !important;
            }
            .print-receipt-card .mb-3,
            .print-receipt-card .mb-2 {
                margin-bottom: 2px !important;
            }
            .print-receipt-card .space-y-3 > * + *,
            .print-receipt-card .space-y-2\.5 > * + *,
            .print-receipt-card .space-y-2 > * + * {
                margin-top: 1.5px !important;
            }
            .print-receipt-card .text-2xl,
            .print-receipt-card .text-3xl {
                font-size: 13pt !important;
                line-height: 1.2 !important;
            }
            .print-receipt-card .text-xl {
                font-size: 11pt !important;
                line-height: 1.2 !important;
            }
            .print-receipt-card .text-base,
            .print-receipt-card .text-sm {
                font-size: 8.5pt !important;
                line-height: 1.25 !important;
            }
            .print-receipt-card .text-xs,
            .print-receipt-card .text-\[11px\],
            .print-receipt-card .text-\[10px\] {
                font-size: 7.5pt !important;
                line-height: 1.2 !important;
            }
            .print-receipt-card canvas {
                width: 44px !important;
                height: 44px !important;
            }
            .print-qr-box {
                padding: 2px !important;
                margin-bottom: 2px !important;
            }
            .ticket-notch {
                display: none !important;
            }
        }
    </style>
</head>
<body class="text-slate-800 min-h-screen selection:bg-[#005ea2] selection:text-white bg-slate-50/70 antialiased flex flex-col">

    <!-- Top Navigation Header (Desktop & Mobile, Hidden in Print) -->
    <header class="no-print sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.menu') }}" class="group flex items-center">
                        <img src="{{ asset('images/logo-flydine.png') }}" alt="FlyDine Juanda" class="w-36 sm:w-44 object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-xs">
                    </a>
                    <div class="hidden md:block h-6 w-px bg-slate-200"></div>
                    <span class="hidden md:inline-flex items-center text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                        Struk Transaksi Resmi
                    </span>
                </div>

                <!-- Action Nav -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <a href="{{ route('customer.history', ['phone_number' => $order->customer->phone_number ?? '']) }}" 
                       class="inline-flex items-center space-x-1.5 px-3 sm:px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-[#005ea2] hover:bg-slate-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span class="hidden sm:inline">Riwayat Pesanan</span>
                        <span class="sm:hidden">Kembali</span>
                    </a>

                    <button type="button" 
                            onclick="window.print()" 
                            class="inline-flex items-center space-x-2 bg-[#005ea2] hover:bg-blue-700 text-white px-4 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold shadow-md shadow-blue-500/20 active:scale-95 transition-all cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span>Cetak / PDF</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-grow max-w-5xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        
        <!-- Desktop 2-Column Responsive Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT / CENTER COLUMN: Official Receipt Document Card (Col 7 on LG, 100% on Mobile) -->
            <div class="lg:col-span-7 w-full print-receipt-container">
                <div class="bg-white rounded-3xl sm:rounded-[2rem] border border-slate-200/90 shadow-xl shadow-slate-200/60 overflow-hidden relative print-receipt-card transition-all">
                    
                    <!-- Top Colored Accent Bar -->
                    <div class="h-2.5 bg-gradient-to-r from-[#003b66] via-[#005ea2] to-[#8dc63f]"></div>

                    <div class="p-6 sm:p-9 print-inner-content">
                        
                        <!-- Header Struk Resmi -->
                        <div class="text-center pb-3 border-b border-dashed border-slate-200 print-compact">
                            <div class="inline-flex items-center justify-center space-x-1.5 mb-0.5">
                                <span class="text-xl sm:text-2xl font-black text-[#005ea2] tracking-tight">FlyDine<span class="text-[#8dc63f]">.</span></span>
                            </div>
                            <p class="text-[9px] text-slate-400 font-extrabold tracking-widest uppercase">PT Angkasa Pura I &bull; Bandara Internasional Juanda Surabaya</p>
                            
                            <div class="mt-2 bg-slate-50 border border-slate-100 rounded-xl p-2 inline-block w-full max-w-sm">
                                <h2 class="text-sm sm:text-base font-black text-slate-800 leading-tight">{{ $order->tenant->name ?? 'Restoran Bandara' }}</h2>
                                <p class="text-xs text-slate-500 font-semibold mt-0.5">
                                    {{ $order->tenant->terminal ?? 'Terminal 1' }} &bull; {{ $order->tenant->floor_location ?? 'Area Keberangkatan' }}
                                </p>
                            </div>
                        </div>

                        <!-- Info Metadata Transaksi -->
                        <div class="py-2.5 border-b border-dashed border-slate-200 space-y-1.5 text-xs sm:text-sm print-compact">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 font-bold uppercase tracking-wider text-[11px]">No. Pesanan</span>
                                <span class="font-mono font-black text-xs sm:text-sm text-slate-900 bg-slate-100 px-2.5 py-0.5 rounded-lg border border-slate-200 tracking-wider">
                                    {{ $order->order_code }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 font-bold uppercase tracking-wider text-[11px]">Waktu Transaksi</span>
                                <span class="font-semibold text-slate-700">
                                    {{ \Carbon\Carbon::parse($order->ordered_at)->translatedFormat('d M Y, H:i') }} WIB
                                </span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 font-bold uppercase tracking-wider text-[11px]">Status Pembayaran</span>
                                @if($order->is_paid)
                                    <span class="inline-flex items-center space-x-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-black px-2.5 py-0.5 rounded-full">
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        <span>LUNAS</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1 bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-black px-2.5 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        <span>MENUNGGU PEMBAYARAN</span>
                                    </span>
                                @endif
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 font-bold uppercase tracking-wider text-[11px]">Status Pesanan</span>
                                @php
                                    $statusLabels = [
                                        'menunggu' => ['bg-amber-100 text-amber-800 border-amber-200', 'Menunggu Konfirmasi Tenant'],
                                        'diproses' => ['bg-blue-100 text-[#005ea2] border-blue-200', 'Sedang Dimasak'],
                                        'siap' => ['bg-emerald-100 text-emerald-800 border-emerald-200', $order->pickup_method === 'diantar' ? 'Sedang Diantar Porter' : 'Siap Diambil di Counter'],
                                        'selesai' => ['bg-emerald-100 text-emerald-800 border-emerald-200', 'Selesai'],
                                        'ditolak' => ['bg-rose-100 text-rose-800 border-rose-200', 'Ditolak Tenant'],
                                        'dibatalkan' => ['bg-rose-100 text-rose-800 border-rose-200', 'Dibatalkan'],
                                    ];
                                    $st = $statusLabels[$order->status] ?? ['bg-slate-100 text-slate-700 border-slate-200', ucfirst($order->status)];
                                @endphp
                                <span class="text-[11px] font-extrabold px-2.5 py-0.5 rounded-full border {{ $st[0] }}">
                                    {{ $st[1] }}
                                </span>
                            </div>
                        </div>

                        <!-- Detail Pelanggan & Penerbangan -->
                        <div class="py-2.5 border-b border-dashed border-slate-200 space-y-1.5 text-xs sm:text-sm print-compact">
                            <div class="flex justify-between items-baseline">
                                <span class="text-slate-400 font-semibold text-[11px] uppercase tracking-wider">Nama Pemesan</span>
                                <span class="font-black text-slate-800 text-right">{{ $order->customer_name }}</span>
                            </div>
                            
                            @if($order->customer && $order->customer->phone_number)
                            <div class="flex justify-between items-baseline">
                                <span class="text-slate-400 font-semibold text-[11px] uppercase tracking-wider">No. WhatsApp / HP</span>
                                <span class="font-mono font-bold text-slate-800 text-right">{{ $order->customer->phone_number }}</span>
                            </div>
                            @endif

                            <div class="flex justify-between items-start gap-4">
                                <span class="text-slate-400 font-semibold text-[11px] uppercase tracking-wider shrink-0">Metode Ambil</span>
                                <span class="font-bold text-right text-slate-800 text-xs">
                                    @if($order->pickup_method === 'diantar' && $order->deliveryLocation)
                                        🛵 Diantar ke {{ $order->deliveryLocation->name }} (Terminal {{ $order->deliveryLocation->terminal }})
                                    @else
                                        🏃 Ambil Sendiri di Counter {{ $order->tenant->name }}
                                    @endif
                                </span>
                            </div>

                            @if($order->flight_number || $order->gate)
                            <div class="flex justify-between items-baseline">
                                <span class="text-slate-400 font-semibold text-[11px] uppercase tracking-wider">Penerbangan & Gate</span>
                                <span class="font-black text-slate-800 text-right">
                                    {{ $order->flight_number ?: '-' }} @if($order->gate)&bull; Gate {{ $order->gate }}@endif
                                    @if($order->boarding_time)
                                        <span class="text-slate-500 font-semibold">(Boarding {{ $order->boarding_time->format('H:i') }} WIB)</span>
                                    @endif
                                </span>
                            </div>
                            @endif

                            @if($order->notes)
                            <div class="mt-2.5 p-2.5 bg-amber-50 rounded-xl border border-amber-200/90 text-xs">
                                <span class="text-[10px] font-black uppercase tracking-wider text-amber-800 flex items-center gap-1 mb-0.5">
                                    <span>📝</span>
                                    <span>Catatan Pesanan:</span>
                                </span>
                                <p class="font-bold text-amber-950 italic leading-snug">"{{ $order->notes }}"</p>
                            </div>
                            @endif
                        </div>

                        <!-- Item Pesanan (Line Items) -->
                        <div class="py-2.5 border-b border-dashed border-slate-200 print-compact">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Rincian Menu</span>
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Subtotal</span>
                            </div>

                            <div class="space-y-2">
                                @forelse($order->orderItems as $item)
                                <div class="flex justify-between items-start text-xs sm:text-sm">
                                    <div class="pr-3 flex-grow">
                                        <h4 class="font-extrabold text-slate-800 text-xs sm:text-sm leading-tight">
                                            {{ $item->product_name_snapshot ?? ($item->product->name ?? 'Menu') }}
                                        </h4>
                                        <p class="text-[11px] text-slate-400 font-semibold mt-0.5">
                                            {{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <span class="font-mono font-black text-slate-900 text-xs sm:text-sm whitespace-nowrap">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                                @empty
                                <div class="text-center py-2 text-slate-400 text-xs italic">
                                    Detail item tidak tersedia
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Ringkasan Biaya (Billing Total) -->
                        <div class="py-2.5 border-b-2 border-slate-800 space-y-1.5 text-xs sm:text-sm print-compact">
                            @php
                                $itemsSubtotal = $order->orderItems->sum('subtotal') ?: $order->total_amount;
                            @endphp
                            <div class="flex justify-between text-slate-500 font-medium">
                                <span>Subtotal Menu</span>
                                <span class="font-mono font-bold text-slate-700">Rp {{ number_format($itemsSubtotal, 0, ',', '.') }}</span>
                            </div>

                            @if($order->delivery_fee && $order->delivery_fee > 0)
                            <div class="flex justify-between text-slate-500 font-medium">
                                <span>Biaya Layanan Antar Porter Bandara</span>
                                <span class="font-mono font-bold text-slate-700">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                            </div>
                            @endif

                            <div class="flex justify-between items-baseline pt-2 border-t border-slate-200">
                                <span class="text-xs sm:text-sm font-black text-slate-900 uppercase tracking-wider">Total Pembayaran</span>
                                <span class="font-mono text-lg sm:text-xl font-black text-[#005ea2]">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between text-[11px] text-slate-400 pt-0.5">
                                <span>Metode Pembayaran</span>
                                <span class="font-bold uppercase text-slate-700">
                                    @if($order->payment_method === 'qris')
                                        QRIS (E-Wallet / M-Banking)
                                    @elseif(str_starts_with((string)$order->payment_method, 'transfer'))
                                        {{ $order->payment_method_label }}
                                    @else
                                        Tunai (Kasir Restoran)
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Barcode & QR Code Scanning Footer -->
                        <div class="pt-3 text-center print-compact">
                            <div class="inline-flex items-center justify-center gap-3.5 text-left">
                                <div class="bg-white p-1.5 rounded-xl border border-slate-200 shrink-0 print-qr-box">
                                    <canvas data-qr-code="{{ $order->order_code }}" data-qr-size="65" class="w-16 h-16 block"></canvas>
                                    <noscript>
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=65x65&data={{ $order->order_code }}" alt="QR Code Pesanan" class="w-16 h-16">
                                    </noscript>
                                </div>
                                <div>
                                    <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest">KODE TRANSAKSI RESMI</p>
                                    <p class="font-mono text-sm font-black text-slate-800">{{ $order->order_code }}</p>
                                    <p class="text-[9px] text-slate-400 leading-tight mt-1 max-w-[240px]">
                                        Bukti transaksi digital resmi FlyDine Bandara Internasional Juanda Surabaya. Tunjukkan struk ini di counter saat pengambilan.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Desktop Sidebar / Quick Actions Hub (Col 5 on LG, Hidden in Print) -->
            <div class="no-print lg:col-span-5 w-full space-y-6 lg:sticky lg:top-24">
                
                <!-- Action Card: Print & Tracking Primary CTA -->
                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-lg shadow-slate-200/40">
                    <div class="flex items-center space-x-3 mb-5">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 text-base">Cetak Dokumen Struk</h3>
                            <p class="text-xs text-slate-400 font-semibold">Simpan atau cetak untuk bukti fisik</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <!-- Big Print Button -->
                        <button type="button" 
                                onclick="window.print()" 
                                class="w-full inline-flex items-center justify-center space-x-2.5 bg-gradient-to-r from-[#003b66] to-[#005ea2] hover:from-[#002f54] hover:to-[#004a80] text-white font-extrabold py-3.5 px-6 rounded-2xl text-sm shadow-lg shadow-blue-500/25 active:scale-98 transition-all cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span>Cetak Struk / Download PDF</span>
                        </button>

                        @if(!in_array($order->status, ['selesai', 'dibatalkan', 'ditolak']))
                        <!-- Live Tracking Button -->
                        <a href="{{ route('customer.tracking', ['order' => $order->order_code]) }}" 
                           class="w-full inline-flex items-center justify-center space-x-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3.5 px-6 rounded-2xl text-sm shadow-lg shadow-emerald-600/20 active:scale-98 transition-all">
                            <span class="w-2.5 h-2.5 rounded-full bg-white animate-ping"></span>
                            <span>Lacak Status Pesanan Real-Time</span>
                        </a>
                        @endif

                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <a href="{{ route('customer.history', ['phone_number' => $order->customer->phone_number ?? '']) }}" 
                               class="inline-flex items-center justify-center space-x-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold py-3 px-4 rounded-xl text-xs transition-all border border-slate-200">
                                <span>&larr; Riwayat</span>
                            </a>
                            <a href="{{ route('customer.menu') }}" 
                               class="inline-flex items-center justify-center space-x-1.5 bg-slate-50 hover:bg-slate-100 text-[#005ea2] font-bold py-3 px-4 rounded-xl text-xs transition-all border border-slate-200">
                                <span>Katalog Menu &rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- Footer FlyDine (Hidden in Print) -->
    <div class="no-print mt-auto">
        <x-footer />
    </div>

</body>
</html>
