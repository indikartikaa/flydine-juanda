@extends('layouts.admin')

@section('title', 'Ringkasan Sistem')

@section('content')
    <!-- Statistic Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center border-b-4 border-blue-500">
            <div class="rounded-full p-3 bg-blue-50 text-blue-600 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Total Tenant</p>
                <h3 class="text-2xl font-extrabold text-gray-800">18</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center border-b-4 border-[#8dc63f]">
            <div class="rounded-full p-3 bg-green-50 text-[#8dc63f] mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Tenant Aktif</p>
                <h3 class="text-2xl font-extrabold text-gray-800">16</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center border-b-4 border-yellow-500">
            <div class="rounded-full p-3 bg-yellow-50 text-yellow-600 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Komplain Baru</p>
                <h3 class="text-2xl font-extrabold text-gray-800">3</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center border-b-4 border-purple-500">
            <div class="rounded-full p-3 bg-purple-50 text-purple-600 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Transaksi Harian</p>
                <h3 class="text-2xl font-extrabold text-gray-800">142</h3>
            </div>
        </div>

    </div>

    <!-- Aktivitas Terbaru -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h2 class="font-bold text-gray-800">Aktivitas Sistem Terbaru</h2>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                <!-- Log 1 -->
                <div class="flex">
                    <div class="flex-shrink-0 mr-4">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center border border-blue-200">
                            <span class="text-blue-600 text-xs font-bold">SYS</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-800">Tenant <span class="font-bold">A&W Terminal 1</span> baru saja diaktifkan.</p>
                        <p class="text-xs text-gray-500 mt-0.5">Hari ini, 14:30 WIB</p>
                    </div>
                </div>
                <!-- Log 2 -->
                <div class="flex">
                    <div class="flex-shrink-0 mr-4">
                        <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center border border-yellow-200">
                            <span class="text-yellow-600 text-xs font-bold">CMP</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-800">Komplain baru dilaporkan untuk pesanan <span class="font-bold text-[#005ea2]">#ORD-98760</span>.</p>
                        <p class="text-xs text-gray-500 mt-0.5">Hari ini, 13:15 WIB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection