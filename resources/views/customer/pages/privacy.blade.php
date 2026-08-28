@extends('customer.pages.layout')

@section('title', 'Kebijakan Privasi')

@section('page_header')
    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-4">Kebijakan Privasi</h1>
    <p class="text-blue-100 text-lg max-w-2xl leading-relaxed">
        Kami menghargai dan melindungi data pribadi Anda selama menggunakan layanan pemesanan FlyDine.
    </p>
@endsection

@section('content')
<div class="bg-white p-6 sm:p-10 rounded-3xl shadow-lg border border-slate-100 w-full max-w-4xl mx-auto">
    <div class="prose prose-slate max-w-none text-slate-600">
        <p>FlyDine menghargai privasi data Anda. Informasi yang kami kumpulkan meliputi:</p>
        
        <ul class="list-disc pl-5 space-y-2 mt-4">
            <li><strong>Data Identitas:</strong> Nama pemesan untuk keperluan verifikasi pengambilan makanan.</li>
            <li><strong>Data Penerbangan:</strong> Nomor penerbangan dan waktu boarding semata-mata digunakan oleh sistem untuk memprioritaskan antrean masakan dan peringatan batas waktu.</li>
        </ul>
        
        <p class="mt-4">Data penerbangan Anda tidak disimpan secara permanen setelah pesanan selesai atau penerbangan lepas landas. Kami tidak pernah membagikan data identitas Anda kepada pihak ketiga di luar ekosistem operasional bandara.</p>
    </div>
</div>
@endsection
