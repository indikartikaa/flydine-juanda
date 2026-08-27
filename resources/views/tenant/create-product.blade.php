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
    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4">
        @foreach ($errors->all() as $error)
            <p class="text-sm text-red-600">• {{ $error }}</p>
        @endforeach
    </div>
@endif

<form action="{{ route('tenant.products.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- KIRI --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- INFORMASI DASAR --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">

                <h2 class="text-lg font-bold text-gray-800 mb-5 border-b pb-3">
                    Informasi Dasar Menu
                </h2>

                <div class="space-y-4">

                    <div>
                        <label class="block text-sm font-semibold mb-1">
                            Nama Menu <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               placeholder="Contoh: Kopi Susu"
                               class="w-full border border-gray-300 rounded-lg
                                      px-4 py-2.5 text-sm focus:border-[#005ea2]
                                      focus:ring-1 focus:ring-[#005ea2] outline-none">
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-semibold mb-1">
                                Kategori
                            </label>

                            <select name="category"
                                    class="w-full border border-gray-300 rounded-lg
                                           px-4 py-2.5 text-sm bg-white">
                                <option value="">Pilih kategori...</option>
                                <option value="Makanan Utama">Makanan Utama</option>
                                <option value="Camilan">Camilan / Snack</option>
                                <option value="Minuman">Minuman</option>
                                <option value="Dessert">Dessert / Bakery</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1">
                                Harga Dasar (Rp)
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="number"
                                   name="price"
                                   value="{{ old('price') }}"
                                   min="0"
                                   required
                                   placeholder="5000"
                                   class="w-full border border-gray-300 rounded-lg
                                          px-4 py-2.5 text-sm focus:border-[#005ea2]
                                          outline-none">
                        </div>

                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">
                            Deskripsi Menu
                        </label>

                        <textarea name="description"
                                  rows="3"
                                  maxlength="300"
                                  placeholder="Deskripsi menu..."
                                  class="w-full border border-gray-300 rounded-lg
                                         px-4 py-2.5 text-sm outline-none">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox"
                               name="is_available"
                               value="1"
                               checked
                               class="rounded border-gray-300 text-[#8dc63f]">

                        <span class="text-sm font-medium text-gray-700">
                            Produk tersedia
                        </span>
                    </div>

                </div>
            </div>


            {{-- VARIAN --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">

                <div class="flex justify-between items-center border-b pb-3 mb-4">

                    <h2 class="text-lg font-bold">
                        Opsi Kustomisasi (Varian/Topping)
                    </h2>

                    <button type="button"
                            onclick="addVariantGroup()"
                            class="text-sm font-bold text-[#8dc63f]">
                        + Tambah Grup Opsi
                    </button>

                </div>

                <p class="text-sm text-gray-500 mb-4">
                    Contoh: Ukuran Gelas, Pilihan Saus, Level Gula.
                </p>

                <div id="variant-list" class="space-y-4">

                    <div class="variant-group border border-blue-100
                                bg-blue-50/30 rounded-lg p-4">

                        <div class="flex justify-between mb-3">

                            <input type="text"
                                   name="variant_groups[0][name]"
                                   placeholder="Nama grup, contoh: Ukuran Gelas"
                                   class="w-1/2 border rounded px-3 py-2 text-sm">

                            <button type="button"
                                    onclick="removeGroup(this)"
                                    class="text-red-500 text-sm">
                                Hapus Grup
                            </button>

                        </div>

                        <select name="variant_groups[0][required]"
                                class="border rounded px-3 py-2 text-sm mb-3 bg-white">

                            <option value="1">Wajib pilih satu</option>
                            <option value="0">Opsional</option>

                        </select>

                        <div class="option-list space-y-2">

                            <div class="option-row flex gap-2">

                                <input type="text"
                                       name="variant_groups[0][options][0][name]"
                                       placeholder="Contoh: Small"
                                       class="flex-1 border rounded px-3 py-2 text-sm">

                                <input type="number"
                                       name="variant_groups[0][options][0][price]"
                                       placeholder="+ Rp"
                                       value="0"
                                       class="w-32 border rounded px-3 py-2 text-sm">

                                <button type="button"
                                        onclick="removeOption(this)"
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
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">

                <h2 class="text-lg font-bold mb-4 border-b pb-3">
                    Foto Produk
                </h2>

                <label for="image"
                       class="block border-2 border-dashed border-gray-300
                              rounded-xl p-5 text-center cursor-pointer
                              hover:bg-gray-50">

                    <div id="uploadPlaceholder">

                        <div class="text-4xl text-gray-300 mb-2">
                            🖼️
                        </div>

                        <p class="text-sm font-semibold text-[#005ea2]">
                            Upload file foto
                        </p>

                        <p class="text-xs text-gray-400 mt-1">
                            JPG, JPEG, PNG maksimal 5 MB
                        </p>

                    </div>

                    <img id="imagePreview"
                         class="hidden w-full h-52 object-cover rounded-lg"
                         alt="Preview">

                </label>

                <input id="image"
                       type="file"
                       name="image"
                       accept="image/png,image/jpeg"
                       class="hidden"
                       onchange="previewImage(event)">

            </div>


            {{-- SUBMIT --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border">

                <button type="submit"
                        class="w-full bg-[#8dc63f] hover:bg-green-600
                               text-white py-3 rounded-lg text-sm
                               font-bold shadow-md">

                    SIMPAN & PUBLIKASIKAN

                </button>

                <a href="{{ route('tenant.products') }}"
                   class="block text-center w-full mt-3
                          border border-gray-300 py-3 rounded-lg
                          text-sm font-bold text-gray-600">

                    Batal

                </a>

            </div>

        </div>

    </div>

</form>


<script>
    let groupIndex = 1;

    function previewImage(event) {
        const file = event.target.files[0];

        if (!file) return;

        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('uploadPlaceholder');

        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
        placeholder.classList.add('hidden');
    }

    function addOption(button) {
        const group = button.closest('.variant-group');
        const list = group.querySelector('.option-list');
        const groupPosition =
            [...document.querySelectorAll('.variant-group')].indexOf(group);

        const optionIndex = list.querySelectorAll('.option-row').length;

        const row = document.createElement('div');

        row.className = 'option-row flex gap-2';

        row.innerHTML = `
            <input type="text"
                name="variant_groups[${groupPosition}][options][${optionIndex}][name]"
                placeholder="Nama pilihan"
                class="flex-1 border rounded px-3 py-2 text-sm">

            <input type="number"
                name="variant_groups[${groupPosition}][options][${optionIndex}][price]"
                value="0"
                placeholder="+ Rp"
                class="w-32 border rounded px-3 py-2 text-sm">

            <button type="button"
                onclick="removeOption(this)"
                class="text-red-400 px-2">✕</button>
        `;

        list.appendChild(row);
    }

    function removeOption(button) {
        button.closest('.option-row').remove();
    }

    function removeGroup(button) {
        const groups = document.querySelectorAll('.variant-group');

        if (groups.length > 1) {
            button.closest('.variant-group').remove();
        }
    }

    function addVariantGroup() {
        const container = document.getElementById('variant-list');

        const div = document.createElement('div');

        div.className =
            'variant-group border border-blue-100 bg-blue-50/30 rounded-lg p-4';

        div.innerHTML = `
            <div class="flex justify-between mb-3">

                <input type="text"
                    name="variant_groups[${groupIndex}][name]"
                    placeholder="Nama grup opsi"
                    class="w-1/2 border rounded px-3 py-2 text-sm">

                <button type="button"
                    onclick="removeGroup(this)"
                    class="text-red-500 text-sm">
                    Hapus Grup
                </button>

            </div>

            <select name="variant_groups[${groupIndex}][required]"
                class="border rounded px-3 py-2 text-sm mb-3 bg-white">

                <option value="1">Wajib pilih satu</option>
                <option value="0">Opsional</option>

            </select>

            <div class="option-list space-y-2">

                <div class="option-row flex gap-2">

                    <input type="text"
                        name="variant_groups[${groupIndex}][options][0][name]"
                        placeholder="Nama pilihan"
                        class="flex-1 border rounded px-3 py-2 text-sm">

                    <input type="number"
                        name="variant_groups[${groupIndex}][options][0][price]"
                        value="0"
                        placeholder="+ Rp"
                        class="w-32 border rounded px-3 py-2 text-sm">

                    <button type="button"
                        onclick="removeOption(this)"
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

        container.appendChild(div);

        groupIndex++;
    }
</script>

@endsection
