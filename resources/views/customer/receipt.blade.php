<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Digital - {{ $order->order_code }} - FlyDine Juanda</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f1f5f9; }
        .font-mono-num { font-family: 'JetBrains Mono', monospace; }
        
        /* Print Styles */
        @media print {
            body { background: white !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .print-receipt {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
                margin: 0 auto !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 1.5rem !important;
            }
        }
    </style>
</head>
<body class="text-slate-800 min-h-screen flex justify-center py-0 sm:py-8 selection:bg-[#005ea2] selection:text-white">

    <!-- Container Wrapper -->
    <div class="w-full max-w-lg bg-white sm:rounded-[2rem] shadow-2xl shadow-slate-200 min-h-screen sm:min-h-0 flex flex-col overflow-hidden relative border border-slate-100 print-receipt">
        
        <!-- Top App Bar (Hidden in Print) -->
        <div class="no-print bg-[#005ea2] text-white px-5 py-4 flex items-center justify-between shadow-sm sticky top-0 z-20">
            <div class="flex items-center space-x-3">
                <a href="{{ route('customer.history', ['phone_number' => $order->customer->phone_number ?? '']) }}" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <div>
                    <h1 class="text-base font-extrabold leading-tight">Struk Digital</h1>
                    <p class="text-[11px] text-blue-100 font-medium">FlyDine Juanda Airport</p>
                </div>
            </div>
            
            <button onclick="window.print()" class="flex items-center space-x-1.5 bg-white/15 hover:bg-white/25 text-white px-3.5 py-2 rounded-xl text-xs font-bold transition-all active:scale-95 border border-white/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                <span>Cetak / PDF</span>
            </button>
        </div>

        <!-- Receipt Body Content -->
        <div class="p-6 sm:p-8 flex-grow">
            
            <!-- Receipt Header Logo & Title -->
            <div class="text-center pb-6 border-b border-dashed border-slate-200">
                <div class="inline-flex items-center justify-center space-x-1 mb-1">
                    <span class="text-2xl font-black text-[#005ea2] tracking-tight">FlyDine<span class="text-[#8dc63f]">.</span></span>
                </div>
                <p class="text-[10px] text-slate-400 font-extrabold tracking-widest uppercase">Bandara Internasional Juanda Surabaya</p>
                <div class="mt-3">
                    <span class="text-base font-extrabold text-slate-800">{{ $order->tenant->name ?? 'Restoran' }}</span>
                    <p class="text-xs text-slate-500 font-medium">{{ $order->tenant->floor_location ?? 'Area Komersial Bandara' }}</p>
                </div>
            </div>

            <!-- Transaction Status & Code -->
            <div class="py-5 border-b border-dashed border-slate-200 space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">No. Pesanan</span>
                    <span class="font-mono-num font-bold text-sm bg-slate-100 text-slate-800 px-2.5 py-1 rounded-lg border border-slate-200">
                        {{ $order->order_code }}
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Waktu Pesan</span>
                    <span class="text-xs font-semibold text-slate-700">
                        {{ \Carbon\Carbon::parse($order->ordered_at)->translatedFormat('d M Y, H:i') }} WIB
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Status Pembayaran</span>
                    @if($order->is_paid)
                        <span class="inline-flex items-center space-x-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-black px-2.5 py-1 rounded-full">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>LUNAS</span>
                        </span>
                    @else
                        <span class="inline-flex items-center space-x-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-black px-2.5 py-1 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span>BELUM LUNAS</span>
                        </span>
                    @endif
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Status Pesanan</span>
                    @php
                        $statusLabels = [
                            'menunggu' => ['bg-amber-100 text-amber-700', 'Menunggu Konfirmasi'],
                            'diproses' => ['bg-emerald-100 text-emerald-700', 'Sedang Dimasak'],
                            'siap' => ['bg-blue-100 text-[#005ea2]', $order->pickup_method === 'diantar' ? 'Sedang Diantar' : 'Siap Diambil'],
                            'selesai' => ['bg-emerald-100 text-emerald-800', 'Selesai'],
                            'ditolak' => ['bg-rose-100 text-rose-700', 'Ditolak'],
                            'dibatalkan' => ['bg-rose-100 text-rose-700', 'Dibatalkan'],
                        ];
                        $st = $statusLabels[$order->status] ?? ['bg-slate-100 text-slate-600', ucfirst($order->status)];
                    @endphp
                    <span class="text-xs font-extrabold px-2.5 py-1 rounded-full {{ $st[0] }}">
                        {{ $st[1] }}
                    </span>
                </div>
            </div>

            <!-- Customer & Flight Details -->
            <div class="py-5 border-b border-dashed border-slate-200 space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-400 font-semibold">Nama Pemesan</span>
                    <span class="font-bold text-slate-800">{{ $order->customer_name }}</span>
                </div>
                
                @if($order->customer && $order->customer->phone_number)
                <div class="flex justify-between">
                    <span class="text-slate-400 font-semibold">No. WhatsApp / HP</span>
                    <span class="font-bold text-slate-800">{{ $order->customer->phone_number }}</span>
                </div>
                @endif

                <div class="flex justify-between items-start">
                    <span class="text-slate-400 font-semibold">Metode Pengambilan</span>
                    <span class="font-bold text-right text-slate-800">
                        @if($order->pickup_method === 'diantar' && $order->deliveryLocation)
                            🛵 Diantar ke {{ $order->deliveryLocation->name }} (Terminal {{ $order->deliveryLocation->terminal }})
                        @else
                            🏃 Ambil Sendiri di Counter ({{ $order->tenant->name }})
                        @endif
                    </span>
                </div>

                @if($order->flight_number || $order->gate)
                <div class="flex justify-between">
                    <span class="text-slate-400 font-semibold">Penerbangan / Gate</span>
                    <span class="font-bold text-slate-800">
                        {{ $order->flight_number ?: '-' }} / Gate {{ $order->gate ?: '-' }}
                        @if($order->boarding_time)
                            <span class="text-slate-500 font-normal">({{ $order->boarding_time->format('H:i') }} WIB)</span>
                        @endif
                    </span>
                </div>
                @endif
            </div>

            <!-- Line Items Table / Billing Details -->
            <div class="py-5 border-b border-dashed border-slate-200">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Rincian Pembelian</p>
                
                <div class="space-y-3.5">
                    @forelse($order->orderItems as $item)
                    <div class="flex justify-between items-start text-xs">
                        <div class="pr-2 flex-grow">
                            <p class="font-bold text-slate-800 text-[13px] leading-tight">
                                {{ $item->product_name_snapshot ?? ($item->product->name ?? 'Menu') }}
                            </p>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                {{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                            </p>
                        </div>
                        <span class="font-mono-num font-bold text-slate-800 text-sm whitespace-nowrap">
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

            <!-- Billing Summary -->
            <div class="py-5 border-b-2 border-slate-800 space-y-2.5 text-xs">
                @php
                    $itemsSubtotal = $order->orderItems->sum('subtotal') ?: $order->total_amount;
                @endphp
                <div class="flex justify-between text-slate-500 font-medium">
                    <span>Subtotal Menu</span>
                    <span class="font-mono-num font-semibold text-slate-700">Rp {{ number_format($itemsSubtotal, 0, ',', '.') }}</span>
                </div>

                @if($order->delivery_fee && $order->delivery_fee > 0)
                <div class="flex justify-between text-slate-500 font-medium">
                    <span>Biaya Layanan Pengantaran Porter</span>
                    <span class="font-mono-num font-semibold text-slate-700">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                </div>
                @endif

                <div class="flex justify-between items-baseline pt-2 border-t border-slate-200">
                    <span class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Total Pembayaran</span>
                    <span class="font-mono-num text-xl font-black text-[#005ea2]">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-between text-[11px] text-slate-400 pt-1">
                    <span>Metode Bayar</span>
                    <span class="font-bold uppercase text-slate-600">
                        @if($order->payment_method === 'qris')
                            QRIS (E-Wallet / M-Banking)
                        @elseif($order->payment_method === 'transfer')
                            Transfer Bank BCA
                        @else
                            Tunai (Cash di Kasir)
                        @endif
                    </span>
                </div>
            </div>

            <!-- Barcode & QR Code Footer -->
            <div class="py-6 text-center">
                <div class="inline-block bg-white p-2.5 rounded-2xl border border-slate-200 shadow-sm mb-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&data={{ $order->order_code }}" alt="QR Code Pesanan" class="w-24 h-24 mx-auto">
                </div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">KODE TRANSAKSI RESMI</p>
                <p class="font-mono-num text-xs font-bold text-slate-700 mt-0.5">{{ $order->order_code }}</p>
                <p class="text-[10px] text-slate-400 mt-2 max-w-xs mx-auto leading-relaxed">
                    Struk digital ini adalah bukti pembayaran dan pemesanan yang sah di FlyDine Bandara Internasional Juanda.
                </p>
            </div>

        </div>

        <!-- Bottom Action CTA Buttons (Hidden in Print) -->
        <div class="no-print bg-slate-50 border-t border-slate-200 p-5 space-y-3">
            <button onclick="window.print()" class="flex items-center justify-center w-full bg-[#005ea2] hover:bg-blue-700 text-white font-extrabold py-3.5 rounded-2xl text-xs uppercase tracking-widest transition-all shadow-md shadow-blue-500/20 active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak Struk / Download PDF
            </button>

            @if(!in_array($order->status, ['selesai', 'dibatalkan', 'ditolak']))
            <a href="{{ route('customer.tracking', ['order' => $order->order_code]) }}" class="flex items-center justify-center w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3.5 rounded-2xl text-xs uppercase tracking-widest transition-all shadow-md shadow-emerald-500/20 active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                Lacak Status Pesanan
            </a>
            @endif

            <div class="flex space-x-3">
                <a href="{{ route('customer.history', ['phone_number' => $order->customer->phone_number ?? '']) }}" class="flex items-center justify-center flex-1 bg-white hover:bg-slate-100 text-slate-700 font-bold py-3 rounded-xl text-xs transition-all border border-slate-200">
                    &larr; Riwayat Pesanan
                </a>
                <a href="{{ route('customer.menu') }}" class="flex items-center justify-center flex-1 bg-white hover:bg-slate-100 text-slate-700 font-bold py-3 rounded-xl text-xs transition-all border border-slate-200">
                    Ke Katalog Menu
                </a>
            </div>
        </div>

    </div>

</body>
</html>
