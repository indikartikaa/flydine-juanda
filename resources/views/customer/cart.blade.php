<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        /* Hilangkan spinner default browser pada input type number */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none !important;
            margin: 0 !important;
        }
        input[type=number] {
            -moz-appearance: textfield !important;
        }
    </style>
</head>
<body class="text-slate-800 min-h-screen selection:bg-[#005ea2] selection:text-white bg-slate-50/60 antialiased flex flex-col">

    <!-- Header / Navigation (Matching Catalog Style & Size) -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm transition-all duration-300" x-data="{ mobileMenuOpen: false }">
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
                    
                    <a href="{{ route('customer.cart') }}" class="flex items-center space-x-2 text-sm font-bold text-[#005ea2] transition-colors group">
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

                    <a href="{{ route('customer.cart') }}" class="flex items-center space-x-2 text-sm font-bold text-[#005ea2] bg-blue-50 py-3 px-4 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        <span>PESANAN SAYA</span>
                    </a>

                    <a href="{{ route('customer.history') }}" class="flex items-center space-x-2 text-sm font-bold text-slate-700 bg-slate-50 py-3 px-4 rounded-xl hover:bg-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
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

    <!-- Main Container -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10" x-data="cartManager()">
        
        <!-- Error / Success Notification Banners -->
        <div x-show="errorMessage" x-cloak class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-5 py-3.5 rounded-2xl text-sm font-bold flex items-center justify-between shadow-sm animate-fade-in">
            <div class="flex items-center space-x-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span x-text="errorMessage"></span>
            </div>
            <button type="button" @click="errorMessage = ''" class="text-rose-400 hover:text-rose-600 p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        @if(session('error'))
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-5 py-3.5 rounded-2xl text-sm font-bold flex items-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2.5 shrink-0 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3.5 rounded-2xl text-sm font-bold flex items-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2.5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(count($cart) > 0)
        <!-- ACTIVE CART LAYOUT (Responsive 2-Column on Desktop) -->
        <div x-show="itemsCount > 0" class="space-y-6">
            
            <!-- Page Title Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-200/80 gap-2">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Checkout Pesanan</h1>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">Konfirmasi menu makanan & rincian pengantaran di Bandara Internasional Juanda</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-blue-50 border border-blue-200 text-[#005ea2] text-xs font-black shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-[#005ea2] animate-pulse"></span>
                        <span x-text="itemsCount + ' Menu Dipilih'"></span>
                    </span>
                </div>
            </div>

            <!-- Two-Column Grid: Left (Items & Form), Right (Sticky Summary) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- LEFT COLUMN: Content & Forms (8 cols) -->
                <div class="lg:col-span-7 xl:col-span-8 space-y-6">
                    
                    <!-- Tenant Card -->
                    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#003b66] to-[#005ea2] text-white flex items-center justify-center shrink-0 font-black text-xl shadow-md shadow-blue-500/20">
                                {{ strtoupper(substr(str_replace([' ', "'"], '', $tenant->name ?? 'FD'), 0, 2)) }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h2 class="font-black text-slate-900 text-lg leading-tight">{{ $tenant->name ?? 'Restoran' }}</h2>
                                    @if(!empty($tenant->terminal))
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-[#005ea2]/10 text-[#005ea2] uppercase">{{ $tenant->terminal }}</span>
                                    @endif
                                </div>
                                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500 mr-1.5 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                    <span>{{ $tenant->floor_location ?? 'Bandara Internasional Juanda' }}</span>
                                </p>
                            </div>
                        </div>
                        <span class="inline-flex items-center space-x-1.5 text-xs font-extrabold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-200/60 self-start sm:self-auto">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Restoran Buka</span>
                        </span>
                    </div>

                    <!-- Items List Card -->
                    <div class="bg-white rounded-3xl p-5 sm:p-7 border border-slate-200/80 shadow-sm">
                        <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                            <div class="flex items-center space-x-2.5">
                                <span class="w-1.5 h-5 bg-[#005ea2] rounded-full"></span>
                                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Daftar Menu yang Dipesan</h3>
                            </div>
                            <form action="{{ route('customer.cart.clear') }}" method="POST" @submit.prevent="clearCart()">
                                @csrf
                                <button type="submit" :disabled="isClearing || loadingItemId !== null" class="text-xs font-bold text-rose-500 hover:text-rose-700 disabled:opacity-50 flex items-center space-x-1.5 py-1 px-3 rounded-lg hover:bg-rose-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    <span x-show="!isClearing">Kosongkan Keranjang</span>
                                    <span x-show="isClearing" x-cloak>Menghapus...</span>
                                </button>
                            </form>
                        </div>

                        <!-- Item Rows -->
                        <div class="divide-y divide-slate-100">
                            @foreach($cart as $id => $item)
                            <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 group relative transition-all duration-200"
                                 x-show="items['{{ $id }}'] && items['{{ $id }}'].quantity > 0"
                                 :class="{ 'opacity-60 pointer-events-none': loadingItemId === '{{ $id }}' }">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-10 h-10 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center font-black text-xs text-[#005ea2] shrink-0">
                                        <span x-text="(items['{{ $id }}'] ? items['{{ $id }}'].quantity : {{ $item['quantity'] }}) + 'x'">{{ $item['quantity'] }}x</span>
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-sm sm:text-base text-slate-800">{{ $item['name'] }}</h4>
                                        <p class="text-xs text-slate-400 font-semibold mt-0.5">@ Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end sm:space-x-6">
                                    <span class="font-black text-slate-900 text-sm sm:text-base">
                                        Rp <span x-text="formatNumber(items['{{ $id }}'] ? (items['{{ $id }}'].price * items['{{ $id }}'].quantity) : {{ $item['price'] * $item['quantity'] }})">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                    </span>
                                    
                                    <!-- Quantity Adjustment Stepper with Direct Number Input -->
                                    <div class="flex items-center space-x-1.5 bg-slate-100 p-1 rounded-xl border border-slate-200/80 shadow-xs">
                                        <!-- Decrease Button (-) -->
                                        <button type="button" 
                                                @click="updateQuantity('{{ $id }}', 'decrease')" 
                                                :disabled="loadingItemId === '{{ $id }}'" 
                                                class="w-7 h-7 rounded-lg bg-white text-slate-700 flex items-center justify-center text-xs font-black shadow-xs hover:bg-rose-50 hover:text-rose-600 transition-colors disabled:opacity-50 active:scale-95 cursor-pointer" 
                                                title="Kurangi 1">
                                            −
                                        </button>
                                        
                                        <!-- Direct Editable Number Input -->
                                        <input type="number" 
                                               inputmode="numeric"
                                               min="1" 
                                               max="99" 
                                               :value="items['{{ $id }}'] ? items['{{ $id }}'].quantity : {{ $item['quantity'] }}" 
                                               @change="
                                                   let val = parseInt($event.target.value, 10);
                                                   if (isNaN(val) || val < 1) val = 1;
                                                   if (val > 99) val = 99;
                                                   $event.target.value = val;
                                                   updateQuantity('{{ $id }}', 'set', val, $event.target);
                                               " 
                                               @keydown.enter.prevent="$event.target.blur()"
                                               :disabled="loadingItemId === '{{ $id }}'" 
                                               class="w-14 h-7 text-center text-xs font-black text-slate-900 bg-white rounded-lg border border-slate-200 px-1 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none focus:outline-none focus:ring-2 focus:ring-[#005ea2] focus:border-[#005ea2] transition-all disabled:opacity-50"
                                               title="Ketik jumlah langsung (contoh: 10)">
                                        
                                        <!-- Increase Button (+) -->
                                        <button type="button" 
                                                @click="updateQuantity('{{ $id }}', 'increase')" 
                                                :disabled="loadingItemId === '{{ $id }}'" 
                                                class="w-7 h-7 rounded-lg bg-white text-slate-700 flex items-center justify-center text-xs font-black shadow-xs hover:bg-blue-50 hover:text-[#005ea2] transition-colors disabled:opacity-50 active:scale-95 cursor-pointer" 
                                                title="Tambah 1">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Checkout Form (Data Pemesan, Metode Antar, Pembayaran) -->
                    <form action="{{ route('customer.checkout') }}" method="POST" id="checkoutForm" @submit="if (isTimeCritical || isTimePassed) { $event.preventDefault(); return false; }" class="space-y-6">
                        @csrf
                        
                        @if ($errors->any())
                        <div class="bg-rose-50 border border-rose-200 text-rose-700 p-5 rounded-3xl text-sm font-bold shadow-sm">
                            <div class="flex items-center space-x-2 mb-2 text-rose-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <span>Harap periksa dan lengkapi data berikut:</span>
                            </div>
                            <ul class="list-disc pl-5 space-y-1 text-xs">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        
                        <!-- Metode Pengambilan Card -->
                        <div class="bg-white p-5 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm">
                            <div class="flex items-center space-x-2.5 mb-5">
                                <span class="w-1.5 h-5 bg-[#8dc63f] rounded-full"></span>
                                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Metode Pengambilan Makanan</h3>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Ambil Sendiri -->
                                <label class="relative flex flex-col items-center justify-center p-5 border-2 rounded-2xl cursor-pointer transition-all duration-200 text-center" :class="pickupMethod === 'ambil_sendiri' ? 'border-[#005ea2] bg-blue-50/40 shadow-sm' : 'border-slate-200 hover:bg-slate-50'">
                                    <input type="radio" name="pickup_method" value="ambil_sendiri" class="sr-only" x-model="pickupMethod" {{ old('pickup_method', 'ambil_sendiri') === 'ambil_sendiri' ? 'checked' : '' }}>
                                    <span class="text-3xl mb-2">🛍️</span>
                                    <span class="block text-sm font-black text-slate-800" :class="pickupMethod === 'ambil_sendiri' ? 'text-[#005ea2]' : ''">Ambil Sendiri di Counter</span>
                                    <span class="block text-xs text-slate-500 font-medium mt-1">Ambil langsung di kasir restoran tanpa biaya tambahan</span>
                                    <div x-show="pickupMethod === 'ambil_sendiri'" class="absolute top-3 right-3 text-[#005ea2]" style="{{ old('pickup_method', 'ambil_sendiri') === 'ambil_sendiri' ? '' : 'display: none;' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    </div>
                                </label>
                                
                                <!-- Diantar Porter -->
                                <label class="relative flex flex-col items-center justify-center p-5 border-2 rounded-2xl transition-all duration-200 text-center" 
                                       :class="deliveryFee !== null ? (pickupMethod === 'diantar' ? 'border-[#005ea2] bg-blue-50/40 shadow-sm cursor-pointer' : 'border-slate-200 hover:bg-slate-50 cursor-pointer') : 'border-slate-100 bg-slate-50 opacity-60 cursor-not-allowed'">
                                    <input type="radio" name="pickup_method" value="diantar" class="sr-only" x-model="pickupMethod" {{ old('pickup_method') === 'diantar' ? 'checked' : '' }} :disabled="deliveryFee === null">
                                    <span class="text-3xl mb-2">🛵</span>
                                    <span class="block text-sm font-black text-slate-800" :class="pickupMethod === 'diantar' ? 'text-[#005ea2]' : ''">Diantar Porter ke Gate</span>
                                    
                                    <template x-if="deliveryFee !== null">
                                        <span class="block text-xs text-emerald-600 font-black mt-1">Antar ke ruang tunggu (+Rp <span x-text="deliveryFee.toLocaleString('id-ID')"></span>)</span>
                                    </template>
                                    <template x-if="deliveryFee === null">
                                        <span class="block text-xs text-rose-500 font-bold mt-1 bg-rose-50 px-2.5 py-0.5 rounded-full">Layanan Belum Tersedia</span>
                                    </template>
                                    
                                    <div x-show="pickupMethod === 'diantar'" class="absolute top-3 right-3 text-[#005ea2]" style="{{ old('pickup_method') === 'diantar' ? '' : 'display: none;' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    </div>
                                </label>
                            </div>

                            <!-- Info Lokasi Pengambilan (Muncul jika Ambil Sendiri) -->
                            <div x-show="pickupMethod === 'ambil_sendiri'" class="mt-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/90 flex flex-col sm:flex-row sm:items-center justify-between gap-3 animate-fade-in">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center shrink-0 border border-blue-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-black text-slate-800">{{ $tenant->name ?? 'Restoran' }}</span>
                                            <span class="text-[10px] font-extrabold text-[#005ea2] bg-blue-100/70 px-2 py-0.5 rounded-md">
                                                {{ $tenant->terminal ?? 'Terminal 1' }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 font-medium">
                                            {{ $tenant->floor_location ?? 'Area Keberangkatan Bandara Juanda' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-500 bg-white px-3 py-1.5 rounded-xl border border-slate-200/70 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Khusus keberangkatan <strong>{{ $tenant->terminal ?? 'Terminal 1' }}</strong></span>
                                </div>
                            </div>

                            <!-- Dropdown Lokasi Antar Porter (Muncul jika Diantar) -->
                            <div x-show="pickupMethod === 'diantar'" class="mt-4 p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-3 animate-fade-in" style="display: none;">
                                @php
                                    $tenantTerminalNum = str_ireplace('t', '', (string)($tenant->terminal ?? '1'));
                                    $tenantTerminalName = ($tenantTerminalNum === '2') ? 'Terminal 2 (Internasional & Garuda)' : 'Terminal 1 (Domestik)';
                                @endphp

                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-1 border-b border-slate-200/60">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-black text-slate-800 uppercase tracking-wider">Lokasi Pengantaran Porter</span>
                                        <span class="inline-flex items-center gap-1 text-[11px] font-extrabold text-[#005ea2] bg-blue-100/70 px-2.5 py-0.5 rounded-full">
                                            <svg class="w-3 h-3 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            Gedung {{ $tenantTerminalName }}
                                        </span>
                                    </div>
                                    <input type="hidden" name="delivery_terminal" value="{{ $tenantTerminalNum }}">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1.5">Pilih Ruang Tunggu / Pintu Boarding Gate</label>
                                    <div class="relative">
                                        <select name="delivery_location_id" 
                                                :disabled="deliveryLocations.length === 0" 
                                                :required="pickupMethod === 'diantar'" 
                                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 disabled:bg-slate-100 disabled:opacity-60 transition-all">
                                            <option value="" disabled selected>-- Pilih Titik Gate / Ruang Tunggu Antar --</option>
                                            <template x-for="loc in deliveryLocations" :key="loc.id">
                                                <option :value="loc.id" x-text="loc.name" :selected="loc.id == '{{ old('delivery_location_id') }}'"></option>
                                            </template>
                                        </select>
                                    </div>
                                    @error('delivery_location_id')
                                        <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Kontak Pemesan -->
                        <div class="bg-white p-5 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm space-y-5">
                            <div class="flex items-center space-x-2.5">
                                <span class="w-1.5 h-5 bg-[#005ea2] rounded-full"></span>
                                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Informasi Kontak & Pemesan</h3>
                            </div>

                            <!-- Tipe Pemesan (Penumpang vs Pengunjung) -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-2">Status Pemesan di Bandara</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <label class="relative flex items-center justify-center p-3.5 border-2 rounded-2xl cursor-pointer transition-all duration-200" :class="customerType === 'penumpang' ? 'border-[#005ea2] bg-blue-50/40 shadow-sm' : 'border-slate-200 hover:bg-slate-50'">
                                        <input type="radio" name="customer_type" value="penumpang" class="sr-only" x-model="customerType" {{ old('customer_type', 'penumpang') === 'penumpang' ? 'checked' : '' }}>
                                        <div class="text-center">
                                            <span class="block text-sm font-black text-slate-800" :class="customerType === 'penumpang' ? 'text-[#005ea2]' : ''">🛫 Penumpang Pesawat</span>
                                            <span class="block text-[11px] text-slate-400 font-semibold mt-0.5">Memiliki jadwal penerbangan</span>
                                        </div>
                                    </label>
                                    
                                    <label class="relative flex items-center justify-center p-3.5 border-2 rounded-2xl cursor-pointer transition-all duration-200" :class="customerType === 'pengunjung' ? 'border-[#005ea2] bg-blue-50/40 shadow-sm' : 'border-slate-200 hover:bg-slate-50'">
                                        <input type="radio" name="customer_type" value="pengunjung" class="sr-only" x-model="customerType" {{ old('customer_type') === 'pengunjung' ? 'checked' : '' }}>
                                        <div class="text-center">
                                            <span class="block text-sm font-black text-slate-800" :class="customerType === 'pengunjung' ? 'text-[#005ea2]' : ''">🚶 Pengunjung / Staf Bandara</span>
                                            <span class="block text-[11px] text-slate-400 font-semibold mt-0.5">Penjemput & masyarakat umum</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Nama Pemesan -->
                                <div>
                                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2" x-text="customerType === 'penumpang' ? 'Nama (Sesuai Tiket/Boarding Pass)' : 'Nama Lengkap'"></label>
                                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required minlength="2" maxlength="100" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] outline-none transition-all placeholder:text-slate-400" placeholder="Contoh: Budi Santoso">
                                    @error('customer_name')
                                        <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>
                                
                                <!-- No WhatsApp -->
                                <div>
                                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">No. WhatsApp / Telepon Aktif</label>
                                    <input type="tel" 
                                           name="phone_number" 
                                           value="{{ old('phone_number') }}" 
                                           required 
                                           inputmode="tel" 
                                           pattern="[0-9+ \-]{9,20}" 
                                           minlength="9" 
                                           maxlength="20" 
                                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] outline-none transition-all placeholder:text-slate-400" 
                                           placeholder="Contoh: 08123456789">
                                    @error('phone_number')
                                        <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Input Khusus Penumpang -->
                            <div x-show="customerType === 'penumpang'" class="space-y-4 pt-3 border-t border-slate-100">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">No. Penerbangan (Flight Number)</label>
                                        <input type="text" 
                                               name="flight_number" 
                                               value="{{ old('flight_number') }}" 
                                               :required="customerType === 'penumpang'" 
                                               maxlength="15"
                                               @input="$event.target.value = $event.target.value.toUpperCase()" 
                                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-black uppercase text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] outline-none transition-all placeholder:text-slate-400" 
                                               placeholder="Contoh: GA-320 / JT-012">
                                        @error('flight_number')
                                            <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                                <span>{{ $message }}</span>
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">Jadwal Waktu Boarding</label>
                                        <input type="time" name="boarding_time" value="{{ old('boarding_time') }}" x-model="boardingTime" :required="customerType === 'penumpang'" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] outline-none transition-all">
                                        <div class="mt-2 flex flex-wrap items-center justify-between gap-1 text-[11px] text-slate-500 font-medium">
                                            <span>Format 24 jam WIB (sesuai tiket pesawat)</span>
                                            <span x-show="boardingTime" class="font-bold text-[#005ea2] bg-sky-50 px-2.5 py-0.5 rounded-lg border border-sky-100 shadow-2xs" x-text="formatTimeDisplay(boardingTime)"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Info Penerbangan Dini Hari (Cross-Midnight: Besok Pagi) -->
                                <div x-show="isCrossMidnight && !isTimeCritical" class="p-4 bg-sky-50 rounded-2xl border border-sky-200 flex items-start space-x-3 shadow-xs animate-fade-in" style="display: none;">
                                    <div class="bg-sky-100 p-2 rounded-xl shrink-0 text-sky-700 mt-0.5">
                                        <span class="text-base">🌙</span>
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-black text-sky-950 uppercase tracking-wider">Penerbangan Dini Hari (Besok)</h5>
                                        <p class="text-xs text-sky-900 font-medium leading-relaxed mt-0.5">
                                            Sistem mendeteksi jadwal boarding Anda tercatat untuk penerbangan dini hari esok hari (pukul <strong x-text="formatTimeDisplay(boardingTime) || (boardingTime + ' WIB')"></strong>). Pesanan Anda akan diproses tepat waktu sebelum jam keberangkatan.
                                        </p>
                                    </div>
                                </div>

                                <!-- Warning Waktu Boarding Sudah Terlewat -->
                                <div x-show="isTimePassed" class="p-4 bg-rose-50 rounded-2xl border-2 border-rose-300 flex items-start space-x-3 shadow-sm animate-fade-in" style="display: none;">
                                    <div class="bg-rose-100 p-2 rounded-xl shrink-0 text-rose-600 mt-0.5 shadow-2xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-black text-rose-900 uppercase tracking-wider">Perhatian: Waktu Boarding Sudah Terlewat!</h5>
                                        <p class="text-xs text-rose-800 font-medium leading-relaxed mt-0.5">
                                            Jadwal waktu boarding yang Anda masukkan sudah terlewat dari waktu saat ini. Harap periksa dan sesuaikan kembali jadwal penerbangan Anda.
                                        </p>
                                    </div>
                                </div>

                                <!-- Critical Warning Boarding Time (< 15 menit) -->
                                <div x-show="isTimeCritical && !isTimePassed" class="p-4 bg-rose-50 rounded-2xl border-2 border-rose-300 flex items-start space-x-3 shadow-sm animate-fade-in" style="display: none;">
                                    <div class="bg-rose-100 p-2 rounded-xl shrink-0 text-rose-600 mt-0.5 shadow-2xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-black text-rose-900 uppercase tracking-wider">Perhatian: Waktu Boarding Sangat Dekat (&lt; 15 Menit)!</h5>
                                        <p class="text-xs text-rose-800 font-medium leading-relaxed mt-0.5">
                                            Dapur membutuhkan estimasi penyiapan makanan 10–15 menit dan gerbang pesawat ditutup sebelum keberangkatan. Sistem tidak mengizinkan pemesanan dengan waktu boarding kurang dari 15 menit demi mencegah risiko tertinggal penerbangan.
                                        </p>
                                    </div>
                                </div>

                                <!-- Soft Warning Boarding Time (15 - 30 menit) -->
                                <div x-show="isTimeWarning && !isTimeCritical && !isTimePassed" class="p-4 bg-amber-50 rounded-2xl border border-amber-200 flex items-start space-x-3 shadow-sm animate-fade-in" style="display: none;">
                                    <div class="bg-amber-100 p-1.5 rounded-xl shrink-0 text-amber-700 mt-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <p class="text-xs text-amber-800 font-medium leading-relaxed">
                                        Waktu boarding tersisa <span class="font-extrabold text-amber-900">kurang dari 30 menit</span>. Segera selesaikan transaksi agar pesanan Anda dapat langsung diproses prioritas oleh dapur restoran.
                                    </p>
                                </div>

                                @error('boarding_time')
                                    <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Catatan Khusus Pesanan (Opsi 1: Quick Chips & Ringkas) -->
                        <div class="bg-white p-5 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm">
                            <div class="flex items-center justify-between mb-3.5">
                                <div class="flex items-center space-x-2.5">
                                    <span class="w-1.5 h-5 bg-[#8dc63f] rounded-full"></span>
                                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Catatan Pesanan (Opsional)</h3>
                                </div>
                                <span class="text-[11px] font-bold text-slate-400">
                                    <span class="font-mono text-slate-700" x-text="100 - (orderNotes ? orderNotes.length : 0)">100</span> karakter tersisa
                                </span>
                            </div>

                            <!-- Pilihan Chip Cepat (Quick Chips) -->
                            <div class="mb-3">
                                <span class="block text-[11px] font-bold text-slate-500 mb-2 uppercase tracking-wider">Pilihan Cepat (Klik untuk memilih):</span>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="chip in quickChips" :key="chip.label">
                                        <button type="button" 
                                                @click="toggleChip(chip.label)"
                                                class="text-xs font-bold px-3 py-1.5 rounded-full border transition-all duration-150 cursor-pointer flex items-center gap-1.5 active:scale-95"
                                                :class="orderNotes.includes(chip.label) 
                                                    ? 'bg-emerald-50 text-emerald-800 border-emerald-300 ring-2 ring-emerald-500/20 shadow-xs' 
                                                    : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200 hover:border-slate-300'">
                                            <span x-text="chip.icon"></span>
                                            <span x-text="chip.label"></span>
                                            <span x-show="orderNotes.includes(chip.label)" class="text-[10px] text-emerald-600 font-black">✓</span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Textarea Catatan -->
                            <div>
                                <textarea name="notes" 
                                          x-model="orderNotes" 
                                          maxlength="100" 
                                          rows="2" 
                                          placeholder="Contoh: Sambal dipisah, kurangi es, atau bungkus terpisah untuk kabin..." 
                                          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] outline-none transition-all placeholder:text-slate-400 placeholder:font-normal resize-none"></textarea>
                            </div>

                            @error('notes')
                                <p class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Metode Pembayaran Card -->
                        <div class="bg-white p-5 sm:p-7 rounded-3xl border border-slate-200/80 shadow-sm">
                            <div class="flex items-center space-x-2.5 mb-4">
                                <span class="w-1.5 h-5 bg-amber-500 rounded-full"></span>
                                <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider">Metode Pembayaran Online</h3>
                            </div>

                            <!-- Input tersembunyi yang dikirim ke backend -->
                            <input type="hidden" name="payment_method" :value="paymentCategory === 'qris' ? 'qris' : ('transfer_' + selectedBank)">

                            <!-- Pilihan Kategori Pembayaran -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 mb-4">
                                <!-- Opsi 1: QRIS Instant -->
                                <button type="button" 
                                        @click="paymentCategory = 'qris'"
                                        class="p-4 rounded-2xl border-2 text-left transition-all duration-200 relative cursor-pointer group"
                                        :class="paymentCategory === 'qris' ? 'border-[#005ea2] bg-blue-50/50 shadow-xs ring-2 ring-[#005ea2]/15' : 'border-slate-200 hover:border-slate-300 bg-white'">
                                    <div class="flex items-center justify-between mb-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 font-black text-xs flex items-center justify-center border border-rose-200">
                                            QR
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                                            Rekomendasi
                                        </span>
                                    </div>
                                    <h4 class="font-black text-sm text-slate-900 mb-0.5 flex items-center gap-1.5">
                                        <span>QRIS Instant</span>
                                        <span x-show="paymentCategory === 'qris'" class="text-[#005ea2] text-xs">●</span>
                                    </h4>
                                    <p class="text-xs text-slate-500 font-medium leading-relaxed">
                                        Scan 1 QR via BCA, Livin', BRImo, GoPay, OVO, ShopeePay, Dana.
                                    </p>
                                </button>

                                <!-- Opsi 2: Transfer Virtual Account -->
                                <button type="button" 
                                        @click="paymentCategory = 'transfer'"
                                        class="p-4 rounded-2xl border-2 text-left transition-all duration-200 relative cursor-pointer group"
                                        :class="paymentCategory === 'transfer' ? 'border-[#005ea2] bg-blue-50/50 shadow-xs ring-2 ring-[#005ea2]/15' : 'border-slate-200 hover:border-slate-300 bg-white'">
                                    <div class="flex items-center justify-between mb-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] font-black text-xs flex items-center justify-center border border-blue-200">
                                            VA
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                                            Bebas Admin
                                        </span>
                                    </div>
                                    <h4 class="font-black text-sm text-slate-900 mb-0.5 flex items-center gap-1.5">
                                        <span>Transfer Virtual Account</span>
                                        <span x-show="paymentCategory === 'transfer'" class="text-[#005ea2] text-xs">●</span>
                                    </h4>
                                    <p class="text-xs text-slate-500 font-medium leading-relaxed">
                                        BCA, Mandiri, BRI, BNI dengan verifikasi otomatis 24 jam.
                                    </p>
                                </button>
                            </div>

                            <!-- Sub-pilihan Bank (Muncul saat Transfer VA dipilih) -->
                            <div x-show="paymentCategory === 'transfer'" 
                                 x-cloak 
                                 x-transition:enter="transition ease-out duration-300" 
                                 x-transition:enter-start="opacity-0 -translate-y-2" 
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150" 
                                 x-transition:leave-start="opacity-100 translate-y-0" 
                                 x-transition:leave-end="opacity-0 -translate-y-2"
                                 class="mt-4 pt-4 border-t border-slate-100">
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-500 mb-2.5">
                                    Pilih Bank Virtual Account Tujuan:
                                </label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                    <!-- BCA -->
                                    <button type="button" 
                                            @click="selectedBank = 'bca'"
                                            class="p-3 rounded-xl border text-center transition-all cursor-pointer relative"
                                            :class="selectedBank === 'bca' ? 'border-blue-600 bg-blue-50/70 ring-2 ring-blue-500/20 text-blue-900 font-black' : 'border-slate-200 hover:border-slate-300 bg-white text-slate-700 font-bold'">
                                        <span class="block text-xs font-black text-blue-700">BCA</span>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">Virtual Account</span>
                                    </button>

                                    <!-- Mandiri -->
                                    <button type="button" 
                                            @click="selectedBank = 'mandiri'"
                                            class="p-3 rounded-xl border text-center transition-all cursor-pointer relative"
                                            :class="selectedBank === 'mandiri' ? 'border-amber-600 bg-amber-50/70 ring-2 ring-amber-500/20 text-amber-900 font-black' : 'border-slate-200 hover:border-slate-300 bg-white text-slate-700 font-bold'">
                                        <span class="block text-xs font-black text-amber-700">Mandiri</span>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">Livin' VA</span>
                                    </button>

                                    <!-- BRI -->
                                    <button type="button" 
                                            @click="selectedBank = 'bri'"
                                            class="p-3 rounded-xl border text-center transition-all cursor-pointer relative"
                                            :class="selectedBank === 'bri' ? 'border-sky-600 bg-sky-50/70 ring-2 ring-sky-500/20 text-sky-900 font-black' : 'border-slate-200 hover:border-slate-300 bg-white text-slate-700 font-bold'">
                                        <span class="block text-xs font-black text-sky-700">BRI</span>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">BRIVA</span>
                                    </button>

                                    <!-- BNI -->
                                    <button type="button" 
                                            @click="selectedBank = 'bni'"
                                            class="p-3 rounded-xl border text-center transition-all cursor-pointer relative"
                                            :class="selectedBank === 'bni' ? 'border-teal-600 bg-teal-50/70 ring-2 ring-teal-500/20 text-teal-900 font-black' : 'border-slate-200 hover:border-slate-300 bg-white text-slate-700 font-bold'">
                                        <span class="block text-xs font-black text-teal-700">BNI</span>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">Virtual Account</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- RIGHT COLUMN: Sticky Order Summary Card (4 cols on Desktop) -->
                <div class="lg:col-span-5 xl:col-span-4 lg:sticky lg:top-28 space-y-4">
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                            <h3 class="text-base font-black text-slate-900">Ringkasan Tagihan</h3>
                            <span class="text-xs font-bold text-slate-400">FlyDine Juanda</span>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center text-slate-600">
                                <span>Subtotal Menu (<span x-text="itemsCount"></span> item)</span>
                                <span class="font-bold text-slate-800">Rp <span x-text="formatNumber(baseTotal)"></span></span>
                            </div>

                            <div x-show="pickupMethod === 'diantar' && deliveryFee !== null" class="flex justify-between items-center text-slate-600" style="display: none;">
                                <span class="flex items-center gap-1.5">
                                    <span>🛵 Biaya Porter Gate</span>
                                </span>
                                <span class="font-bold text-slate-800">Rp <span x-text="deliveryFee ? deliveryFee.toLocaleString('id-ID') : 0"></span></span>
                            </div>

                            <div class="pt-4 border-t border-dashed border-slate-200 flex justify-between items-end">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-wider text-slate-400">Total Pembayaran</p>
                                    <p class="text-[11px] text-slate-400">Termasuk pajak & layanan</p>
                                </div>
                                <span class="text-2xl font-black text-[#005ea2] tracking-tight">
                                    Rp <span x-text="formatNumber(grandTotal)">{{ number_format($total, 0, ',', '.') }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- CTA Button on Desktop -->
                        <div class="mt-6">
                            <button type="submit" 
                                    form="checkoutForm" 
                                    :disabled="isTimeCritical || isTimePassed" 
                                    class="w-full text-white font-black py-4 px-6 rounded-2xl text-sm uppercase tracking-wider transition-all flex items-center justify-center space-x-2 relative overflow-hidden group shadow-lg"
                                    :class="(isTimeCritical || isTimePassed) 
                                        ? 'bg-rose-600/80 cursor-not-allowed opacity-75 shadow-none' 
                                        : 'bg-gradient-to-r from-[#003b66] to-[#005ea2] hover:from-[#002f54] hover:to-[#004a80] shadow-blue-500/25 active:scale-[0.98] cursor-pointer'"
                                    :style="(isTimeCritical || isTimePassed) ? 'background-color: #e11d48;' : 'background: linear-gradient(to right, #003b66, #005ea2); background-color: #005ea2;'">
                                <span class="relative z-10 flex items-center justify-center">
                                    <template x-if="!isTimeCritical && !isTimePassed">
                                        <span class="flex items-center">
                                            Pesan & Lanjut Bayar
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                        </span>
                                    </template>
                                    <template x-if="isTimePassed">
                                        <span class="flex items-center text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            Waktu Boarding Sudah Terlewat
                                        </span>
                                    </template>
                                    <template x-if="isTimeCritical && !isTimePassed">
                                        <span class="flex items-center text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                            Waktu Boarding Terlalu Dekat
                                        </span>
                                    </template>
                                </span>
                                <div x-show="!isTimeCritical && !isTimePassed" class="shine-effect"></div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Mobile Bottom Bar (Only on Small Screens < lg) -->
        <div x-show="itemsCount > 0" class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-lg border-t border-slate-200 p-4 pb-safe z-50 shadow-xl">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Bayar</p>
                    <p class="text-lg font-black text-[#005ea2] leading-tight">Rp <span x-text="formatNumber(grandTotal)">{{ number_format($total, 0, ',', '.') }}</span></p>
                </div>
                <button type="submit" 
                        form="checkoutForm" 
                        :disabled="isTimeCritical || isTimePassed" 
                        class="flex-1 text-white font-black py-3.5 px-4 rounded-2xl text-xs uppercase tracking-wider transition-all flex items-center justify-center space-x-1.5"
                        :class="(isTimeCritical || isTimePassed) 
                            ? 'bg-rose-600/80 cursor-not-allowed opacity-75 shadow-none' 
                            : 'bg-gradient-to-r from-[#003b66] to-[#005ea2] shadow-md shadow-blue-500/20 active:scale-[0.98] cursor-pointer'"
                        :style="(!isTimeCritical && !isTimePassed) ? 'background: linear-gradient(to right, #003b66, #005ea2); background-color: #005ea2;' : 'background-color: #e11d48;'">
                    <template x-if="!isTimeCritical && !isTimePassed">
                        <span class="flex items-center space-x-1.5">
                            <span>Lanjut Bayar</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </span>
                    </template>
                    <template x-if="isTimePassed">
                        <span class="flex items-center space-x-1 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="truncate">Waktu Sudah Terlewat</span>
                        </span>
                    </template>
                    <template x-if="isTimeCritical && !isTimePassed">
                        <span class="flex items-center space-x-1 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <span class="truncate">Waktu Boarding Terlalu Dekat</span>
                        </span>
                    </template>
                </button>
            </div>
        </div>

        <!-- Dynamic Empty State (When all items removed via JS in active session) -->
        <div x-show="itemsCount === 0" x-cloak class="max-w-3xl mx-auto bg-white rounded-3xl p-5 sm:p-10 md:p-14 text-center border border-slate-200/80 shadow-sm animate-fade-up">
            <div class="relative w-28 h-28 sm:w-36 sm:h-36 mx-auto mb-5 sm:mb-6 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-blue-100/80 via-blue-50 to-indigo-50 border border-blue-200/50 shadow-inner"></div>
                <div class="relative z-10 w-16 h-16 sm:w-20 sm:h-20 text-[#005ea2]/80 flex items-center justify-center">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </div>
            </div>

            <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-blue-50 text-[#005ea2] text-xs font-bold mb-3 border border-blue-100">
                <span>✈️ Bandara Internasional Juanda</span>
            </div>

            <h2 class="text-xl sm:text-3xl font-black text-slate-900 mb-2">Keranjang Belanja Anda Kosong</h2>
            <p class="text-slate-500 font-medium text-xs sm:text-base max-w-lg mx-auto mb-6 sm:mb-7 leading-relaxed">
                Belum ada makanan yang dipilih. Jelajahi menu lezat dari 76 tenant restoran favorit di Terminal 1 & 2 Juanda tanpa antre!
            </p>

            <a href="{{ route('customer.menu') }}" class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-[#003b66] to-[#005ea2] hover:from-[#002f54] hover:to-[#004a80] text-white font-extrabold px-8 py-3.5 sm:px-9 sm:py-4 rounded-2xl sm:rounded-full text-sm sm:text-base shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 hover:-translate-y-0.5 active:translate-y-0 transition-all" style="background: linear-gradient(to right, #003b66, #005ea2); background-color: #005ea2;">
                <span>Jelajahi Menu Restoran</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>

            <!-- Quick Category Discovery Pills (Symmetrical 2x2 Grid on Mobile, Flow on Desktop) -->
            <div class="mt-8 pt-6 border-t border-slate-100">
                <p class="text-[11px] sm:text-xs font-black text-slate-400 uppercase tracking-widest mb-3.5">Pilihan Kategori Favorit</p>
                <div class="grid grid-cols-2 sm:flex sm:flex-wrap sm:justify-center gap-2 sm:gap-2.5 max-w-sm sm:max-w-none mx-auto">
                    <a href="{{ route('customer.menu', ['category' => 'cepat-saji']) }}" class="px-2.5 py-2.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-full bg-slate-50 hover:bg-blue-50 hover:text-[#005ea2] hover:border-blue-200 border border-slate-200/90 text-[11px] sm:text-xs font-bold text-slate-700 transition-all text-center flex items-center justify-center gap-1.5 shadow-2xs hover:shadow-xs active:scale-[0.98]">
                        <span class="text-sm">🍔</span>
                        <span class="truncate">Cepat Saji</span>
                    </a>
                    <a href="{{ route('customer.menu', ['category' => 'minuman']) }}" class="px-2.5 py-2.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-full bg-slate-50 hover:bg-blue-50 hover:text-[#005ea2] hover:border-blue-200 border border-slate-200/90 text-[11px] sm:text-xs font-bold text-slate-700 transition-all text-center flex items-center justify-center gap-1.5 shadow-2xs hover:shadow-xs active:scale-[0.98]">
                        <span class="text-sm">☕</span>
                        <span class="truncate">Kopi & Minuman</span>
                    </a>
                    <a href="{{ route('customer.menu', ['category' => 'roti-kue']) }}" class="px-2.5 py-2.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-full bg-slate-50 hover:bg-blue-50 hover:text-[#005ea2] hover:border-blue-200 border border-slate-200/90 text-[11px] sm:text-xs font-bold text-slate-700 transition-all text-center flex items-center justify-center gap-1.5 shadow-2xs hover:shadow-xs active:scale-[0.98]">
                        <span class="text-sm">🥐</span>
                        <span class="truncate">Roti & Pastry</span>
                    </a>
                    <a href="{{ route('customer.menu', ['category' => 'makanan-berat']) }}" class="px-2.5 py-2.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-full bg-slate-50 hover:bg-blue-50 hover:text-[#005ea2] hover:border-blue-200 border border-slate-200/90 text-[11px] sm:text-xs font-bold text-slate-700 transition-all text-center flex items-center justify-center gap-1.5 shadow-2xs hover:shadow-xs active:scale-[0.98]">
                        <span class="text-sm">🍲</span>
                        <span class="truncate">Tradisional</span>
                    </a>
                </div>
            </div>
        </div>

        @else
        <!-- Server-rendered Empty State (Wide & Beautiful on Desktop) -->
        <div class="max-w-3xl mx-auto bg-white rounded-3xl p-5 sm:p-10 md:p-14 text-center border border-slate-200/80 shadow-sm animate-fade-up">
            <div class="relative w-28 h-28 sm:w-36 sm:h-36 mx-auto mb-5 sm:mb-6 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-blue-100/80 via-blue-50 to-indigo-50 border border-blue-200/50 shadow-inner"></div>
                <div class="relative z-10 w-16 h-16 sm:w-20 sm:h-20 text-[#005ea2]/80 flex items-center justify-center">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </div>
            </div>

            <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-blue-50 text-[#005ea2] text-xs font-bold mb-3 border border-blue-100">
                <span>✈️ Bandara Internasional Juanda</span>
            </div>

            <h2 class="text-xl sm:text-3xl font-black text-slate-900 mb-2">Keranjang Belanja Anda Masih Kosong</h2>
            <p class="text-slate-500 font-medium text-xs sm:text-base max-w-lg mx-auto mb-6 sm:mb-7 leading-relaxed">
                Belum ada makanan yang dipilih. Nikmati kemudahan memesan dari 76 tenant restoran favorit di Terminal 1 & 2 Juanda tanpa antre!
            </p>

            <a href="{{ route('customer.menu') }}" class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-[#003b66] to-[#005ea2] hover:from-[#002f54] hover:to-[#004a80] text-white font-extrabold px-8 py-3.5 sm:px-9 sm:py-4 rounded-2xl sm:rounded-full text-sm sm:text-base shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 hover:-translate-y-0.5 active:translate-y-0 transition-all" style="background: linear-gradient(to right, #003b66, #005ea2); background-color: #005ea2;">
                <span>Jelajahi Menu Restoran</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>

            <!-- Quick Category Discovery Pills (Symmetrical 2x2 Grid on Mobile, Flow on Desktop) -->
            <div class="mt-8 pt-6 border-t border-slate-100">
                <p class="text-[11px] sm:text-xs font-black text-slate-400 uppercase tracking-widest mb-3.5">Pilihan Kategori Favorit</p>
                <div class="grid grid-cols-2 sm:flex sm:flex-wrap sm:justify-center gap-2 sm:gap-2.5 max-w-sm sm:max-w-none mx-auto">
                    <a href="{{ route('customer.menu', ['category' => 'cepat-saji']) }}" class="px-2.5 py-2.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-full bg-slate-50 hover:bg-blue-50 hover:text-[#005ea2] hover:border-blue-200 border border-slate-200/90 text-[11px] sm:text-xs font-bold text-slate-700 transition-all text-center flex items-center justify-center gap-1.5 shadow-2xs hover:shadow-xs active:scale-[0.98]">
                        <span class="text-sm">🍔</span>
                        <span class="truncate">Cepat Saji</span>
                    </a>
                    <a href="{{ route('customer.menu', ['category' => 'minuman']) }}" class="px-2.5 py-2.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-full bg-slate-50 hover:bg-blue-50 hover:text-[#005ea2] hover:border-blue-200 border border-slate-200/90 text-[11px] sm:text-xs font-bold text-slate-700 transition-all text-center flex items-center justify-center gap-1.5 shadow-2xs hover:shadow-xs active:scale-[0.98]">
                        <span class="text-sm">☕</span>
                        <span class="truncate">Kopi & Minuman</span>
                    </a>
                    <a href="{{ route('customer.menu', ['category' => 'roti-kue']) }}" class="px-2.5 py-2.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-full bg-slate-50 hover:bg-blue-50 hover:text-[#005ea2] hover:border-blue-200 border border-slate-200/90 text-[11px] sm:text-xs font-bold text-slate-700 transition-all text-center flex items-center justify-center gap-1.5 shadow-2xs hover:shadow-xs active:scale-[0.98]">
                        <span class="text-sm">🥐</span>
                        <span class="truncate">Roti & Pastry</span>
                    </a>
                    <a href="{{ route('customer.menu', ['category' => 'makanan-berat']) }}" class="px-2.5 py-2.5 sm:px-4 sm:py-2 rounded-xl sm:rounded-full bg-slate-50 hover:bg-blue-50 hover:text-[#005ea2] hover:border-blue-200 border border-slate-200/90 text-[11px] sm:text-xs font-bold text-slate-700 transition-all text-center flex items-center justify-center gap-1.5 shadow-2xs hover:shadow-xs active:scale-[0.98]">
                        <span class="text-sm">🍲</span>
                        <span class="truncate">Tradisional</span>
                    </a>
                </div>
            </div>

            <!-- 3 Highlights Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5 sm:gap-4 mt-8 pt-6 border-t border-slate-100 text-left">
                <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                    <span class="text-xl mb-1 block">⚡</span>
                    <h4 class="text-xs font-black text-slate-800">Pesan Cepat Tanpa Antre</h4>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Pesan langsung dari HP atau laptop saat menunggu jadwal boarding.</p>
                </div>
                <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                    <span class="text-xl mb-1 block">🛵</span>
                    <h4 class="text-xs font-black text-slate-800">Layanan Antar ke Gate</h4>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Porter resmi bandara siap mengantarkan makanan ke ruang tunggu Anda.</p>
                </div>
                <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                    <span class="text-xl mb-1 block">💳</span>
                    <h4 class="text-xs font-black text-slate-800">QRIS & Transfer Instan</h4>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Mendukung semua bank dan e-wallet dengan struk digital otomatis.</p>
                </div>
            </div>
        </div>
        @endif

    </main>

    <!-- Official FlyDine Corporate Footer -->
    <x-footer />

    <!-- Alpine.js Cart Logic Script -->
    <script nonce="{{ $cspNonce ?? '' }}">
        function cartManager() {
            return {
                items: @json((object)$cart),
                customerType: '{{ old('customer_type', 'penumpang') }}', 
                boardingTime: '{{ old('boarding_time', '') }}',
                pickupMethod: '{{ old('pickup_method', 'ambil_sendiri') }}',
                deliveryTerminal: '{{ old('delivery_terminal', str_ireplace('t', '', (string)($tenant->terminal ?? '1'))) }}',
                deliveryLocations: [],
                deliveryFee: {{ ($tenant && !is_null($tenant->delivery_fee)) ? $tenant->delivery_fee : 'null' }},
                paymentCategory: '{{ old('payment_method') ? (str_starts_with(old('payment_method'), 'transfer') ? 'transfer' : 'qris') : 'qris' }}',
                selectedBank: '{{ old('payment_method') && str_starts_with(old('payment_method'), 'transfer_') ? str_replace('transfer_', '', old('payment_method')) : 'bca' }}',
                orderNotes: '{{ old('notes', '') }}',
                quickChips: [
                    { icon: '🧊', label: 'Kurangi Es' },
                    { icon: '🌶️', label: 'Sambal Dipisah' },
                    { icon: '🍴', label: 'Tanpa Sendok Plastik' },
                    { icon: '🛍️', label: 'Bungkus Kabin' }
                ],
                loadingItemId: null,
                isClearing: false,
                errorMessage: '',

                toggleChip(chipLabel) {
                    let current = (this.orderNotes || '').trim();
                    if (current.includes(chipLabel)) {
                        current = current.replace(chipLabel, '')
                            .replace(/,\s*,/g, ',')
                            .replace(/^,\s*|,\s*$/g, '')
                            .trim();
                    } else {
                        let toAppend = current.length > 0 ? `, ${chipLabel}` : chipLabel;
                        if ((current.length + toAppend.length) <= 100) {
                            current = current ? `${current}, ${chipLabel}` : chipLabel;
                        }
                    }
                    this.orderNotes = current.slice(0, 100);
                },

                init() {
                    this.fetchLocations();
                },

                get itemsCount() {
                    return Object.keys(this.items).length;
                },
                get baseTotal() {
                    return Object.values(this.items).reduce((sum, item) => sum + (Number(item.price) * Number(item.quantity)), 0);
                },
                get grandTotal() {
                    return (this.pickupMethod === 'diantar' && this.deliveryFee !== null) 
                        ? this.baseTotal + this.deliveryFee 
                        : this.baseTotal;
                },
                formatNumber(num) {
                    return Number(num).toLocaleString('id-ID');
                },
                formatTimeDisplay(timeStr) {
                    if (!timeStr) return '';
                    const parts = timeStr.split(':');
                    if (parts.length < 2) return timeStr;
                    const h = parseInt(parts[0], 10);
                    const m = parts[1];
                    if (isNaN(h)) return timeStr;
                    const period = h >= 12 ? 'PM' : 'AM';
                    const h12 = h % 12 === 0 ? 12 : h % 12;
                    let desc = '';
                    if (h >= 0 && h < 5) desc = 'Dini Hari';
                    else if (h >= 5 && h < 11) desc = 'Pagi';
                    else if (h >= 11 && h < 15) desc = 'Siang';
                    else if (h >= 15 && h < 18) desc = 'Sore';
                    else desc = 'Malam';
                    return `${timeStr} WIB (${h12}:${m} ${period} • ${desc})`;
                },
                fetchLocations() {
                    if (!this.deliveryTerminal) {
                        this.deliveryLocations = [];
                        return;
                    }
                    fetch(`/api/delivery-locations?terminal=${this.deliveryTerminal}`)
                        .then(res => res.json())
                        .then(data => {
                            this.deliveryLocations = data;
                        });
                },
                get boardingDiff() {
                    if (this.customerType !== 'penumpang' || !this.boardingTime) return null;
                    const now = new Date();
                    const [hours, minutes] = this.boardingTime.split(':');
                    const boardTime = new Date();
                    boardTime.setHours(parseInt(hours, 10), parseInt(minutes, 10), 0, 0);

                    let diffInMinutes = (boardTime - now) / 60000;
                    let isCrossMidnight = false;

                    // Skenario Cross-Midnight:
                    // Jika selisih hari ini bernilai negatif, namun jika dimajukan 1 hari (besok)
                    // berada dalam rentang wajar penyiapan makanan (antara 0 hingga 8 jam / 480 menit ke depan)
                    if (diffInMinutes < 0 && (diffInMinutes + 1440) >= 0 && (diffInMinutes + 1440) <= 480) {
                        diffInMinutes += 1440;
                        isCrossMidnight = true;
                    }

                    return {
                        diff: diffInMinutes,
                        isCrossMidnight: isCrossMidnight,
                        isPassed: diffInMinutes < 0,
                        isCritical: diffInMinutes >= 0 && diffInMinutes < 15,
                        isWarning: diffInMinutes >= 15 && diffInMinutes < 30
                    };
                },
                get isTimeCritical() {
                    return this.boardingDiff ? (this.boardingDiff.isCritical || this.boardingDiff.isPassed) : false;
                },
                get isTimeWarning() {
                    return this.boardingDiff ? this.boardingDiff.isWarning : false;
                },
                get isTimePassed() {
                    return this.boardingDiff ? this.boardingDiff.isPassed : false;
                },
                get isCrossMidnight() {
                    return this.boardingDiff ? this.boardingDiff.isCrossMidnight : false;
                },
                updateQuantity(productId, action, newQty = null, inputElement = null) {
                    this.loadingItemId = productId;
                    this.errorMessage = '';

                    const payload = {
                        product_id: productId,
                        action: action
                    };
                    if (action === 'set' && newQty !== null) {
                        payload.quantity = parseInt(newQty, 10);
                    }

                    fetch('{{ route('customer.cart.update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(async res => {
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok) {
                            if (inputElement && this.items[productId]) {
                                inputElement.value = this.items[productId].quantity;
                            }
                            throw new Error(data.message || 'Gagal memperbarui item.');
                        }
                        return data;
                    })
                    .then(data => {
                        if (data.success) {
                            if (data.jumlah <= 0 || !data.item) {
                                const newItems = { ...this.items };
                                delete newItems[productId];
                                this.items = newItems;
                            } else {
                                this.items = {
                                    ...this.items,
                                    [productId]: data.item
                                };
                            }
                        } else {
                            this.errorMessage = data.message || 'Gagal memperbarui item keranjang.';
                            setTimeout(() => { this.errorMessage = ''; }, 4000);
                            if (inputElement && this.items[productId]) {
                                inputElement.value = this.items[productId].quantity;
                            }
                        }
                    })
                    .catch(err => {
                        this.errorMessage = err.message || 'Terjadi kesalahan pada sistem. Silakan coba lagi.';
                        setTimeout(() => { this.errorMessage = ''; }, 4000);
                        if (inputElement && this.items[productId]) {
                            inputElement.value = this.items[productId].quantity;
                        }
                    })
                    .finally(() => {
                        this.loadingItemId = null;
                    });
                },
                clearCart() {
                    if (!confirm('Kosongkan keranjang?')) return;
                    this.isClearing = true;
                    this.errorMessage = '';

                    fetch('{{ route('customer.cart.clear') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(async res => {
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok) {
                            throw new Error(data.message || 'Gagal mengosongkan keranjang.');
                        }
                        return data;
                    })
                    .then(data => {
                        if (data.success) {
                            this.items = {};
                        } else {
                            this.errorMessage = data.message || 'Gagal mengosongkan keranjang.';
                            setTimeout(() => { this.errorMessage = ''; }, 4000);
                        }
                    })
                    .catch(err => {
                        this.errorMessage = err.message || 'Terjadi kesalahan saat mengosongkan keranjang.';
                        setTimeout(() => { this.errorMessage = ''; }, 4000);
                    })
                    .finally(() => {
                        this.isClearing = false;
                    });
                }
            };
        }
    </script>
</body>
</html>