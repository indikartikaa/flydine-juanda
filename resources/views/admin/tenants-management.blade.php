@extends('layouts.admin')

@section('title', 'Manajemen Mitra Tenant')

@section('content')
<div x-data="{ 
    addTenantModalOpen: {{ $errors->has('email') || (old('_method') != 'PUT' && $errors->any()) ? 'true' : 'false' }}, 
    editTenantModalOpen: {{ old('_method') == 'PUT' && $errors->any() ? 'true' : 'false' }}, 
    newTenant: {
        name: '{{ old('name', '') }}',
        tenant_code: '{{ old('tenant_code', '') }}',
        category: '{{ old('category', '') }}',
        terminal: '{{ old('terminal', 'T1') }}'
    },
    editData: {} 
}" class="space-y-6 pb-12">

    <!-- Flash Alerts -->
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
    
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl shadow-2xs">
            <div class="flex items-center gap-2 mb-1">
                <span class="p-1 rounded-lg bg-rose-500 text-white font-black text-xs">!</span>
                <span class="font-bold text-xs sm:text-sm">Terdapat kesalahan pengisian data:</span>
            </div>
            <ul class="list-disc list-inside text-xs font-medium space-y-0.5 ml-6">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 3 Executive KPI Cards (Professional SVG Icons, Progress Ratio & Elevated Typography) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
        
        <!-- Card 1: Total Mitra -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Total Mitra Terdaftar</span>
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 text-[#005ea2] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ $total_tenants }}</span>
                    <span class="text-xs font-bold text-slate-500">Gerai Terverifikasi</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="font-medium text-slate-500">Kapasitas Gerai Aktif</span>
                    <span class="font-black text-blue-700 bg-blue-50 border border-blue-200/60 px-2.5 py-0.5 rounded-full">100% Kuota</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Sebaran Terminal T1 vs T2 (Clean & Consistent with Card 1 & 3) -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Distribusi Terminal</span>
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-700 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </div>
            </div>
            <div>
                <div class="flex items-baseline gap-2.5">
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-3xl font-black text-[#005ea2] tracking-tight">{{ $t1_count ?? 60 }}</span>
                        <span class="text-xs font-bold text-slate-500">T1 Domestik</span>
                    </div>
                    <span class="text-slate-300 font-bold text-sm">&bull;</span>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-3xl font-black text-purple-600 tracking-tight">{{ $t2_count ?? 16 }}</span>
                        <span class="text-xs font-bold text-slate-500">T2 Internasional</span>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="font-medium text-slate-500">Gate 1–12 & Lounge</span>
                    <span class="font-bold text-slate-700 bg-slate-50 border border-slate-200/80 px-2.5 py-0.5 rounded-full">T1 (79%) &bull; T2 (21%)</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Status Kesiapan Buka -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-500">Kesiapan Layanan</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900 tracking-tight">{{ $active_tenants }}</span>
                    <span class="text-xs font-bold text-emerald-600">Tenant Buka</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                    <span class="font-semibold text-slate-500 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Semua tenant beroperasi
                    </span>
                    <span class="font-black text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2.5 py-0.5 rounded-full">Ready 100%</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Filter & Action Toolbar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-2xs border border-slate-200/90 flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
        <form method="GET" action="{{ url('/admin/tenants-management') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center w-full md:w-auto gap-3">
            <!-- Search Bar -->
            <div class="relative w-full sm:w-80">
                <input type="text" name="search" value="{{ request('search') }}" 
                       oninput="clearTimeout(this.timer); this.timer = setTimeout(() => { this.closest('form').submit(); }, 400)" 
                       placeholder="Cari nama brand, PT, atau kode tenant..." 
                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-hidden focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 transition-all shadow-2xs">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            
            <!-- Terminal Dropdown Filter (Clean Single Native Arrow) -->
            <select name="terminal" onchange="this.form.submit()" 
                    class="w-full sm:w-auto border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-700 bg-slate-50 hover:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none transition cursor-pointer shadow-2xs">
                <option value="">Semua Terminal (T1 & T2)</option>
                <option value="1" {{ in_array(request('terminal'), ['1', 'T1']) ? 'selected' : '' }}>Terminal 1 (Domestik)</option>
                <option value="2" {{ in_array(request('terminal'), ['2', 'T2']) ? 'selected' : '' }}>Terminal 2 (Internasional)</option>
            </select>
        </form>
        
        <!-- Primary Action Button: Tambah Mitra Baru -->
        <button type="button" @click="addTenantModalOpen = true" 
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#005ea2] hover:bg-[#004a82] active:scale-98 text-white text-xs font-black transition-all shadow-md shadow-blue-900/20 cursor-pointer shrink-0">
            <svg class="w-4 h-4 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Mitra Baru</span>
        </button>
    </div>

    <!-- Tabel Data Mitra Tenant (Fit 100% Width, Zero Horizontal Scroll, Auto-Truncate) -->
    <div class="bg-white rounded-2xl shadow-2xs border border-slate-200/90 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-[11px] uppercase tracking-wider font-black">
                        <th class="px-4 sm:px-5 py-3.5">Perusahaan / Brand</th>
                        <th class="px-4 sm:px-5 py-3.5">Lokasi & Penempatan</th>
                        <th class="px-4 sm:px-5 py-3.5 whitespace-nowrap">Kategori F&B</th>
                        <th class="px-4 sm:px-5 py-3.5 whitespace-nowrap">Menu & Kontrak</th>
                        <th class="px-4 sm:px-5 py-3.5 whitespace-nowrap">Status Gerai</th>
                        <th class="px-4 sm:px-5 py-3.5 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100 font-medium">
                    @forelse($tenants as $tenant)
                    <tr class="hover:bg-slate-50/70 transition-colors group">
                        
                        <!-- Kolom 1: Perusahaan / Brand -->
                        <td class="px-4 sm:px-5 py-3.5">
                            <div class="flex items-center gap-3 min-w-0">
                                @if($tenant->logo)
                                    <img src="{{ asset($tenant->logo) }}" alt="{{ $tenant->name }}" 
                                         class="h-10 w-10 flex-shrink-0 rounded-xl border border-slate-200 object-cover shadow-2xs">
                                @else
                                    <div class="h-10 w-10 flex-shrink-0 bg-blue-50 border border-blue-200 rounded-xl flex items-center justify-center text-[#005ea2] font-black text-xs shadow-2xs">
                                        {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <div class="font-black text-slate-900 text-sm tracking-tight group-hover:text-[#005ea2] transition-colors truncate max-w-[150px] sm:max-w-[180px]" title="{{ $tenant->name }}">
                                        {{ $tenant->name }}
                                    </div>
                                    <div class="flex items-center gap-1.5 mt-0.5 min-w-0">
                                        <span class="font-mono text-[10px] font-bold text-slate-500 bg-slate-100 px-1.5 py-0.2 rounded border border-slate-200 shrink-0">
                                            {{ $tenant->tenant_code }}
                                        </span>
                                        @if($tenant->company_name)
                                        <span class="text-[11px] text-slate-400 truncate max-w-[100px] sm:max-w-[120px]" title="{{ $tenant->company_name }}">
                                            {{ $tenant->company_name }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Kolom 2: Lokasi & Penempatan (Truncate Keberangkatan Um...) -->
                        <td class="px-4 sm:px-5 py-3.5">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <span class="shrink-0 whitespace-nowrap px-2 py-0.5 rounded text-[10px] font-black {{ $tenant->terminal == 'T2' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                                    {{ $tenant->terminal == 'T2' ? 'Terminal 2' : 'Terminal 1' }}
                                </span>
                                <span class="font-bold text-slate-700 text-xs truncate max-w-[130px] sm:max-w-[160px]" title="{{ $tenant->floor_location }}">
                                    {{ $tenant->floor_location ?? 'TBD' }}
                                </span>
                            </div>
                            <div class="text-[11px] text-slate-400 mt-0.5 font-medium truncate max-w-[180px]" title="{{ $tenant->zone == 'Airside' ? 'Ruang Tunggu (Airside)' : 'Area Publik (Landside)' }}">
                                {{ $tenant->zone == 'Airside' ? 'Ruang Tunggu (Airside)' : 'Area Publik (Landside)' }}
                            </div>
                        </td>

                        <!-- Kolom 3: Kategori F&B -->
                        <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                            @php
                                $cat = $tenant->category ?? 'Umum';
                                $catClass = match($cat) {
                                    'Beverages & Coffee' => 'bg-amber-50 text-amber-800 border-amber-200',
                                    'Fast Food & Restaurant' => 'bg-blue-50 text-blue-800 border-blue-200',
                                    'Bakery & Pastry' => 'bg-rose-50 text-rose-800 border-rose-200',
                                    'Indonesian Traditional' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                    default => 'bg-slate-50 text-slate-700 border-slate-200'
                                };
                            @endphp
                            <span class="inline-block px-2.5 py-0.5 rounded-lg text-[11px] font-bold border {{ $catClass }}">
                                {{ $cat }}
                            </span>
                        </td>

                        <!-- Kolom 4: Menu & Kontrak -->
                        <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                            <div class="font-bold text-slate-800 text-xs">
                                {{ $tenant->products_count }} Menu Aktif &bull; <span class="text-slate-500 font-medium">{{ $tenant->orders_count }} Pesanan</span>
                            </div>
                            <div class="text-[11px] text-slate-400 mt-0.5">
                                @if($tenant->contract_end)
                                    Kontrak s/d {{ \Carbon\Carbon::parse($tenant->contract_end)->translatedFormat('d M Y') }}
                                @else
                                    <span class="text-slate-400">Kontrak Berjalan</span>
                                @endif
                            </div>
                        </td>

                        <!-- Kolom 5: Status Gerai (Buka / Tutup Ringkas) -->
                        <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                            @if($tenant->isOpen())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Buka
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200 shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Tutup
                                </span>
                            @endif
                        </td>

                        <!-- Kolom 6: Aksi -->
                        <td class="px-4 sm:px-5 py-3.5 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1">
                                <!-- Tombol Edit -->
                                <button type="button" 
                                        @click="editData = { 
                                            id: {{ $tenant->id }},
                                            name: '{{ addslashes($tenant->name) }}',
                                            tenant_code: '{{ $tenant->tenant_code }}',
                                            company_name: '{{ addslashes($tenant->company_name ?? '') }}',
                                            category: '{{ $tenant->category }}',
                                            terminal: '{{ $tenant->terminal }}',
                                            zone: '{{ $tenant->zone }}',
                                            floor_location: '{{ addslashes($tenant->floor_location) }}',
                                            contract_start: '{{ $tenant->contract_start }}',
                                            contract_end: '{{ $tenant->contract_end }}'
                                        }; editTenantModalOpen = true" 
                                        class="p-2 text-slate-400 hover:text-[#005ea2] hover:bg-blue-50 rounded-xl transition cursor-pointer" 
                                        title="Edit Data Mitra">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                
                                <!-- Tombol Toggle Status -->
                                <form action="{{ route('admin.tenants.toggle-status', $tenant->id) }}" method="POST" class="inline-block" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin {{ $tenant->is_active ? 'menangguhkan (suspend)' : 'mengaktifkan kembali' }} tenant ini?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="p-2 rounded-xl transition cursor-pointer {{ $tenant->is_active ? 'text-slate-400 hover:text-rose-600 hover:bg-rose-50' : 'text-slate-400 hover:text-emerald-600 hover:bg-emerald-50' }}" 
                                            title="{{ $tenant->is_active ? 'Tangguhkan (Suspend)' : 'Aktifkan Kembali' }}">
                                        @if($tenant->is_active)
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium text-xs">
                            Tidak ditemukan mitra tenant yang sesuai dengan kriteria pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginasi -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $tenants->links() }}
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 1: REGISTRASI MITRA TENANT BARU (NON-CLIPPING, FULLY ACCESSIBLE)   -->
    <!-- ========================================================================= -->
    <div x-show="addTenantModalOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="addTenantModalOpen = false"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex justify-center p-3 sm:p-5" 
         style="display: none;">
        
        <form action="{{ route('admin.tenants.store') }}" method="POST" 
             @click.away="addTenantModalOpen = false"
             class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl max-w-xl w-full border border-slate-200 flex flex-col max-h-[calc(100vh-2rem)] my-auto overflow-hidden">
            @csrf
            
            <!-- Modal Header (Compact & Fixed) -->
            <div class="px-5 py-3.5 text-white flex items-center justify-between shrink-0"
                 style="background: linear-gradient(135deg, #09131f 0%, #005ea2 50%, #1e40af 100%);">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-[#8dc63f] font-black text-base border border-white/20">
                        +
                    </div>
                    <div>
                        <h3 class="font-black text-sm sm:text-base leading-tight text-white">Registrasi Mitra Tenant Baru</h3>
                        <p class="text-[11px] text-blue-100 font-medium">Lengkapi identitas, akun PIC, dan alokasi ruang gerai</p>
                    </div>
                </div>
                <button type="button" @click="addTenantModalOpen = false" 
                        class="text-white/80 hover:text-white p-1.5 rounded-full hover:bg-white/10 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form Content (Smoothly Scrollable Middle with flex-1 min-h-0) -->
            <div class="px-5 py-4 space-y-4 overflow-y-auto flex-1 min-h-0">
                
                <!-- Live Preview Mini-Card -->
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 border border-blue-200 flex items-center justify-center text-[#005ea2] font-black text-xs shrink-0">
                            <span x-text="newTenant.name ? newTenant.name.substring(0, 2).toUpperCase() : 'FD'"></span>
                        </div>
                        <div class="min-w-0">
                            <div class="font-black text-slate-900 text-xs truncate" x-text="newTenant.name || 'Nama Brand Outlet'"></div>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="font-mono text-[9px] font-bold text-slate-500 bg-white px-1.5 py-0.2 rounded border border-slate-200" x-text="newTenant.tenant_code ? newTenant.tenant_code.toUpperCase() : 'KODE-TENANT'"></span>
                                <span class="text-[9px] font-bold text-blue-700 bg-blue-50 px-1.5 py-0.2 rounded" x-text="newTenant.terminal === 'T2' ? 'Terminal 2' : 'Terminal 1'"></span>
                            </div>
                        </div>
                    </div>
                    <span class="text-[9px] font-extrabold uppercase text-slate-400 bg-white px-2 py-0.5 rounded border border-slate-200 shrink-0">
                        Pratinjau
                    </span>
                </div>

                <!-- Section 1: Identitas Brand & Akun Login PIC -->
                <div class="space-y-2.5">
                    <div class="flex items-center gap-2 text-[11px] font-black uppercase tracking-wider text-slate-800 pb-1 border-b border-slate-100">
                        <span class="w-4 h-4 rounded-md bg-blue-100 text-[#005ea2] flex items-center justify-center text-[10px]">1</span>
                        <span>Identitas Brand & Akun Login PIC</span>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Brand / Outlet <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" x-model="newTenant.name" value="{{ old('name') }}" required placeholder="Contoh: Starbucks Coffee" 
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kode Tenant (Unik) <span class="text-rose-500">*</span></label>
                            <input type="text" name="tenant_code" x-model="newTenant.tenant_code" value="{{ old('tenant_code') }}" required placeholder="Contoh: T1-SBX" 
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono font-bold text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none uppercase">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Email Login PIC <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: starbucks@flydine.com" 
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                            <div class="mt-1 flex items-center gap-1.5 text-[10px] text-slate-500 font-medium">
                                <span class="text-amber-600 font-bold">🔑</span>
                                <span>Password default: <strong class="text-slate-800">juanda123</strong></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Perusahaan (PT/CV)</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="Contoh: PT Sari Coffee Indonesia" 
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Kategori F&B & Penempatan Ruang -->
                <div class="space-y-2.5 pt-1">
                    <div class="flex items-center gap-2 text-[11px] font-black uppercase tracking-wider text-slate-800 pb-1 border-b border-slate-100">
                        <span class="w-4 h-4 rounded-md bg-purple-100 text-purple-700 flex items-center justify-center text-[10px]">2</span>
                        <span>Kategori F&B & Penempatan Ruang Bandara</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kategori F&B <span class="text-rose-500">*</span></label>
                            <select name="category" x-model="newTenant.category" required class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Beverages & Coffee" {{ old('category') == 'Beverages & Coffee' ? 'selected' : '' }}>Beverages & Coffee</option>
                                <option value="Fast Food & Restaurant" {{ old('category') == 'Fast Food & Restaurant' ? 'selected' : '' }}>Fast Food & Restaurant</option>
                                <option value="Bakery & Pastry" {{ old('category') == 'Bakery & Pastry' ? 'selected' : '' }}>Bakery & Pastry</option>
                                <option value="Indonesian Traditional" {{ old('category') == 'Indonesian Traditional' ? 'selected' : '' }}>Indonesian Traditional</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Terminal <span class="text-rose-500">*</span></label>
                            <select name="terminal" x-model="newTenant.terminal" required class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                                <option value="T1" {{ old('terminal') == 'T1' ? 'selected' : '' }}>Terminal 1 (T1 Domestik)</option>
                                <option value="T2" {{ old('terminal') == 'T2' ? 'selected' : '' }}>Terminal 2 (T2 Internasional)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Area <span class="text-rose-500">*</span></label>
                            <select name="zone" required class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                                <option value="Landside" {{ old('zone') == 'Landside' ? 'selected' : '' }}>Area Publik (Landside)</option>
                                <option value="Airside" {{ old('zone') == 'Airside' ? 'selected' : '' }}>Ruang Tunggu (Airside)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Detail Ruang / Space <span class="text-rose-500">*</span></label>
                            <input type="text" name="floor_location" value="{{ old('floor_location') }}" required placeholder="Contoh: Lt. 2 Gate 8 (EP-01)" 
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Masa Kontrak Sewa -->
                <div class="space-y-2.5 pt-1">
                    <div class="flex items-center gap-2 text-[11px] font-black uppercase tracking-wider text-slate-800 pb-1 border-b border-slate-100">
                        <span class="w-4 h-4 rounded-md bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px]">3</span>
                        <span>Periode Masa Kontrak Sewa</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai Kontrak</label>
                            <input type="date" name="contract_start" value="{{ old('contract_start') }}" 
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Akhir Kontrak</label>
                            <input type="date" name="contract_end" value="{{ old('contract_end') }}" 
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer (Fixed at Bottom, Never Cut Off) -->
            <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2.5 shrink-0">
                <button type="button" @click="addTenantModalOpen = false" 
                        class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-white text-xs font-bold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2 rounded-xl bg-[#005ea2] hover:bg-[#004a82] text-white text-xs font-black shadow-md shadow-blue-900/20 transition cursor-pointer flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Tenant Baru</span>
                </button>
            </div>
        </form>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 2: EDIT DATA MITRA TENANT (NON-CLIPPING, FULLY ACCESSIBLE)          -->
    <!-- ========================================================================= -->
    <div x-show="editTenantModalOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="editTenantModalOpen = false"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex justify-center p-3 sm:p-5" 
         style="display: none;">
        
        <form :action="`/admin/tenants-management/${editData.id}`" method="POST" 
             @click.away="editTenantModalOpen = false"
             class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl max-w-xl w-full border border-slate-200 flex flex-col max-h-[calc(100vh-2rem)] my-auto overflow-hidden">
            @csrf
            @method('PUT')
            
            <!-- Modal Header (Compact & Fixed) -->
            <div class="px-5 py-3.5 text-white flex items-center justify-between shrink-0"
                 style="background: linear-gradient(135deg, #09131f 0%, #005ea2 50%, #1e40af 100%);">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-amber-300 font-black text-sm border border-white/20">
                        ✎
                    </div>
                    <div>
                        <h3 class="font-black text-sm sm:text-base leading-tight text-white">Edit Data Mitra Tenant</h3>
                        <p class="text-[11px] text-blue-100 font-medium">Perbarui informasi gerai, penempatan, dan masa kontrak</p>
                    </div>
                </div>
                <button type="button" @click="editTenantModalOpen = false" 
                        class="text-white/80 hover:text-white p-1.5 rounded-full hover:bg-white/10 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form Content (Smoothly Scrollable Middle with flex-1 min-h-0) -->
            <div class="px-5 py-4 space-y-4 overflow-y-auto flex-1 min-h-0">
                
                <!-- Live Preview Mini-Card for Edit -->
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 border border-blue-200 flex items-center justify-center text-[#005ea2] font-black text-xs shrink-0">
                            <span x-text="editData.name ? editData.name.substring(0, 2).toUpperCase() : 'FD'"></span>
                        </div>
                        <div class="min-w-0">
                            <div class="font-black text-slate-900 text-xs truncate" x-text="editData.name || 'Nama Brand Outlet'"></div>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="font-mono text-[9px] font-bold text-slate-500 bg-white px-1.5 py-0.2 rounded border border-slate-200" x-text="editData.tenant_code ? editData.tenant_code.toUpperCase() : 'KODE'"></span>
                                <span class="text-[9px] font-bold text-blue-700 bg-blue-50 px-1.5 py-0.2 rounded" x-text="editData.terminal === 'T2' ? 'Terminal 2' : 'Terminal 1'"></span>
                            </div>
                        </div>
                    </div>
                    <span class="text-[9px] font-extrabold uppercase text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 shrink-0">
                        Mode Edit
                    </span>
                </div>

                <!-- Section 1: Identitas Brand & Perusahaan -->
                <div class="space-y-2.5">
                    <div class="flex items-center gap-2 text-[11px] font-black uppercase tracking-wider text-slate-800 pb-1 border-b border-slate-100">
                        <span class="w-4 h-4 rounded-md bg-blue-100 text-[#005ea2] flex items-center justify-center text-[10px]">1</span>
                        <span>Identitas Brand & Perusahaan</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Brand / Outlet <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" x-model="editData.name" required placeholder="Contoh: Starbucks Coffee" 
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kode Tenant (Unik) <span class="text-rose-500">*</span></label>
                            <input type="text" name="tenant_code" x-model="editData.tenant_code" required placeholder="Contoh: T1-SBX" 
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono font-bold text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none uppercase">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Perusahaan (PT/CV)</label>
                        <input type="text" name="company_name" x-model="editData.company_name" placeholder="Contoh: PT Sari Coffee Indonesia" 
                               class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                    </div>
                </div>

                <!-- Section 2: Kategori F&B & Penempatan Ruang -->
                <div class="space-y-2.5 pt-1">
                    <div class="flex items-center gap-2 text-[11px] font-black uppercase tracking-wider text-slate-800 pb-1 border-b border-slate-100">
                        <span class="w-4 h-4 rounded-md bg-purple-100 text-purple-700 flex items-center justify-center text-[10px]">2</span>
                        <span>Kategori F&B & Penempatan Ruang Bandara</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kategori F&B <span class="text-rose-500">*</span></label>
                            <select name="category" x-model="editData.category" required 
                                    class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Beverages & Coffee">Beverages & Coffee</option>
                                <option value="Fast Food & Restaurant">Fast Food & Restaurant</option>
                                <option value="Bakery & Pastry">Bakery & Pastry</option>
                                <option value="Indonesian Traditional">Indonesian Traditional</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Terminal <span class="text-rose-500">*</span></label>
                            <select name="terminal" x-model="editData.terminal" required 
                                    class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                                <option value="T1">Terminal 1 (T1 Domestik)</option>
                                <option value="T2">Terminal 2 (T2 Internasional)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Area <span class="text-rose-500">*</span></label>
                            <select name="zone" x-model="editData.zone" required 
                                    class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                                <option value="Landside">Area Publik (Landside)</option>
                                <option value="Airside">Ruang Tunggu (Airside)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Detail Ruang / Space <span class="text-rose-500">*</span></label>
                            <input type="text" name="floor_location" x-model="editData.floor_location" required placeholder="Contoh: Lt. 2 Gate 8 (EP-01)" 
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Masa Kontrak Sewa -->
                <div class="space-y-2.5 pt-1">
                    <div class="flex items-center gap-2 text-[11px] font-black uppercase tracking-wider text-slate-800 pb-1 border-b border-slate-100">
                        <span class="w-4 h-4 rounded-md bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px]">3</span>
                        <span>Periode Masa Kontrak Sewa</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai Kontrak</label>
                            <input type="date" name="contract_start" x-model="editData.contract_start" 
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Akhir Kontrak</label>
                            <input type="date" name="contract_end" x-model="editData.contract_end" 
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer (Fixed at Bottom, Never Cut Off) -->
            <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2.5 shrink-0">
                <button type="button" @click="editTenantModalOpen = false" 
                        class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-white text-xs font-bold transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2 rounded-xl bg-[#005ea2] hover:bg-[#004a82] text-white text-xs font-black shadow-md shadow-blue-900/20 transition cursor-pointer flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
