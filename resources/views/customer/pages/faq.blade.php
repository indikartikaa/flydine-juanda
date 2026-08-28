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

        <div class="bg-blue-50 p-6 rounded-xl border border-blue-100 mt-8">
            <h4 class="font-bold text-blue-800 mb-2">Butuh Bantuan Langsung?</h4>
            <p class="text-blue-700">Tim Support FlyDine (Givan, Fira, Septi, atau Zaidan) siap membantu Anda. Silakan temui petugas lapangan kami di area Boarding Lounge Terminal 1, atau hubungi pusat bantuan bandara.</p>
        </div>
    </div>
</div>
@endsection
