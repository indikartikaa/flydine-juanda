@extends('layouts.tenant')

@section('title', 'Katalog Produk Saya')

@section('content')

@if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Katalog Produk</h1>
        <p class="text-sm text-gray-500">Kelola menu tenant Anda.</p>
    </div>

    <a href="{{ route('tenant.products.create') }}"
       class="bg-[#8dc63f] hover:bg-green-600 text-white px-5 py-2.5
              rounded-xl text-sm font-bold self-start">
        + Tambah Produk
    </a>
</div>

{{-- Search --}}
<div class="mb-5">
    <input id="searchProduct" type="text"
           placeholder="Cari produk..."
           class="w-full md:w-80 border border-gray-200 rounded-xl
                  px-4 py-2.5 text-sm focus:outline-none
                  focus:border-[#006ca8]">
</div>

{{-- Produk --}}
@if($products->count())

    <div id="productGrid"
         class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">

        @foreach($products as $product)

            <div class="product-card bg-white rounded-2xl border border-gray-100
                        shadow-sm hover:shadow-lg transition overflow-hidden">

                {{-- Foto sementara --}}
                <div class="h-40 bg-gradient-to-br from-[#e8f5fb] to-[#cde9f5]
                            flex items-center justify-center relative">

                    <div class="w-16 h-16 rounded-full bg-white shadow
                                flex items-center justify-center
                                text-2xl font-bold text-[#006ca8]">
                        {{ strtoupper(substr($product->name, 0, 1)) }}
                    </div>

                    @if($product->is_available)
                        <span class="absolute top-3 left-3 bg-[#8dc63f]
                                     text-white text-[10px] font-bold
                                     px-3 py-1 rounded-full">
                            AKTIF
                        </span>
                    @else
                        <span class="absolute top-3 left-3 bg-gray-500
                                     text-white text-[10px] font-bold
                                     px-3 py-1 rounded-full">
                            NONAKTIF
                        </span>
                    @endif
                </div>

                <div class="p-4">

                    <p class="text-xs text-gray-400">
                        {{ auth()->user()->tenant?->name ?? 'FlyDine' }}
                    </p>

                    <h3 class="product-name font-semibold text-gray-800 mt-1">
                        {{ $product->name }}
                    </h3>

                    <p class="text-lg font-bold text-[#006ca8] mt-2">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    <div class="border-t mt-4 pt-3 flex justify-between items-center">
                        <span class="text-xs text-gray-400">
                            Produk #{{ $product->id }}
                        </span>

                        <button class="text-sm font-semibold text-[#006ca8]">
                            Detail →
                        </button>
                    </div>

                </div>
            </div>

        @endforeach

    </div>

    <div id="emptySearch" class="hidden text-center py-12 text-gray-400">
        Produk tidak ditemukan.
    </div>

@else

    <div class="bg-white rounded-2xl border text-center py-16">

        <div class="text-5xl mb-3">🍽️</div>

        <h2 class="font-bold text-gray-700">
            Belum ada produk
        </h2>

        <p class="text-sm text-gray-400 mt-1">
            Tambahkan produk pertama Anda.
        </p>

        <a href="{{ route('tenant.products.create') }}"
           class="inline-block mt-4 bg-[#006ca8] text-white
                  px-5 py-2.5 rounded-xl text-sm font-semibold">
            + Tambah Produk
        </a>

    </div>

@endif

<script>
const search = document.getElementById('searchProduct');

search?.addEventListener('input', function () {
    let visible = 0;
    const keyword = this.value.toLowerCase();

    document.querySelectorAll('.product-card').forEach(card => {
        const name = card.querySelector('.product-name').textContent.toLowerCase();
        const show = name.includes(keyword);

        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    document.getElementById('emptySearch')?.classList.toggle('hidden', visible > 0);
});
</script>

@endsection
