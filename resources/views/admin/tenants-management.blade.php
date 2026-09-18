@extends('layouts.admin')

@section('title', 'Manajemen Mitra Tenant')

@section('content')
<div x-data="{ addTenantModalOpen: {{ $errors->has('email') || (old('_method') != 'PUT' && $errors->any()) ? 'true' : 'false' }}, editTenantModalOpen: {{ old('_method') == 'PUT' && $errors->any() ? 'true' : 'false' }}, editData: {}, searchKeyword: '' }">

    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl">
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Quick Stats Khusus Tenant (rounded-2xl cards with hover elevation) -->
    <div class="grid grid-cols-1 mb-8">
        <!-- Stat Card 1 (Royal Blue Gradient) -->
        <div class="bg-gradient-to-br from-[#005ea2] to-blue-800 rounded-2xl p-6 text-white shadow-md shadow-blue-900/10 relative overflow-hidden group hover:shadow-xl hover:shadow-blue-900/20 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
            <div class="absolute -right-4 -bottom-4 opacity-15 text-white group-hover:scale-110 transition-transform duration-500 pointer-events-none">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/>
                </svg>
            </div>
            <p class="text-blue-100 text-xs font-bold uppercase tracking-wider mb-1 relative z-10">Total Mitra Terdaftar</p>
            <h3 class="text-3xl md:text-4xl font-extrabold relative z-10 tracking-tight">{{ $total_tenants }} <span class="text-sm font-medium opacity-80">Gerai Bandara</span></h3>
            <p class="text-xs text-blue-200 mt-2 relative z-10 font-semibold">{{ $active_tenants }} Tenant Sedang Buka</p>
        </div>
    </div>


    <!-- Toolbar: Search, Filter, Action Button (#005ea2 CTA) -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 mb-6 flex flex-col md:flex-row justify-between items-center gap-4 hover:shadow-md transition-shadow">
        <form method="GET" action="{{ url('/admin/tenants-management') }}" class="flex flex-col md:flex-row w-full md:w-auto gap-3">
            <div class="relative w-full md:w-80">
                <input type="text" name="search" value="{{ request('search') }}" oninput="clearTimeout(this.timer); this.timer = setTimeout(() => { this.closest('form').submit(); }, 500)" placeholder="Cari nama brand, PT, atau kode ruang..." 
                       class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none transition-all bg-slate-50 focus:bg-white placeholder-slate-400">
                <button type="submit" class="absolute left-3.5 top-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 hover:text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
            
            <select name="terminal" onchange="this.form.submit()" class="border border-slate-200 rounded-xl px-4 py-2.5 text-xs bg-slate-50 hover:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none transition-colors cursor-pointer text-slate-700 font-bold">
                <option value="">Filter: Semua Terminal</option>
                <option value="1" {{ request('terminal') == '1' ? 'selected' : '' }}>Hanya Terminal 1 (T1)</option>
                <option value="2" {{ request('terminal') == '2' ? 'selected' : '' }}>Hanya Terminal 2 (T2)</option>
            </select>
        </form>
        
        <!-- Highly Prominent Primary CTA Button in #005ea2 -->
        <button @click="addTenantModalOpen = true" 
                class="w-full md:w-auto bg-[#005ea2] hover:bg-[#004a82] active:scale-95 text-white px-6 py-3 rounded-xl text-xs font-extrabold shadow-md shadow-blue-600/25 hover:shadow-lg hover:shadow-blue-600/35 flex items-center justify-center gap-2 transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span>Tambah Mitra Baru</span>
        </button>
    </div>

    <!-- Tabel Tenant Premium (rounded-2xl card container) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 text-[11px] uppercase tracking-wider font-extrabold">
                        <th class="px-6 py-4">Perusahaan / Brand</th>
                        <th class="px-6 py-4">Lokasi & Kategori</th>
                        <th class="px-6 py-4">Masa Kontrak</th>
                        <th class="px-6 py-4">Status Operasional</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100 font-medium">
                    @forelse($tenants as $tenant)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center">
                                <div class="h-12 w-12 flex-shrink-0 bg-blue-50 rounded-2xl border border-blue-100 flex items-center justify-center text-[#005ea2] font-extrabold text-base shadow-xs group-hover:scale-105 transition-transform">
                                    {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="font-extrabold text-slate-900 text-sm group-hover:text-[#005ea2] transition-colors">{{ $tenant->name }}</div>
                                    <div class="text-xs text-slate-500 font-semibold mt-0.5">Kode: {{ $tenant->tenant_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="font-bold text-slate-800">
                                {{ $tenant->terminal == 'T1' ? 'Terminal 1' : ($tenant->terminal == 'T2' ? 'Terminal 2' : 'TBD') }} 
                                <span class="bg-slate-100 text-slate-600 font-bold px-2 py-0.5 rounded-md text-[11px] ml-1">{{ $tenant->floor_location ?? 'TBD' }}</span>
                            </div>
                            <div class="text-[11px] text-[#005ea2] font-extrabold bg-blue-50 border border-blue-100 inline-block px-2.5 py-0.5 rounded-full mt-1.5">
                                Kategori: {{ $tenant->category ?? 'Umum' }}
                            </div>
                            @if($tenant->contract_end)
                                <div class="text-[10px] text-slate-400 mt-1 font-semibold">Kontrak s/d {{ \Carbon\Carbon::parse($tenant->contract_end)->format('d M Y') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-xs font-extrabold text-slate-800">{{ $tenant->products_count }} Produk</div>
                            <div class="text-[11px] text-slate-400 mt-0.5 font-semibold">
                                {{ $tenant->orders_count }} Pesanan Terselesaikan
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @if($tenant->isOpen())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Buka / Beroperasi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200/80 shadow-xs">
                                    Tutup / Tidak Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right space-x-1 flex justify-end items-center">
                            <!-- Edit Button -->
                            <button @click="editData = { 
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
                            }; editTenantModalOpen = true" class="p-2 text-slate-400 hover:text-[#005ea2] hover:bg-blue-50 rounded-xl transition-all" title="Edit Tenant">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            
                            <!-- Toggle Status Form -->
                            <form action="{{ route('admin.tenants.toggle-status', $tenant->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin {{ $tenant->is_active ? 'menangguhkan' : 'mengaktifkan' }} tenant ini?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="p-2 rounded-xl transition-all {{ $tenant->is_active ? 'text-slate-400 hover:text-rose-600 hover:bg-rose-50' : 'text-slate-400 hover:text-emerald-600 hover:bg-emerald-50' }}" title="{{ $tenant->is_active ? 'Tangguhkan (Suspend)' : 'Aktifkan Kembali' }}">
                                    @if($tenant->is_active)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500 font-medium">Belum ada tenant yang terdaftar.</td>
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

    <!-- Modal Form Tambah Mitra Tenant Baru -->
    <div x-show="addTenantModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" 
         style="display: none;">
        
        <form action="{{ route('admin.tenants.store') }}" method="POST" x-show="addTenantModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             @click.away="addTenantModalOpen = false"
             class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden border border-slate-100">
            @csrf
            
            <!-- Modal Header -->
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 via-[#005ea2] to-blue-700 text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-[#8dc63f]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-base leading-tight">Registrasi Mitra Tenant Baru</h3>
                        <p class="text-xs text-blue-100">Tambahkan informasi gerai baru di Terminal Juanda</p>
                    </div>
                </div>
                <button type="button" @click="addTenantModalOpen = false" class="text-white/80 hover:text-white p-1.5 rounded-full hover:bg-white/10 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form Content -->
            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Brand / Outlet <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Starbucks Coffee" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kode Tenant (Unik) <span class="text-rose-500">*</span></label>
                        <input type="text" name="tenant_code" value="{{ old('tenant_code') }}" required placeholder="Contoh: T1-SBX" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none uppercase">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 border-t border-slate-100 pt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Login PIC <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="Contoh: starbucks@flydine.com" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                        <p class="text-[10px] text-slate-500 mt-1">Password bawaan untuk tenant ini adalah: <strong class="text-slate-700">juanda123</strong></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-100 pt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Perusahaan (PT/CV)</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="Contoh: PT Sari Coffee Indonesia" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori F&B <span class="text-rose-500">*</span></label>
                        <select name="category" required class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Beverages & Coffee" {{ old('category') == 'Beverages & Coffee' ? 'selected' : '' }}>Beverages & Coffee</option>
                            <option value="Fast Food & Restaurant" {{ old('category') == 'Fast Food & Restaurant' ? 'selected' : '' }}>Fast Food & Restaurant</option>
                            <option value="Bakery & Pastry" {{ old('category') == 'Bakery & Pastry' ? 'selected' : '' }}>Bakery & Pastry</option>
                            <option value="Snacks & Convenience" {{ old('category') == 'Snacks & Convenience' ? 'selected' : '' }}>Snacks & Convenience</option>
                            <option value="Indonesian Traditional" {{ old('category') == 'Indonesian Traditional' ? 'selected' : '' }}>Indonesian Traditional</option>
                            <option value="Lounge" {{ old('category') == 'Lounge' ? 'selected' : '' }}>Lounge</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-slate-100 pt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Terminal <span class="text-rose-500">*</span></label>
                        <select name="terminal" required class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                            <option value="T1" {{ old('terminal') == 'T1' ? 'selected' : '' }}>Terminal 1 (T1)</option>
                            <option value="T2" {{ old('terminal') == 'T2' ? 'selected' : '' }}>Terminal 2 (T2)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Area <span class="text-rose-500">*</span></label>
                        <select name="zone" required class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                            <option value="Landside" {{ old('zone') == 'Landside' ? 'selected' : '' }}>Area Publik (Landside)</option>
                            <option value="Airside" {{ old('zone') == 'Airside' ? 'selected' : '' }}>Ruang Tunggu (Airside)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Detail Ruang / Space <span class="text-rose-500">*</span></label>
                        <input type="text" name="floor_location" value="{{ old('floor_location') }}" required placeholder="Contoh: Lt. 2 (EP-01)" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-100 pt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mulai Kontrak</label>
                        <input type="date" name="contract_start" value="{{ old('contract_start') }}" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Akhir Kontrak</label>
                        <input type="date" name="contract_end" value="{{ old('contract_end') }}" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" @click="addTenantModalOpen = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-white text-xs font-bold transition-all">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-[#005ea2] hover:bg-[#004a82] text-white text-xs font-extrabold shadow-md shadow-blue-600/25 hover:shadow-lg transition-all">
                    Simpan Tenant Baru
                </button>
            </div>
        </form>
    </div>

    <!-- Modal Form Edit Mitra Tenant -->
    <div x-show="editTenantModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" 
         style="display: none;">
        
        <form :action="`/admin/tenants-management/${editData.id}`" method="POST" x-show="editTenantModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             @click.away="editTenantModalOpen = false"
             class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden border border-slate-100">
            @csrf
            @method('PUT')
            
            <!-- Modal Header -->
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 via-amber-600 to-amber-700 text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-base leading-tight">Edit Data Mitra Tenant</h3>
                        <p class="text-xs text-amber-100">Perbarui informasi gerai di Terminal Juanda</p>
                    </div>
                </div>
                <button type="button" @click="editTenantModalOpen = false" class="text-white/80 hover:text-white p-1.5 rounded-full hover:bg-white/10 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form Content -->
            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Brand / Outlet <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="editData.name" required placeholder="Contoh: Starbucks Coffee" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kode Tenant (Unik) <span class="text-rose-500">*</span></label>
                        <input type="text" name="tenant_code" x-model="editData.tenant_code" required placeholder="Contoh: T1-SBX" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none uppercase">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-100 pt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Perusahaan (PT/CV)</label>
                        <input type="text" name="company_name" x-model="editData.company_name" placeholder="Contoh: PT Sari Coffee Indonesia" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori F&B <span class="text-rose-500">*</span></label>
                        <select name="category" x-model="editData.category" required class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Beverages & Coffee">Beverages & Coffee</option>
                            <option value="Fast Food & Restaurant">Fast Food & Restaurant</option>
                            <option value="Bakery & Pastry">Bakery & Pastry</option>
                            <option value="Snacks & Convenience">Snacks & Convenience</option>
                            <option value="Indonesian Traditional">Indonesian Traditional</option>
                            <option value="Lounge">Lounge</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-slate-100 pt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Terminal <span class="text-rose-500">*</span></label>
                        <select name="terminal" x-model="editData.terminal" required class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none">
                            <option value="T1">Terminal 1 (T1)</option>
                            <option value="T2">Terminal 2 (T2)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Area <span class="text-rose-500">*</span></label>
                        <select name="zone" x-model="editData.zone" required class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none">
                            <option value="Landside">Area Publik (Landside)</option>
                            <option value="Airside">Ruang Tunggu (Airside)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Detail Ruang / Space <span class="text-rose-500">*</span></label>
                        <input type="text" name="floor_location" x-model="editData.floor_location" required placeholder="Contoh: Lt. 2 (EP-01)" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-100 pt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mulai Kontrak</label>
                        <input type="date" name="contract_start" x-model="editData.contract_start" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Akhir Kontrak</label>
                        <input type="date" name="contract_end" x-model="editData.contract_end" class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-800 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none">
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" @click="editTenantModalOpen = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-white text-xs font-bold transition-all">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-extrabold shadow-md shadow-amber-600/25 hover:shadow-lg transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
