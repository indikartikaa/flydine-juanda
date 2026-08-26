@extends('layouts.tenant')

@section('title', 'Manajemen Pesanan')

@section('content')
    <!-- Filter Status -->
    <div class="flex space-x-2 mb-6">
        <button class="bg-[#005ea2] text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm">Semua Pesanan</button>
        <button class="bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors">Menunggu (2)</button>
        <button class="bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors">Sedang Dimasak (4)</button>
        <button class="bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors">Selesai</button>
    </div>

    <!-- Tabel Pesanan Lengkap -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-medium">Waktu</th>
                        <th class="px-6 py-4 font-medium">ID Pesanan</th>
                        <th class="px-6 py-4 font-medium">Detail Penerbangan</th>
                        <th class="px-6 py-4 font-medium">Item Pesanan</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500">13:10 WIB</td>
                        <td class="px-6 py-4 font-bold text-[#005ea2]">#ORD-98765</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">JT-012</div>
                            <div class="text-xs text-red-500 font-medium">Boarding: 14:45</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            2x Paket Fried Chicken<br>
                            1x Root Beer
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu</span>
                        </td>
                        <td class="px-6 py-4 text-center space-y-2">
                            <button class="w-full bg-[#005ea2] hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs font-semibold transition-colors">Terima & Masak</button>
                            <button class="w-full bg-white border border-red-500 text-red-500 hover:bg-red-50 px-3 py-1.5 rounded text-xs font-semibold transition-colors">Tolak</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection