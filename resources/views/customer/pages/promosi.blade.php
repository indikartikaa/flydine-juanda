@extends('customer.pages.layout')

@section('title', 'Promosi Kolaborasi')

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
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse shadow-[0_0_8px_#f43f5e]"></span>
                <span class="text-[10px] font-extrabold text-slate-700 tracking-widest uppercase">Kolaborasi & Iklan</span>
            </div>
            
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight mb-4 text-slate-900 leading-tight">
                Promosi Kolaborasi
            </h1>
            <p class="text-slate-500 text-sm sm:text-base leading-relaxed font-medium max-w-lg mx-auto md:mx-0">
                Jalin kerja sama co-branding dan promosi strategis bersama FlyDine untuk menjangkau ribuan penumpang potensial setiap hari.
            </p>
        </div>
        
        <div class="hidden md:flex justify-center items-center relative w-48 h-48 animate-float">
            <div class="absolute inset-0 bg-rose-500 blur-3xl opacity-20 rounded-full scale-110"></div>
            
            <div class="relative w-32 h-32 bg-white border border-slate-100 rounded-3xl shadow-[0_16px_32px_rgba(0,0,0,0.05)] flex items-center justify-center rotate-6 hover:rotate-0 transition-transform duration-500 cursor-default">
                <svg class="w-14 h-14 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                </svg>
                
                <div class="absolute -top-3 -right-3 w-8 h-8 bg-rose-500 rounded-xl flex items-center justify-center shadow-lg animate-pulse">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="bg-white p-6 sm:p-10 md:p-12 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 w-full max-w-4xl mx-auto -mt-8 sm:-mt-12 relative z-20 mb-16 hover:-translate-y-1 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500">
    
    <div class="prose prose-slate max-w-none">
        <p class="text-slate-500 font-medium leading-relaxed text-base sm:text-lg mb-10 text-center max-w-2xl mx-auto">
            FlyDine membuka peluang kolaborasi seluas-luasnya bagi instansi perbankan, penyedia dompet digital (e-wallet), maskapai penerbangan, maupun merek ritel yang ingin mengadakan kampanye promosi bersama (co-branding).
        </p>

        <!-- Format Kolaborasi Cards -->
        <h3 class="text-xl font-extrabold text-slate-800 mb-6 flex items-center">
            <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center mr-3 text-rose-500">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
            </div>
            Format Kolaborasi yang Tersedia
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-10">
            <!-- Promo 1 -->
            <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-[1.5rem] p-6 text-white shadow-lg shadow-rose-500/20 hover:scale-[1.02] transition-transform duration-300 relative overflow-hidden group">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4 border border-white/30">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <h4 class="font-extrabold text-white text-lg mb-2">Sponsor Banner</h4>
                <p class="text-rose-100 text-sm font-medium">Penempatan banner eksklusif pada halaman utama direktori FlyDine yang dilihat oleh setiap pelanggan.</p>
            </div>

            <!-- Promo 2 -->
            <div class="bg-slate-50 border border-slate-100 rounded-[1.5rem] p-6 hover:shadow-md hover:border-rose-200 transition-all duration-300 group">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-rose-500 shadow-sm mb-4 border border-slate-100 group-hover:-translate-y-1 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                </div>
                <h4 class="font-bold text-slate-800 text-lg mb-2">Diskon Bank/E-Wallet</h4>
                <p class="text-sm text-slate-500 leading-relaxed font-medium">Program diskon khusus menggunakan metode pembayaran mitra (misal: "Diskon 20% dengan Kartu Debit X").</p>
            </div>

            <!-- Promo 3 -->
            <div class="bg-slate-50 border border-slate-100 rounded-[1.5rem] p-6 hover:shadow-md hover:border-rose-200 transition-all duration-300 group md:col-span-2">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-rose-500 shadow-sm mb-4 border border-slate-100 group-hover:-translate-y-1 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
                </div>
                <h4 class="font-bold text-slate-800 text-lg mb-2">Voucher Bundling Maskapai</h4>
                <p class="text-sm text-slate-500 leading-relaxed font-medium">Pemberian voucher makan atau menu eksklusif FlyDine yang di-bundling langsung dengan pembelian tiket maskapai penerbangan tertentu.</p>
            </div>
        </div>
        
        <!-- CTA Block -->
        <div class="bg-rose-50/50 p-6 md:p-8 rounded-[1.5rem] border border-rose-100 text-center relative overflow-hidden group">
            <div class="relative z-10">
                <h4 class="text-lg font-bold text-rose-900 mb-3">Mari Bekerja Sama</h4>
                <p class="text-sm font-medium text-rose-700 mb-6">Untuk diskusi lebih lanjut mengenai peluang promosi strategis ini, silakan hubungi tim Business Development kami.</p>
                <a href="mailto:bd@flydine.co.id" class="inline-flex items-center space-x-2 bg-white border border-rose-200 text-rose-700 font-extrabold px-6 py-3.5 rounded-xl hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    <span>bd@flydine.co.id</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
