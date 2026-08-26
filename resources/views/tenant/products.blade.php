@extends('layouts.tenant')

@section('title', 'Katalog Produk Saya')

@section('content')
    <!-- Header Aksi & Pencarian -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <!-- Search Bar -->
        <div class="relative w-full md:w-72">
            <input type="text" placeholder="Cari nama menu..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#005ea2] focus:ring-1 focus:ring-[#005ea2]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </div>
        
        <!-- Tombol Tambah Menu (Diarahkan ke halaman Create) -->
        <a href="{{ url('/tenant/products/create') }}" class="bg-[#8dc63f] hover:bg-green-600 text-white px-5 py-2.5 rounded-lg text-sm font-bold shadow-md flex items-center transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Menu
        </a>
    </div>

    <!-- Tabel Produk -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold">Nama Produk</th>
                        <th class="px-6 py-4 font-semibold">Harga</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <!-- Item 1: Fried Chicken -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-800">
                            Fried Chicken
                            <p class="text-xs text-gray-500 font-normal mt-0.5">Kategori: Makanan Utama</p>
                        </td>
                        <td class="px-6 py-4 font-semibold text-[#005ea2]">Rp 45.000</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-[#005ea2] hover:text-blue-800 font-semibold text-xs mr-2 px-3 py-1.5 rounded hover:bg-blue-50 transition-colors">Edit</button>
                            <button class="text-red-500 hover:text-red-700 font-semibold text-xs px-3 py-1.5 rounded hover:bg-red-50 transition-colors">Hapus</button>
                        </td>
                    </tr>

                    <!-- Item 2: Root Beer -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-800">
                            Root Beer
                            <p class="text-xs text-gray-500 font-normal mt-0.5">Kategori: Minuman</p>
                        </td>
                        <td class="px-6 py-4 font-semibold text-[#005ea2]">Rp 15.000</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-[#005ea2] hover:text-blue-800 font-semibold text-xs mr-2 px-3 py-1.5 rounded hover:bg-blue-50 transition-colors">Edit</button>
                            <button class="text-red-500 hover:text-red-700 font-semibold text-xs px-3 py-1.5 rounded hover:bg-red-50 transition-colors">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection