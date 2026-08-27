@extends('layouts.tenant')

@section('title', 'Katalog Produk Saya')

@section('content')

@if(session('success'))
    <div class="mb-5 bg-green-50 border border-green-200 text-green-700
                px-4 py-3 rounded-xl text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Katalog Produk</h1>
        <p class="text-sm text-gray-500">
            Kelola menu tenant Anda.
        </p>
    </div>

    <a href="{{ route('tenant.products.create') }}"
       class="bg-[#8dc63f] hover:bg-green-600 text-white
              px-5 py-2.5 rounded-xl text-sm font-bold">
        + Tambah Produk
    </a>
</div>


{{-- SEARCH --}}
<input id="searchProduct"
       type="text"
       placeholder="Cari produk..."
       class="w-full md:w-80 border border-gray-200 rounded-xl
              px-4 py-2.5 text-sm mb-6 focus:outline-none
              focus:border-[#006ca8]">


{{-- PRODUK --}}
@if($products->count())

<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">

@foreach($products as $product)

<div class="product-card bg-white rounded-2xl border border-gray-100
            shadow-sm hover:shadow-lg transition overflow-hidden">

    {{-- FOTO --}}
    <div class="h-40 bg-gray-100 relative overflow-hidden">

        @if($product->image)

            <img src="{{ asset('storage/'.$product->image) }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover">

        @else

            <div class="w-full h-full flex items-center justify-center
                        bg-gradient-to-br from-[#e8f5fb] to-[#cde9f5]">

                <span class="text-3xl font-bold text-[#006ca8]">
                    {{ strtoupper(substr($product->name, 0, 1)) }}
                </span>

            </div>

        @endif

        <span class="absolute top-3 left-3
            {{ $product->is_available ? 'bg-[#8dc63f]' : 'bg-gray-500' }}
            text-white text-[10px] font-bold px-3 py-1 rounded-full">

            {{ $product->is_available ? 'AKTIF' : 'NONAKTIF' }}

        </span>

    </div>


    {{-- INFORMASI --}}
    <div class="p-4">

        <p class="text-xs text-gray-400">
            {{ $product->category ?? 'Tanpa kategori' }}
        </p>

        <h3 class="product-name font-semibold text-gray-800 mt-1">
            {{ $product->name }}
        </h3>

        <p class="text-lg font-bold text-[#006ca8] mt-2">
            Rp {{ number_format($product->price, 0, ',', '.') }}
        </p>


        {{-- DETAIL --}}
        <button type="button"
                onclick="showDetail(this)"
                data-name="{{ $product->name }}"
                data-category="{{ $product->category ?? '-' }}"
                data-price="{{ number_format($product->price, 0, ',', '.') }}"
                data-description="{{ $product->description ?? '-' }}"
                data-note="{{ $product->note ?? '-' }}"
                data-image="{{ $product->image ? asset('storage/'.$product->image) : '' }}"
                data-status="{{ $product->is_available ? 'Aktif' : 'Tidak tersedia' }}"
                class="w-full mt-4 border border-[#006ca8]
                       text-[#006ca8] hover:bg-blue-50
                       py-2 rounded-lg text-xs font-semibold">
            Lihat Detail
        </button>


        {{-- EDIT / HAPUS --}}
        <div class="grid grid-cols-2 gap-2 mt-2">

            <button type="button"
                    onclick="editProduct(this)"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-category="{{ $product->category ?? '' }}"
                    data-description="{{ $product->description ?? '' }}"
                    data-note="{{ $product->note ?? '' }}"
                    data-price="{{ $product->price }}"
                    data-status="{{ $product->is_available }}"
                    class="bg-blue-50 hover:bg-blue-100
                           text-[#006ca8] py-2 rounded-lg
                           text-xs font-semibold">
                Edit
            </button>

            <form method="POST"
                  action="{{ route('tenant.products.destroy', $product) }}"
                  onsubmit="return confirm('Hapus produk ini?')">

                @csrf
                @method('DELETE')

                <button type="submit"
                        class="w-full bg-red-50 hover:bg-red-100
                               text-red-500 py-2 rounded-lg
                               text-xs font-semibold">
                    Hapus
                </button>

            </form>

        </div>

    </div>

</div>

@endforeach

</div>

@else

{{-- EMPTY --}}
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


{{-- ================= DETAIL MODAL ================= --}}
<div id="detailModal"
     class="hidden fixed inset-0 bg-black/50 z-50
            items-center justify-center p-5">

    <div class="bg-white rounded-2xl max-w-lg w-full
                max-h-[90vh] overflow-y-auto">

        <div id="detailImage"
             class="h-64 bg-[#e8f5fb]
                    flex items-center justify-center">
        </div>

        <div class="p-6">

            <p class="text-xs text-gray-400">
                Detail Produk
            </p>

            <h2 id="detailName"
                class="text-2xl font-bold text-gray-800 mt-1">
            </h2>

            <p id="detailPrice"
               class="text-xl font-bold text-[#006ca8] mt-2">
            </p>

            <div class="mt-4 space-y-3 text-sm">

                <div>
                    <p class="text-xs text-gray-400">
                        Kategori
                    </p>
                    <p id="detailCategory"
                       class="font-semibold text-gray-700">
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-400">
                        Deskripsi
                    </p>
                    <p id="detailDescription"
                       class="text-gray-600">
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-400">
                        Catatan
                    </p>
                    <p id="detailNote"
                       class="text-gray-600">
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-400">
                        Status
                    </p>
                    <p id="detailStatus"
                       class="font-semibold">
                    </p>
                </div>

            </div>

            <button onclick="closeModal('detailModal')"
                    class="w-full mt-6 bg-[#006ca8]
                           text-white py-3 rounded-xl
                           font-semibold">
                Tutup
            </button>

        </div>
    </div>
</div>


{{-- ================= EDIT MODAL ================= --}}
<div id="editModal"
     class="hidden fixed inset-0 bg-black/50 z-50
            items-center justify-center p-5">

    <div class="bg-white rounded-2xl max-w-lg w-full
                max-h-[90vh] overflow-y-auto p-6">

        <h2 class="text-xl font-bold text-gray-800 mb-5">
            Edit Produk
        </h2>

        <form id="editForm"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            {{-- Nama --}}
            <label class="text-sm font-semibold">
                Nama Produk
            </label>

            <input id="editName"
                   name="name"
                   required
                   class="w-full border rounded-xl
                          px-4 py-3 mt-2 mb-4">


            {{-- Kategori --}}
            <label class="text-sm font-semibold">
                Kategori
            </label>

            <select id="editCategory"
                    name="category"
                    class="w-full border rounded-xl
                           px-4 py-3 mt-2 mb-4">

                <option value="">Pilih kategori</option>
                <option value="Makanan Utama">Makanan Utama</option>
                <option value="Snack">Snack</option>
                <option value="Minuman">Minuman</option>
                <option value="Dessert">Dessert</option>

            </select>


            {{-- Deskripsi --}}
            <label class="text-sm font-semibold">
                Deskripsi
            </label>

            <textarea id="editDescription"
                      name="description"
                      rows="3"
                      class="w-full border rounded-xl
                             px-4 py-3 mt-2 mb-4"></textarea>


            {{-- Harga --}}
            <label class="text-sm font-semibold">
                Harga
            </label>

            <input id="editPrice"
                   type="number"
                   name="price"
                   required
                   class="w-full border rounded-xl
                          px-4 py-3 mt-2 mb-4">


            {{-- Foto --}}
            <label class="text-sm font-semibold">
                Foto Produk
            </label>

            <input type="file"
                   name="image"
                   accept="image/png,image/jpeg"
                   class="w-full mt-2 mb-4">


            {{-- Catatan --}}
            <label class="text-sm font-semibold">
                Catatan
            </label>

            <textarea id="editNote"
                      name="note"
                      rows="3"
                      class="w-full border rounded-xl
                             px-4 py-3 mt-2 mb-4"></textarea>


            {{-- Status --}}
            <label class="flex items-center gap-2 mb-5">

                <input id="editStatus"
                       type="checkbox"
                       name="is_available"
                       value="1">

                <span class="text-sm">
                    Produk tersedia
                </span>

            </label>


            <div class="grid grid-cols-2 gap-3">

                <button type="button"
                        onclick="closeModal('editModal')"
                        class="border rounded-xl py-3
                               text-sm font-semibold">
                    Batal
                </button>

                <button type="submit"
                        class="bg-[#8dc63f]
                               text-white rounded-xl
                               py-3 text-sm font-bold">
                    Simpan
                </button>

            </div>

        </form>

    </div>
</div>


<script>

const search = document.getElementById('searchProduct');

search?.addEventListener('input', function () {

    const keyword = this.value.toLowerCase();

    document.querySelectorAll('.product-card').forEach(card => {

        const name = card.querySelector('.product-name')
            .textContent
            .toLowerCase();

        card.style.display =
            name.includes(keyword) ? '' : 'none';

    });

});


function showDetail(button)
{
    document.getElementById('detailName').textContent =
        button.dataset.name;

    document.getElementById('detailCategory').textContent =
        button.dataset.category;

    document.getElementById('detailPrice').textContent =
        'Rp ' + button.dataset.price;

    document.getElementById('detailDescription').textContent =
        button.dataset.description;

    document.getElementById('detailNote').textContent =
        button.dataset.note;

    document.getElementById('detailStatus').textContent =
        button.dataset.status;

    const image = document.getElementById('detailImage');

    image.innerHTML = button.dataset.image
        ? `<img src="${button.dataset.image}"
                class="w-full h-full object-cover">`
        : `<span class="text-5xl font-bold text-[#006ca8]">
                ${button.dataset.name.charAt(0).toUpperCase()}
           </span>`;

    openModal('detailModal');
}


function editProduct(button)
{
    document.getElementById('editName').value =
        button.dataset.name;

    document.getElementById('editCategory').value =
        button.dataset.category;

    document.getElementById('editDescription').value =
        button.dataset.description;

    document.getElementById('editPrice').value =
        button.dataset.price;

    document.getElementById('editNote').value =
        button.dataset.note;

    document.getElementById('editStatus').checked =
        button.dataset.status === '1';

    document.getElementById('editForm').action =
        `/tenant/products/${button.dataset.id}`;

    openModal('editModal');
}


function openModal(id)
{
    const modal = document.getElementById(id);

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}


function closeModal(id)
{
    const modal = document.getElementById(id);

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

</script>

@endsection
