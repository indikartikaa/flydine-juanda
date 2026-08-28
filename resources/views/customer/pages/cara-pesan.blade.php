@extends('customer.pages.layout')

@section('title', 'Cara Pesan')

@section('page_header')
    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-4">Cara Pesan di FlyDine</h1>
    <p class="text-blue-100 text-lg max-w-2xl leading-relaxed">
        Nikmati hidangan favorit Anda di Bandara Internasional Juanda tanpa perlu antre panjang. Berikut adalah langkah mudah memesan makanan.
    </p>
@endsection

@section('content')
<div class="bg-white p-6 sm:p-10 rounded-3xl shadow-lg border border-slate-100 w-full">
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <!-- Step 1 -->
        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-[#005ea2] text-white font-black text-2xl rounded-2xl flex items-center justify-center mb-5 shadow-sm shadow-blue-500/30">
                    1
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Pilih Lokasi Anda</h3>
                <p class="text-slate-600 leading-relaxed text-sm">
                    Pada halaman utama, pastikan Anda memilih Terminal (contoh: Terminal 1) dan area keberangkatan Anda.
                </p>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-[#005ea2] text-white font-black text-2xl rounded-2xl flex items-center justify-center mb-5 shadow-sm shadow-blue-500/30">
                    2
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Pilih Restoran & Menu</h3>
                <p class="text-slate-600 leading-relaxed text-sm">
                    Jelajahi direktori restoran kami. Pilih menu favorit Anda dan klik "Tambah ke Keranjang". 
                </p>
                <div class="mt-4 bg-amber-50 border border-amber-200 text-amber-800 text-xs px-3 py-2 rounded-lg font-medium">
                    Catatan: Anda hanya dapat memesan dari satu restoran dalam satu transaksi.
                </div>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-[#005ea2] text-white font-black text-2xl rounded-2xl flex items-center justify-center mb-5 shadow-sm shadow-blue-500/30">
                    3
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Isi Data Penerbangan</h3>
                <p class="text-slate-600 leading-relaxed text-sm">
                    Masuk ke halaman Keranjang. Masukkan Nama, Nomor Penerbangan (misal: JT-012), Gate, dan Waktu Boarding Anda. Sistem kami akan memastikan pesanan selesai sebelum waktu boarding.
                </p>
            </div>
        </div>

        <!-- Step 4 -->
        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-[#005ea2] text-white font-black text-2xl rounded-2xl flex items-center justify-center mb-5 shadow-sm shadow-blue-500/30">
                    4
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Konfirmasi & Bayar</h3>
                <p class="text-slate-600 leading-relaxed text-sm">
                    Pilih metode pembayaran (QRIS/Transfer Bank) dan selesaikan transaksi Anda.
                </p>
            </div>
        </div>

        <!-- Step 5 -->
        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden group md:col-span-2 lg:col-span-1">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-[#8dc63f] text-white font-black text-2xl rounded-2xl flex items-center justify-center mb-5 shadow-sm shadow-green-500/30">
                    5
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Lacak & Ambil</h3>
                <p class="text-slate-600 leading-relaxed text-sm">
                    Pantau status pesanan secara real-time. Jika status menunjukkan "Siap Diambil", silakan menuju konter restoran untuk mengambil pesanan Anda dengan menunjukkan ID Pesanan.
                </p>
            </div>
        </div>
    </div>
    
    <div class="mt-12 text-center">
        <a href="{{ route('customer.menu') }}" class="inline-flex items-center justify-center space-x-2 font-bold text-white bg-[#005ea2] px-8 py-4 rounded-full hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-1 transform duration-300">
            <span>Mulai Pesan Sekarang</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </a>
    </div>
</div>
@endsection
