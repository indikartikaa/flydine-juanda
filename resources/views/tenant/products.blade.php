@extends('layouts.tenant')

@section('title', 'Katalog Produk Saya')

@section('content')

@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-medium flex items-center justify-between shadow-sm">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
@endif

<div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Katalog Produk</h1>
        <p class="text-sm text-gray-500">
            Kelola menu tenant Anda.
        </p>
    </div>

    <a href="{{ route('tenant.products.create') }}"
       class="bg-[#005ea2] hover:bg-blue-700 text-white
              px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all hover:-translate-y-0.5 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Tambah Produk
    </a>
</div>


{{-- SEARCH --}}
<input id="searchProduct"
       type="text"
       placeholder="Cari produk..."
       class="w-full md:w-80 border border-gray-200 rounded-xl
              px-4 py-2.5 text-sm mb-6 focus:outline-none
              focus:border-[#006ca8]">

@if($products->count())

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">

@foreach($products as $product)

<div class="product-card bg-white rounded-2xl border border-slate-100
            shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group">

    {{-- FOTO --}}
    <div class="h-48 bg-slate-50 relative overflow-hidden">

        @if($product->image)

            <img src="{{ asset('storage/'.$product->image) }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

        @else

            <div class="w-full h-full flex items-center justify-center
                        bg-gradient-to-br from-blue-50 to-slate-100 group-hover:scale-105 transition-transform duration-500">

                <span class="text-4xl font-extrabold text-blue-200">
                    {{ strtoupper(substr($product->name, 0, 1)) }}
                </span>

            </div>

        @endif

        <span class="absolute top-3 left-3
            {{ $product->is_available ? 'bg-emerald-500 shadow-emerald-500/30' : 'bg-slate-400 shadow-slate-400/30' }}
            text-white text-[10px] font-extrabold px-3 py-1 rounded-full shadow-md tracking-wider uppercase">

            {{ $product->is_available ? 'Aktif' : 'Nonaktif' }}

        </span>

    </div>


    {{-- INFORMASI --}}
    <div class="p-5 flex flex-col flex-1">

        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
            {{ $product->category ?? 'Tanpa kategori' }}
        </p>

        <h3 class="product-name font-bold text-slate-800 text-lg mt-1 leading-tight line-clamp-2">
            {{ $product->name }}
        </h3>

        <p class="text-lg font-extrabold text-[#005ea2] mt-2 mb-4">
            Rp {{ number_format($product->price, 0, ',', '.') }}
        </p>

        <p class="text-xs text-gray-500 mt-1">
            Stok:
            <span class="font-semibold
                {{ $product->stock > 0 ? 'text-gray-700' : 'text-red-500' }}">
                {{ $product->stock }}
            </span>
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
            <div class="grid grid-cols-2 gap-2">

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
                            class="w-full bg-rose-50 hover:bg-rose-100 text-rose-600 py-2.5 rounded-xl text-xs font-bold transition-colors">
                        Hapus
                    </button>

                </form>

            </div>
        </div>

    </div>

</div>

@endforeach

</div>

@else

{{-- EMPTY --}}
<div class="bg-white rounded-2xl border text-center py-16">

    <div class="h-24 w-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-5">
        <span class="text-5xl">🍽️</span>
    </div>

    <h2 class="text-xl font-bold text-slate-800">
        Belum ada produk
    </h2>

    <p class="text-sm text-slate-500 mt-2 max-w-sm mx-auto">
        Katalog produk Anda masih kosong. Ayo tambahkan produk pertama Anda agar customer bisa mulai memesan.
    </p>

    <a href="{{ route('tenant.products.create') }}"
       class="inline-flex items-center mt-6 bg-[#005ea2] hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all hover:-translate-y-0.5">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Tambah Produk
    </a>

</div>

@endif


{{-- DETAIL MODAL --}}
<div id="detailModal"
     class="hidden fixed inset-0 bg-black/50 z-50
            items-center justify-center p-5">

    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl max-h-[90vh] overflow-hidden flex flex-col transform transition-transform scale-95" id="detailModalCard">

        <div id="detailImage"
             class="h-64 bg-slate-100 flex items-center justify-center relative">
            <button onclick="closeModal('detailModal')" class="absolute top-4 right-4 h-8 w-8 bg-black/20 hover:bg-black/40 backdrop-blur-md rounded-full flex items-center justify-center text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6 md:p-8 overflow-y-auto">

            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                Detail Produk
            </p>

            <h2 id="detailName"
                class="text-2xl font-extrabold text-slate-800 mt-1 leading-tight">
            </h2>

            <p id="detailPrice"
               class="text-2xl font-extrabold text-[#005ea2] mt-3">
            </p>

            <div class="mt-6 space-y-4 text-sm bg-slate-50 p-4 rounded-xl border border-slate-100">

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


{{-- EDIT MODAL --}}
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
        const name = card.querySelector('.product-name').textContent.toLowerCase();
        if (name.includes(keyword)) {
            card.classList.remove('hidden');
            card.classList.add('flex');
        } else {
            card.classList.add('hidden');
            card.classList.remove('flex');
        }
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
    if (button.dataset.image) {
        image.innerHTML = `<img src="${button.dataset.image}" class="w-full h-full object-cover">
                           <button onclick="closeModal('detailModal')" class="absolute top-4 right-4 h-8 w-8 bg-black/20 hover:bg-black/40 backdrop-blur-md rounded-full flex items-center justify-center text-white transition-colors z-10"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>`;
    } else {
        image.innerHTML = `<div class="w-full h-full bg-gradient-to-br from-blue-50 to-slate-100 flex items-center justify-center"><span class="text-6xl font-extrabold text-blue-200">${button.dataset.name.charAt(0).toUpperCase()}</span></div>
                           <button onclick="closeModal('detailModal')" class="absolute top-4 right-4 h-8 w-8 bg-black/5 hover:bg-black/10 rounded-full flex items-center justify-center text-slate-500 transition-colors z-10"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>`;
    }

    openModal('detailModal', 'detailModalCard');
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

    openModal('editModal', 'editModalCard');
}

function openModal(modalId, cardId) {
    const modal = document.getElementById(modalId);
    const card = document.getElementById(cardId);

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Slight delay for animation
    setTimeout(() => {
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
    }, 10);
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    // Find the card inside this modal
    const card = modal.querySelector('div[id$="Card"]');

    if (card) {
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
    }

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
}
</script>
@endsection
