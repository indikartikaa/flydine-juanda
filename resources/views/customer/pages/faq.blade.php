@extends('customer.pages.layout')

@section('title', 'Pusat Bantuan (FAQ)')

@section('page_header')
    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-4">Pusat Bantuan (FAQ)</h1>
    <p class="text-blue-100 text-lg max-w-2xl leading-relaxed">
        Temukan jawaban untuk pertanyaan yang sering ditanyakan seputar layanan pemesanan makanan FlyDine di Bandara Juanda.
    </p>
@endsection

@section('content')
<div class="bg-white p-6 sm:p-10 rounded-3xl shadow-lg border border-slate-100 w-full max-w-4xl mx-auto">
    <div class="space-y-6 text-slate-600">
        <div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">T: Apa itu FlyDine?</h3>
            <p>J: FlyDine adalah platform pemesanan makanan digital pertama di Bandara Internasional Juanda yang memungkinkan penumpang memesan makanan dari <em>boarding lounge</em> tanpa harus mengantre di kasir tenant.</p>
        </div>
        
        <div class="h-px bg-slate-100"></div>

        <div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">T: Bagaimana jika waktu boarding saya sudah sangat dekat?</h3>
            <p>J: Sistem cerdas kami akan mendeteksi waktu boarding yang Anda masukkan. Jika waktu tersisa kurang dari 15 menit, pesanan akan ditolak secara otomatis untuk mencegah Anda tertinggal pesawat.</p>
        </div>
        
        <div class="h-px bg-slate-100"></div>

        <div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">T: Saya sudah memesan, tapi ingin menambah makanan dari restoran lain?</h3>
            <p>J: Selesaikan terlebih dahulu pesanan di keranjang Anda saat ini. Setelah pembayaran berhasil, Anda dapat membuat pesanan baru untuk restoran yang berbeda.</p>
        </div>

        <div class="h-px bg-slate-100 mt-8 mb-8"></div>

        <!-- Universal Help Center Form -->
        <div id="hubungi-kami" class="bg-blue-50/50 p-6 md:p-8 rounded-2xl border border-blue-100">
            <div class="mb-6">
                <h3 class="text-xl font-extrabold text-[#005ea2] tracking-tight mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
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
                        <input type="text" name="reporter_name" required placeholder="Contoh: Budi Santoso" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">No. WhatsApp <span class="text-rose-500">*</span></label>
                        <input type="text" name="reporter_contact" required placeholder="Contoh: 08123456789" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">ID Pesanan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="order_code" value="{{ request('order') }}" placeholder="Contoh: ORD-ABCDEF" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Kategori Masalah <span class="text-rose-500">*</span></label>
                        <select name="category" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                            <option value="pesanan_salah" {{ request('order') ? 'selected' : '' }}>Pesanan Tidak Sesuai / Terlambat</option>
                            <option value="status_tidak_update">Status Aplikasi Tidak Sesuai</option>
                            <option value="lainnya" {{ !request('order') ? 'selected' : '' }}>Pertanyaan Umum / Saran / Lainnya</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Pesan atau Detail Kendala <span class="text-rose-500">*</span></label>
                    <textarea name="description" rows="4" required placeholder="Tuliskan secara detail apa yang bisa kami bantu..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all resize-none"></textarea>
                </div>
                
                <div class="pt-2">
                    <button type="submit" class="bg-[#005ea2] hover:bg-[#004a82] text-white font-extrabold py-3.5 px-6 rounded-xl text-sm transition-all shadow-md shadow-blue-500/20 w-full sm:w-auto flex items-center justify-center">
                        Kirim Pesan Sekarang
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
