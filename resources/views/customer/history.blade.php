<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan Saya - FlyDine Juanda</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif; background-color: #e2e8f0; }
        
        /* Subtle entrance animation */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        
        /* Glassmorphism utility */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="text-slate-800 min-h-screen selection:bg-[#005ea2] selection:text-white flex justify-center bg-slate-200 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">

    <!-- Mobile-First Container Wrapper -->
    <div class="w-full max-w-lg bg-[#f8fafc] min-h-screen relative flex flex-col shadow-2xl overflow-hidden">
        
        <!-- Premium Header Area (Gradient + Glass) -->
        <div class="relative bg-gradient-to-br from-[#003b66] via-[#005ea2] to-blue-500 pb-10 pt-safe-top z-0">
            <!-- Decorative blur orbs -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-400 rounded-full mix-blend-multiply filter blur-2xl opacity-50 animate-pulse"></div>
            <div class="absolute bottom-0 left-10 w-24 h-24 bg-[#8dc63f] rounded-full mix-blend-multiply filter blur-2xl opacity-30"></div>
            
            <header class="px-4 py-4 flex items-center relative z-10">
                <a href="/" class="p-2 -ml-2 rounded-full hover:bg-white/10 transition-colors text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <div class="ml-2 flex-grow text-center pr-8">
                    <h1 class="text-lg font-extrabold text-white tracking-tight drop-shadow-sm">Riwayat Pesanan</h1>
                </div>
            </header>

            <div class="px-6 pt-4 pb-2 relative z-10 text-center">
                <h2 class="text-2xl font-black text-white drop-shadow-md mb-2 leading-tight">Temukan Pesanan<br>Masa Lalu Anda</h2>
                <p class="text-blue-100 text-xs font-medium max-w-[250px] mx-auto opacity-90">Masukkan nomor yang terdaftar saat Anda memesan makanan di FlyDine Juanda.</p>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-grow px-5 py-6 pb-32 -mt-8 relative z-10">
            
            <!-- Pencarian Card (Glassmorphic overlapping header) -->
            <div class="glass p-6 rounded-[2rem] shadow-xl shadow-blue-900/5 mb-8 animate-fade-up" style="animation-delay: 0.1s;">
                <form action="{{ route('customer.history') }}" method="GET" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2 ml-1">Nomor WhatsApp / Telepon</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-transform group-focus-within:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-focus-within:text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            </div>
                            <input type="tel" name="phone_number" value="{{ request('phone_number') }}" required class="w-full bg-white/80 border border-slate-200 rounded-2xl pl-12 pr-4 py-4 text-sm font-bold text-slate-800 focus:bg-white focus:ring-4 focus:ring-[#005ea2]/10 focus:border-[#005ea2] outline-none transition-all placeholder:text-slate-400 placeholder:font-medium shadow-inner" placeholder="0812-XXXX-XXXX">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-[#005ea2] to-blue-600 hover:to-blue-700 text-white font-black py-4 rounded-2xl text-sm uppercase tracking-widest transition-all shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:-translate-y-0.5 active:translate-y-0 relative overflow-hidden group">
                        <span class="relative z-10 flex items-center justify-center">
                            Lacak Sekarang
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </span>
                        <!-- Button shine effect -->
                        <div class="absolute top-0 -inset-full h-full w-1/2 z-0 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-20 group-hover:animate-[shine_1s_ease-in-out]"></div>
                    </button>
                </form>
            </div>

            @if($searched)
                <div class="mb-5 flex items-end justify-between animate-fade-up" style="animation-delay: 0.2s;">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest pl-2 border-l-4 border-[#8dc63f]">Hasil Pencarian</h3>
                    @if($customer)
                        <div class="bg-blue-50 text-[#005ea2] font-bold px-3 py-1.5 rounded-full border border-blue-100 flex items-center space-x-1 shadow-sm">
                            <span class="w-1.5 h-1.5 bg-[#005ea2] rounded-full animate-pulse"></span>
                            <span class="text-[11px]">Hai, {{ $customer->name }}</span>
                        </div>
                    @endif
                </div>

                @if($customer && $orders->count() > 0)
                    <div class="space-y-5">
                        @foreach($orders as $index => $order)
                        <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 relative overflow-hidden group animate-fade-up" style="animation-delay: {{ 0.3 + ($index * 0.1) }}s;">
                            <!-- Top Decor Accent -->
                            <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-[#005ea2] to-blue-400 opacity-80"></div>
                            
                            <!-- Header Card -->
                            <div class="flex justify-between items-start mb-5">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0">
                                        <span class="text-sm font-black text-[#005ea2]">
                                            {{ strtoupper(substr(str_replace([' ', "'"], '', $order->tenant->name ?? 'RS'), 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-slate-800 text-sm leading-tight">{{ $order->tenant->name ?? 'Restoran' }}</h4>
                                        <p class="text-[10px] text-slate-400 font-bold tracking-wider mt-0.5">{{ \Carbon\Carbon::parse($order->ordered_at)->translatedFormat('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <span class="bg-slate-100/80 text-slate-500 text-[9px] font-black uppercase tracking-widest px-2.5 py-1.5 rounded-lg border border-slate-200">
                                    {{ $order->order_code }}
                                </span>
                            </div>

                            <!-- Divider with dots -->
                            <div class="w-full border-t-[1.5px] border-dashed border-slate-200 mb-5"></div>

                            <!-- Body Card -->
                            <div class="flex justify-between items-end mb-1">
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Total Transaksi</p>
                                    <p class="font-black text-xl text-[#005ea2] tracking-tight">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>
                                
                                @php
                                    $statusData = [
                                        'menunggu' => ['bg-amber-100 text-amber-700 border-amber-200', 'Menunggu', 'bg-amber-500 animate-pulse'],
                                        'diproses' => ['bg-emerald-100 text-emerald-700 border-emerald-200', 'Dimasak', 'bg-emerald-500 animate-pulse'],
                                        'siap' => ['bg-blue-100 text-blue-700 border-blue-200', 'Siap Diambil', 'bg-blue-500 animate-pulse'],
                                        'selesai' => ['bg-slate-100 text-slate-600 border-slate-200', 'Selesai', 'bg-slate-400'],
                                        'ditolak' => ['bg-rose-100 text-rose-700 border-rose-200', 'Ditolak', 'bg-rose-500'],
                                        'dibatalkan' => ['bg-rose-100 text-rose-700 border-rose-200', 'Dibatalkan', 'bg-rose-500'],
                                    ];
                                    $s = $statusData[$order->status] ?? $statusData['menunggu'];
                                @endphp
                                
                                <div class="flex items-center space-x-1.5 {{ $s[0] }} px-3 py-1.5 rounded-full border shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $s[2] }}"></span>
                                    <span class="text-[10px] font-black tracking-wide">{{ $s[1] }}</span>
                                </div>
                            </div>
                            
                            <!-- Action Button -->
                            <div class="mt-6">
                                <a href="{{ route('customer.tracking', ['order' => $order->order_code]) }}" class="block w-full text-center bg-[#f8fafc] hover:bg-slate-800 text-slate-700 hover:text-white font-extrabold py-3.5 rounded-2xl text-xs transition-all duration-300 border border-slate-200 hover:border-slate-800 shadow-sm flex justify-center items-center group/btn">
                                    <span>Lihat Struk Lengkap</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5 opacity-50 group-hover/btn:opacity-100 group-hover/btn:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white/80 backdrop-blur border border-rose-100 rounded-[2rem] p-8 text-center shadow-lg shadow-rose-900/5 animate-fade-up" style="animation-delay: 0.3s;">
                        <div class="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm border border-rose-100 text-rose-500 transform rotate-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h3 class="font-extrabold text-slate-800 text-lg mb-2">Riwayat Tidak Ditemukan</h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[200px] mx-auto">Kami tidak dapat menemukan pesanan yang terhubung dengan nomor tersebut. Pastikan nomor sudah benar.</p>
                    </div>
                @endif
            @endif

        </main>
        
        <style>
            @keyframes shine {
                100% { left: 125%; }
            }
        </style>
    </div>
</body>
</html>
