@extends('layouts.tenant')

@section('title', 'Tambah Menu Baru')

@section('content')

<div class="mb-6">
    <a href="{{ route('tenant.products') }}"
       class="text-sm font-semibold text-[#005ea2] hover:underline">
        ← Kembali ke Katalog
    </a>
</div>

@if ($errors->any())
    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-600">
        @foreach ($errors->all() as $error)
            <p>• {{ $error }}</p>
        @endforeach
    </div>
@endif

<form action="{{ route('tenant.products.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-7">

        {{-- KIRI --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- INFORMASI PRODUK --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

                <h2 class="text-lg font-bold text-gray-800 border-b pb-3 mb-5">
                    Informasi Dasar Menu
                </h2>

                <div class="space-y-4">

                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            Nama Menu <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Contoh: Kopi Susu"
                               required
                               maxlength="120"
                               class="w-full border border-gray-300 rounded-xl
                                      px-4 py-3 text-sm outline-none
                                      focus:border-[#005ea2]">
                    </div>

                    {{-- Kategori + Harga --}}
                    <div class="grid md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-semibold mb-2">
                                Kategori
                            </label>

                            <select name="category"
                                    class="w-full border border-gray-300 rounded-xl
                                           px-4 py-3 text-sm bg-white">

                                <option value="">Pilih kategori...</option>
                                <option value="Makanan Utama">Makanan Utama</option>
                                <option value="Snack">Snack</option>
                                <option value="Minuman">Minuman</option>
                                <option value="Dessert">Dessert</option>

                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2">
                                Harga Dasar (Rp) <span class="text-red-500">*</span>
                            </label>

                            <input type="number"
                                   name="price"
                                   value="{{ old('price') }}"
                                   placeholder="5000"
                                   min="0"
                                   required
                                   class="w-full border border-gray-300 rounded-xl
                                          px-4 py-3 text-sm">
                        </div>

                    </div>

                    {{-- Stok --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            Jumlah Stok <span class="text-red-500">*</span>
                        </label>

                        <input type="number"
                               name="stock"
                               value="{{ old('stock', 0) }}"
                               min="0"
                               required
                               class="w-full border border-gray-300 rounded-xl
                                      px-4 py-3 text-sm">
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            Deskripsi Menu
                        </label>

                        <textarea name="description"
                                  rows="3"
                                  maxlength="300"
                                  placeholder="Contoh: Kopi susu creamy dengan rasa manis yang ringan..."
                                  class="w-full border border-gray-300 rounded-xl
                                         px-4 py-3 text-sm outline-none
                                         focus:border-[#005ea2]">{{ old('description') }}</textarea>

                        <p class="text-xs text-gray-400 mt-1">
                            Maksimal 300 karakter.
                        </p>
                    </div>

                    {{-- Status --}}
                    <label class="flex items-center gap-2">

                        <input type="checkbox"
                               name="is_available"
                               value="1"
                               checked
                               class="rounded text-[#8dc63f]">

                        <span class="text-sm font-medium text-gray-700">
                            Produk tersedia
                        </span>

                    </label>

                </div>
            </div>


            {{-- OPSI KUSTOMISASI --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

                <div class="flex justify-between items-center border-b pb-3 mb-4">

                    <h2 class="text-lg font-bold">
                        Opsi Kustomisasi
                    </h2>

                    <button type="button"
                            onclick="addGroup()"
                            class="text-sm font-bold text-[#8dc63f]">
                        + Tambah Grup Opsi
                    </button>

                </div>

                <p class="text-sm text-gray-500 mb-4">
                    Contoh: Ukuran Gelas, Level Gula, Pilihan Saus, atau Topping.
                </p>

                <div id="groupList">

                    {{-- GROUP DEFAULT --}}
                    <div class="group-box border border-blue-100
                                bg-blue-50/30 rounded-xl p-4">

                        <div class="flex justify-between items-center mb-3">

                            <input type="text"
                                   name="groups[0][name]"
                                   placeholder="Nama grup, contoh: Ukuran Gelas"
                                   class="w-1/2 border rounded-lg px-3 py-2 text-sm">

                            <button type="button"
                                    onclick="this.closest('.group-box').remove()"
                                    class="text-red-500 text-xs font-semibold">
                                Hapus Grup
                            </button>

                        </div>

                        <select name="groups[0][required]"
                                class="border rounded-lg px-3 py-2 text-sm bg-white mb-3">

                            <option value="1">Wajib dipilih</option>
                            <option value="0">Opsional</option>

                        </select>

                        <div class="option-list space-y-2">

                            <div class="option-row flex gap-2">

                                <input type="text"
                                       name="groups[0][options][0][name]"
                                       placeholder="Ukuran S"
                                       class="flex-1 border rounded-lg px-3 py-2 text-sm">

                                <input type="number"
                                       name="groups[0][options][0][price]"
                                       value="0"
                                       min="0"
                                       class="w-28 border rounded-lg px-3 py-2 text-sm">

                                <button type="button"
                                        onclick="this.closest('.option-row').remove()"
                                        class="text-red-400 px-2">
                                    ✕
                                </button>

                            </div>

                            <div class="option-row flex gap-2">

                                <input type="text"
                                       name="groups[0][options][1][name]"
                                       placeholder="Ukuran M"
                                       class="flex-1 border rounded-lg px-3 py-2 text-sm">

                                <input type="number"
                                       name="groups[0][options][1][price]"
                                       value="3000"
                                       min="0"
                                       class="w-28 border rounded-lg px-3 py-2 text-sm">

                                <button type="button"
                                        onclick="this.closest('.option-row').remove()"
                                        class="text-red-400 px-2">
                                    ✕
                                </button>

                            </div>

                            <div class="option-row flex gap-2">

                                <input type="text"
                                       name="groups[0][options][2][name]"
                                       placeholder="Ukuran L"
                                       class="flex-1 border rounded-lg px-3 py-2 text-sm">

                                <input type="number"
                                       name="groups[0][options][2][price]"
                                       value="5000"
                                       min="0"
                                       class="w-28 border rounded-lg px-3 py-2 text-sm">

                                <button type="button"
                                        onclick="this.closest('.option-row').remove()"
                                        class="text-red-400 px-2">
                                    ✕
                                </button>

                            </div>

                        </div>

                        <button type="button"
                                onclick="addOption(this)"
                                class="mt-3 text-xs font-semibold text-[#005ea2]">
                            + Tambah pilihan lain
                        </button>

                    </div>

                </div>
            </div>

        </div>


        {{-- KANAN --}}
        <div class="space-y-6">

            {{-- FOTO --}}
            <div class="bg-white rounded-2xl border border-gray-100
                        shadow-sm p-6">

                <h2 class="text-lg font-bold border-b pb-3 mb-4">
                    Foto Produk
                </h2>

                <label for="image"
                       class="block border-2 border-dashed border-gray-300
                              rounded-xl p-5 text-center cursor-pointer
                              hover:bg-gray-50">

                    <div id="uploadPlaceholder">

                        <div class="text-4xl mb-2">
                            🖼️
                        </div>

                        <p class="text-sm font-semibold text-[#005ea2]">
                            Upload file foto
                        </p>

                        <p class="text-xs text-gray-400 mt-1">
                            JPG, JPEG, PNG maksimal 5 MB
                        </p>

                    </div>

                    <img id="preview"
                         class="hidden w-full h-48 object-cover rounded-xl">

                </label>

                <input id="image"
                       type="file"
                       name="image"
                       accept="image/jpeg,image/png"
                       class="hidden"
                       onchange="previewImage(event)">

            </div>


            {{-- CATATAN --}}
            <div class="bg-white rounded-2xl border border-gray-100
                        shadow-sm p-6">

                <h2 class="font-bold mb-3">
                    Catatan Produk
                </h2>

                <textarea name="note"
                          rows="4"
                          maxlength="1000"
                          placeholder="Contoh: Sajikan tanpa es jika pelanggan memilih..."
                          class="w-full border border-gray-300 rounded-xl
                                 px-4 py-3 text-sm outline-none
                                 focus:border-[#005ea2]">{{ old('note') }}</textarea>

            </div>


            {{-- ACTION --}}
            <div class="bg-white rounded-2xl border border-gray-100
                        shadow-sm p-6">

                <button type="submit"
                        class="w-full bg-[#8dc63f]
                               hover:bg-green-600 text-white
                               py-3 rounded-xl text-sm font-bold">
                    SIMPAN & PUBLIKASIKAN
                </button>

                <a href="{{ route('tenant.products') }}"
                   class="block text-center w-full mt-3
                          border border-gray-300
                          py-3 rounded-xl text-sm font-semibold">
                    Batal
                </a>

            </div>

        </div>

    </div>

</form>


<script>

let groupIndex = 1;

function previewImage(event)
{
    const file = event.target.files[0];

    if (!file) return;

    const preview = document.getElementById('preview');
    const placeholder = document.getElementById('uploadPlaceholder');

    preview.src = URL.createObjectURL(file);
    preview.classList.remove('hidden');
    placeholder.classList.add('hidden');
}

function addOption(button)
{
    const group = button.closest('.group-box');
    const list = group.querySelector('.option-list');
    const groupId = [...document.querySelectorAll('.group-box')]
        .indexOf(group);

    const index = list.querySelectorAll('.option-row').length;

    const row = document.createElement('div');

    row.className = 'option-row flex gap-2';

    row.innerHTML = `
        <input type="text"
               name="groups[${groupId}][options][${index}][name]"
               placeholder="Nama pilihan"
               class="flex-1 border rounded-lg px-3 py-2 text-sm">

        <input type="number"
               name="groups[${groupId}][options][${index}][price]"
               value="0"
               min="0"
               class="w-28 border rounded-lg px-3 py-2 text-sm">

        <button type="button"
                onclick="this.closest('.option-row').remove()"
                class="text-red-400 px-2">
            ✕
        </button>
    `;

    list.appendChild(row);
}

function addGroup()
{
    const container = document.getElementById('groupList');

    const groupId = groupIndex++;

    const group = document.createElement('div');

    group.className =
        'group-box border border-blue-100 bg-blue-50/30 rounded-xl p-4 mt-4';

    group.innerHTML = `
        <div class="flex justify-between items-center mb-3">

            <input type="text"
                   name="groups[${groupId}][name]"
                   placeholder="Nama grup opsi"
                   class="w-1/2 border rounded-lg px-3 py-2 text-sm">

            <button type="button"
                    onclick="this.closest('.group-box').remove()"
                    class="text-red-500 text-xs font-semibold">
                Hapus Grup
            </button>

        </div>

        <select name="groups[${groupId}][required]"
                class="border rounded-lg px-3 py-2 text-sm bg-white mb-3">

            <option value="1">Wajib dipilih</option>
            <option value="0">Opsional</option>

        </select>

        <div class="option-list">

            <div class="option-row flex gap-2">

                <input type="text"
                       name="groups[${groupId}][options][0][name]"
                       placeholder="Nama pilihan"
                       class="flex-1 border rounded-lg px-3 py-2 text-sm">

                <input type="number"
                       name="groups[${groupId}][options][0][price]"
                       value="0"
                       min="0"
                       class="w-28 border rounded-lg px-3 py-2 text-sm">

                <button type="button"
                        onclick="this.closest('.option-row').remove()"
                        class="text-red-400 px-2">
                    ✕
                </button>

            </div>

        </div>

        <button type="button"
                onclick="addOption(this)"
                class="mt-3 text-xs font-semibold text-[#005ea2]">
            + Tambah pilihan lain
        </button>
    `;

    container.appendChild(group);
}

</script>

@endsection
