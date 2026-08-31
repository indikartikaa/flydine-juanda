<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelacakan Pesanan - FlyDine Juanda</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif; background-color: #f8fafc; }
        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            .pb-safe { padding-bottom: calc(env(safe-area-inset-bottom) + 1rem); }
        }
    </style>
</head>
<body class="text-slate-800 min-h-screen selection:bg-[#005ea2] selection:text-white flex justify-center bg-slate-100">

    <!-- Mobile-First Container Wrapper -->
    <div class="w-full max-w-lg bg-[#f8fafc] min-h-screen relative flex flex-col shadow-2xl shadow-slate-200/50">
        
        <!-- Header (Simple & Clean) -->
        <header class="bg-white/90 backdrop-blur-md sticky top-0 z-50 px-4 py-4 border-b border-slate-200/50 shadow-sm text-center">
            <h1 class="text-xl font-extrabold text-[#005ea2] tracking-tight">FlyDine<span class="text-[#8dc63f]">.</span></h1>
            <p class="text-[10px] text-slate-400 font-bold tracking-widest uppercase mt-0.5">Juanda Airport</p>
        </header>

        <!-- Main Scrollable Content -->
        <main class="flex-grow px-4 sm:px-6 py-8 pb-32" x-data="{ loaded: false, showComplaintModal: false }" x-init="setTimeout(() => loaded = true, 100)">
            
            @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-sm font-bold flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('success') }}
            </div>
            @endif

            <!-- Header Status (Modern Card with Pulse) -->
            <div class="text-center mb-8 bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden transition-all duration-700 transform" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                <!-- Top Accent Line -->
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#005ea2] to-[#8dc63f]"></div>
                
                <span class="inline-block bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full mb-4">ID Pesanan: {{ $order->order_code }}</span>
                
                @if(!$order->is_paid && in_array($order->payment_method, ['qris', 'transfer']))
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight mb-3 flex items-center justify-center gap-2">
                        <span class="relative flex h-4 w-4">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-4 w-4 bg-amber-500"></span>
                        </span>
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-amber-600 to-[#f59e0b]">Menunggu Pembayaran</span>
                    </h2>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed px-2 mb-6">
                        Silakan selesaikan pembayaran agar pesanan segera diproses oleh <span class="font-extrabold text-[#005ea2]">{{ $order->tenant->name }}</span>.
                    </p>

                    @if($order->payment_method == 'qris')
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 inline-block w-full max-w-xs mx-auto mb-6">
                            <p class="font-bold text-slate-700 text-sm mb-4">Scan QRIS dengan e-wallet/m-banking Anda:</p>
                            <!-- Dummy QR Code -->
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $order->order_code }}" alt="QRIS Dummy" class="mx-auto rounded-lg shadow-sm w-48 h-48 mix-blend-multiply">
                            <p class="text-[10px] text-slate-400 mt-4">*Ini adalah QR simulasi untuk pengujian (MVP)</p>
                        </div>
                    @else
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 inline-block w-full max-w-xs mx-auto mb-6 text-left">
                            <p class="font-bold text-slate-700 text-sm mb-2">Transfer ke Rekening Berikut:</p>
                            <p class="text-xs text-slate-500 mb-1">Bank BCA - FlyDine Juanda</p>
                            <div class="flex items-center justify-between bg-white px-4 py-3 rounded-xl border border-slate-200 mb-4 shadow-sm">
                                <span class="font-black text-lg text-slate-800 tracking-widest">8720 1928 33</span>
                                <button onclick="navigator.clipboard.writeText('8720192833'); alert('Disalin!')" class="text-[#005ea2] text-xs font-bold hover:underline">Salin</button>
                            </div>
                            <p class="text-sm font-bold text-slate-700 flex justify-between">Total Bayar: <span class="text-[#005ea2] text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
                        </div>
                    @endif

                    <form action="{{ route('customer.simulate_payment') }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_code" value="{{ $order->order_code }}">
                        <button type="submit" class="w-full bg-[#005ea2] hover:bg-blue-700 text-white font-extrabold py-3.5 rounded-xl text-sm uppercase tracking-widest transition-all shadow-md shadow-blue-500/30">
                            Simulasi: Saya Sudah Bayar
                        </button>
                    </form>

                @else
                    <!-- Status Normal (Sudah Bayar atau Cash) -->
                    @php
                        $statusColors = [
                            'menunggu' => 'from-amber-600 to-[#f59e0b]',
                            'diproses' => 'from-emerald-600 to-[#8dc63f]',
                            'siap' => 'from-blue-600 to-[#005ea2]',
                            'selesai' => 'from-slate-600 to-slate-400',
                            'dibatalkan' => 'from-rose-600 to-rose-400'
                        ];
                        $statusDot = [
                            'menunggu' => 'bg-amber-500',
                            'diproses' => 'bg-emerald-500',
                            'siap' => 'bg-blue-500',
                            'selesai' => 'bg-slate-500',
                            'dibatalkan' => 'bg-rose-500'
                        ];
                        $labels = [
                            'menunggu' => 'Pesanan Diterima',
                            'diproses' => 'Sedang Dimasak',
                            'siap' => 'Siap Diambil',
                            'selesai' => 'Pesanan Selesai',
                            'dibatalkan' => 'Dibatalkan'
                        ];
                    @endphp
                    
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight mb-3 flex items-center justify-center gap-2">
                        @if(!in_array($order->status, ['selesai', 'dibatalkan']))
                        <span class="relative flex h-4 w-4">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ str_replace('500', '400', $statusDot[$order->status]) }} opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-4 w-4 {{ $statusDot[$order->status] }}"></span>
                        </span>
                        @endif
                        <span class="bg-clip-text text-transparent bg-gradient-to-r {{ $statusColors[$order->status] }}">{{ $labels[$order->status] }}</span>
                    </h2>
                    
                    <p class="text-sm text-slate-500 font-medium leading-relaxed px-2">
                        @if($order->status == 'menunggu')
                            Restoran <span class="font-extrabold text-[#005ea2]">{{ $order->tenant->name }}</span> sedang meninjau pesanan Anda.
                        @elseif($order->status == 'diproses')
                            Pesanan Anda sedang dipersiapkan oleh <span class="font-extrabold text-[#005ea2]">{{ $order->tenant->name }}</span>.
                        @elseif($order->status == 'siap')
                            Makanan sudah siap! Silakan ambil pesanan Anda.
                        @endif
                    </p>
                @endif
            </div>

            <!-- Progress Tracker / Refund Info -->
            @if(in_array($order->status, ['ditolak', 'dibatalkan']))
            <div class="bg-rose-50 p-6 rounded-[1.5rem] border border-rose-200 mb-6 text-center transition-all duration-700 delay-100 transform" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <h3 class="font-extrabold text-rose-800 mb-2">Informasi Pengembalian Dana</h3>
                <p class="text-sm text-rose-600 mb-5 leading-relaxed">Mohon maaf, pesanan Anda dibatalkan karena kendala operasional (misal: stok habis). Dana Anda akan dikembalikan (Refund) 100%.</p>
                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20FlyDine%2C%20saya%20ingin%20mengajukan%20Refund%20untuk%20pesanan%20{{ $order->order_code }}" target="_blank" class="inline-flex items-center justify-center bg-rose-600 text-white font-bold px-5 py-2.5 rounded-xl text-xs shadow-md shadow-rose-500/30 hover:bg-rose-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                    Hubungi CS via WhatsApp
                </a>
            </div>
            @elseif($order->is_paid || $order->payment_method == 'cash')
            <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-100 mb-6 transition-all duration-700 delay-100 transform" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-6">Status Pesanan</p>
                
                <div class="relative pl-3">
                    <!-- Vertical Line -->
                    <div class="absolute left-[15px] top-3 bottom-3 w-0.5 bg-slate-100"></div>
                    
                    <!-- Dynamic Height based on status -->
                    @php
                        $height = '0%';
                        if($order->status == 'menunggu') $height = '0%';
                        if($order->status == 'diproses') $height = '50%';
                        if($order->status == 'siap' || $order->status == 'selesai') $height = '100%';
                    @endphp
                    <div class="absolute left-[15px] top-3 w-0.5 bg-gradient-to-b from-[#005ea2] to-emerald-500 transition-all duration-1000" style="height: {{ $height }}"></div>

                    <div class="space-y-6 relative">
                        <!-- Step 1 (Diterima) -->
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full {{ in_array($order->status, ['menunggu', 'diproses', 'siap', 'selesai']) ? 'bg-[#005ea2] text-white shadow-blue-500/20 ring-white' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center z-10 shrink-0 shadow-md ring-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div class="ml-4 mt-1">
                                <h4 class="text-sm {{ in_array($order->status, ['menunggu', 'diproses', 'siap', 'selesai']) ? 'font-bold text-slate-800' : 'font-medium text-slate-500' }}">Pesanan Diterima</h4>
                            </div>
                        </div>
                        
                        <!-- Step 2 (Diproses) -->
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full {{ in_array($order->status, ['diproses', 'siap', 'selesai']) ? 'bg-emerald-500 text-white shadow-emerald-500/30 ring-white' : 'bg-slate-100 border-2 border-slate-200 text-slate-400' }} flex items-center justify-center z-10 shrink-0 shadow-md ring-4">
                                @if($order->status == 'diproses')
                                    <span class="w-2.5 h-2.5 bg-white rounded-full animate-pulse"></span>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                @endif
                            </div>
                            <div class="ml-4 mt-1">
                                <h4 class="text-sm {{ in_array($order->status, ['diproses', 'siap', 'selesai']) ? 'font-extrabold text-emerald-600' : 'font-medium text-slate-400' }}">Sedang Dimasak</h4>
                            </div>
                        </div>

                        <!-- Step 3 (Siap) -->
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full {{ in_array($order->status, ['siap', 'selesai']) ? 'bg-blue-500 text-white shadow-blue-500/30 ring-white' : 'bg-slate-100 border-2 border-slate-200 text-slate-400' }} flex items-center justify-center z-10 shrink-0 ring-4">
                                @if($order->status == 'siap')
                                    <span class="w-2.5 h-2.5 bg-white rounded-full animate-pulse"></span>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                                @endif
                            </div>
                            <div class="ml-4 mt-1">
                                <h4 class="text-sm {{ in_array($order->status, ['siap', 'selesai']) ? 'font-bold text-blue-600' : 'font-medium text-slate-400' }}">Siap Diambil</h4>
                                <p class="text-xs text-slate-400 font-medium mt-1">Silakan ambil di lokasi counter.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Info Card Detail Pengambilan -->
            <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-slate-100 space-y-5 mb-8 transition-all duration-700 delay-200 transform" :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'">
                <div class="flex items-start space-x-4">
                    <div class="bg-blue-50 p-3.5 rounded-2xl text-[#005ea2] shrink-0 border border-blue-100/50 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">Waktu Boarding Pesawat</p>
                        <p class="font-extrabold text-slate-800 text-lg tracking-tight">{{ $order->boarding_time ? $order->boarding_time->format('H:i') : '-' }} WIB</p>
                        <p class="text-xs font-semibold text-slate-500 mt-1">Flight: <span class="text-slate-700">{{ $order->flight_number }}</span> - Gate <span class="text-slate-700">{{ $order->gate }}</span></p>
                    </div>
                </div>
                
                <div class="w-full h-px bg-slate-100"></div>
                
                <div class="flex items-start space-x-4">
                    <div class="bg-emerald-50 p-3.5 rounded-2xl text-emerald-600 shrink-0 border border-emerald-100/50 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">Lokasi Pengambilan</p>
                        <p class="font-extrabold text-slate-800 text-sm leading-snug">{{ $order->tenant->name }} <br><span class="text-[#005ea2] font-semibold">({{ $order->tenant->floor_location }})</span></p>
                    </div>
                </div>
            </div>

        </main>

        <!-- Sticky Bottom CTA -->
        <div class="fixed bottom-0 w-full max-w-lg bg-white/90 backdrop-blur-lg border-t border-slate-200/80 p-4 pb-safe z-40 space-y-3">
            <a href="{{ route('page.faq', ['order' => $order->order_code]) }}" class="flex items-center justify-center w-full bg-rose-50 hover:bg-rose-100 text-rose-600 font-extrabold py-3.5 rounded-[1.25rem] text-sm uppercase tracking-widest transition-all shadow-sm border border-rose-100 active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                Laporkan Kendala Pesanan
            </a>
            <a href="/" class="flex items-center justify-center w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold py-3.5 rounded-[1.25rem] text-sm uppercase tracking-widest transition-all active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Katalog
            </a>
        </div>

    </div>

</body>
</html>