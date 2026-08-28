@extends('customer.pages.layout')

@section('title', 'Syarat & Ketentuan')

@section('page_header')
    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-4">Syarat & Ketentuan</h1>
    <p class="text-blue-100 text-lg max-w-2xl leading-relaxed">
        Pahami syarat dan ketentuan yang berlaku untuk setiap transaksi di platform FlyDine.
    </p>
@endsection

@section('content')
<div class="bg-white p-6 sm:p-10 rounded-3xl shadow-lg border border-slate-100 w-full max-w-4xl mx-auto">
    <div class="prose prose-slate max-w-none text-slate-600">
        <p>Dengan menggunakan layanan FlyDine, pengguna menyetujui ketentuan berikut:</p>
        
        <ul class="list-disc pl-5 space-y-2 mt-4">
            <li>FlyDine bertindak sebagai perantara pemesanan antara penumpang dan pihak restoran (Tenant).</li>
            <li>Keterlambatan pengambilan pesanan yang mengakibatkan tertinggalnya jadwal penerbangan sepenuhnya merupakan tanggung jawab pengguna.</li>
            <li>Pesanan yang telah dibayar dan mulai diproses oleh dapur restoran tidak dapat dibatalkan atau di-refund (dikembalikan dananya).</li>
            <li>Harga yang tertera pada katalog FlyDine sudah termasuk pajak restoran sesuai ketentuan yang berlaku di area bandara.</li>
        </ul>
    </div>
</div>
@endsection
