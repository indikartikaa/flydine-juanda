@extends('customer.pages.layout')

@section('title', 'Pusat Bantuan (FAQ)')

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
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse shadow-[0_0_8px_#3b82f6]"></span>
                <span class="text-[10px] font-extrabold text-slate-700 tracking-widest uppercase">Support Center</span>
            </div>
            
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight mb-4 text-slate-900 leading-tight">
                Pusat Bantuan
            </h1>
            <p class="text-slate-500 text-sm sm:text-base leading-relaxed font-medium max-w-lg mx-auto md:mx-0">
                Temukan jawaban untuk pertanyaan yang sering diajukan seputar layanan pemesanan makanan FlyDine di Bandara Juanda.
            </p>
        </div>
        
        <div class="hidden md:flex justify-center items-center relative w-48 h-48 animate-float">
            <div class="absolute inset-0 bg-[#005ea2] blur-3xl opacity-20 rounded-full scale-110"></div>
            
            <div class="relative w-32 h-32 bg-white border border-slate-100 rounded-3xl shadow-[0_16px_32px_rgba(0,0,0,0.05)] flex items-center justify-center rotate-6 hover:rotate-0 transition-transform duration-500 cursor-default">
                <svg class="w-14 h-14 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                
                <div class="absolute -top-3 -right-3 w-8 h-8 bg-amber-400 rounded-xl flex items-center justify-center shadow-lg animate-pulse">
                    <span class="text-white text-lg font-black">?</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="bg-white p-6 sm:p-10 md:p-12 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 w-full max-w-4xl mx-auto -mt-8 sm:-mt-12 relative z-20 mb-16 hover:-translate-y-1 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500">
    
    <div class="space-y-6 text-slate-600 mb-12">
        <h3 class="text-xl font-extrabold text-slate-800 mb-6 flex items-center">
            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center mr-3 text-[#005ea2]">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            </div>
            Pertanyaan Populer
        </h3>

        <!-- FAQ Item 1 -->
        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 hover:shadow-md hover:border-blue-200 transition-all duration-300">
            <h4 class="text-lg font-bold text-[#005ea2] mb-2">T: Apa itu FlyDine?</h4>
            <p class="text-sm font-medium leading-relaxed">J: FlyDine adalah platform pemesanan makanan digital pertama di Bandara Internasional Juanda yang memungkinkan penumpang memesan makanan dari <em>boarding lounge</em> tanpa harus mengantre di kasir tenant.</p>
        </div>

        <!-- FAQ Item 2 -->
        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 hover:shadow-md hover:border-blue-200 transition-all duration-300">
            <h4 class="text-lg font-bold text-[#005ea2] mb-2">T: Bagaimana jika waktu boarding saya sudah sangat dekat?</h4>
            <p class="text-sm font-medium leading-relaxed">J: Sistem cerdas kami akan mendeteksi waktu boarding yang Anda masukkan. Jika waktu tersisa kurang dari 15 menit, pesanan akan ditolak secara otomatis untuk mencegah Anda tertinggal pesawat.</p>
        </div>

        <!-- FAQ Item 3 -->
        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 hover:shadow-md hover:border-blue-200 transition-all duration-300">
            <h4 class="text-lg font-bold text-[#005ea2] mb-2">T: Saya sudah memesan, tapi ingin menambah makanan dari restoran lain?</h4>
            <p class="text-sm font-medium leading-relaxed">J: Selesaikan terlebih dahulu pesanan di keranjang Anda saat ini. Setelah pembayaran berhasil, Anda dapat membuat pesanan baru untuk restoran yang berbeda.</p>
        </div>
    </div>

    <!-- Universal Help Center Form -->
    <div id="hubungi-kami" class="bg-blue-50/50 p-6 md:p-8 rounded-[1.5rem] border border-blue-100 relative overflow-hidden group">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-100 rounded-full opacity-50 blur-xl group-hover:scale-110 transition-transform duration-700"></div>
        <div class="relative z-10">
            <div class="mb-8">
                <h3 class="text-xl font-extrabold text-[#005ea2] tracking-tight mb-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
                    Hubungi Kami / Kirim Laporan
                </h3>
                <p class="text-sm text-slate-500 font-medium leading-relaxed">Tim Support FlyDine siap membantu Anda. Silakan isi form di bawah ini dan kami akan segera menghubungi Anda kembali melalui WhatsApp.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('customer.complaint') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="reporter_name" required placeholder="Contoh: Budi Santoso" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all shadow-sm hover:border-blue-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">No. WhatsApp <span class="text-rose-500">*</span></label>
                        <input type="text" name="reporter_contact" required placeholder="Contoh: 08123456789" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all shadow-sm hover:border-blue-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">ID Pesanan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="order_code" value="{{ request('order') }}" placeholder="Contoh: ORD-ABCDEF" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all shadow-sm hover:border-blue-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Kategori Masalah <span class="text-rose-500">*</span></label>
                        <select name="category" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all shadow-sm hover:border-blue-300 appearance-none">
                            <option value="pesanan_salah" {{ request('order') ? 'selected' : '' }}>Pesanan Tidak Sesuai / Terlambat</option>
                            <option value="status_tidak_update">Status Aplikasi Tidak Sesuai</option>
                            <option value="lainnya" {{ !request('order') ? 'selected' : '' }}>Pertanyaan Umum / Saran / Lainnya</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Pesan atau Detail Kendala <span class="text-rose-500">*</span></label>
                    <textarea name="description" rows="4" required placeholder="Tuliskan secara detail apa yang bisa kami bantu..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all resize-none shadow-sm hover:border-blue-300"></textarea>
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="bg-[#005ea2] hover:bg-[#004a82] text-white font-extrabold py-3.5 px-8 rounded-xl text-sm transition-all shadow-lg shadow-blue-500/30 w-full sm:w-auto flex items-center justify-center group/btn">
                        Kirim Pesan Sekarang
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
