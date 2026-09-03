@extends('customer.pages.layout')

@section('title', 'Daftar Menjadi Tenant')

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
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                <span class="text-[10px] font-extrabold text-slate-700 tracking-widest uppercase">Kemitraan Bisnis</span>
            </div>
            
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight mb-4 text-slate-900 leading-tight">
                Daftar Tenant
            </h1>
            <p class="text-slate-500 text-sm sm:text-base leading-relaxed font-medium max-w-lg mx-auto md:mx-0">
                Bergabunglah bersama FlyDine dan tingkatkan omset bisnis kuliner Anda dengan menjangkau ribuan penumpang di Bandara Juanda.
            </p>
        </div>
        
        <div class="hidden md:flex justify-center items-center relative w-48 h-48 animate-float">
            <div class="absolute inset-0 bg-emerald-500 blur-3xl opacity-20 rounded-full scale-110"></div>
            
            <div class="relative w-32 h-32 bg-white border border-slate-100 rounded-3xl shadow-[0_16px_32px_rgba(0,0,0,0.05)] flex items-center justify-center rotate-6 hover:rotate-0 transition-transform duration-500 cursor-default">
                <svg class="w-14 h-14 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                
                <div class="absolute -top-3 -right-3 w-8 h-8 bg-[#005ea2] rounded-xl flex items-center justify-center shadow-lg animate-pulse">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="bg-white p-6 sm:p-10 md:p-12 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 w-full max-w-4xl mx-auto -mt-8 sm:-mt-12 relative z-20 mb-16 hover:-translate-y-1 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500">
    
    <div class="prose prose-slate max-w-none">
        <p class="text-slate-500 font-medium leading-relaxed text-base sm:text-lg mb-10 text-center max-w-2xl mx-auto">
            Perluas jangkauan bisnis kuliner Anda di Bandara Internasional Juanda. Dengan bergabung bersama FlyDine, tenant Anda akan mendapatkan visibilitas eksklusif kepada ribuan penumpang setiap harinya yang sedang bersantai di <em>boarding lounge</em>.
        </p>
        
        <h3 class="text-xl font-extrabold text-slate-800 mb-6 flex items-center justify-center">
            <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center mr-3 text-emerald-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            Keuntungan Bergabung
        </h3>

        <!-- Fitur Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-12">
            <!-- Fitur 1 -->
            <div class="bg-slate-50 border border-slate-100 rounded-[1.5rem] p-6 hover:shadow-md hover:border-emerald-200 transition-all duration-300 text-center group">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-emerald-500 shadow-sm mx-auto mb-4 group-hover:-translate-y-1 transition-transform">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </div>
                <h4 class="font-bold text-slate-800 mb-2">Sistem Khusus Dapur</h4>
                <p class="text-xs text-slate-500 leading-relaxed font-medium">Sistem manajemen pesanan (Order Management System) khusus yang mudah digunakan oleh staf dapur.</p>
            </div>

            <!-- Fitur 2 -->
            <div class="bg-slate-50 border border-slate-100 rounded-[1.5rem] p-6 hover:shadow-md hover:border-emerald-200 transition-all duration-300 text-center group">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-emerald-500 shadow-sm mx-auto mb-4 group-hover:-translate-y-1 transition-transform">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                </div>
                <h4 class="font-bold text-slate-800 mb-2">Peningkatan Omset</h4>
                <p class="text-xs text-slate-500 leading-relaxed font-medium">Tingkatkan volume penjualan dari penumpang yang enggan berjalan jauh meninggalkan ruang tunggu.</p>
            </div>

            <!-- Fitur 3 -->
            <div class="bg-slate-50 border border-slate-100 rounded-[1.5rem] p-6 hover:shadow-md hover:border-emerald-200 transition-all duration-300 text-center group">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-emerald-500 shadow-sm mx-auto mb-4 group-hover:-translate-y-1 transition-transform">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
                <h4 class="font-bold text-slate-800 mb-2">Laporan Analitik</h4>
                <p class="text-xs text-slate-500 leading-relaxed font-medium">Dapatkan laporan penjualan harian dan analitik produk terlaris langsung dari dasbor Anda.</p>
            </div>
        </div>
        
        <!-- CTA Block -->
        <div class="bg-emerald-50/50 p-6 md:p-8 rounded-[1.5rem] border border-emerald-100 text-center relative overflow-hidden group">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-emerald-200 rounded-full opacity-30 blur-xl group-hover:scale-110 transition-transform duration-700"></div>
            <div class="relative z-10">
                <h4 class="text-lg font-bold text-emerald-900 mb-3">Siap Bermitra dengan Kami?</h4>
                <p class="text-sm font-medium text-emerald-700 mb-6">Kirimkan proposal kemitraan bisnis Anda beserta profil restoran ke alamat email manajemen kami.</p>
                <a href="mailto:partnership@flydine.co.id" class="inline-flex items-center space-x-2 bg-white border border-emerald-200 text-emerald-700 font-extrabold px-6 py-3.5 rounded-xl hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    <span>partnership@flydine.co.id</span>
                </a>
                <p class="text-xs font-medium text-emerald-600/70 mt-4">Atau kunjungi kantor manajemen komersial Terminal 1 Juanda.</p>
            </div>
        </div>
    </div>
</div>
@endsection
