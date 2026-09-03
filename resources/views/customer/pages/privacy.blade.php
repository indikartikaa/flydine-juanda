@extends('customer.pages.layout')

@section('title', 'Kebijakan Privasi')

@section('page_header')
    <!-- CSS Khusus untuk Animasi Ngambang (Floating) yang Halus -->
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
        <!-- Bagian Teks Header -->
        <div class="text-center md:text-left md:max-w-xl">

            
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight mb-4 text-slate-900 leading-tight">
                Kebijakan Privasi
            </h1>
            <p class="text-slate-500 text-sm sm:text-base leading-relaxed font-medium max-w-lg mx-auto md:mx-0">
                Keamanan dan privasi data Anda adalah prioritas utama kami. Pelajari bagaimana FlyDine melindungi informasi Anda selama menggunakan layanan di Bandara Juanda.
            </p>
        </div>
        
        <!-- Ilustrasi Ngambang (Floating Glassmorphism Icon) -->
        <div class="hidden md:flex justify-center items-center relative w-48 h-48 animate-float">
            <!-- Cahaya Blur di Belakang Ikon -->
            <div class="absolute inset-0 bg-[#8dc63f] blur-3xl opacity-20 rounded-full scale-110"></div>
            
            <!-- Kotak Kaca Utama -->
            <div class="relative w-32 h-32 bg-white border border-slate-100 rounded-3xl shadow-[0_16px_32px_rgba(0,0,0,0.05)] flex items-center justify-center rotate-6 hover:rotate-0 transition-transform duration-500 cursor-default">
                <!-- Ikon Keamanan / Gembok -->
                <svg class="w-14 h-14 text-[#8dc63f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                
                <!-- Elemen Pemanis -->
                <div class="absolute -top-3 -right-3 w-8 h-8 bg-[#005ea2] rounded-xl flex items-center justify-center shadow-lg animate-pulse">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<!-- Kontainer Utama (Card yang juga ikut terangkat halus saat di-hover) -->
<div class="bg-white p-6 sm:p-10 md:p-12 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 w-full max-w-4xl mx-auto -mt-8 sm:-mt-12 relative z-20 mb-16 hover:-translate-y-1 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500">
    
    <div class="prose prose-slate max-w-none">
        
        <p class="text-slate-500 font-medium leading-relaxed text-base sm:text-lg mb-8">
            FlyDine beroperasi dengan prinsip <strong>pengumpulan data seminimal mungkin</strong> (<i>data minimization</i>). Kami hanya meminta informasi yang mutlak diperlukan untuk memastikan makanan Anda siap tepat waktu sebelum keberangkatan.
        </p>
        
        <h3 class="text-xl font-extrabold text-slate-800 mb-6 flex items-center">
            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center mr-3 text-[#005ea2]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            Informasi yang Kami Kumpulkan
        </h3>

        <!-- Grid Kartu Informasi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-10">
            <!-- Kartu 1: Data Identitas -->
            <div class="bg-slate-50 border border-slate-100 rounded-[1.5rem] p-6 hover:shadow-md hover:border-[#005ea2]/30 transition-all duration-300 group">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-[#005ea2] shadow-sm mb-5 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <h4 class="font-extrabold text-slate-800 mb-2">Data Identitas</h4>
                <p class="text-sm text-slate-500 leading-relaxed font-medium">Nama pemesan diperlukan murni untuk keperluan pencocokan dan verifikasi saat Anda melakukan pengambilan makanan di konter restoran.</p>
            </div>
            
            <!-- Kartu 2: Data Penerbangan -->
            <div class="bg-slate-50 border border-slate-100 rounded-[1.5rem] p-6 hover:shadow-md hover:border-[#005ea2]/30 transition-all duration-300 group">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-[#005ea2] shadow-sm mb-5 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h4 class="font-extrabold text-slate-800 mb-2">Data Penerbangan</h4>
                <p class="text-sm text-slate-500 leading-relaxed font-medium">Nomor penerbangan dan waktu boarding digunakan otomatis oleh sistem untuk memprioritaskan antrean dapur dan mencegah keterlambatan.</p>
            </div>
        </div>

        <!-- Banner Keamanan / Shield -->
        <div class="bg-[#e8fbe8] rounded-[1.5rem] p-6 sm:p-8 border border-[#8dc63f]/30 flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6 relative overflow-hidden group">
            <!-- Aksen background lingkaran -->
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-green-200/40 rounded-full blur-2xl group-hover:bg-green-300/40 transition-colors duration-500"></div>
            
            <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-green-100 flex items-center justify-center text-[#00a550] flex-shrink-0 relative z-10 group-hover:-translate-y-1 transition-transform duration-300">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div class="relative z-10">
                <h4 class="text-base font-extrabold text-emerald-900 mb-2">Jaminan Penghapusan Data (Data Retention)</h4>
                <p class="text-sm text-emerald-700 leading-relaxed font-medium">
                    Data penerbangan Anda <strong>tidak disimpan secara permanen</strong>. Begitu pesanan selesai atau penerbangan Anda lepas landas, data tersebut akan segera dihapus dari sistem operasional kami. Kami menjamin 100% tidak pernah membagikan data identitas Anda kepada pihak ketiga.
                </p>
            </div>
        </div>
        
    </div>
</div>

@endsection