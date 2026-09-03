@extends('customer.pages.layout')

@section('title', 'Syarat & Ketentuan')

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
                <span class="w-2 h-2 rounded-full bg-[#005ea2] animate-pulse shadow-[0_0_8px_#005ea2]"></span>
                <span class="text-[10px] font-extrabold text-slate-700 tracking-widest uppercase">Kebijakan Layanan</span>
            </div>
            
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight mb-4 text-slate-900 leading-tight">
                Syarat & Ketentuan
            </h1>
            <p class="text-slate-500 text-sm sm:text-base leading-relaxed font-medium max-w-lg mx-auto md:mx-0">
                Pahami syarat dan ketentuan operasional yang berlaku untuk setiap transaksi di platform pemesanan FlyDine Bandara Juanda.
            </p>
        </div>
        
        <div class="hidden md:flex justify-center items-center relative w-48 h-48 animate-float">
            <div class="absolute inset-0 bg-[#005ea2] blur-3xl opacity-20 rounded-full scale-110"></div>
            
            <div class="relative w-32 h-32 bg-white border border-slate-100 rounded-3xl shadow-[0_16px_32px_rgba(0,0,0,0.05)] flex items-center justify-center rotate-6 hover:rotate-0 transition-transform duration-500 cursor-default">
                <svg class="w-14 h-14 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                
                <div class="absolute -top-3 -right-3 w-8 h-8 bg-[#8dc63f] rounded-xl flex items-center justify-center shadow-lg animate-pulse">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="bg-white p-6 sm:p-10 md:p-12 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 w-full max-w-4xl mx-auto -mt-8 sm:-mt-12 relative z-20 mb-16 hover:-translate-y-1 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500">
    
    <div class="prose prose-slate max-w-none">
        <p class="text-slate-500 font-medium leading-relaxed text-base sm:text-lg mb-8">
            Dengan menggunakan layanan FlyDine, pengguna secara otomatis menyetujui seluruh ketentuan operasional berikut. Harap baca dengan saksama demi kelancaran pesanan penerbangan Anda.
        </p>

        <!-- Rule Block 1 -->
        <div class="flex items-start space-x-4 mb-6 p-5 rounded-2xl hover:bg-slate-50 transition-colors">
            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-[#005ea2] flex-shrink-0 mt-1">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-lg mb-2">Platform Perantara</h4>
                <p class="text-sm font-medium text-slate-500 leading-relaxed">FlyDine murni bertindak sebagai platform perantara pemesanan digital antara Anda (penumpang) dan pihak restoran (Tenant). Layanan persiapan dan kualitas makanan sepenuhnya merupakan tanggung jawab pihak restoran yang Anda pilih.</p>
            </div>
        </div>

        <!-- Rule Block 2 -->
        <div class="flex items-start space-x-4 mb-6 p-5 rounded-2xl hover:bg-slate-50 transition-colors">
            <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center text-rose-500 flex-shrink-0 mt-1">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-lg mb-2">Risiko Keterlambatan Boarding</h4>
                <p class="text-sm font-medium text-slate-500 leading-relaxed">Sistem kami didesain untuk mencegah pesanan jika waktu sudah mepet. Namun, <strong>setiap keterlambatan pengambilan pesanan yang mengakibatkan Anda tertinggal jadwal penerbangan sepenuhnya merupakan tanggung jawab Anda sebagai pengguna</strong>. Harap perhatikan waktu Boarding Anda dengan baik.</p>
            </div>
        </div>

        <!-- Rule Block 3 -->
        <div class="flex items-start space-x-4 mb-6 p-5 rounded-2xl hover:bg-slate-50 transition-colors">
            <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 flex-shrink-0 mt-1">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-lg mb-2">Kebijakan Pembatalan & Pengembalian Dana</h4>
                <p class="text-sm font-medium text-slate-500 leading-relaxed">Pesanan yang telah berhasil dibayar (melalui QRIS/Transfer) dan statusnya berubah menjadi "Sedang Diproses" oleh dapur restoran <strong>tidak dapat dibatalkan atau di-refund</strong> (dikembalikan dananya) dengan alasan apapun.</p>
            </div>
        </div>

        <!-- Rule Block 4 -->
        <div class="flex items-start space-x-4 mb-6 p-5 rounded-2xl hover:bg-slate-50 transition-colors">
            <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 flex-shrink-0 mt-1">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-lg mb-2">Transparansi Harga</h4>
                <p class="text-sm font-medium text-slate-500 leading-relaxed">Harga yang tertera pada direktori menu FlyDine sudah bersifat final (termasuk pajak restoran) sesuai ketentuan yang berlaku di wilayah Bandara Internasional Juanda.</p>
            </div>
        </div>

    </div>
</div>
@endsection
