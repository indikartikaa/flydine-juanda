<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan Saya - FlyDine Juanda</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="text-slate-800 min-h-screen selection:bg-[#005ea2] selection:text-white flex justify-center bg-slate-50 relative">
    
    <!-- Latar Belakang Elegan Desktop (Subtle Gradient Background) -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-200 via-slate-50 to-slate-100 z-0"></div>

    <!-- Mobile-First Container Wrapper -->
    <div class="w-full max-w-[480px] bg-white min-h-screen relative flex flex-col shadow-[0_0_50px_rgba(0,0,0,0.05)] overflow-hidden z-10">
        
        <!-- Premium Header Area -->
        <div class="relative bg-gradient-to-br from-[#003b66] to-[#005ea2] pb-14 pt-safe-top overflow-hidden">
            <!-- Decorative Orbs -->
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-400 rounded-full mix-blend-screen filter blur-3xl opacity-40 animate-pulse"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-[#8dc63f] rounded-full mix-blend-screen filter blur-3xl opacity-20"></div>
            
            <!-- Navbar -->
            <header class="px-6 py-5 flex items-center justify-between relative z-10">
                <a href="/" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 flex items-center justify-center transition-colors text-white backdrop-blur-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <h1 class="text-sm font-bold text-white tracking-widest uppercase opacity-90">Riwayat Pesanan</h1>
                <div class="w-10"></div> <!-- Placeholder untuk keseimbangan layout -->
            </header>

            <!-- Teks Sambutan -->
            <div class="px-8 pt-4 pb-4 relative z-10 text-center animate-fade-up">
                <h2 class="text-2xl sm:text-3xl font-black text-white drop-shadow-md mb-3 leading-tight">Lacak Pesanan<br>Anda Disini.</h2>
                <p class="text-blue-100 text-xs sm:text-sm font-medium max-w-[280px] mx-auto opacity-90 leading-relaxed">
                    Masukkan nomor WhatsApp atau telepon yang Anda gunakan saat memesan makanan.
                </p>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-grow px-6 pb-20 relative z-20">
            
            <!-- Pencarian Card (Clean & Floating) -->
            <div class="bg-white rounded-[2rem] p-7 shadow-xl shadow-slate-200/60 border border-slate-100 -mt-10 mb-10 relative animate-fade-up" style="animation-delay: 0.1s;">
                <form action="{{ route('customer.history') }}" method="GET" class="space-y-5">
                    <div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-transform group-focus-within:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-focus-within:text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            </div>
                            <input type="tel" name="phone_number" value="{{ !empty($customer) ? $cleanPhone : '' }}" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-12 pr-4 py-4 text-sm font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#005ea2] focus:border-transparent outline-none transition-all placeholder:text-slate-400 placeholder:font-medium" placeholder="Contoh: 08123456789">
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-[#005ea2] hover:bg-blue-700 text-white font-black py-4 rounded-2xl text-sm uppercase tracking-wider transition-all shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5 active:translate-y-0 relative overflow-hidden group">
                        <span class="relative z-10 flex items-center justify-center">
                            Cari Riwayat
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </span>
                        <!-- Button shine effect CSS -->
                        <div class="shine-effect"></div>
                    </button>
                </form>
            </div>

            @if($searched)
                <!-- Label Hasil Pencarian -->
                <div class="mb-6 flex items-center justify-between animate-fade-up" style="animation-delay: 0.2s;">
                    <div class="flex items-center space-x-2">
                        <div class="w-1 h-4 bg-[#8dc63f] rounded-full"></div>
                        <h3 class="text-sm font-black text-slate-800 tracking-tight">HASIL PENCARIAN</h3>
                    </div>
                    
                    @if($customer)
                        <div class="bg-blue-50 text-[#005ea2] font-bold px-3 py-1.5 rounded-full border border-blue-100/50 flex items-center space-x-1.5 shadow-sm">
                            <span class="w-1.5 h-1.5 bg-[#005ea2] rounded-full animate-pulse"></span>
                            <span class="text-[10px] tracking-wide">Hai, {{ explode(' ', $customer->name)[0] }}</span>
                        </div>
                    @endif
                </div>

                @if($customer && $orders->count() > 0)
                    <!-- Daftar Pesanan (Card Style Baru) -->
                    <div class="space-y-5">
                        @foreach($orders as $index => $order)
                        <div class="bg-white rounded-[1.5rem] p-5 shadow-sm hover:shadow-lg transition-all duration-300 border border-slate-100 group animate-fade-up" style="animation-delay: {{ 0.3 + ($index * 0.1) }}s;">
                            
                            <!-- Header Card: Restoran & Waktu -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                        <span class="text-base font-black text-[#005ea2]">
                                            {{ strtoupper(substr(str_replace([' ', "'"], '', $order->tenant->name ?? 'RS'), 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-slate-800 text-sm md:text-base leading-tight">{{ $order->tenant->name ?? 'Restoran' }}</h4>
                                        <p class="text-[11px] text-slate-400 font-semibold tracking-wide mt-1">{{ \Carbon\Carbon::parse($order->ordered_at)->translatedFormat('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <span class="bg-slate-50 text-slate-500 text-[9px] font-black uppercase tracking-widest px-2 py-1.5 rounded-md border border-slate-200">
                                    {{ $order->order_code }}
                                </span>
                            </div>

                            <!-- Divider with dashed line -->
                            <div class="w-full border-t border-dashed border-slate-200 my-4"></div>

                            <!-- Item Breakdown Preview -->
                            @if($order->orderItems && $order->orderItems->count() > 0)
                            <div class="mb-4 bg-slate-50/80 rounded-xl p-3 border border-slate-100 space-y-1.5 text-xs">
                                @foreach($order->orderItems->take(2) as $item)
                                <div class="flex justify-between items-center text-slate-600">
                                    <span class="truncate max-w-[210px] font-medium">{{ $item->quantity }}x {{ $item->product_name_snapshot ?? ($item->product->name ?? 'Menu') }}</span>
                                    <span class="font-bold text-slate-800 shrink-0">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                                @endforeach
                                @if($order->orderItems->count() > 2)
                                <p class="text-[10px] text-slate-400 font-semibold italic pt-0.5">+{{ $order->orderItems->count() - 2 }} menu lainnya</p>
                                @endif
                            </div>
                            @endif

                            <!-- Body Card: Total & Status -->
                            <div class="flex justify-between items-end mb-4">
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Total Belanja</p>
                                    <p class="font-black text-lg text-slate-800 tracking-tight">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>
                                
                                @php
                                    $statusData = [
                                        'menunggu' => ['bg-amber-100 text-amber-700', 'Menunggu', 'bg-amber-500 animate-pulse'],
                                        'diproses' => ['bg-emerald-100 text-emerald-700', 'Dimasak', 'bg-emerald-500 animate-pulse'],
                                        'siap' => ['bg-[#005ea2]/10 text-[#005ea2]', 'Siap Diambil', 'bg-[#005ea2] animate-pulse'],
                                        'selesai' => ['bg-slate-100 text-slate-600', 'Selesai', 'bg-slate-400'],
                                        'ditolak' => ['bg-rose-100 text-rose-700', 'Ditolak', 'bg-rose-500'],
                                        'dibatalkan' => ['bg-rose-100 text-rose-700', 'Dibatalkan', 'bg-rose-500'],
                                    ];
                                    $s = $statusData[$order->status] ?? $statusData['menunggu'];
                                @endphp
                                
                                <div class="flex items-center space-x-1.5 {{ $s[0] }} px-3 py-1.5 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $s[2] }}"></span>
                                    <span class="text-[10px] font-extrabold tracking-wide">{{ $s[1] }}</span>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="space-y-2">
                                <a href="{{ route('customer.receipt', ['order' => $order->order_code]) }}" class="w-full text-center bg-slate-50 hover:bg-[#005ea2] text-slate-700 hover:text-white font-extrabold py-3.5 rounded-xl text-xs transition-all duration-300 border border-slate-200 hover:border-[#005ea2] shadow-sm group/btn flex items-center justify-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    <span>Lihat Struk Digital</span>
                                </a>

                                @if(!in_array($order->status, ['selesai', 'dibatalkan', 'ditolak']))
                                <a href="{{ route('customer.tracking', ['order' => $order->order_code]) }}" class="w-full text-center bg-blue-50 hover:bg-blue-100 text-[#005ea2] font-extrabold py-2.5 rounded-xl text-xs transition-all border border-blue-200/60 flex items-center justify-center space-x-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#005ea2] animate-ping"></span>
                                    <span>Lacak Pesanan Sedang Berlangsung &rarr;</span>
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State / Tidak Ditemukan -->
                    <div class="bg-white rounded-[2rem] p-8 text-center shadow-sm border border-slate-100 mt-4 animate-fade-up" style="animation-delay: 0.3s;">
                        <div class="w-16 h-16 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-rose-100 text-rose-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                        </div>
                        <h3 class="font-extrabold text-slate-800 text-lg mb-2">Tidak Ditemukan</h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[220px] mx-auto">Kami tidak dapat menemukan pesanan terkait. Pastikan nomor yang dimasukkan sudah benar.</p>
                    </div>
                @endif
            @endif

        </main>
    </div>
</body>
</html>