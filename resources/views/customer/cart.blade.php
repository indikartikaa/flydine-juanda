<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Pesanan - FlyDine Juanda</title>
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
        
        /* Kustomisasi Scrollbar */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Sticky Bottom CTA SafeArea */
        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            .pb-safe { padding-bottom: calc(env(safe-area-inset-bottom) + 1rem); }
        }
    </style>
</head>
<body class="text-slate-800 min-h-screen selection:bg-[#005ea2] selection:text-white flex justify-center bg-slate-100">

    <!-- Mobile-First Container Wrapper -->
    <div class="w-full max-w-lg bg-[#f8fafc] min-h-screen relative flex flex-col shadow-2xl shadow-slate-200/50">
        
        <!-- Header / AppBar (Mobile App Style) -->
        <header class="bg-white/90 backdrop-blur-md sticky top-0 z-50 px-4 py-4 flex items-center justify-between border-b border-slate-200/50 shadow-sm">
            <div class="flex items-center">
                <a href="/" class="p-2 -ml-2 rounded-full hover:bg-slate-100 transition-colors text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <div class="ml-2">
                    <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">Checkout</h1>
                </div>
            </div>
            <div>
                <span class="bg-[#005ea2]/10 text-[#005ea2] px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">Juanda T1</span>
            </div>
        </header>

        <!-- Main Scrollable Content -->
        <main class="flex-grow px-4 sm:px-6 py-6 pb-40">
            @if(count($cart) > 0)
                
            <!-- Lokasi Pengambilan (Tenant) -->
            <div class="mb-6">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-2">Lokasi Restoran</p>
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-start space-x-4">
                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center shrink-0 border border-blue-200/50 overflow-hidden text-blue-600 font-black text-lg">
                        {{ strtoupper(substr(str_replace([' ', "'"], '', $tenant->name), 0, 2)) }}
                    </div>
                    <div>
                        <h2 class="font-extrabold text-slate-800">{{ $tenant->name }}</h2>
                        <p class="text-xs text-slate-500 font-medium mt-0.5 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-amber-500 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                            {{ $tenant->floor_location ?? 'Terminal' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Receipt / Rincian Pesanan -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-3">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest ml-2">Rincian Pesanan</p>
                    <form action="{{ route('customer.cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Kosongkan keranjang?')" class="text-xs font-bold text-rose-500 hover:text-rose-700">Kosongkan</button>
                    </form>
                </div>
                
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 relative overflow-hidden">
                    <!-- Decor receipt edge -->
                    <div class="absolute -top-2 left-0 right-0 h-4 bg-[radial-gradient(circle,transparent_4px,#fff_4px)] bg-[length:12px_12px] opacity-0"></div>

                    <!-- Item List -->
                    <div class="space-y-4">
                        @foreach($cart as $id => $item)
                        <!-- Item -->
                        <div class="flex justify-between items-start group relative">
                            <div class="flex space-x-3">
                                <div class="w-6 text-sm font-bold text-[#005ea2]">{{ $item['quantity'] }}x</div>
                                <div>
                                    <h3 class="font-bold text-sm text-slate-800">{{ $item['name'] }}</h3>
                                    <p class="text-xs text-slate-400 font-medium">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="font-bold text-slate-800 text-sm">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                
                                <!-- Update Actions -->
                                <div class="flex items-center space-x-2 mt-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                    <form action="{{ route('customer.cart.update') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit" class="w-6 h-6 rounded-md bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-bold hover:bg-rose-100 hover:text-rose-600 transition-colors">-</button>
                                    </form>
                                    <form action="{{ route('customer.cart.update') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <input type="hidden" name="action" value="increase">
                                        <button type="submit" class="w-6 h-6 rounded-md bg-slate-100 text-slate-600 flex items-center justify-center text-xs font-bold hover:bg-[#005ea2]/10 hover:text-[#005ea2] transition-colors">+</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Divider -->
                    <div class="my-5 border-b-2 border-dashed border-slate-200"></div>

                    <!-- Total -->
                    <div class="flex justify-between items-end">
                        <span class="font-extrabold text-sm text-slate-800">Total Pembayaran</span>
                        <span class="font-black text-2xl text-[#005ea2] tracking-tight">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Form Data Pemesan (Checkout) -->
            <form action="{{ route('customer.checkout') }}" method="POST" id="checkoutForm" x-data="{ 
                customerType: 'penumpang', 
                boardingTime: '',
                get isTimeWarning() {
                    if (this.customerType !== 'penumpang' || !this.boardingTime) return false;
                    const now = new Date();
                    const [hours, minutes] = this.boardingTime.split(':');
                    const boardTime = new Date();
                    boardTime.setHours(parseInt(hours), parseInt(minutes), 0, 0);
                    const diffInMinutes = (boardTime - now) / 60000;
                    return diffInMinutes >= 0 && diffInMinutes < 30;
                }
            }">
                @csrf
                
                @if ($errors->any())
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl text-sm font-bold">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <div class="mb-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-2">Informasi Pemesan</p>
                    <div class="space-y-5 bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
                        
                        <!-- Tipe Pemesan (Radio) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2 ml-1">Tipe Pemesan</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative flex items-center justify-center p-3 border rounded-xl cursor-pointer transition-all" :class="customerType === 'penumpang' ? 'border-[#005ea2] bg-blue-50/50' : 'border-slate-200 hover:bg-slate-50'">
                                    <input type="radio" name="customer_type" value="penumpang" class="sr-only" x-model="customerType">
                                    <div class="text-center">
                                        <span class="block text-sm font-bold text-slate-800" :class="customerType === 'penumpang' ? 'text-[#005ea2]' : ''">Penumpang</span>
                                        <span class="block text-[10px] text-slate-500 mt-0.5">Berangkat penerbangan</span>
                                    </div>
                                    <div x-show="customerType === 'penumpang'" class="absolute top-2 right-2 text-[#005ea2]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    </div>
                                </label>
                                
                                <label class="relative flex items-center justify-center p-3 border rounded-xl cursor-pointer transition-all" :class="customerType === 'pengunjung' ? 'border-[#005ea2] bg-blue-50/50' : 'border-slate-200 hover:bg-slate-50'">
                                    <input type="radio" name="customer_type" value="pengunjung" class="sr-only" x-model="customerType">
                                    <div class="text-center">
                                        <span class="block text-sm font-bold text-slate-800" :class="customerType === 'pengunjung' ? 'text-[#005ea2]' : ''">Umum / Staf</span>
                                        <span class="block text-[10px] text-slate-500 mt-0.5">Pengunjung bandara</span>
                                    </div>
                                    <div x-show="customerType === 'pengunjung'" class="absolute top-2 right-2 text-[#005ea2]" style="display: none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Input Nama Pemesan -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 ml-1" x-text="customerType === 'penumpang' ? 'Nama (Sesuai Boarding Pass)' : 'Nama Lengkap'"></label>
                            <input type="text" name="customer_name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-[#005ea2] outline-none transition-all placeholder:text-slate-400" placeholder="Contoh: Budi Santoso">
                        </div>
                        
                        <!-- Input Nomor HP / WA -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 ml-1">No. WhatsApp / Telepon</label>
                            <input type="tel" name="phone_number" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-[#005ea2] outline-none transition-all placeholder:text-slate-400" placeholder="Contoh: 08123456789">
                            <p class="text-[10px] text-slate-500 mt-1.5 ml-1">Nomor ini digunakan untuk menghubungi Anda & melacak riwayat pesanan.</p>
                        </div>
                        
                        <!-- Input Penerbangan (Disembunyikan jika bukan penumpang) -->
                        <div x-show="customerType === 'penumpang'">
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <!-- Input No Penerbangan -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5 ml-1">No. Penerbangan</label>
                                    <input type="text" name="flight_number" :required="customerType === 'penumpang'" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium uppercase focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-[#005ea2] outline-none transition-all placeholder:text-slate-400" placeholder="JT-012">
                                </div>
                                <!-- Input Gate -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5 ml-1">Gate (Pintu)</label>
                                    <input type="text" name="gate" :required="customerType === 'penumpang'" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium uppercase focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-[#005ea2] outline-none transition-all placeholder:text-slate-400" placeholder="Gate 8">
                                </div>
                            </div>

                            <!-- Input Boarding Time -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5 ml-1">Waktu Boarding</label>
                                <input type="time" name="boarding_time" x-model="boardingTime" :required="customerType === 'penumpang'" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-[#005ea2] outline-none transition-all text-slate-700">
                            </div>

                            <!-- Peringatan Soft Warning Boarding Time -->
                            <div x-show="isTimeWarning" class="mt-3 p-3 bg-rose-50/80 rounded-xl border border-rose-200/50 flex items-start space-x-3" style="display: none;">
                                <div class="bg-rose-100 p-1.5 rounded-lg shrink-0 text-rose-600 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                </div>
                                <p class="text-[11px] sm:text-xs text-rose-800 font-medium leading-relaxed">
                                    Waktu boarding kurang dari <span class="font-bold">30 menit</span>! Mohon pastikan Anda bisa mengambil pesanan sesegera mungkin. <span class="font-bold underline">Tidak ada refund</span> jika tertinggal penerbangan.
                                </p>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <div class="mb-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 ml-2">Metode Pembayaran</p>
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
                        <select name="payment_method" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-[#005ea2] outline-none transition-all text-slate-700">
                            <option value="qris">QRIS (Langsung Proses)</option>
                            <option value="transfer">Transfer Bank (Virtual Account)</option>
                        </select>
                        <p class="text-[11px] text-slate-500 mt-3 flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Pesanan Anda baru akan dibuat oleh restoran setelah pembayaran online berhasil dikonfirmasi.
                        </p>
                    </div>
                </div>
            </form>
            
            <!-- Peringatan Sistem (Fitur Khas FR FlyDine) -->
            <div class="mt-4 p-3 bg-amber-50/80 rounded-xl border border-amber-200/50 flex items-start space-x-3">
                <div class="bg-amber-100 p-1.5 rounded-lg shrink-0 text-amber-600 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <p class="text-[11px] sm:text-xs text-amber-800 font-medium leading-relaxed">
                    Pesanan akan otomatis dibatalkan tepat pada <span class="font-bold">jadwal boarding</span> (atau maksimal 15 menit) jika pembayaran belum diselesaikan.
                </p>
            </div>
            
            @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center text-center py-20 px-6">
                <div class="w-32 h-32 mb-6 opacity-80">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300 w-full h-full">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">Keranjang Kosong</h3>
                <p class="text-slate-500 font-medium text-sm mb-8">Belum ada makanan yang Anda pilih. Yuk lihat-lihat menu lezat dari tenant kami!</p>
                <a href="{{ route('customer.menu') }}" class="bg-white border border-[#005ea2] text-[#005ea2] hover:bg-[#005ea2] hover:text-white px-8 py-3.5 rounded-full font-bold text-sm shadow-sm transition-all flex items-center">
                    Lihat Restoran
                </a>
            </div>
            @endif
        </main>

        @if(count($cart) > 0)
        <!-- Sticky Bottom CTA (Mobile App Feel) -->
        <div class="fixed bottom-0 w-full max-w-lg bg-white/90 backdrop-blur-lg border-t border-slate-200/80 p-4 pb-safe z-50">
            <button type="submit" form="checkoutForm" class="w-full bg-[#005ea2] hover:bg-blue-700 text-white font-extrabold py-4 rounded-[1.25rem] text-sm uppercase tracking-widest transition-all shadow-lg shadow-blue-500/30 active:scale-[0.98] flex items-center justify-center">
                <span>Pesan & Lanjut Pembayaran</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </button>
        </div>
        @endif
    </div>

    <!-- Footer Tipis -->
    <footer class="py-6 text-center text-gray-400 text-xs mt-auto">
        <p>FlyDine System MVP v1.0 • Juanda International Airport</p>
    </footer>

</body>
</html>