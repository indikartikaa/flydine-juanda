@extends('layouts.admin')

@section('title', 'Manajemen Komplain Pelanggan')

@section('content')
<div x-data="{ 
    modalOpen: false, 
    selectedTicket: '',
    selectedCustomer: '',
    selectedTenant: '',
    selectedIssue: '',
    formAction: ''
}">

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-sm font-bold flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Header: Judul & Ringkasan Singkat -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 bg-rose-50 text-rose-600 border border-rose-100 px-3 py-1 rounded-full text-xs font-bold mb-2">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                <span>Pusat Resolusi Layanan Terminal</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Komplain Pelanggan</h1>
            <p class="text-sm text-slate-500 font-medium mt-1">Pantau dan tindak lanjuti kendala pesanan penumpang bandara secara cepat & tepat.</p>
        </div>
        
        <div class="flex items-center space-x-3 bg-white p-2 rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-3.5 py-2 bg-rose-50 text-rose-700 rounded-xl text-xs font-extrabold border border-rose-100 flex items-center shadow-xs">
                <span class="w-2 h-2 rounded-full bg-rose-500 mr-2 animate-pulse"></span>
                {{ $complaints->where('status', 'open')->count() }} Menunggu
            </div>
            <div class="px-3.5 py-2 bg-blue-50 text-[#005ea2] rounded-xl text-xs font-extrabold border border-blue-100 shadow-xs">
                {{ $complaints->where('status', 'in_progress')->count() }} Diproses
            </div>
            <div class="px-3.5 py-2 bg-emerald-50 text-emerald-700 rounded-xl text-xs font-extrabold border border-emerald-100 shadow-xs">
                {{ $complaints->whereIn('status', ['resolved', 'closed'])->count() }} Selesai
            </div>
        </div>
    </div>

    <!-- Tabel Komplain Premium -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
        
        <!-- Toolbar Tabel -->
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="relative w-full sm:w-80">
                <input type="text" placeholder="Pencarian belum aktif (MVP)..." disabled
                       class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium bg-slate-100 placeholder-slate-400 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- Isi Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-400 text-[11px] uppercase tracking-wider font-extrabold border-b border-slate-100">
                        <th class="px-6 py-4">Tiket & Waktu</th>
                        <th class="px-6 py-4">Detail Pelapor</th>
                        <th class="px-6 py-4">Keluhan Pelanggan</th>
                        <th class="px-6 py-4 text-center">Status Resolusi</th>
                        <th class="px-6 py-4 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100 font-medium">
                    
                    @forelse($complaints as $c)
                    <tr class="hover:bg-slate-50/80 transition-colors group relative">
                        <td class="px-6 py-5 align-top">
                            @if($c->status == 'open')
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-rose-500 rounded-r-sm"></div>
                            @endif
                            <div class="font-extrabold text-slate-900 text-sm flex items-center">
                                {{ $c->complaint_code }}
                                @if($c->status == 'open')
                                <span class="ml-2 bg-rose-100 text-rose-700 text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase tracking-wider">Urgent</span>
                                @endif
                            </div>
                            <div class="text-[11px] text-slate-400 mt-1.5 flex items-center font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $c->created_at->format('d M Y, H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-5 align-top">
                            <div class="font-bold text-slate-900 text-sm">{{ $c->reporter_name }}</div>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $c->reporter_contact) }}" target="_blank" class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full inline-flex items-center mt-1 border border-emerald-100 hover:bg-emerald-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                {{ $c->reporter_contact }}
                            </a>
                            @if($c->order)
                                <div class="text-[10px] text-slate-500 mt-2 font-semibold bg-slate-50 p-1.5 rounded-lg border border-slate-100">
                                    <div class="text-[#005ea2] font-bold">{{ $c->order->order_code }}</div>
                                    Tenant: {{ $c->order->tenant->name ?? '-' }}
                                </div>
                            @else
                                <div class="text-[10px] text-slate-500 mt-2 font-semibold bg-slate-50 p-1.5 rounded-lg border border-slate-100">
                                    Komplain Umum
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-5 align-top max-w-md">
                            <div class="font-bold text-slate-900 mb-1.5 text-xs">{{ ucwords(str_replace('_', ' ', $c->category)) }}</div>
                            <div class="text-xs leading-relaxed text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 group-hover:bg-white transition-colors">
                                "{{ $c->description }}"
                            </div>
                        </td>
                        <td class="px-6 py-5 align-top text-center">
                            @if($c->status == 'open')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200/80 shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Menunggu Respon
                                </span>
                            @elseif($c->status == 'in_progress')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-extrabold bg-blue-50 text-[#005ea2] border border-blue-200/80 shadow-xs">
                                    <svg class="animate-spin h-3 w-3 text-[#005ea2]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Diproses
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200 shadow-xs">
                                    Selesai / Ditutup
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 align-top text-right">
                            @if($c->status == 'open')
                                <button @click="modalOpen = true; selectedTicket = '{{ $c->complaint_code }}'; selectedCustomer = '{{ addslashes($c->reporter_name) }}'; selectedTenant = '{{ addslashes($c->order ? ($c->order->tenant->name ?? '-') : 'Komplain Umum') }}'; selectedIssue = '{{ addslashes($c->description) }}'; formAction = '{{ route('admin.complaints.status', $c->id) }}'"
                                        class="bg-[#005ea2] hover:bg-[#004a82] active:scale-95 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 shadow-md shadow-blue-600/20 flex items-center justify-center gap-1.5 ml-auto">
                                    <span>Tindak Lanjuti</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                            @else
                                <button @click="modalOpen = true; selectedTicket = '{{ $c->complaint_code }}'; selectedCustomer = '{{ addslashes($c->reporter_name) }}'; selectedTenant = '{{ addslashes($c->order ? ($c->order->tenant->name ?? '-') : 'Komplain Umum') }}'; selectedIssue = '{{ addslashes($c->description) }}'; formAction = '{{ route('admin.complaints.status', $c->id) }}'"
                                        class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center ml-auto shadow-xs">
                                    Ubah Status
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500 font-medium">Belum ada data komplain dari pelanggan.</td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        
        <!-- Footer / Paginasi -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <span class="text-xs text-slate-500 font-semibold">Menampilkan total <span class="font-extrabold text-slate-800">{{ $complaints->count() }}</span> komplain.</span>
        </div>
    </div>

    <!-- Modal Interaktif Tindak Lanjuti Komplain -->
    <div x-show="modalOpen" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" 
         style="display: none;">
        
        <div x-show="modalOpen" @click.away="modalOpen = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-100">
            
            <!-- Modal Header -->
            <div class="px-6 py-5 bg-gradient-to-r from-slate-900 to-[#005ea2] text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-base leading-tight">Resolusi Tiket <span x-text="selectedTicket"></span></h3>
                        <p class="text-xs text-blue-100" x-text="selectedTenant"></p>
                    </div>
                </div>
                <button @click="modalOpen = false" class="text-white/80 hover:text-white p-1.5 rounded-full hover:bg-white/10 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content Form -->
            <form :action="formAction" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Pelapor & Kendala</div>
                        <div class="font-bold text-slate-800 text-sm" x-text="selectedCustomer"></div>
                        <div class="text-xs font-semibold text-rose-600 mt-0.5 line-clamp-2" x-text="selectedIssue"></div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Update Status Tiket</label>
                        <select name="status" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:border-[#005ea2] focus:ring-2 focus:ring-[#005ea2]/20 outline-none">
                            <option value="in_progress">Sedang Diproses (Investigasi)</option>
                            <option value="resolved">Selesai (Berikan Kompensasi/Penjelasan)</option>
                            <option value="closed">Ditolak / Tidak Valid / Ditutup</option>
                        </select>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-white text-xs font-bold transition-all">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-[#005ea2] hover:bg-[#004a82] text-white text-xs font-extrabold shadow-md shadow-blue-600/20 hover:shadow-lg transition-all">
                        Simpan & Update
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection