@extends('layouts.admin')

@section('title', 'Manajemen Tenant & Mitra Usaha')

@section('content')
    <!-- Header Aksi & Filter -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex items-center space-x-2 w-full md:w-auto">
            <div class="relative w-full md:w-72">
                <input type="text" placeholder="Cari nama tenant / brand..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#005ea2] focus:ring-1 focus:ring-[#005ea2]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white outline-none focus:border-[#005ea2]">
                <option>Semua Terminal</option>
                <option>Terminal 1</option>
                <option>Terminal 2</option>
            </select>
        </div>
        
        <button class="w-full md:w-auto bg-[#8dc63f] hover:bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-sm flex items-center justify-center transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Mitra Tenant
        </button>
    </div>

    <!-- Tabel Tenant (Mengacu pada data database mitra Juanda) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold">No / Ruang</th>
                        <th class="px-6 py-4 font-semibold">Perusahaan / Brand</th>
                        <th class="px-6 py-4 font-semibold">Jenis Usaha</th>
                        <th class="px-6 py-4 font-semibold">Masa Kontrak</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <!-- Tenant 1 -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">EP-01-01</div>
                            <div class="text-xs text-gray-500">Terminal 1</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-[#005ea2]">Beard papa's</div>
                            <div class="text-xs text-gray-500">PT Sebastian Citra Indonesia[cite: 2]</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded text-xs font-semibold">F&B (Bakery)[cite: 2]</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600">
                            02.01.2025 - 31.12.2025[cite: 2]
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">Aktif</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-[#005ea2] hover:text-blue-800 font-semibold text-xs mr-2 px-3 py-1.5 rounded hover:bg-blue-50 transition-colors">Edit</button>
                            <button class="text-red-500 hover:text-red-700 font-semibold text-xs px-3 py-1.5 rounded hover:bg-red-50 transition-colors">Blokir</button>
                        </td>
                    </tr>

                    <!-- Tenant 2 -->
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">EP-01-02</div>
                            <div class="text-xs text-gray-500">Terminal 1</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-[#005ea2]">A&W Restaurant</div>
                            <div class="text-xs text-gray-500">PT Fast Food Indonesia</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-orange-50 text-orange-700 px-2.5 py-1 rounded text-xs font-semibold">F&B (Fast Food)</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600">
                            01.03.2025 - 28.02.2026
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">Aktif</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-[#005ea2] hover:text-blue-800 font-semibold text-xs mr-2 px-3 py-1.5 rounded hover:bg-blue-50 transition-colors">Edit</button>
                            <button class="text-red-500 hover:text-red-700 font-semibold text-xs px-3 py-1.5 rounded hover:bg-red-50 transition-colors">Blokir</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection