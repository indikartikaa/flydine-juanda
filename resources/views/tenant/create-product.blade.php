@extends('layouts.tenant')

@section('title', 'Tambah Menu Baru')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('tenant.products') }}"
       class="flex items-center text-sm font-bold text-slate-500 hover:text-[#005ea2] transition-colors bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Katalog
    </a>
</div>

@if ($errors->any())
    <div class="mb-8 bg-rose-50 border border-rose-200 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="text-sm font-bold text-rose-800">Terdapat kesalahan pada input Anda:</h3>
        </div>
        <ul class="list-disc list-inside text-sm text-rose-600 pl-2 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('tenant.products.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- KIRI --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- INFORMASI PRODUK --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-lg font-extrabold text-slate-800 tracking-tight">
                        Informasi Dasar Menu
                    </h2>
                    <p class="text-sm text-slate-500 font-medium mt-1">Lengkapi detail dasar tentang produk yang akan dijual.</p>
                </div>

                <div class="p-6 md:p-8 space-y-6">

                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Nama Menu <span class="text-rose-500">*</span>
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Contoh: Kopi Susu Aren"
                               required
                               maxlength="120"
                               class="w-full border border-slate-200 rounded-xl
                                      px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20
                                      focus:border-[#005ea2] transition-colors bg-slate-50 focus:bg-white">
                    </div>

                    {{-- Kategori + Harga --}}
                    <div class="grid md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Kategori
                            </label>

                            <select name="category"
                                    class="w-full border border-slate-200 rounded-xl
                                           px-4 py-3 text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#005ea2] transition-colors appearance-none">
                                <option value="">Pilih kategori...</option>
                                <option value="Makanan Utama">Makanan Utama</option>
                                <option value="Snack">Snack</option>
                                <option value="Minuman">Minuman</option>
                                <option value="Dessert">Dessert</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                Harga Dasar (Rp) <span class="text-rose-500">*</span>
                            </label>

                            <input type="number"
                                   name="price"
                                   value="{{ old('price') }}"
                                   placeholder="15000"
                                   min="0"
                                   required
                                   class="w-full border border-slate-200 rounded-xl
                                          px-4 py-3 text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#005ea2] transition-colors">
                        </div>

                    </div>

                    {{-- Stok --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Jumlah Stok <span class="text-rose-500">*</span>
                        </label>

                        <input type="number"
                               name="stock"
                               value="{{ old('stock', 0) }}"
                               min="0"
                               required
                               class="w-full border border-slate-200 rounded-xl
                                      px-4 py-3 text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#005ea2] transition-colors">
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            Deskripsi Menu
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  maxlength="300"
                                  placeholder="Berikan deskripsi menarik tentang menu ini..."
                                  class="w-full border border-slate-200 rounded-xl
                                         px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20
                                         focus:border-[#005ea2] transition-colors bg-slate-50 focus:bg-white">{{ old('description') }}</textarea>

                        <p class="text-[11px] font-bold text-slate-400 mt-2 uppercase tracking-wider">
                            Maksimal 300 karakter.
                        </p>
                    </div>

                    {{-- Status --}}
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 mt-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox"
                                   name="is_available"
                                   value="1"
                                   checked
                                   class="w-5 h-5 rounded border-slate-300 text-[#005ea2] focus:ring-[#005ea2] cursor-pointer">
                            <span class="ml-3 text-sm font-bold text-slate-700">
                                Produk langsung tersedia (Aktif)
                            </span>
                        </label>
                    </div>

                </div>
            </div>


            {{-- OPSI KUSTOMISASI --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 md:p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-800 tracking-tight">
                            Opsi Kustomisasi
                        </h2>
                        <p class="text-sm text-slate-500 font-medium mt-1">
                            Contoh: Ukuran Gelas, Level Gula, Topping.
                        </p>
                    </div>

                    <button type="button"
                            onclick="addGroup()"
                            class="text-sm font-bold text-[#005ea2] bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-xl transition-colors">
                        + Tambah Grup
                    </button>
                </div>

                <div class="p-6 md:p-8" id="groupList">
                    {{-- GROUP DEFAULT --}}
                    <div class="group-box border border-slate-200 bg-slate-50 rounded-2xl p-6 relative group mb-6">
                        <button type="button"
                                onclick="this.closest('.group-box').remove()"
                                class="absolute top-4 right-4 h-8 w-8 bg-white border border-rose-200 text-rose-500 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-rose-50 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>

                        <div class="grid sm:grid-cols-2 gap-4 mb-5 pr-8">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Nama Grup</label>
                                <input type="text"
                                       name="groups[0][name]"
                                       placeholder="Contoh: Ukuran Gelas"
                                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#005ea2] bg-white">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Sifat Pilihan</label>
                                <select name="groups[0][required]"
                                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#005ea2] bg-white appearance-none">
                                    <option value="1">Wajib dipilih</option>
                                    <option value="0">Opsional</option>
                                </select>
                            </div>
                        </div>

                        <div class="option-list space-y-3 bg-white p-4 rounded-xl border border-slate-200">
                            <div class="option-row flex items-center gap-3">
                                <input type="text"
                                       name="groups[0][options][0][name]"
                                       placeholder="Nama pilihan (ex: Regular)"
                                       class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#005ea2]">
                                <input type="number"
                                       name="groups[0][options][0][price]"
                                       value="0"
                                       min="0"
                                       placeholder="+ Harga"
                                       class="w-28 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#005ea2]">
                                <button type="button"
                                        onclick="this.closest('.option-row').remove()"
                                        class="text-rose-400 hover:text-rose-600 p-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>

                            <div class="option-row flex items-center gap-3">
                                <input type="text"
                                       name="groups[0][options][1][name]"
                                       placeholder="Nama pilihan (ex: Large)"
                                       class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#005ea2]">
                                <input type="number"
                                       name="groups[0][options][1][price]"
                                       value="3000"
                                       min="0"
                                       placeholder="+ Harga"
                                       class="w-28 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#005ea2]">
                                <button type="button"
                                        onclick="this.closest('.option-row').remove()"
                                        class="text-rose-400 hover:text-rose-600 p-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </div>

                        <button type="button"
                                onclick="addOption(this)"
                                class="mt-4 flex items-center text-[13px] font-bold text-[#005ea2] hover:text-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                            Tambah Pilihan Lain
                        </button>
                    </div>
                </div>
            </div>

        </div>


        {{-- KANAN --}}
        <div class="space-y-8">

            {{-- FOTO --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-lg font-extrabold text-slate-800 tracking-tight">
                        Foto Produk
                    </h2>
                </div>
                
                <div class="p-6">
                    <label for="image"
                           class="block border-2 border-dashed border-slate-200 bg-slate-50 rounded-2xl p-8 text-center cursor-pointer hover:bg-slate-100 hover:border-blue-300 transition-all group overflow-hidden relative">

                        <div id="uploadPlaceholder" class="flex flex-col items-center">
                            <div class="h-16 w-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform">
                                <span class="text-2xl">📸</span>
                            </div>
                            <p class="text-sm font-extrabold text-[#005ea2]">
                                Upload file foto
                            </p>
                            <p class="text-xs font-medium text-slate-400 mt-2">
                                Format JPG/PNG maksimal 5 MB
                            </p>
                        </div>

                        <img id="preview"
                             class="hidden absolute inset-0 w-full h-full object-cover">
                        
                        <div id="previewOverlay" class="hidden absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <p class="text-white font-bold text-sm">Ganti Foto</p>
                        </div>

                    </label>

                    <input id="image"
                           type="file"
                           name="image"
                           accept="image/jpeg,image/png"
                           class="hidden"
                           onchange="previewImage(event)">
                </div>
            </div>


            {{-- CATATAN --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-lg font-extrabold text-slate-800 tracking-tight">
                        Catatan Internal
                    </h2>
                </div>
                
                <div class="p-6">
                    <textarea name="note"
                              rows="4"
                              maxlength="1000"
                              placeholder="Catatan khusus untuk staf saat menyiapkan menu ini..."
                              class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#005ea2] transition-colors bg-slate-50 focus:bg-white">{{ old('note') }}</textarea>
                </div>
            </div>


            {{-- ACTION --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <button type="submit"
                        class="w-full bg-[#005ea2] hover:bg-blue-700 text-white py-3.5 rounded-xl text-sm font-extrabold shadow-md shadow-blue-500/20 transition-all hover:-translate-y-0.5 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Simpan & Publikasikan
                </button>

                <a href="{{ route('tenant.products') }}"
                   class="block text-center w-full mt-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 py-3 rounded-xl text-sm font-bold transition-colors">
                    Batalkan
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
    const overlay = document.getElementById('previewOverlay');

    preview.src = URL.createObjectURL(file);
    preview.classList.remove('hidden');
    overlay.classList.remove('hidden');
    placeholder.classList.add('hidden');
}

function addOption(button)
{
    const group = button.closest('.group-box');
    const list = group.querySelector('.option-list');
    const groupId = [...document.querySelectorAll('.group-box')].indexOf(group);
    const index = list.querySelectorAll('.option-row').length;

    const row = document.createElement('div');
    row.className = 'option-row flex items-center gap-3';
    row.innerHTML = `
        <input type="text"
               name="groups[${groupId}][options][${index}][name]"
               placeholder="Nama pilihan"
               class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#005ea2]">

        <input type="number"
               name="groups[${groupId}][options][${index}][price]"
               value="0"
               min="0"
               class="w-28 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#005ea2]">

        <button type="button"
                onclick="this.closest('.option-row').remove()"
                class="text-rose-400 hover:text-rose-600 p-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    `;
    list.appendChild(row);
}

function addGroup()
{
    const container = document.getElementById('groupList');
    const groupId = groupIndex++;

    const group = document.createElement('div');
    group.className = 'group-box border border-slate-200 bg-slate-50 rounded-2xl p-6 relative group mb-6';
    group.innerHTML = `
        <button type="button"
                onclick="this.closest('.group-box').remove()"
                class="absolute top-4 right-4 h-8 w-8 bg-white border border-rose-200 text-rose-500 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-rose-50 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>

        <div class="grid sm:grid-cols-2 gap-4 mb-5 pr-8">
            <div>
                <label class="block text-[11px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Nama Grup</label>
                <input type="text"
                       name="groups[${groupId}][name]"
                       placeholder="Contoh: Ukuran Gelas"
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#005ea2] bg-white">
            </div>
            <div>
                <label class="block text-[11px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Sifat Pilihan</label>
                <select name="groups[${groupId}][required]"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-[#005ea2] bg-white appearance-none">
                    <option value="1">Wajib dipilih</option>
                    <option value="0">Opsional</option>
                </select>
            </div>
        </div>

        <div class="option-list space-y-3 bg-white p-4 rounded-xl border border-slate-200">
            <div class="option-row flex items-center gap-3">
                <input type="text"
                       name="groups[${groupId}][options][0][name]"
                       placeholder="Nama pilihan"
                       class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#005ea2]">
                <input type="number"
                       name="groups[${groupId}][options][0][price]"
                       value="0"
                       min="0"
                       class="w-28 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#005ea2]">
                <button type="button"
                        onclick="this.closest('.option-row').remove()"
                        class="text-rose-400 hover:text-rose-600 p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        <button type="button"
                onclick="addOption(this)"
                class="mt-4 flex items-center text-[13px] font-bold text-[#005ea2] hover:text-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
            Tambah Pilihan Lain
        </button>
    `;
    container.appendChild(group);
}
</script>

@endsection
