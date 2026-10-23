@extends('layouts.tenant')

@section('title', 'Tambah Menu Baru')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">

    <!-- ========================================================================= -->
    <!-- 1. HEADER HALAMAN & TOMBOL KEMBALI                                        -->
    <!-- ========================================================================= -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('tenant.products') }}" 
                   class="p-2 -ml-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                   title="Kembali ke Katalog">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Tambah Menu Baru</h2>
            </div>
            <p class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">
                Lengkapi rincian menu, tentukan harga, tetapkan kuota stok porsi harian, dan unggah foto hidangan.
            </p>
        </div>

        <div class="shrink-0">
            <a href="{{ route('tenant.products') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-bold shadow-2xs transition cursor-pointer">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Batal & Kembali</span>
            </a>
        </div>
    </div>

    <!-- Alert Kesalahan Validasi -->
    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 sm:p-5 shadow-2xs">
            <div class="flex items-center gap-2 mb-2 text-rose-800">
                <svg class="h-5 w-5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h4 class="text-sm font-bold">Mohon periksa kembali input Anda:</h4>
            </div>
            <ul class="list-disc list-inside text-xs sm:text-sm text-rose-600 space-y-1 pl-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- 2. FORMULIR TAMBAH PRODUK                                                 -->
    <!-- ========================================================================= -->
    <form action="{{ route('tenant.products.store') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf

        <!-- ======================= KOLOM KIRI (2 SPAN) ======================= -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Card: Detail Dasar Menu -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-base font-black text-slate-900 tracking-tight">Informasi Dasar Hidangan</h3>
                    <p class="text-xs text-slate-500 font-semibold mt-0.5">Nama menu, kategori, harga jual, dan stok porsi harian.</p>
                </div>

                <div class="p-5 sm:p-6 space-y-4 sm:space-y-5">
                    
                    <!-- Nama Menu -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                            Nama Menu Hidangan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               maxlength="120" 
                               placeholder="Contoh: Nasi Lemak With Chicken Katsu" 
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition">
                    </div>

                    <!-- Kategori & Harga -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <!-- Kategori (Dengan Panah Dropdown Rapi Tanpa Dobel) -->
                        <div>
                            <label for="category" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                Kategori Sajian <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="category" 
                                        name="category" 
                                        required 
                                        style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: none;"
                                        class="w-full pl-4 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition appearance-none cursor-pointer">
                                    <option value="">Pilih Kategori Menu...</option>
                                    <option value="Makanan Utama" {{ old('category') === 'Makanan Utama' ? 'selected' : '' }}>Makanan Utama</option>
                                    <option value="Snack" {{ old('category') === 'Snack' ? 'selected' : '' }}>Snack / Camilan</option>
                                    <option value="Minuman" {{ old('category') === 'Minuman' ? 'selected' : '' }}>Minuman</option>
                                    <option value="Dessert" {{ old('category') === 'Dessert' ? 'selected' : '' }}>Dessert</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Harga Jual -->
                        <div>
                            <label for="price" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                Harga Jual (Rp) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-xs sm:text-sm font-black text-slate-400">Rp</span>
                                <input type="number" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price') }}" 
                                       required 
                                       min="0" 
                                       step="any" 
                                       placeholder="50000" 
                                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition">
                            </div>
                        </div>

                    </div>

                    <!-- Jumlah Stok Porsi (Default 50, Bukan 0!) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="stock" class="block text-xs font-bold uppercase tracking-wider text-slate-600">
                                    Jumlah Stok (Porsi) <span class="text-rose-500">*</span>
                                </label>
                                <span class="text-[11px] font-semibold text-slate-400">Porsi siap saji</span>
                            </div>
                            <input type="number" 
                                   id="stock" 
                                   name="stock" 
                                   value="{{ old('stock', 50) }}" 
                                   required 
                                   min="0" 
                                   placeholder="50" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition">
                            <p class="text-[11px] text-slate-400 font-medium mt-1">
                                Rekomendasi kuota porsi harian. Bila diisi 0, menu otomatis berstatus Habis.
                            </p>
                        </div>

                        <!-- Estimasi SLA Masak Dapur -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">
                                    Target SLA Dapur
                                </label>
                                <span class="text-[11px] font-bold text-emerald-600">Standar Bandara</span>
                            </div>
                            <div class="w-full px-4 py-3 bg-slate-100/70 border border-slate-200/60 rounded-xl text-xs sm:text-sm font-bold text-slate-700 flex items-center justify-between">
                                <span>≤ 15 Menit Penyajian</span>
                                <span class="text-emerald-600 text-xs font-extrabold">✓ Terstandar</span>
                            </div>
                            <p class="text-[11px] text-slate-400 font-medium mt-1">
                                Waktu maksimal penyiapan hidangan sebelum diserahkan ke penumpang/porter.
                            </p>
                        </div>
                    </div>

                    <!-- Deskripsi Hidangan -->
                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                            Deskripsi Menu (Opsional)
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3" 
                                  maxlength="300" 
                                  placeholder="Jelaskan kelezatan hidangan, bahan utama, atau sensasi rasa..." 
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition">{{ old('description') }}</textarea>
                        <p class="text-[11px] font-semibold text-slate-400 mt-1">
                            Maksimal 300 karakter. Ditampilkan pada katalog aplikasi pelanggan.
                        </p>
                    </div>

                    <!-- Catatan Khusus Dapur -->
                    <div>
                        <label for="note" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                            Catatan Khusus Dapur (Opsional)
                        </label>
                        <textarea id="note" 
                                  name="note" 
                                  rows="2" 
                                  maxlength="1000" 
                                  placeholder="Contoh: Pisahkan sambal, saus pedas di wadah kecil, alergen kacang..." 
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition">{{ old('note') }}</textarea>
                        <p class="text-[11px] font-semibold text-slate-400 mt-1">
                            Instruksi internal untuk staf dan koki gerai saat pesanan disiapkan.
                        </p>
                    </div>

                    <!-- Checkbox Status Aktif Langsung -->
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" 
                                   name="is_available" 
                                   value="1" 
                                   checked 
                                   class="w-5 h-5 mt-0.5 text-[#005ea2] bg-white border-slate-300 rounded focus:ring-[#005ea2] focus:ring-2 transition cursor-pointer">
                            <div>
                                <span class="text-xs sm:text-sm font-bold text-slate-800 block">Langsung Aktifkan & Tayangkan Menu Ini</span>
                                <span class="text-[11px] text-slate-500 font-medium">Bila dicentang, menu akan langsung muncul di katalog pemesanan online penumpang Juanda.</span>
                            </div>
                        </label>
                    </div>

                </div>
            </div>

        </div>

        <!-- ======================= KOLOM KANAN (1 SPAN) ======================= -->
        <div class="space-y-6">

            <!-- Card: Upload Foto Produk -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-base font-black text-slate-900 tracking-tight">Foto Produk</h3>
                    <p class="text-xs text-slate-500 font-semibold mt-0.5">Unggah foto sajian menarik untuk menggugah selera pembeli.</p>
                </div>

                <div class="p-5">
                    <label for="imageInput" 
                           class="block border-2 border-dashed border-slate-300 hover:border-[#005ea2] bg-slate-50 hover:bg-slate-100/60 rounded-2xl p-6 text-center cursor-pointer transition-all duration-200 relative overflow-hidden group">
                        
                        <!-- Placeholder Sebelum Upload -->
                        <div id="uploadPlaceholder" class="flex flex-col items-center">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-xs border border-slate-200 mb-3 group-hover:scale-110 transition-transform text-[#005ea2]">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-xs sm:text-sm font-extrabold text-[#005ea2] block">
                                Klik untuk Unggah Foto
                            </span>
                            <span class="text-[11px] font-semibold text-slate-400 mt-1 block">
                                Format JPG, PNG, WEBP (Maksimal 5 MB)
                            </span>
                        </div>

                        <!-- Gambar Preview Saat Dipilih -->
                        <img id="imagePreview" 
                             class="hidden w-full h-48 object-cover rounded-xl shadow-xs" 
                             alt="Preview Foto Menu">

                        <!-- Overlay Ganti Foto -->
                        <div id="previewOverlay" 
                             class="hidden absolute inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="px-3 py-1.5 rounded-lg bg-white text-slate-800 text-xs font-bold shadow-md">
                                Ganti Foto
                            </span>
                        </div>

                    </label>

                    <input type="file" 
                           id="imageInput" 
                           name="image" 
                           accept="image/jpeg,image/png,image/webp,image/jpg" 
                           class="hidden" 
                           onchange="handleImagePreview(event)">

                    <p class="text-[11px] text-slate-400 font-medium text-center mt-3">
                        *Jika tidak diunggah, katalog otomatis menggunakan ikon sajian kuliner elegan.
                    </p>
                </div>
            </div>

            <!-- Card: Tombol Simpan & Publikasikan -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-5 space-y-3">
                <button type="submit" 
                        class="w-full bg-[#005ea2] hover:bg-[#004b82] active:scale-95 text-white py-3 rounded-xl text-sm font-bold shadow-md shadow-blue-600/20 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan & Publikasikan</span>
                </button>

                <a href="{{ route('tenant.products') }}" 
                   class="block text-center w-full bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 py-2.5 rounded-xl text-xs font-bold transition cursor-pointer">
                    Batalkan
                </a>
            </div>

        </div>

    </form>

</div>

<script nonce="{{ $cspNonce ?? '' }}">
function handleImagePreview(event) {
    const file = event.target.files[0];
    if (!file) return;

    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('uploadPlaceholder');
    const overlay = document.getElementById('previewOverlay');

    preview.src = URL.createObjectURL(file);
    preview.classList.remove('hidden');
    overlay.classList.remove('hidden');
    placeholder.classList.add('hidden');
}
</script>
@endsection
