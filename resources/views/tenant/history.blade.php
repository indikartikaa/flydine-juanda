@extends('layouts.tenant')

@section('title', 'Riwayat Pesanan')

@section('content')
<div>
    <!-- Header & Filter -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Riwayat Pesanan</h2>
            <p class="text-sm font-medium text-slate-400 mt-1">Lihat pesanan yang sudah selesai, dibatalkan, atau ditolak.</p>
        </div>
        
        <!-- Filter Tanggal -->
        <form action="{{ route('tenant.orders.history') }}" method="GET" class="flex items-center">
            <div class="relative">
                <select name="date_filter" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200 text-slate-600 font-semibold text-sm rounded-xl pl-4 pr-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] shadow-sm cursor-pointer hover:bg-slate-50 transition-colors">
                    <option value="all" {{ request('date_filter') == 'all' ? 'selected' : '' }}>Semua Waktu</option>
                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="yesterday" {{ request('date_filter') == 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                    <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Riwayat Pesanan -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 text-[11px] uppercase tracking-wider font-bold">
                        <th class="px-6 py-4">Waktu Pesan</th>
                        <th class="px-6 py-4">Waktu Selesai</th>
                        <th class="px-6 py-4">ID Pesanan</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-700">
                                    {{ \Carbon\Carbon::parse($order->ordered_at)->format('d M Y, H:i') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-500">
                                    {{ $order->completed_at ? \Carbon\Carbon::parse($order->completed_at)->format('d M Y, H:i') : '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-extrabold text-[#005ea2] bg-blue-50 px-2 py-1 rounded-lg border border-blue-100">
                                    {{ $order->order_code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $order->customer_name }}</div>
                                @if($order->flight_number)
                                    <div class="text-[11px] text-slate-500 mt-1">{{ $order->flight_number }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-emerald-600">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($order->status === 'selesai')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        Selesai
                                    </span>
                                @elseif($order->status === 'ditolak')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-600 border border-rose-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        Dibatalkan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                Belum ada riwayat pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
