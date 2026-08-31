@extends('layouts.tenant')

@section('title', 'Manajemen Pesanan')

@section('content')
<div x-data="orderManagement()">
    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Daftar Pesanan</h2>
            <p class="text-sm font-medium text-slate-400 mt-1">Kelola pesanan masuk dan pantau status masak.</p>
        </div>
        
        <div class="flex items-center space-x-2">
            <div class="relative">
                <input type="text" x-model="searchQuery" placeholder="Cari ID Pesanan..." class="w-full sm:w-64 pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-[#005ea2] focus:ring-1 focus:ring-[#005ea2] shadow-sm transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 p-2.5 rounded-xl shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Filter Status (Pill Tabs Gojek Style) & Alpine JS state -->
    <div class="mb-8">
        <div class="flex space-x-2 mb-6 overflow-x-auto pb-2 scrollbar-hide">
            <button @click="activeTab = 'semua'" :class="{'bg-[#005ea2] text-white shadow-md shadow-blue-500/20 font-bold border-transparent': activeTab === 'semua', 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 font-medium': activeTab !== 'semua'}" class="px-5 py-2.5 rounded-full text-sm transition-all whitespace-nowrap border">
                Semua Pesanan
            </button>
            <button @click="activeTab = 'menunggu'" :class="{'bg-[#005ea2] text-white shadow-md shadow-blue-500/20 font-bold border-transparent': activeTab === 'menunggu', 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 font-medium': activeTab !== 'menunggu'}" class="px-5 py-2.5 rounded-full text-sm transition-all whitespace-nowrap border flex items-center space-x-2">
                <span>Menunggu</span>
                <span :class="{'bg-white text-[#005ea2]': activeTab === 'menunggu', 'bg-rose-100 text-rose-600': activeTab !== 'menunggu'}" class="px-1.5 py-0.5 rounded-md text-[10px] font-extrabold" x-text="countMenunggu"></span>
            </button>
            <button @click="activeTab = 'diproses'" :class="{'bg-[#005ea2] text-white shadow-md shadow-blue-500/20 font-bold border-transparent': activeTab === 'diproses', 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200 font-medium': activeTab !== 'diproses'}" class="px-5 py-2.5 rounded-full text-sm transition-all whitespace-nowrap border flex items-center space-x-2">
                <span>Sedang Dimasak</span>
                <span :class="{'bg-white text-[#005ea2]': activeTab === 'diproses', 'bg-amber-100 text-amber-600': activeTab !== 'diproses'}" class="px-1.5 py-0.5 rounded-md text-[10px] font-extrabold" x-text="countDiproses"></span>
            </button>
        </div>

        <!-- Tabel Pesanan Lengkap -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 text-[11px] uppercase tracking-wider font-bold">
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">ID Pesanan</th>
                            <th class="px-6 py-4">Detail Penerbangan</th>
                            <th class="px-6 py-4">Item Pesanan</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        <template x-for="order in filteredOrders" :key="order.id">
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 rounded-full" :class="order.status === 'menunggu' ? 'bg-rose-500 animate-pulse' : 'bg-slate-300'"></div>
                                        <span class="font-bold text-slate-700" x-text="formatTime(order.ordered_at)"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-extrabold text-[#005ea2] bg-blue-50 px-2 py-1 rounded-lg border border-blue-100" x-text="order.order_code"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800" x-text="order.flight_number"></div>
                                    <div class="flex items-center mt-1 space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" :class="order.status === 'selesai' ? 'text-emerald-500' : 'text-rose-500'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-[11px] font-bold uppercase tracking-wider" :class="order.status === 'selesai' ? 'text-emerald-600' : 'text-rose-500'" x-text="'Boarding: ' + formatTime(order.boarding_time)"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <ul class="text-slate-600 font-medium space-y-1">
                                        <template x-for="item in order.order_items" :key="item.id">
                                            <li class="flex items-start">
                                                <span class="text-slate-400 mr-2 font-bold" x-text="item.quantity + 'x'"></span>
                                                <span x-text="item.product_name_snapshot"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </td>
                                <td class="px-6 py-4">
                                    <!-- Status Menunggu -->
                                    <span x-show="order.status === 'menunggu'" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                        Menunggu
                                    </span>
                                    
                                    <!-- Status Diproses -->
                                    <span x-show="order.status === 'diproses'" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-600 border border-blue-200 shadow-sm">
                                        <svg class="animate-spin -ml-0.5 mr-1.5 h-3 w-3 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Sedang Dimasak
                                    </span>

                                    <!-- Status Selesai -->
                                    <span x-show="order.status === 'selesai'" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        Selesai
                                    </span>

                                    <!-- Status Ditolak -->
                                    <span x-show="order.status === 'ditolak'" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-600 border border-rose-200 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        Ditolak
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col space-y-2">
                                        <!-- Actions for Menunggu -->
                                        <template x-if="order.status === 'menunggu'">
                                            <div>
                                                <button @click="updateStatus(order.id, 'diproses')" class="w-full bg-[#005ea2] hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 transition-all hover:-translate-y-0.5 mb-2">
                                                    Terima & Masak
                                                </button>
                                                <button @click="updateStatus(order.id, 'ditolak')" class="w-full bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 px-4 py-2 rounded-xl text-xs font-bold transition-all">
                                                    Tolak
                                                </button>
                                            </div>
                                        </template>

                                        <!-- Actions for Diproses -->
                                        <template x-if="order.status === 'diproses'">
                                            <button @click="updateStatus(order.id, 'selesai')" class="w-full bg-[#8dc63f] hover:bg-green-600 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-md shadow-green-500/20 transition-all hover:-translate-y-0.5">
                                                Tandai Selesai
                                            </button>
                                        </template>
                                        
                                        <template x-if="order.status === 'selesai' || order.status === 'ditolak'">
                                            <span class="text-xs text-slate-400 font-medium">Selesai</span>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredOrders.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                Tidak ada pesanan untuk filter ini.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    <!-- Script to handle dynamic fetching and updates -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderManagement', () => ({
                activeTab: 'semua',
                searchQuery: '',
                orders: @json($orders),
                
                get filteredOrders() {
                    let result = this.orders;
                    
                    if (this.searchQuery !== '') {
                        const q = this.searchQuery.toLowerCase();
                        result = result.filter(order => order.order_code.toLowerCase().includes(q));
                    }

                    if (this.activeTab !== 'semua') {
                        result = result.filter(order => order.status === this.activeTab);
                    }
                    
                    return result;
                },
                
                get countMenunggu() {
                    return this.orders.filter(order => order.status === 'menunggu').length;
                },
                
                get countDiproses() {
                    return this.orders.filter(order => order.status === 'diproses').length;
                },

                formatTime(dateString) {
                    if (!dateString) return '--:--';
                    const date = new Date(dateString);
                    return date.getHours().toString().padStart(2, '0') + ':' + date.getMinutes().toString().padStart(2, '0') + ' WIB';
                },

                async updateStatus(orderId, newStatus) {
                    try {
                        const response = await fetch(`/tenant/orders/${orderId}/status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ status: newStatus })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            // Update order in state or remove if completed/rejected
                            const index = this.orders.findIndex(o => o.id === orderId);
                            if (index !== -1) {
                                if (newStatus === 'selesai' || newStatus === 'ditolak') {
                                    // Remove from active list
                                    this.orders.splice(index, 1);
                                } else {
                                    this.orders[index].status = newStatus;
                                }
                            }
                        } else {
                            alert('Gagal mengupdate pesanan: ' + (data.message || 'Unknown error'));
                        }
                    } catch (error) {
                        alert('Terjadi kesalahan jaringan.');
                        console.error(error);
                    }
                }
            }))
        })
    </script>
@endsection