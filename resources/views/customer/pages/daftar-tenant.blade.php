@extends('customer.pages.layout')

@section('title', 'Daftar Menjadi Tenant')

@section('page_header')
    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-4">Daftar Menjadi Tenant</h1>
    <p class="text-blue-100 text-lg max-w-2xl leading-relaxed">
        Bergabunglah bersama kami dan tingkatkan omset bisnis kuliner Anda dengan menjangkau ribuan penumpang di Bandara Juanda.
    </p>
@endsection

@section('content')
<div class="bg-white p-6 sm:p-10 rounded-3xl shadow-lg border border-slate-100 w-full max-w-4xl mx-auto">
    <div class="prose prose-slate max-w-none text-slate-600">
        <p>Perluas jangkauan bisnis kuliner Anda di Bandara Internasional Juanda. Dengan bergabung bersama FlyDine, tenant Anda akan mendapatkan visibilitas eksklusif kepada ribuan penumpang setiap harinya yang sedang bersantai di <em>boarding lounge</em>.</p>
        
        <h4 class="font-bold text-slate-800 mt-6 mb-2">Keuntungan Bergabung:</h4>
        <ul class="list-disc pl-5 space-y-2">
            <li>Sistem manajemen pesanan (Order Management System) khusus staf dapur.</li>
            <li>Peningkatan volume penjualan dari penumpang yang enggan berjalan jauh ke area <em>food court</em>.</li>
            <li>Laporan penjualan dan analitik produk terlaris.</li>
        </ul>
        
        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 mt-8">
            <p class="m-0">Kirimkan proposal kemitraan bisnis Anda beserta profil restoran ke alamat email: <a href="mailto:partnership@flydine.co.id" class="font-bold text-[#005ea2]">partnership@flydine.co.id</a> atau kunjungi kantor manajemen komersial Terminal 1 Juanda.</p>
        </div>
    </div>
</div>
@endsection
