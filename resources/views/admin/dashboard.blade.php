@extends('layouts.admin')

@section('title', 'Ringkasan Sistem')

@section('content')
    <!-- Sapaan Admin -->
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-gray-800">Selamat Datang, Admin Ops! 👋</h1>
        <p class="text-gray-500 text-sm mt-1">Berikut adalah ringkasan performa sistem FlyDine Juanda hari ini.</p>
    </div>

    <!-- Statistic Cards (Premium Design) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Card 1: Total Tenant (Gradient Style) -->
        <div class="bg-gradient-to-br from-[#005ea2] to-blue-800 rounded-xl p-6 text-white shadow-md relative overflow-hidden group hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
            <!-- Dekorasi SVG Background -->
            <svg class="absolute -right-4 -bottom-4 opacity-20 w-28 h-28 group-hover:scale-110 transition-transform duration-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" /></svg>
            <p class="text-blue-100 text-sm font-medium mb-1 relative z-10">Total Mitra Terdaftar</p>
            <h3 class="text-4xl font-extrabold relative z-10">18 <span class="text-base font-medium opacity-80">Tenant</span></h3>
        </div>

        <!-- Card 2: Tenant Aktif -->
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex justify-between items-center group hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Mitra Beroperasi</p>
                <h3 class="text-3xl font-extrabold text-gray-800">16</h3>
            </div>
            <div class="h-14 w-14 bg-green-50 text-[#8dc63f] rounded-full flex items-center justify-center group-hover:bg-[#8dc63f] group-hover:text-white transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>

        <!-- Card 3: Komplain Baru -->
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex justify-between items-center group hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 cursor-pointer relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-yellow-400"></div>
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Menunggu Respon</p>
                <h3 class="text-3xl font-extrabold text-gray-800">3 <span class="text-sm font-medium text-red-500 animate-pulse">Baru!</span></h3>
            </div>
            <div class="h-14 w-14 bg-yellow-50 text-yellow-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
            </div>
        </div>

        <!-- Card 4: Transaksi -->
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex justify-between items-center group hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Pesanan Hari Ini</p>
                <h3 class="text-3xl font-extrabold text-gray-800">142</h3>
            </div>
            <div class="h-14 w-14 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </div>
        </div>

    </div>

    <!-- Aktivitas Terbaru (Timeline Style) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h2 class="font-bold text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                Audit Trail & Aktivitas Sistem
            </h2>
            <button class="text-sm font-semibold text-[#005ea2] hover:text-blue-800 transition-colors">Lihat Semua</button>
        </div>
        
        <div class="p-6">
            <div class="space-y-6 relative before:absolute before:inset-0 before:ml-10 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gray-200">
                
                <!-- Log 1 -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-blue-100 text-blue-600 font-bold text-xs shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 relative z-10">
                        SYS
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-4 rounded-lg border border-gray-100 shadow-sm group-hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-gray-800 text-sm">Aktivasi Tenant</span>
                            <span class="text-xs font-medium text-gray-400">14:30 WIB</span>
                        </div>
                        <p class="text-sm text-gray-600">Tenant <span class="font-bold text-[#005ea2]">A&W Terminal 1</span> baru saja diaktifkan dan siap menerima pesanan.</p>
                    </div>
                </div>

                <!-- Log 2 -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-yellow-100 text-yellow-600 font-bold text-xs shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 relative z-10">
                        CMP
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-4 rounded-lg border border-yellow-100 shadow-sm group-hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-gray-800 text-sm">Komplain Diterima</span>
                            <span class="text-xs font-medium text-gray-400">13:15 WIB</span>
                        </div>
                        <p class="text-sm text-gray-600">Komplain baru dilaporkan oleh pelanggan untuk pesanan <span class="font-bold text-yellow-600">#ORD-98760</span>.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection