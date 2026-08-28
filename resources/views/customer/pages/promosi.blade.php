@extends('customer.pages.layout')

@section('title', 'Promosi Kolaborasi')

@section('page_header')
    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-4">Promosi Kolaborasi</h1>
    <p class="text-blue-100 text-lg max-w-2xl leading-relaxed">
        Peluang co-branding dan promosi strategis bersama FlyDine untuk memberikan nilai tambah bagi pelanggan Anda.
    </p>
@endsection

@section('content')
<div class="bg-white p-6 sm:p-10 rounded-3xl shadow-lg border border-slate-100 w-full max-w-4xl mx-auto">
    
    <div class="w-16 h-16 bg-rose-100 text-rose-500 rounded-2xl flex items-center justify-center mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
        </svg>
    </div>
    
    <div class="prose prose-slate max-w-none text-slate-600">
        <p>FlyDine membuka peluang kolaborasi seluas-luasnya bagi instansi perbankan, penyedia dompet digital (e-wallet), maskapai penerbangan, maupun merek ritel yang ingin mengadakan kampanye promosi bersama (co-branding).</p>
        
        <h4 class="font-bold text-slate-800 mt-6 mb-2">Format kolaborasi yang tersedia meliputi:</h4>
        <ul class="list-disc pl-5 space-y-2">
            <li>Sponsor banner pada halaman direktori FlyDine.</li>
            <li>Diskon khusus menggunakan metode pembayaran mitra (misal: "Diskon 20% dengan Kartu Kredit Bank X").</li>
            <li>Voucher makan FlyDine yang di-bundling dengan tiket maskapai penerbangan tertentu.</li>
        </ul>
        
        <p class="mt-8 font-medium">Untuk diskusi lebih lanjut mengenai peluang promosi strategis, silakan hubungi tim Business Development kami.</p>
    </div>
</div>
@endsection
