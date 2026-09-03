@extends('layouts.admin')

@section('title', 'Pemantauan Fraud & Spam')

@section('content')
<div>
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-sm font-bold flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 bg-rose-50 text-rose-600 border border-rose-100 px-3 py-1 rounded-full text-xs font-bold mb-2">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                <span>Sistem Deteksi Anomali</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Fraud Monitoring</h1>
            <p class="text-sm text-slate-500 font-medium mt-1">Pantau akun pelanggan yang diblokir akibat pola transaksi mencurigakan (Spam/Fraud).</p>
        </div>
    </div>

    <!-- Tabel Monitoring -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300">
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-400 text-[11px] uppercase tracking-wider font-extrabold border-b border-slate-100">
                        <th class="px-6 py-4">Waktu Pemblokiran</th>
                        <th class="px-6 py-4">Pelanggan (No HP)</th>
                        <th class="px-6 py-4 text-center">Pesanan Batal</th>
                        <th class="px-6 py-4">Alasan Pemblokiran</th>
                        <th class="px-6 py-4 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-100 font-medium">
                    
                    @forelse($customers as $c)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-5 align-middle">
                            <div class="font-extrabold text-slate-900 text-sm">
                                {{ \Carbon\Carbon::parse($c->blocked_at)->format('d M Y') }}
                            </div>
                            <div class="text-[11px] text-slate-400 font-semibold mt-1">
                                {{ \Carbon\Carbon::parse($c->blocked_at)->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-5 align-middle">
                            <div class="font-bold text-slate-900 text-sm">{{ $c->name ?? 'Guest' }}</div>
                            <div class="text-[11px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full inline-flex items-center mt-1 border border-rose-100">
                                {{ $c->phone_number }}
                            </div>
                        </td>
                        <td class="px-6 py-5 align-middle text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-extrabold text-sm border border-slate-200">
                                {{ $c->cancelled_orders_count }}
                            </span>
                        </td>
                        <td class="px-6 py-5 align-middle max-w-md">
                            <div class="text-xs leading-relaxed text-slate-600 bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                {{ $c->blocked_reason }}
                            </div>
                        </td>
                        <td class="px-6 py-5 align-middle text-right">
                            <form action="{{ route('admin.fraud.unblock', $c->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membuka blokir akun ini?');">
                                @csrf
                                <button type="submit" class="bg-emerald-50 text-emerald-700 hover:bg-emerald-500 hover:text-white border border-emerald-200 px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-sm">
                                    Buka Blokir (Unblock)
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-emerald-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            Belum ada akun yang terblokir saat ini. Sistem aman!
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
