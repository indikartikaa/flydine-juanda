@extends('customer.pages.layout')

@section('title', 'Cara Pesan')

@section('page_header')
    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
    </style>

    <div class="flex flex-col md:flex-row items-center justify-between gap-8 relative z-10 w-full max-w-5xl mx-auto">
        <div class="text-center md:text-left md:max-w-xl">
            <div class="inline-flex items-center space-x-2 bg-white border border-slate-200 px-4 py-1.5 rounded-full mb-6 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse shadow-[0_0_8px_#f59e0b]"></span>
                <span class="text-[10px] font-extrabold text-slate-700 tracking-widest uppercase">Panduan Pemesanan</span>
            </div>
            
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight mb-4 text-slate-900 leading-tight">
                Cara Pesan
            </h1>
            <p class="text-slate-500 text-sm sm:text-base leading-relaxed font-medium max-w-lg mx-auto md:mx-0">
                Nikmati hidangan favorit Anda di Bandara Internasional Juanda tanpa perlu antre panjang. Ikuti langkah sederhana ini.
            </p>
        </div>
        
        <div class="hidden md:flex justify-center items-center relative w-48 h-48 animate-float">
            <div class="absolute inset-0 bg-amber-500 blur-3xl opacity-20 rounded-full scale-110"></div>
            
            <div class="relative w-32 h-32 bg-white border border-slate-100 rounded-3xl shadow-[0_16px_32px_rgba(0,0,0,0.05)] flex items-center justify-center rotate-6 hover:rotate-0 transition-transform duration-500 cursor-default">
                <svg class="w-14 h-14 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                
                <div class="absolute -top-3 -right-3 w-8 h-8 bg-[#005ea2] rounded-xl flex items-center justify-center shadow-lg animate-pulse">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="bg-white p-6 sm:p-10 md:p-12 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 w-full max-w-4xl mx-auto -mt-8 sm:-mt-12 relative z-20 mb-16 hover:-translate-y-1 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-8">
        <!-- Step 1 -->
        <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-[#005ea2]/5 hover:-translate-y-2 transition-all duration-300 group relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="w-12 h-12 bg-gradient-to-br from-[#005ea2] to-blue-600 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-md shadow-blue-500/20 mb-5 relative z-10">1</div>
            <h4 class="text-lg font-extrabold text-slate-800 mb-2 group-hover:text-[#005ea2] transition-colors relative z-10">Pilih Lokasi</h4>
            <p class="text-sm text-slate-500 leading-relaxed font-medium relative z-10">Pilih Terminal dan area keberangkatan Anda (contoh: Terminal 1) untuk menemukan restoran yang relevan.</p>
        </div>

        <!-- Step 2 -->
        <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-[#005ea2]/5 hover:-translate-y-2 transition-all duration-300 group relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="w-12 h-12 bg-gradient-to-br from-[#005ea2] to-blue-600 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-md shadow-blue-500/20 mb-5 relative z-10">2</div>
            <h4 class="text-lg font-extrabold text-slate-800 mb-2 group-hover:text-[#005ea2] transition-colors relative z-10">Tentukan Menu</h4>
            <p class="text-sm text-slate-500 leading-relaxed font-medium mb-3 relative z-10">Jelajahi direktori dan pilih hidangan favorit Anda.</p>
            <div class="inline-flex items-center space-x-1.5 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-100 relative z-10">
                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                <span class="text-[9px] text-amber-700 font-extrabold uppercase tracking-wide">Maks 1 Restoran</span>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-[#005ea2]/5 hover:-translate-y-2 transition-all duration-300 group relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="w-12 h-12 bg-gradient-to-br from-[#005ea2] to-blue-600 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-md shadow-blue-500/20 mb-5 relative z-10">3</div>
            <h4 class="text-lg font-extrabold text-slate-800 mb-2 group-hover:text-[#005ea2] transition-colors relative z-10">Data Penerbangan</h4>
            <p class="text-sm text-slate-500 leading-relaxed font-medium relative z-10">Masukkan Nomor Penerbangan dan Waktu Boarding agar pesanan Anda selesai tepat waktu.</p>
        </div>

        <!-- Step 4 -->
        <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-[#8dc63f]/30 hover:border-[#8dc63f] hover:shadow-xl hover:shadow-green-500/10 hover:-translate-y-2 transition-all duration-300 group relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-green-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="w-12 h-12 bg-gradient-to-br from-[#8dc63f] to-green-500 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-md shadow-green-500/30 mb-5 relative z-10 animate-pulse">4</div>
            <h4 class="text-lg font-extrabold text-slate-800 mb-2 group-hover:text-[#8dc63f] transition-colors relative z-10">Lacak & Ambil</h4>
            <p class="text-sm text-slate-500 leading-relaxed font-medium relative z-10">Lakukan pembayaran, pantau pesanan real-time, dan ambil di konter restoran.</p>
        </div>
    </div>
    
    <div class="mt-12 text-center relative z-10">
        <a href="{{ route('customer.menu') }}" class="inline-flex items-center justify-center space-x-2 font-bold text-white bg-gradient-to-r from-[#005ea2] to-blue-600 px-8 py-4 rounded-2xl hover:from-[#004a82] hover:to-blue-700 transition-all shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-1 duration-300">
            <span>Mulai Pesan Sekarang</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </a>
    </div>
</div>
@endsection
