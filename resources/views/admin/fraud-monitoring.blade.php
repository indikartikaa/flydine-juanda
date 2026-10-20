@extends('layouts.admin')

@section('title', 'Pemantauan Fraud & Spam')

@section('content')
<div x-data="{ 
    unblockModalOpen: false, 
    selectedCustomerId: null,
    selectedCustomerName: '',
    selectedCustomerPhone: '',
    selectedCustomerReason: '',
    selectedCancelledCount: 0,
    unblockActionUrl: ''
}" class="space-y-6 pb-12">

    <!-- Flash Alert Notification -->
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

    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Pemantauan Fraud & Spam</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Pantau dan mitigasi akun penumpang yang diblokir otomatis oleh sistem anti-fraud karena pola transaksi mencurigakan.</p>
        </div>
    </div>

    <!-- Security KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        
        <!-- Card 1: Total Akun Terblokir -->
        <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200/90 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Akun Dibatasi (Blocked)</span>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ $total_blocked }}</span>
                    <span class="text-xs font-bold text-slate-400">Akun Pelanggan</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                    <span>Akses pemesanan dinonaktifkan</span>
                    <span class="font-bold text-slate-700">{{ $total_active }} Pelanggan Aktif</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Pesanan Dibatalkan -->
        <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200/90 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Akumulasi Batal Sistem</span>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ $total_cancelled_orders }}</span>
                    <span class="text-xs font-bold text-slate-400">Total Pesanan Batal</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                    <span>Rule deteksi otomatis</span>
                    <span class="font-bold text-amber-700">SOP: Batas 5x / 30 Menit</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Kasus Minggu Ini -->
        <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200/90 flex flex-col justify-between sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Insiden 7 Hari Terakhir</span>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ $recent_blocked_7d }}</span>
                    <span class="text-xs font-bold text-slate-400">Kasus Baru</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                    <span>Lalu lintas bandara Juanda</span>
                    <span class="font-bold text-emerald-600">Terproteksi 24/7</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-2xs border border-slate-200/90 flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
        <form method="GET" action="{{ route('admin.fraud') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center w-full gap-3">
            
            <!-- Search Bar: Nama, No HP, Alasan -->
            <div class="relative w-full sm:w-80">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama, no HP, atau alasan blokir..." 
                       class="w-full pl-9 pr-20 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 transition-all shadow-2xs">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <button type="submit" 
                        class="absolute right-1.5 top-1.5 px-3 py-1 bg-[#005ea2] hover:bg-[#004a82] active:scale-95 text-white rounded-lg text-xs font-bold transition shadow-xs cursor-pointer">
                    Cari
                </button>
            </div>

            <!-- Quick Filter Preset (Smooth Alpine.js Dropdown) -->
            <div x-data="{ 
                    open: false, 
                    selected: '{{ request('filter') === 'high_cancel' ? 'Batal Berulang (≥5)' : (request('filter') === 'recent' ? 'Terbaru (7 Hari)' : 'Semua Status Blokir') }}',
                    value: '{{ request('filter', '') }}'
                 }" 
                 @click.outside="open = false" 
                 class="relative w-full sm:w-auto">
                
                <input type="hidden" name="filter" :value="value">
                
                <button type="button" @click="open = !open" 
                        class="w-full sm:w-auto min-w-[170px] inline-flex items-center justify-between gap-2.5 px-3.5 py-2.5 bg-slate-50 hover:bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 transition-all duration-200 shadow-2xs cursor-pointer">
                    <span class="truncate" x-text="selected"></span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180 text-[#005ea2]' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-150 transform" 
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1" 
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100 transform" 
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                     style="display: none;"
                     class="absolute left-0 mt-1.5 w-full sm:w-52 bg-white border border-slate-200/90 rounded-2xl shadow-xl shadow-slate-200/50 py-1.5 z-50 overflow-hidden">
                    
                    <button type="button" 
                            @click="value = ''; selected = 'Semua Status Blokir'; open = false; $nextTick(() => $el.closest('form').submit())"
                            class="w-full px-3.5 py-2 text-left text-xs font-bold flex items-center justify-between hover:bg-slate-50 transition-colors cursor-pointer"
                            :class="value === '' ? 'text-[#005ea2] bg-blue-50/50 font-black' : 'text-slate-700'">
                        <span>Semua Status Blokir</span>
                        <span x-show="value === ''" class="text-[#005ea2]">✓</span>
                    </button>

                    <button type="button" 
                            @click="value = 'high_cancel'; selected = 'Batal Berulang (≥5)'; open = false; $nextTick(() => $el.closest('form').submit())"
                            class="w-full px-3.5 py-2 text-left text-xs font-bold flex items-center justify-between hover:bg-slate-50 transition-colors cursor-pointer"
                            :class="value === 'high_cancel' ? 'text-[#005ea2] bg-blue-50/50 font-black' : 'text-slate-700'">
                        <span>Batal Berulang (&ge; 5)</span>
                        <span x-show="value === 'high_cancel'" class="text-[#005ea2]">✓</span>
                    </button>

                    <button type="button" 
                            @click="value = 'recent'; selected = 'Terbaru (7 Hari)'; open = false; $nextTick(() => $el.closest('form').submit())"
                            class="w-full px-3.5 py-2 text-left text-xs font-bold flex items-center justify-between hover:bg-slate-50 transition-colors cursor-pointer"
                            :class="value === 'recent' ? 'text-[#005ea2] bg-blue-50/50 font-black' : 'text-slate-700'">
                        <span>Terbaru (7 Hari)</span>
                        <span x-show="value === 'recent'" class="text-[#005ea2]">✓</span>
                    </button>
                </div>
            </div>

            <!-- Reset Filter Button -->
            @if(request()->hasAny(['search', 'filter']))
            <a href="{{ route('admin.fraud') }}" 
               class="inline-flex items-center justify-center px-3.5 py-2.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-bold transition shadow-2xs shrink-0"
               title="Hapus Semua Filter">
                ✕ Reset Filter
            </a>
            @endif

        </form>
    </div>

    <!-- Tabel Data Akun Terblokir -->
    <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/90 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-[11px] uppercase tracking-wider font-black">
                        <th class="px-5 py-3.5">Waktu Pemblokiran</th>
                        <th class="px-5 py-3.5">Pelanggan & Kontak</th>
                        <th class="px-5 py-3.5 text-center whitespace-nowrap">Pesanan Batal</th>
                        <th class="px-5 py-3.5">Alasan Pemblokiran</th>
                        <th class="px-5 py-3.5 text-center whitespace-nowrap">Status Akun</th>
                        <th class="px-5 py-3.5 text-right whitespace-nowrap">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100 font-medium">
                    
                    @forelse($customers as $c)
                    @php
                        $rawPhone = preg_replace('/[^0-9]/', '', $c->phone_number);
                        $waPhone = str_starts_with($rawPhone, '0') ? '62' . substr($rawPhone, 1) : $rawPhone;
                        $waMessage = urlencode("Halo {$c->name}, kami dari tim Customer Care & Security FlyDine Bandara Internasional Juanda ingin melakukan konfirmasi terkait akun pemesanan Anda.");
                        $waLink = "https://wa.me/{$waPhone}?text={$waMessage}";
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors group">
                        
                        <!-- Waktu Pemblokiran -->
                        <td class="px-5 py-4 align-top whitespace-nowrap">
                            <div class="font-mono font-black text-slate-900 text-sm tracking-tight">
                                {{ $c->blocked_at ? $c->blocked_at->format('d M Y') : '-' }}
                            </div>
                            <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1 font-semibold">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $c->blocked_at ? $c->blocked_at->format('H:i') . ' WIB' : '-' }}</span>
                                @if($c->blocked_at)
                                <span class="text-slate-300">•</span>
                                <span class="text-slate-500 font-medium">{{ $c->blocked_at->diffForHumans() }}</span>
                                @endif
                            </div>
                        </td>

                        <!-- Pelanggan & Kontak WhatsApp -->
                        <td class="px-5 py-4 align-top">
                            <div class="font-black text-slate-900 text-sm">{{ $c->name ?? 'Guest Passenger' }}</div>
                            <div class="mt-1">
                                <a href="{{ $waLink }}" target="_blank" 
                                   class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-colors shadow-2xs group/wa" 
                                   title="Hubungi Pelanggan via WhatsApp">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span class="font-mono">{{ $c->phone_number }}</span>
                                    <svg class="w-3 h-3 text-emerald-600 group-hover/wa:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </td>

                        <!-- Pesanan Batal Counter -->
                        <td class="px-5 py-4 align-top text-center whitespace-nowrap">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-rose-50 text-rose-700 font-black text-sm border border-rose-200 shadow-2xs">
                                {{ $c->cancelled_orders_count }}
                            </span>
                            <div class="text-[10px] text-slate-400 mt-1 font-semibold">Total Batal</div>
                        </td>

                        <!-- Alasan Pemblokiran -->
                        <td class="px-5 py-4 align-top max-w-sm">
                            <div class="text-xs leading-relaxed text-slate-700 bg-slate-50/80 p-2.5 rounded-xl border border-slate-200/60 font-medium">
                                {{ $c->blocked_reason ?? 'Terdeteksi pola pesanan tidak wajar oleh automated rate-limiter.' }}
                            </div>
                        </td>

                        <!-- Status Akun -->
                        <td class="px-5 py-4 align-top text-center whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black bg-rose-50 text-rose-700 border border-rose-200/80 shadow-2xs">
                                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                                <span>Akses Dibatasi</span>
                            </span>
                        </td>

                        <!-- Tindakan: Buka Blokir (Unblock) dengan Modal Konfirmasi Frosted Blur -->
                        <td class="px-5 py-4 align-top text-right whitespace-nowrap">
                            <button type="button"
                                    @click="
                                        unblockModalOpen = true;
                                        selectedCustomerId = '{{ $c->id }}';
                                        selectedCustomerName = '{{ addslashes($c->name ?? 'Pelanggan') }}';
                                        selectedCustomerPhone = '{{ addslashes($c->phone_number) }}';
                                        selectedCustomerReason = '{{ addslashes($c->blocked_reason ?? '-') }}';
                                        selectedCancelledCount = {{ $c->cancelled_orders_count }};
                                        unblockActionUrl = '{{ route('admin.fraud.unblock', $c->id) }}';
                                    "
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white border border-emerald-200/80 transition-all cursor-pointer shadow-xs active:scale-95">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                                <span>Buka Blokir (Unblock)</span>
                            </button>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 shadow-2xs">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <p class="font-bold text-slate-800 text-sm">Tidak ada akun penumpang yang diblokir</p>
                            <p class="text-xs text-slate-400 mt-1 max-w-md mx-auto">
                                @if(request()->hasAny(['search', 'filter']))
                                    Tidak ditemukan akun terblokir yang sesuai dengan kata kunci pencarian Anda.
                                @else
                                    Sistem proteksi transaksi berjalan normal. Seluruh lalu lintas pemesanan penumpang bandara terpantau bersih dan aman dari spam atau fraud.
                                @endif
                            </p>
                            @if(request()->hasAny(['search', 'filter']))
                            <div class="mt-4">
                                <a href="{{ route('admin.fraud') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#005ea2] text-white rounded-xl text-xs font-bold hover:bg-[#004a82] transition shadow-xs">
                                    <span>Reset Filter Pencarian</span>
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
        @if($customers->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $customers->links('components.pagination', ['itemName' => 'akun terblokir', 'itemNameEn' => 'blocked accounts']) }}
        </div>
        @else
        <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500 font-medium">
            <span>Menampilkan total <strong class="font-bold text-slate-800">{{ $customers->total() }}</strong> akun penumpang terblokir.</span>
            <span class="text-slate-400 text-[11px]">Halaman 1 dari 1</span>
        </div>
        @endif

    </div>

    <!-- ========================================================================= -->
    <!-- MODAL KONFIRMASI: BUKA BLOKIR AKUN PELANGGAN (FULL FROSTED GLASS BLUR)    -->
    <!-- ========================================================================= -->
    <template x-teleport="body">
        <div x-show="unblockModalOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="unblockModalOpen = false"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-5 overflow-hidden" 
             style="display: none; background-color: rgba(15, 23, 42, 0.55); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
            
            <div @click.away="unblockModalOpen = false"
                 class="bg-white rounded-3xl shadow-2xl max-w-md w-full border border-slate-200 flex flex-col my-auto overflow-hidden animate-scale-up">
                
                <!-- Modal Header -->
                <div class="px-5 py-4 text-white flex items-center justify-between shrink-0"
                     style="background: linear-gradient(135deg, #09131f 0%, #005ea2 50%, #059669 100%);">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white border border-white/20 shrink-0">
                            <svg class="w-5 h-5 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-black text-sm sm:text-base leading-tight text-white">Konfirmasi Buka Blokir</h3>
                            <p class="text-[11px] text-emerald-100 font-medium mt-0.5">Pemulihan Hak Akses Akun Penumpang</p>
                        </div>
                    </div>
                    <button type="button" @click="unblockModalOpen = false" 
                            class="text-white/80 hover:text-white p-1.5 rounded-full hover:bg-white/10 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Content Form -->
                <form :action="unblockActionUrl" method="POST" class="flex flex-col flex-1 min-h-0">
                    @csrf
                    
                    <div class="p-5 space-y-4">
                        
                        <!-- Ringkasan Akun Pelanggan -->
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <div>
                                    <span class="text-slate-400 font-bold uppercase text-[10px] tracking-wider block">Pelanggan</span>
                                    <span class="font-black text-slate-800 text-sm" x-text="selectedCustomerName"></span>
                                </div>
                                <div class="text-right">
                                    <span class="text-slate-400 font-bold uppercase text-[10px] tracking-wider block">Nomor HP</span>
                                    <span class="font-mono font-bold text-slate-800 text-xs" x-text="selectedCustomerPhone"></span>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-200/60">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Alasan Terblokir Sebelumnya</span>
                                <div class="text-xs text-rose-700 bg-rose-50/80 p-2.5 rounded-xl border border-rose-200/60 font-medium" x-text="selectedCustomerReason"></div>
                            </div>
                        </div>

                        <!-- Info Dampak Tindakan -->
                        <div class="flex items-start gap-2.5 text-xs text-slate-600 bg-amber-50/70 p-3 rounded-xl border border-amber-200/60">
                            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="leading-relaxed">
                                Membuka blokir akan mengizinkan kembali nomor pelanggan ini untuk melakukan pemesanan di seluruh tenant FlyDine Bandara Internasional Juanda. Aksi ini akan dicatat ke dalam *Activity Audit Log*.
                            </p>
                        </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2.5 shrink-0">
                        <button type="button" @click="unblockModalOpen = false" 
                                class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-white text-xs font-bold transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white text-xs font-black shadow-md shadow-emerald-900/20 transition cursor-pointer flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Ya, Buka Blokir Sekarang</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </template>

</div>
@endsection
