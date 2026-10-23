@extends('layouts.tenant')

@section('title', 'Katalog Menu & Produk')

@php
    $tenantName = auth()->user()?->tenant?->name ?? 'Gerai Tenant';
    $tenantCategory = auth()->user()?->tenant?->category ?? 'Kuliner Bandara';
    
    // Siapkan koleksi metadata produk untuk sinkronisasi Alpine.js
    $productsData = $products->map(function($product) use ($tenantCategory) {
        $imgUrl = null;
        if (!empty($product->image)) {
            if (\Illuminate\Support\Str::startsWith($product->image, ['http://', 'https://'])) {
                $imgUrl = $product->image;
            } elseif (\Illuminate\Support\Str::startsWith($product->image, 'images/')) {
                if (file_exists(public_path($product->image))) {
                    $imgUrl = asset($product->image);
                }
            } else {
                if (file_exists(public_path('storage/' . $product->image)) || file_exists(storage_path('app/public/' . $product->image))) {
                    $imgUrl = asset('storage/' . $product->image);
                }
            }
        }

        // Tentukan kategori default jika masih kosong di database
        $computedCat = $product->category;
        if (empty($computedCat)) {
            $lowerName = strtolower($product->name);
            if (\Illuminate\Support\Str::contains($lowerName, ['kopi', 'coffee', 'latte', 'tea', 'teh', 'milo', 'juice', 'jus', 'drink', 'beverage', 'air', 'mineral'])) {
                $computedCat = 'Minuman';
            } elseif (\Illuminate\Support\Str::contains($lowerName, ['toast', 'roti', 'bread', 'croissant', 'donut', 'cookie', 'cake', 'sandwich', 'pastry', 'snack', 'fries'])) {
                $computedCat = 'Snack';
            } elseif (\Illuminate\Support\Str::contains($lowerName, ['dessert', 'ice cream', 'pudding', 'es'])) {
                $computedCat = 'Dessert';
            } else {
                $computedCat = 'Makanan Utama';
            }
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'category' => $computedCat,
            'original_category' => $product->category ?? '',
            'price' => (float) $product->price,
            'formatted_price' => number_format($product->price, 0, ',', '.'),
            'stock' => (int) $product->stock,
            'is_available' => (bool) $product->is_available,
            'description' => $product->description ?? '',
            'note' => $product->note ?? '',
            'image' => $imgUrl,
            'has_image' => !empty($imgUrl),
        ];
    });

    $totalProducts = $products->count();
    $activeProducts = $products->where('is_available', true)->count();
    $emptyStockProducts = $products->where('stock', '<=', 0)->count();
    $categoriesList = $productsData->pluck('category')->unique()->values()->all();
@endphp

<div x-data="productCatalog()" class="space-y-6">

    <!-- ========================================================================= -->
    <!-- 1. HEADER HALAMAN & TOMBOL TAMBAH MENU                                    -->
    <!-- ========================================================================= -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Katalog Menu & Produk</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-[#005ea2] border border-blue-200/60">
                    {{ $tenantName }}
                </span>
            </div>
            <p class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">
                Kelola hidangan, sesuaikan harga jual, stok harian, dan pantau ketersediaan menu gerai di bandara.
            </p>
        </div>
        
        <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
            <!-- Tombol Tambah Menu Baru -->
            <a href="{{ route('tenant.products.create') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-[#005ea2] hover:bg-[#004b82] active:scale-95 text-white text-xs sm:text-sm font-bold shadow-sm shadow-blue-600/20 transition-all cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Menu Baru</span>
            </a>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. RINGKASAN METRIK OPERASIONAL KATALOG (4 KARTU RESPONSIF)                -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        
        <!-- Kartu 1: Total Menu -->
        <div @click="filterStatus = 'all'; filterStock = 'all'" 
             class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-all cursor-pointer">
            <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Total Menu</span>
                <span class="w-2.5 h-2.5 rounded-full bg-[#005ea2]"></span>
            </div>
            <h3 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight leading-tight">
                {{ $totalProducts }}
            </h3>
            <span class="text-[10px] sm:text-xs font-semibold text-slate-500 block mt-1">Menu Terdaftar di Gerai</span>
        </div>

        <!-- Kartu 2: Menu Aktif Siap Jual -->
        <div @click="filterStatus = 'active'" 
             class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:border-emerald-300 transition-all cursor-pointer">
            <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Menu Aktif</span>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            </div>
            <h3 class="text-lg sm:text-2xl font-black text-emerald-600 tracking-tight leading-tight">
                {{ $activeProducts }}
            </h3>
            <span class="text-[10px] sm:text-xs font-semibold text-emerald-600 block mt-1">Tersedia untuk Pesanan</span>
        </div>

        <!-- Kartu 3: Stok Habis / Perlu Update -->
        <div @click="filterStock = 'empty'" 
             class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:border-rose-300 transition-all cursor-pointer">
            <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Stok Kosong / 0</span>
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
            </div>
            <h3 class="text-lg sm:text-2xl font-black text-rose-600 tracking-tight leading-tight">
                {{ $emptyStockProducts }}
            </h3>
            <span class="text-[10px] sm:text-xs font-semibold text-rose-500 block mt-1">Perlu Pembaruan Stok</span>
        </div>

        <!-- Kartu 4: Variasi Kategori -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
            <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400">Kategori Menu</span>
                <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
            </div>
            <h3 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight leading-tight">
                {{ count($categoriesList) }}
            </h3>
            <span class="text-[10px] sm:text-xs font-semibold text-purple-600 block mt-1">Variasi Sajian Gerai</span>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- 3. BAR FILTER REAL-TIME & PENCARIAN PRODUK                                -->
    <!-- ========================================================================= -->
    <div class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            
            <!-- Input Pencarian Teks -->
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" 
                       x-model="searchQuery" 
                       placeholder="Cari nama menu, kategori, harga..." 
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Filter Dropdowns Row -->
            <div class="flex flex-wrap items-center gap-2">
                
                <!-- Filter Kategori -->
                <div class="relative flex-1 sm:flex-none">
                    <select x-model="filterCategory" 
                            style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: none;"
                            class="w-full sm:w-auto pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition appearance-none cursor-pointer">
                        <option value="all">Semua Kategori</option>
                        @foreach($categoriesList as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <!-- Filter Status Ketersediaan -->
                <div class="relative flex-1 sm:flex-none">
                    <select x-model="filterStatus" 
                            style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: none;"
                            class="w-full sm:w-auto pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition appearance-none cursor-pointer">
                        <option value="all">Semua Status</option>
                        <option value="active">Aktif (Tersedia)</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <!-- Filter Stok -->
                <div class="relative flex-1 sm:flex-none">
                    <select x-model="filterStock" 
                            style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: none;"
                            class="w-full sm:w-auto pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition appearance-none cursor-pointer">
                        <option value="all">Semua Stok</option>
                        <option value="ready">Tersedia (>0)</option>
                        <option value="empty">Stok 0 / Habis</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <!-- Tombol Reset Filter -->
                <button type="button" 
                        x-show="searchQuery || filterCategory !== 'all' || filterStatus !== 'all' || filterStock !== 'all'" 
                        @click="resetFilters()" 
                        class="text-xs font-bold text-slate-500 hover:text-rose-600 px-3 py-2 transition-colors flex items-center gap-1 cursor-pointer">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Reset</span>
                </button>

            </div>

        </div>

        <!-- Info Hitungan Hasil Filter -->
        <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
            <span>
                Menampilkan <strong class="text-slate-800" x-text="filteredProducts.length"></strong> dari <strong class="text-[#005ea2]">{{ $totalProducts }}</strong> menu
            </span>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 4. GRID KATALOG MENU (RESPONSIF & AESTHETIC PREMIUM)                      -->
    <!-- ========================================================================= -->
    @if($totalProducts > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5"
             x-show="filteredProducts.length > 0">
            
            @foreach($products as $product)
                @php
                    $pData = $productsData->firstWhere('id', $product->id);
                    $productCategory = $pData['category'];
                    $hasImage = $pData['has_image'];
                    $imageUrl = $pData['image'];

                    // Deteksi jenis icon fallback kuliner
                    $lowerName = strtolower($product->name);
                    $isBeverage = \Illuminate\Support\Str::contains($lowerName, ['kopi', 'coffee', 'latte', 'tea', 'teh', 'milo', 'juice', 'jus', 'drink', 'beverage', 'air', 'mineral']) || $productCategory === 'Minuman';
                    $isBakery = \Illuminate\Support\Str::contains($lowerName, ['toast', 'roti', 'bread', 'croissant', 'donut', 'cookie', 'cake', 'sandwich', 'pastry', 'snack', 'fries']) || $productCategory === 'Snack';
                @endphp

                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col overflow-hidden group"
                     x-show="isProductVisible({{ $product->id }})"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">

                    <!-- Foto Produk / Fallback Kuliner Estetik -->
                    <div class="h-44 sm:h-48 w-full relative overflow-hidden bg-slate-50 border-b border-slate-100 flex items-center justify-center shrink-0">
                        @if($hasImage)
                            <img src="{{ $imageUrl }}" 
                                 alt="{{ $product->name }}" 
                                 loading="lazy" 
                                 onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            
                            <!-- Fallback jika link gambar rusak -->
                            <div class="hidden w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-blue-50/40 p-4 text-center">
                                <div class="w-12 h-12 rounded-2xl bg-white shadow-2xs border border-slate-200/60 flex items-center justify-center text-slate-400 mb-1.5">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Foto Produk</span>
                            </div>
                        @else
                            <!-- Tampilan Pengganti Foto (Rapi, Elegan, Bebas Huruf Raksasa) -->
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 via-slate-100/60 to-blue-50/30 p-4 text-center group-hover:bg-slate-100/80 transition-colors">
                                <div class="w-13 h-13 rounded-2xl bg-white shadow-2xs border border-slate-200/80 flex items-center justify-center text-[#005ea2] mb-2 group-hover:scale-110 transition-transform">
                                    @if($isBeverage)
                                        <!-- Ikon Minuman -->
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 9l-7 7-7-7m14-5v2a4 4 0 01-4 4H8a4 4 0 01-4-4V4m0 0h16" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 16v5m-4 0h8" />
                                        </svg>
                                    @elseif($isBakery)
                                        <!-- Ikon Roti / Snack -->
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 15.5a5 5 0 01-7 4.5 5 5 0 01-7-4.5V11a2 2 0 012-2h10a2 2 0 012 2v4.5z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 11V7a2 2 0 012-2h12a2 2 0 012 2v4" />
                                        </svg>
                                    @else
                                        <!-- Ikon Makanan Utama / Sajian Dapur -->
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 3v2m0 16a9 9 0 009-9H3a9 9 0 009 9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 10h10" />
                                        </svg>
                                    @endif
                                </div>
                                <span class="text-[11px] font-bold text-slate-500">Foto Belum Diunggah</span>
                                <span class="text-[10px] text-slate-400 font-semibold mt-0.5">Klik edit untuk pasang foto</span>
                            </div>
                        @endif

                        <!-- Badge Status Tayang di Pojok Kiri Atas -->
                        <div class="absolute top-3 left-3">
                            @if(!$product->is_available)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-600 text-white shadow-xs">
                                    <span>Nonaktif</span>
                                </span>
                            @elseif($product->stock <= 0)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-500 text-white shadow-xs">
                                    <span>Stok Habis</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500 text-white shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                    <span>Aktif</span>
                                </span>
                            @endif
                        </div>

                        <!-- Tombol Cepat Toggle Status di Pojok Kanan Atas -->
                        <div class="absolute top-3 right-3">
                            <form method="POST" action="{{ route('tenant.products.toggle', $product) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        title="{{ $product->is_available ? 'Klik untuk nonaktifkan menu ini' : 'Klik untuk mengaktifkan menu ini' }}"
                                        class="h-7 px-2.5 rounded-lg bg-white/95 hover:bg-white text-slate-700 shadow-xs backdrop-blur-xs text-[10px] font-bold flex items-center gap-1.5 transition-all active:scale-95 border border-slate-200 cursor-pointer">
                                    @if(!$product->is_available)
                                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                        <span class="text-slate-500">Off</span>
                                    @elseif($product->stock <= 0)
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        <span class="text-rose-600 font-bold">Habis</span>
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span class="text-emerald-700 font-bold">On</span>
                                    @endif
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Informasi Produk -->
                    <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between">
                        
                        <div>
                            <!-- Kategori & Stok Badge -->
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md truncate max-w-[140px]" 
                                      title="{{ $productCategory }}">
                                    {{ $productCategory }}
                                </span>
                                
                                @if($product->stock > 10)
                                    <span class="text-[10px] sm:text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/70 px-2 py-0.5 rounded-md shrink-0">
                                        Stok: {{ $product->stock }}
                                    </span>
                                @elseif($product->stock > 0)
                                    <span class="text-[10px] sm:text-[11px] font-bold text-amber-700 bg-amber-50 border border-amber-200/70 px-2 py-0.5 rounded-md shrink-0">
                                        Stok: {{ $product->stock }}
                                    </span>
                                @else
                                    <span class="text-[10px] sm:text-[11px] font-bold text-rose-700 bg-rose-50 border border-rose-200/70 px-2 py-0.5 rounded-md shrink-0">
                                        Stok: 0
                                    </span>
                                @endif
                            </div>

                            <!-- Nama Produk -->
                            <h3 class="font-black text-slate-900 text-sm sm:text-base leading-snug line-clamp-2 group-hover:text-[#005ea2] transition-colors" 
                                title="{{ $product->name }}">
                                {{ $product->name }}
                            </h3>

                            <!-- Harga -->
                            <div class="text-base sm:text-lg font-black text-[#005ea2] mt-1.5">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>

                            <!-- Deskripsi Singkat jika ada -->
                            @if($product->description)
                                <p class="text-xs text-slate-500 font-medium line-clamp-1 mt-1">
                                    {{ $product->description }}
                                </p>
                            @endif
                        </div>

                        <!-- Baris Aksi Kartu: Rapi, Modern, dan Sejajar -->
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-1.5">
                            
                            <!-- Tombol Edit Menu -->
                            <button type="button" 
                                    @click="openEditModal(@js($pData))" 
                                    class="flex-1 bg-slate-100 hover:bg-[#005ea2] hover:text-white text-slate-700 font-bold text-xs py-2 px-3 rounded-xl transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-2xs">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span>Edit</span>
                            </button>

                            <!-- Tombol Lihat Detail -->
                            <button type="button" 
                                    @click="openDetailModal(@js($pData))" 
                                    class="p-2 rounded-xl bg-blue-50/80 hover:bg-blue-100 text-[#005ea2] transition-colors flex items-center justify-center cursor-pointer shadow-2xs" 
                                    title="Lihat detail menu">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            <!-- Tombol Hapus Menu -->
                            <form method="POST" 
                                  action="{{ route('tenant.products.destroy', $product) }}" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu \'{{ addslashes($product->name) }}\'? Menu yang dihapus tidak dapat dipulihkan.')" 
                                  class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 transition-colors flex items-center justify-center cursor-pointer shadow-2xs" 
                                        title="Hapus menu dari katalog">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

        <!-- State Kosong Jika Filter Tidak Menemukan Hasil -->
        <div x-show="filteredProducts.length === 0" 
             x-cloak 
             class="bg-white rounded-2xl border border-dashed border-slate-300 text-center py-16 px-4 shadow-2xs">
            <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3 text-slate-400">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h4 class="text-base font-black text-slate-900">Menu Tidak Ditemukan</h4>
            <p class="text-xs text-slate-500 font-semibold mt-1 max-w-sm mx-auto">
                Tidak ada menu yang cocok dengan kata kunci atau filter yang Anda pilih.
            </p>
            <button type="button" 
                    @click="resetFilters()" 
                    class="mt-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition cursor-pointer">
                Reset Semua Filter
            </button>
        </div>

    @else
        <!-- State Kosong: Belum Ada Produk Sama Sekali -->
        <div class="bg-white rounded-2xl border border-dashed border-slate-300 text-center py-20 px-4 shadow-2xs">
            <div class="w-16 h-16 bg-blue-50 text-[#005ea2] rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <h3 class="text-lg font-black text-slate-900">Katalog Menu Masih Kosong</h3>
            <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-1 max-w-md mx-auto">
                Tambahkan hidangan andalan gerai Anda sekarang agar penumpang di bandara dapat mulai memesan secara online.
            </p>
            <a href="{{ route('tenant.products.create') }}" 
               class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#005ea2] hover:bg-[#004b82] text-white text-xs sm:text-sm font-bold shadow-md shadow-blue-600/20 transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Menu Pertama</span>
            </a>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- 5. MODAL DETAIL PRODUK (TELEPORT KE BODY)                                 -->
    <!-- ========================================================================= -->
    <template x-teleport="body">
        <div x-show="showDetailModal" 
             x-cloak 
             class="fixed inset-0 z-[100] overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
             @keydown.escape.window="showDetailModal = false">
            
            <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 relative overflow-hidden flex flex-col max-h-[90vh]"
                 @click.away="showDetailModal = false">
                
                <!-- Foto Banner / Fallback di Modal -->
                <div class="h-52 bg-slate-100 relative flex items-center justify-center overflow-hidden shrink-0 border-b border-slate-100">
                    <template x-if="activeProduct && activeProduct.image">
                        <img :src="activeProduct.image" :alt="activeProduct.name" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!activeProduct || !activeProduct.image">
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-blue-50/50 p-4">
                            <div class="w-14 h-14 rounded-2xl bg-white shadow-2xs border border-slate-200 flex items-center justify-center text-[#005ea2] mb-1.5">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 3v2m0 16a9 9 0 009-9H3a9 9 0 009 9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 10h10" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-slate-500">Foto Belum Diunggah</span>
                        </div>
                    </template>
                    
                    <!-- Tombol Tutup Silang di Sudut Atas -->
                    <button type="button" 
                            @click="showDetailModal = false" 
                            class="absolute top-3 right-3 w-8 h-8 rounded-full bg-slate-900/40 hover:bg-slate-900/60 backdrop-blur-xs text-white flex items-center justify-center transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Konten Rincian Menu -->
                <div class="p-5 sm:p-6 overflow-y-auto space-y-4">
                    
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-md" 
                                  x-text="activeProduct ? activeProduct.category : ''"></span>
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full"
                                  :class="activeProduct && activeProduct.is_available ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200'"
                                  x-text="activeProduct && activeProduct.is_available ? 'Aktif (Siap Dipesan)' : 'Nonaktif'"></span>
                        </div>
                        
                        <h3 class="text-xl font-black text-slate-900 mt-2 leading-snug" 
                            x-text="activeProduct ? activeProduct.name : ''"></h3>
                        
                        <div class="text-2xl font-black text-[#005ea2] mt-1" 
                             x-text="activeProduct ? 'Rp ' + activeProduct.formatted_price : ''"></div>
                    </div>

                    <!-- Kotak Parameter Operasional -->
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80 space-y-2.5 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-500 uppercase tracking-wider">Jumlah Stok:</span>
                            <span class="font-black text-slate-800" x-text="activeProduct ? activeProduct.stock + ' porsi' : ''"></span>
                        </div>
                        <div class="flex items-center justify-between border-t border-slate-200/60 pt-2">
                            <span class="font-bold text-slate-500 uppercase tracking-wider">Estimasi SLA Dapur:</span>
                            <span class="font-black text-emerald-600">≤ 15 Menit</span>
                        </div>
                    </div>

                    <!-- Deskripsi Produk -->
                    <div>
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Deskripsi Hidangan:</h4>
                        <p class="text-xs sm:text-sm text-slate-700 font-medium leading-relaxed bg-white p-3 rounded-xl border border-slate-100" 
                           x-text="activeProduct && activeProduct.description ? activeProduct.description : 'Tidak ada deskripsi tambahan.'"></p>
                    </div>

                    <!-- Catatan Dapur -->
                    <div>
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Catatan Khusus Dapur:</h4>
                        <p class="text-xs sm:text-sm text-slate-700 font-medium italic bg-amber-50/50 p-3 rounded-xl border border-amber-200/50 text-amber-900" 
                           x-text="activeProduct && activeProduct.note ? activeProduct.note : 'Tidak ada catatan dapur khusus.'"></p>
                    </div>

                    <!-- Tombol Aksi Modal -->
                    <div class="pt-2 flex items-center gap-2.5">
                        <button type="button" 
                                @click="showDetailModal = false; openEditModal(activeProduct)" 
                                class="flex-1 py-2.5 px-4 rounded-xl bg-[#005ea2] hover:bg-[#004b82] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Edit Menu Ini</span>
                        </button>
                        <button type="button" 
                                @click="showDetailModal = false" 
                                class="py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">
                            Tutup
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </template>

    <!-- ========================================================================= -->
    <!-- 6. MODAL EDIT PRODUK (TELEPORT KE BODY)                                   -->
    <!-- ========================================================================= -->
    <template x-teleport="body">
        <div x-show="showEditModal" 
             x-cloak 
             class="fixed inset-0 z-[100] overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
             @keydown.escape.window="showEditModal = false">
            
            <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 relative flex flex-col max-h-[92vh] overflow-hidden"
                 @click.away="showEditModal = false">
                
                <!-- Header Modal Edit -->
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/60 shrink-0">
                    <div>
                        <h3 class="text-base font-black text-slate-900">Edit Informasi Menu</h3>
                        <p class="text-xs text-slate-500 font-semibold mt-0.5">Perbarui harga, ketersediaan, stok, atau foto produk.</p>
                    </div>
                    <button type="button" 
                            @click="showEditModal = false" 
                            class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form Edit Produk -->
                <form :action="editActionUrl" 
                      method="POST" 
                      enctype="multipart/form-data" 
                      class="overflow-y-auto p-5 sm:p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Nama Menu -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                            Nama Menu <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               x-model="editForm.name" 
                               required 
                               maxlength="120" 
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition">
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                            Kategori Menu
                        </label>
                        <select name="category" 
                                x-model="editForm.category" 
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition cursor-pointer">
                            <option value="">Pilih Kategori Menu...</option>
                            <option value="Makanan Utama">Makanan Utama</option>
                            <option value="Snack">Snack</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Dessert">Dessert</option>
                            @foreach($categoriesList as $c)
                                @if(!in_array($c, ['Makanan Utama', 'Snack', 'Minuman', 'Dessert']))
                                    <option value="{{ $c }}">{{ $c }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Grid Harga & Stok -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                Harga Jual (Rp) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   name="price" 
                                   x-model="editForm.price" 
                                   required 
                                   min="0" 
                                   step="any" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                Jumlah Stok (Porsi) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   name="stock" 
                                   x-model="editForm.stock" 
                                   required 
                                   min="0" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition">
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                            Deskripsi Menu (Opsional)
                        </label>
                        <textarea name="description" 
                                  x-model="editForm.description" 
                                  rows="2" 
                                  maxlength="300" 
                                  placeholder="Jelaskan komposisi atau rasa hidangan ini..." 
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition"></textarea>
                    </div>

                    <!-- Catatan Dapur -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                            Catatan Khusus Dapur (Opsional)
                        </label>
                        <textarea name="note" 
                                  x-model="editForm.note" 
                                  rows="2" 
                                  maxlength="1000" 
                                  placeholder="Petunjuk khusus koki, misal: bumbu terpisah, alergi, dsb..." 
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition"></textarea>
                    </div>

                    <!-- Foto Produk -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                            Foto Menu Produk (Opsional)
                        </label>
                        
                        <div class="flex items-center gap-3 mb-2.5">
                            <!-- Preview Box -->
                            <div class="w-16 h-16 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden shrink-0">
                                <template x-if="editPreviewUrl">
                                    <img :src="editPreviewUrl" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!editPreviewUrl">
                                    <span class="text-[10px] text-slate-400 font-bold text-center px-1">Tanpa Foto</span>
                                </template>
                            </div>
                            <div class="text-xs text-slate-500">
                                <p class="font-bold text-slate-700" x-text="editPreviewUrl ? 'Foto siap digunakan' : 'Pilih file gambar untuk menambah foto'"></p>
                                <p class="text-[11px] text-slate-400">Format: JPG, PNG, WEBP (Maksimal 5MB)</p>
                            </div>
                        </div>

                        <input type="file" 
                               name="image" 
                               accept="image/png,image/jpeg,image/jpg,image/webp" 
                               @change="handleImageChange($event)" 
                               class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#005ea2] hover:file:bg-blue-100 transition cursor-pointer">
                    </div>

                    <!-- Status Ketersediaan Checkbox -->
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/80">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" 
                                   name="is_available" 
                                   value="1" 
                                   x-model="editForm.is_available" 
                                   class="w-4 h-4 text-[#005ea2] bg-white border-slate-300 rounded focus:ring-[#005ea2] focus:ring-2 transition cursor-pointer">
                            <div>
                                <span class="text-xs sm:text-sm font-bold text-slate-800 block">Tampilkan menu ini di katalog penumpang</span>
                                <span class="text-[11px] text-slate-400 font-medium">Bila dicentang, menu akan langsung dapat dipesan oleh penumpang.</span>
                            </div>
                        </label>
                    </div>

                    <!-- Tombol Sticky Footer -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                        <button type="button" 
                                @click="showEditModal = false" 
                                class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-xl bg-[#005ea2] hover:bg-[#004b82] text-white text-xs font-bold shadow-md shadow-blue-600/20 transition-all cursor-pointer">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </template>

</div>

<script nonce="{{ $cspNonce ?? '' }}">
function productCatalog() {
    return {
        products: @js($productsData),
        searchQuery: '',
        filterCategory: 'all',
        filterStatus: 'all',
        filterStock: 'all',
        showDetailModal: false,
        showEditModal: false,
        activeProduct: null,
        editActionUrl: '',
        editPreviewUrl: null,
        editForm: {
            id: null,
            name: '',
            category: '',
            price: 0,
            stock: 0,
            description: '',
            note: '',
            is_available: true
        },

        get filteredProducts() {
            return this.products.filter(p => {
                // Filter Pencarian Teks
                if (this.searchQuery) {
                    const q = this.searchQuery.toLowerCase();
                    const matchName = p.name.toLowerCase().includes(q);
                    const matchCat = (p.category || '').toLowerCase().includes(q);
                    const matchPrice = p.price.toString().includes(q) || p.formatted_price.includes(q);
                    const matchDesc = (p.description || '').toLowerCase().includes(q);
                    if (!matchName && !matchCat && !matchPrice && !matchDesc) {
                        return false;
                    }
                }

                // Filter Kategori
                if (this.filterCategory !== 'all' && p.category !== this.filterCategory) {
                    return false;
                }

                // Filter Status Ketersediaan
                if (this.filterStatus === 'active' && !p.is_available) {
                    return false;
                }
                if (this.filterStatus === 'inactive' && p.is_available) {
                    return false;
                }

                // Filter Stok
                if (this.filterStock === 'ready' && p.stock <= 0) {
                    return false;
                }
                if (this.filterStock === 'empty' && p.stock > 0) {
                    return false;
                }

                return true;
            });
        },

        isProductVisible(id) {
            return this.filteredProducts.some(p => p.id === id);
        },

        resetFilters() {
            this.searchQuery = '';
            this.filterCategory = 'all';
            this.filterStatus = 'all';
            this.filterStock = 'all';
        },

        openDetailModal(product) {
            this.activeProduct = product;
            this.showDetailModal = true;
        },

        openEditModal(product) {
            this.editForm = {
                id: product.id,
                name: product.name,
                category: product.category,
                price: product.price,
                stock: product.stock,
                description: product.description,
                note: product.note,
                is_available: product.is_available
            };
            this.editActionUrl = '/tenant/products/' + product.id;
            this.editPreviewUrl = product.image;
            this.showEditModal = true;
        },

        handleImageChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.editPreviewUrl = URL.createObjectURL(file);
            }
        }
    };
}
</script>
@endsection