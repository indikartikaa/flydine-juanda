@extends('layouts.admin')

@section('title', 'Komplain Pelanggan')

@section('content')
<div x-data="{ 
    modalOpen: false, 
    selectedTicket: '',
    selectedCustomer: '',
    selectedContact: '',
    selectedWaLink: '',
    selectedTenant: '',
    selectedOrderCode: '',
    selectedCategory: '',
    selectedIssue: '',
    selectedStatus: 'open',
    formAction: ''
}" class="space-y-6 pb-12">

    <!-- Flash Notification -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-2xs">
        <div class="flex items-center gap-2.5">
            <span class="p-1.5 rounded-xl bg-emerald-500 text-white font-black text-xs">✓</span>
            <span class="font-bold text-xs sm:text-sm">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    @endif

    <!-- Header: Judul & Status Filter Tabs Cepat -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Manajemen Komplain Pelanggan</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Pantau, investigasi, dan tindak lanjuti kendala pesanan penumpang bandara secara cepat & tepat.</p>
        </div>
        
        <!-- Quick Status Filter Pills (Aktif & Interaktif) -->
        <div class="inline-flex items-center p-1.5 bg-white border border-slate-200/90 rounded-2xl shadow-2xs gap-1.5 overflow-x-auto">
            <!-- Semua Tiket -->
            <a href="{{ route('admin.complaints', array_filter(array_merge(request()->query(), ['status' => null, 'page' => null]))) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ !request('status') ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
                <span>Semua</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black {{ !request('status') ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $total_all }}</span>
            </a>

            <!-- Menunggu (Open) -->
            <a href="{{ route('admin.complaints', array_filter(array_merge(request()->query(), ['status' => 'open', 'page' => null]))) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('status') === 'open' ? 'bg-rose-600 text-white shadow-xs' : 'text-rose-700 hover:bg-rose-50' }}">
                <span class="w-2 h-2 rounded-full {{ request('status') === 'open' ? 'bg-white' : 'bg-rose-500 animate-pulse' }}"></span>
                <span>Menunggu</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black {{ request('status') === 'open' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-800' }}">{{ $total_open }}</span>
            </a>

            <!-- Diproses (In Progress) -->
            <a href="{{ route('admin.complaints', array_filter(array_merge(request()->query(), ['status' => 'in_progress', 'page' => null]))) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('status') === 'in_progress' ? 'bg-[#005ea2] text-white shadow-xs' : 'text-[#005ea2] hover:bg-blue-50' }}">
                <span class="w-2 h-2 rounded-full {{ request('status') === 'in_progress' ? 'bg-white' : 'bg-blue-500' }}"></span>
                <span>Diproses</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black {{ request('status') === 'in_progress' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-800' }}">{{ $total_in_progress }}</span>
            </a>

            <!-- Selesai (Resolved / Closed) -->
            <a href="{{ route('admin.complaints', array_filter(array_merge(request()->query(), ['status' => 'resolved', 'page' => null]))) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('status') === 'resolved' ? 'bg-emerald-600 text-white shadow-xs' : 'text-emerald-700 hover:bg-emerald-50' }}">
                <span class="w-2 h-2 rounded-full {{ request('status') === 'resolved' ? 'bg-white' : 'bg-emerald-500' }}"></span>
                <span>Selesai</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black {{ request('status') === 'resolved' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">{{ $total_resolved }}</span>
            </a>
        </div>
    </div>

    <!-- Modul Analitik Komplain & SLA Resolusi -->
    @php
        $totalCats = $complaintCategories->sum('total');
        $avgMins = $avgResolutionTime ? round($avgResolutionTime) : 0;
        
        $catStyle = function($cat) {
            if ($cat === 'pesanan_salah') return ['color' => 'rose', 'label' => 'Pesanan Salah', 'badge' => 'bg-rose-50 text-rose-700 border-rose-200', 'bar' => 'bg-rose-500', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'];
            if ($cat === 'status_tidak_update') return ['color' => 'amber', 'label' => 'Status Lambat', 'badge' => 'bg-amber-50 text-amber-700 border-amber-200', 'bar' => 'bg-amber-500', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'];
            return ['color' => 'slate', 'label' => 'Lainnya', 'badge' => 'bg-slate-100 text-slate-700 border-slate-200', 'bar' => 'bg-slate-500', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'];
        };
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        
        <!-- Kartu 1: Rata-rata Waktu Penyelesaian (SLA Resolution Time) -->
        <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200/90 relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">SLA Resolusi Komplain</span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black border {{ $avgMins > 60 ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">
                    {{ $avgMins > 60 ? 'Perlu Percepatan' : 'Sesuai Standar' }}
                </span>
            </div>
            
            <div class="my-auto py-2">
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black text-slate-900 tracking-tight">{{ $avgMins }}</span>
                    <span class="text-sm font-bold text-slate-500">Menit Rata-rata</span>
                </div>
                <div class="mt-3 flex items-center gap-2 text-xs">
                    <span class="w-2 h-2 rounded-full {{ $avgMins > 60 ? 'bg-rose-500 animate-pulse' : 'bg-emerald-500' }}"></span>
                    <span class="text-slate-600 font-medium">Target SOP Penanganan: <strong class="text-slate-800 font-bold">&le; 60 Menit</strong></span>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-400 flex items-center justify-between">
                <span>Dihitung sejak tiket dibuat hingga berstatus selesai</span>
            </div>
        </div>

        <!-- Kartu 2: Distribusi Kategori Akar Masalah (Root Cause Distribution) -->
        <div class="lg:col-span-2 bg-white p-5 rounded-2xl shadow-2xs border border-slate-200/90 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-[11px] font-black text-slate-500 uppercase tracking-wider">Akar Masalah Utama (Root Cause Distribution)</h3>
                <span class="text-xs font-bold text-slate-400">{{ $totalCats }} Total Kasus Terdata</span>
            </div>
            
            @if($totalCats > 0)
                <div class="space-y-3.5 my-auto">
                    @foreach($complaintCategories as $category)
                        @php
                            $style = $catStyle($category->category);
                            $pct = round(($category->total / $totalCats) * 100);
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                                <a href="{{ route('admin.complaints', array_filter(array_merge(request()->query(), ['category' => $category->category, 'page' => null]))) }}" 
                                   class="text-slate-700 hover:text-[#005ea2] flex items-center gap-1.5 transition-colors group"
                                   title="Filter berdasarkan kategori {{ $style['label'] }}">
                                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#005ea2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $style['icon'] }}"></path>
                                    </svg>
                                    <span>{{ $style['label'] }}</span>
                                    @if(request('category') === $category->category)
                                    <span class="text-[10px] text-blue-600 bg-blue-50 px-1.5 rounded font-black border border-blue-200">Aktif</span>
                                    @endif
                                </a>
                                <span class="font-mono text-slate-800">{{ $category->total }} Kasus <span class="text-slate-400 font-normal">({{ $pct }}%)</span></span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="{{ $style['bar'] }} h-2 rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-slate-400 text-xs font-medium border-2 border-dashed border-slate-100 rounded-xl my-auto">
                    Belum ada data komplain untuk dianalisis.
                </div>
            @endif

            <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-400 flex items-center justify-between">
                <span>Klik nama kategori di atas untuk memfilter data tabel secara spesifik</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar (Bekerja 100%, Enter-Search & Clean Dropdowns) -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-2xs border border-slate-200/90 flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
        <form method="GET" action="{{ route('admin.complaints') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center w-full gap-3">
            
            <!-- Pertahankan parameter status jika ada -->
            @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <!-- Search Bar: Kode Tiket, Nama Pelapor, Nomor Kontak, Pesanan -->
            <div class="relative w-full sm:w-80">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari kode tiket, nama pelapor, no WA, atau pesanan..." 
                       class="w-full pl-9 pr-20 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 transition-all shadow-2xs">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <button type="submit" 
                        class="absolute right-1.5 top-1.5 px-3 py-1 bg-[#005ea2] hover:bg-[#004a82] active:scale-95 text-white rounded-lg text-xs font-bold transition shadow-xs cursor-pointer">
                    Cari
                </button>
            </div>
            
            <!-- Filter Kategori (Single Arrow Bersih) -->
            <select name="category" onchange="this.form.submit()"
                    class="w-full sm:w-auto border border-slate-200 rounded-xl pl-3.5 pr-9 py-2.5 text-xs font-bold text-slate-700 bg-slate-50 hover:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none transition cursor-pointer shadow-2xs sm:min-w-[170px]">
                <option value="">Semua Kategori Keluhan</option>
                <option value="pesanan_salah" {{ request('category') === 'pesanan_salah' ? 'selected' : '' }}>Pesanan Salah</option>
                <option value="status_tidak_update" {{ request('category') === 'status_tidak_update' ? 'selected' : '' }}>Status Lambat / Tidak Update</option>
                <option value="lainnya" {{ request('category') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>

            <!-- Reset Filter -->
            @if(request()->hasAny(['search', 'status', 'category']))
            <a href="{{ route('admin.complaints') }}" 
               class="inline-flex items-center justify-center px-3.5 py-2.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-bold transition shadow-2xs shrink-0"
               title="Hapus Semua Filter">
                ✕ Reset Filter
            </a>
            @endif
        </form>
    </div>

    <!-- Tabel Data Komplain (Fit Width, Clean Typography & Action Buttons) -->
    <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/90 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-[11px] uppercase tracking-wider font-black">
                        <th class="px-5 py-3.5">Tiket & Waktu</th>
                        <th class="px-5 py-3.5">Pelapor & Kontak</th>
                        <th class="px-5 py-3.5">Pesanan & Tenant</th>
                        <th class="px-5 py-3.5">Keluhan Pelanggan</th>
                        <th class="px-5 py-3.5 text-center whitespace-nowrap">Status Resolusi</th>
                        <th class="px-5 py-3.5 text-right whitespace-nowrap">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100 font-medium">
                    
                    @forelse($complaints as $c)
                    @php
                        // Format WA Phone: pastikan kode negara 62
                        $rawPhone = preg_replace('/[^0-9]/', '', $c->reporter_contact);
                        $waPhone = str_starts_with($rawPhone, '0') ? '62' . substr($rawPhone, 1) : $rawPhone;
                        $waMessage = urlencode("Halo {$c->reporter_name}, kami dari tim Customer Care FlyDine Bandara Internasional Juanda menindaklanjuti keluhan Anda dengan nomor tiket [{$c->complaint_code}].");
                        $waLink = "https://wa.me/{$waPhone}?text={$waMessage}";
                        
                        $tenantName = $c->order && $c->order->tenant ? $c->order->tenant->name : 'Komplain Umum';
                        $orderCode = $c->order ? $c->order->order_code : '-';
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors group relative">
                        
                        <!-- Kolom 1: Tiket & Waktu -->
                        <td class="px-5 py-4 align-top">
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-black text-slate-900 text-sm tracking-tight">
                                    {{ $c->complaint_code }}
                                </span>
                                @if($c->status === 'open')
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200 animate-pulse">
                                    Urgent
                                </span>
                                @endif
                            </div>
                            <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1 font-semibold">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $c->created_at->format('d M Y, H:i') }}</span>
                                <span class="text-slate-300">•</span>
                                <span class="text-slate-500 font-medium">{{ $c->created_at->diffForHumans() }}</span>
                            </div>
                        </td>

                        <!-- Kolom 2: Detail Pelapor & Kontak WhatsApp -->
                        <td class="px-5 py-4 align-top">
                            <div class="font-black text-slate-900 text-sm">{{ $c->reporter_name }}</div>
                            <div class="mt-1">
                                <a href="{{ $waLink }}" target="_blank" 
                                   class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-colors shadow-2xs group/wa" 
                                   title="Hubungi Pelapor via WhatsApp">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span class="font-mono">{{ $c->reporter_contact }}</span>
                                    <svg class="w-3 h-3 text-emerald-600 group-hover/wa:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </td>

                        <!-- Kolom 3: Pesanan & Tenant -->
                        <td class="px-5 py-4 align-top">
                            @if($c->order)
                                <div class="font-black text-slate-800 text-xs">
                                    {{ $c->order->tenant->name ?? 'Tenant' }}
                                    @if($c->order->tenant && $c->order->tenant->terminal)
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-black {{ $c->order->tenant->terminal === 'T2' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $c->order->tenant->terminal === 'T2' ? 'T2' : 'T1' }}
                                    </span>
                                    @endif
                                </div>
                                <div class="font-mono text-[11px] font-bold text-[#005ea2] mt-0.5">
                                    {{ $c->order->order_code }}
                                </div>
                            @else
                                <div class="font-bold text-slate-600 text-xs">Komplain Non-Pesanan</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">Layanan Umum Bandara</div>
                            @endif
                        </td>

                        <!-- Kolom 4: Keluhan Pelanggan -->
                        <td class="px-5 py-4 align-top max-w-sm">
                            @php
                                $badgeStyle = $catStyle($c->category);
                            @endphp
                            <div class="mb-1.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black border {{ $badgeStyle['badge'] }}">
                                    {{ $badgeStyle['label'] }}
                                </span>
                            </div>
                            <div class="text-xs leading-relaxed text-slate-700 bg-slate-50/80 p-2.5 rounded-xl border border-slate-200/60 font-medium">
                                &ldquo;{{ $c->description }}&rdquo;
                            </div>
                        </td>

                        <!-- Kolom 5: Status Resolusi -->
                        <td class="px-5 py-4 align-top text-center whitespace-nowrap">
                            @if($c->status === 'open')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-rose-50 text-rose-700 border border-rose-200/80 shadow-2xs">
                                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                                    <span>Menunggu Respon</span>
                                </span>
                            @elseif($c->status === 'in_progress')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-blue-50 text-[#005ea2] border border-blue-200/80 shadow-2xs">
                                    <svg class="animate-spin h-3 w-3 text-[#005ea2]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span>Sedang Diproses</span>
                                </span>
                            @elseif($c->status === 'resolved')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-2xs">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span>Selesai (Resolved)</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-slate-100 text-slate-600 border border-slate-200 shadow-2xs">
                                    <span>Ditutup (Closed)</span>
                                </span>
                            @endif

                            @if($c->resolved_at)
                            <div class="text-[10px] text-slate-400 mt-1 font-mono">
                                {{ $c->resolved_at->format('d M, H:i') }}
                            </div>
                            @endif
                        </td>

                        <!-- Kolom 6: Tindakan / Ubah Status -->
                        <td class="px-5 py-4 align-top text-right whitespace-nowrap">
                            <button type="button"
                                    @click="
                                        modalOpen = true; 
                                        selectedTicket = '{{ $c->complaint_code }}'; 
                                        selectedCustomer = '{{ addslashes($c->reporter_name) }}'; 
                                        selectedContact = '{{ addslashes($c->reporter_contact) }}';
                                        selectedWaLink = '{{ $waLink }}';
                                        selectedTenant = '{{ addslashes($tenantName) }}'; 
                                        selectedOrderCode = '{{ addslashes($orderCode) }}';
                                        selectedCategory = '{{ addslashes($badgeStyle['label']) }}';
                                        selectedIssue = '{{ addslashes($c->description) }}'; 
                                        selectedStatus = '{{ $c->status }}';
                                        formAction = '{{ route('admin.complaints.status', $c->id) }}';
                                    "
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer shadow-xs {{ $c->status === 'open' ? 'bg-[#005ea2] hover:bg-[#004a82] active:scale-95 text-white shadow-blue-900/20' : 'bg-slate-50 hover:bg-slate-100 active:scale-95 text-slate-700 border border-slate-200' }}">
                                @if($c->status === 'open')
                                    <span>Tindak Lanjuti</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                                @else
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    <span>Ubah Status</span>
                                @endif
                            </button>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="font-bold text-slate-800 text-sm">Tidak ada tiket komplain yang sesuai filter</p>
                            <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci lain atau bersihkan filter pencarian.</p>
                            @if(request()->hasAny(['search', 'status', 'category']))
                            <div class="mt-4">
                                <a href="{{ route('admin.complaints') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#005ea2] text-white rounded-xl text-xs font-bold hover:bg-[#004a82] transition shadow-xs">
                                    <span>Reset Semua Filter</span>
                                </a>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        
        <!-- Paginasi & Total Data Counter -->
        @if($complaints->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $complaints->links('components.pagination', ['itemName' => 'tiket komplain', 'itemNameEn' => 'complaint tickets']) }}
        </div>
        @else
        <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 font-medium">
            <span>Menampilkan total <strong class="font-bold text-slate-800">{{ $complaints->total() }}</strong> tiket komplain pelanggan.</span>
            <span class="text-slate-400 text-[11px]">Halaman 1 dari 1</span>
        </div>
        @endif
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL INTERAKTIF: RESOLUSI & TINDAK LANJUT TIKET (FULL FROSTED BLUR)     -->
    <!-- ========================================================================= -->
    <template x-teleport="body">
        <div x-show="modalOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="modalOpen = false"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-5 overflow-hidden" 
             style="display: none; background-color: rgba(15, 23, 42, 0.55); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
            
            <div @click.away="modalOpen = false"
                 class="bg-white rounded-3xl shadow-2xl max-w-lg w-full border border-slate-200 flex flex-col my-auto overflow-hidden animate-scale-up"
                 style="max-height: 90vh;">
                
                <!-- Modal Header -->
                <div class="px-5 py-4 text-white flex items-center justify-between shrink-0"
                     style="background: linear-gradient(135deg, #09131f 0%, #005ea2 50%, #1e40af 100%);">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white border border-white/20 shrink-0">
                            <svg class="w-5 h-5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-black text-sm sm:text-base leading-tight text-white">Resolusi Tiket</h3>
                                <span class="font-mono text-xs font-black px-2 py-0.5 rounded bg-white/20 text-white" x-text="selectedTicket"></span>
                            </div>
                            <p class="text-[11px] text-blue-100 font-medium mt-0.5" x-text="selectedTenant + (selectedOrderCode !== '-' ? ' • ' + selectedOrderCode : '')"></p>
                        </div>
                    </div>
                    <button type="button" @click="modalOpen = false" 
                            class="text-white/80 hover:text-white p-1.5 rounded-full hover:bg-white/10 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Content Form -->
                <form :action="formAction" method="POST" class="flex flex-col flex-1 min-h-0">
                    @csrf
                    
                    <div class="p-5 space-y-4 overflow-y-auto" style="flex: 1 1 0%; min-height: 0;">
                        
                        <!-- Rincian Pelapor & Komplain -->
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 space-y-2.5">
                            <div class="flex items-center justify-between text-xs">
                                <div>
                                    <span class="text-slate-400 font-bold uppercase text-[10px] tracking-wider block">Pelapor</span>
                                    <span class="font-black text-slate-800 text-sm" x-text="selectedCustomer"></span>
                                </div>
                                <a :href="selectedWaLink" target="_blank" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer">
                                    <span>Chat WA</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                </a>
                            </div>

                            <div class="pt-2 border-t border-slate-200/60">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Isi Keluhan Pelanggan</span>
                                    <span class="text-[10px] font-black text-rose-700 bg-rose-50 px-2 py-0.2 rounded border border-rose-200" x-text="selectedCategory"></span>
                                </div>
                                <div class="text-xs text-slate-700 font-medium italic bg-white p-3 rounded-xl border border-slate-200 leading-relaxed" x-text="'&ldquo;' + selectedIssue + '&rdquo;'"></div>
                            </div>
                        </div>

                        <!-- Pilih Status Baru -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Perbarui Status Resolusi Tiket <span class="text-rose-500">*</span>
                            </label>
                            
                            <select name="status" x-model="selectedStatus" required 
                                    class="w-full border border-slate-200 rounded-xl pl-3.5 pr-9 py-2.5 text-xs font-bold text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none bg-white cursor-pointer shadow-2xs">
                                <option value="open">Menunggu Respon (Open)</option>
                                <option value="in_progress">Sedang Diproses (Investigasi & Koordinasi Tenant)</option>
                                <option value="resolved">Selesai (Solusi / Kompensasi Diterima)</option>
                                <option value="closed">Ditutup (Closed / Selesai Tanpa Tindakan)</option>
                            </select>

                            <p class="text-[11px] text-slate-500 mt-1.5 font-medium">
                                Status <strong class="text-emerald-700">Selesai</strong> atau <strong class="text-slate-700">Ditutup</strong> akan otomatis merekam waktu penyelesaian (*resolved_at*) untuk kalkulasi SLA operasional.
                            </p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2.5 shrink-0">
                        <button type="button" @click="modalOpen = false" 
                                class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-white text-xs font-bold transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2 rounded-xl bg-[#005ea2] hover:bg-[#004a82] active:scale-95 text-white text-xs font-black shadow-md shadow-blue-900/20 transition cursor-pointer flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Status Baru</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </template>

</div>
@endsection