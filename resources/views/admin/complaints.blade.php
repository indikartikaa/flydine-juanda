@extends('layouts.admin')

@section('title', 'Manajemen Komplain Pelanggan')

@section('content')
    <!-- Filter Status Komplain -->
    <div class="flex space-x-2 mb-6">
        <button class="bg-[#1e293b] text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm">Semua Komplain</button>
        <button class="bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors">Baru / Pending (2)</button>
        <button class="bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors">Sedang Diproses (1)</button>
        <button class="bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors">Selesai</button>
    </div>

    <!-- Tabel Komplain -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold">Kode / Waktu</th>
                        <th class="px-6 py-4 font-semibold">Pelanggan & Pesanan</th>
                        <th class="px-6 py-4 font-semibold">Tenant Terkait</th>
                        <th class="px-6 py-4 font-semibold">Isi Komplain</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-red-600">#CMP-3021</div>
                            <div class="text-xs text-gray-400 mt-0.5">Hari ini, 13:15 WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">Budi Santoso</div>
                            <div class="text-xs text-[#005ea2]">#ORD-98765</div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">
                            A&W Restaurant (T1)
                        </td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs">
                            <p class="text-xs leading-relaxed">"Makanan agak lama disiapkan padahal waktu boarding saya sudah dekat."</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                Pending
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="bg-[#005ea2] hover:bg-blue-800 text-white px-3 py-1.5 rounded text-xs font-semibold transition-colors shadow-sm">Tindak Lanjuti</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection