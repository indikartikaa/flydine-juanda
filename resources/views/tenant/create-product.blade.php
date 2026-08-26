@extends('layouts.tenant')

@section('title', 'Tambah Menu Baru')

@section('content')
    <!-- Tombol Kembali -->
    <div class="mb-6">
        <a href="{{ url('/tenant/products') }}" class="inline-flex items-center text-sm font-semibold text-[#005ea2] hover:text-blue-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali ke Katalog
        </a>
    </div>

    <!-- Form Utama -->
    <form action="#" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Kolom Kiri: Informasi Dasar & Varian Dinamis -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Informasi Dasar -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Informasi Dasar Menu</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Menu <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:border-[#005ea2] focus:ring-1 focus:ring-[#005ea2] outline-none" placeholder="Contoh: Kopi Tarik (Coffee Shop) atau Paket Combo (Fast Food)">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori Internal <span class="text-red-500">*</span></label>
                                <select class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:border-[#005ea2] focus:ring-1 focus:ring-[#005ea2] outline-none bg-white">
                                    <option disabled selected>Pilih kategori...</option>
                                    <option>Makanan Utama</option>
                                    <option>Camilan / Snack</option>
                                    <option>Minuman</option>
                                    <option>Dessert / Bakery</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Dasar (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:border-[#005ea2] focus:ring-1 focus:ring-[#005ea2] outline-none" placeholder="Misal: 45000">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Menu</label>
                            <textarea rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:border-[#005ea2] focus:ring-1 focus:ring-[#005ea2] outline-none" placeholder="Ceritakan kelezatan menu ini (maks 300 karakter)..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Kustomisasi Varian Dinamis -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100" id="variant-container">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-lg font-bold text-gray-800">Opsi Kustomisasi (Varian/Topping)</h2>
                        <button type="button" onclick="addVariantGroup()" class="text-sm font-bold text-[#8dc63f] hover:text-green-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Tambah Grup Opsi
                        </button>
                    </div>
                    
                    <p class="text-sm text-gray-500 mb-4">Buat opsi khusus sesuai jenis tenant Anda (misal: "Suhu Minuman", "Pilihan Saus", "Ukuran").</p>

                    <!-- Tempat Grup Varian akan ditambahkan via JS -->
                    <div id="variant-list" class="space-y-4">
                        <!-- Grup Varian Contoh (Bisa Dihapus) -->
                        <div class="border border-blue-100 bg-blue-50/30 rounded-lg p-4 variant-group relative">
                            <button type="button" onclick="this.parentElement.remove()" class="absolute top-3 right-3 text-red-400 hover:text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                            
                            <div class="grid grid-cols-2 gap-4 mb-3 pr-8">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Grup Opsi</label>
                                    <input type="text" class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm" placeholder="Misal: Ukuran Gelas">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Wajib Dipilih?</label>
                                    <select class="w-full border border-gray-300 rounded px-3 py-1.5 text-sm bg-white">
                                        <option value="1">Ya, pembeli wajib memilih 1</option>
                                        <option value="0">Tidak (Opsional / Bisa pilih banyak)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Daftar Pilihan dalam Grup -->
                            <div class="pl-4 border-l-2 border-[#8dc63f] space-y-2">
                                <div class="flex gap-2">
                                    <input type="text" class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Nama pilihan (Misal: Reguler)">
                                    <input type="number" class="w-32 border border-gray-300 rounded px-2 py-1 text-sm" placeholder="+ Rp (Misal: 0)">
                                    <button type="button" class="text-gray-400 hover:text-red-500 px-1">✕</button>
                                </div>
                                <div class="flex gap-2">
                                    <input type="text" class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm" placeholder="Nama pilihan (Misal: Large)">
                                    <input type="number" class="w-32 border border-gray-300 rounded px-2 py-1 text-sm" placeholder="+ Rp (Misal: 5000)">
                                    <button type="button" class="text-gray-400 hover:text-red-500 px-1">✕</button>
                                </div>
                                <button type="button" class="text-xs text-[#005ea2] font-semibold mt-1 hover:underline">+ Tambah pilihan lain</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Kolom Kanan: Foto & Submit -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Upload Foto -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Foto Resolusi Tinggi</h2>
                    
                    <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-400 px-6 py-10 hover:bg-gray-50 transition-colors cursor-pointer">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                            </svg>
                            <div class="mt-4 flex text-sm leading-6 text-gray-600 justify-center">
                                <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-[#005ea2] focus-within:outline-none focus-within:ring-2 focus-within:ring-[#005ea2] focus-within:ring-offset-2 hover:text-blue-800">
                                    <span>Upload file foto</span>
                                    <input id="file-upload" name="file-upload" type="file" class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs leading-5 text-gray-500 mt-1">PNG, JPG up to 5MB</p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <button type="submit" class="w-full bg-[#8dc63f] hover:bg-green-600 text-white px-4 py-3 rounded-lg text-sm font-bold shadow-md transition-colors uppercase tracking-wide">
                        Simpan & Publikasikan
                    </button>
                    <button type="button" class="w-full mt-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-600 px-4 py-3 rounded-lg text-sm font-bold shadow-sm transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Script Sederhana untuk nambah grup Varian di Frontend (Opsional untuk MVP visual) -->
    <script>
        function addVariantGroup() {
            const container = document.getElementById('variant-list');
            const clone = container.children[0].cloneNode(true);
            // Kosongkan input di clone baru
            const inputs = clone.querySelectorAll('input[type="text"], input[type="number"]');
            inputs.forEach(input => input.value = '');
            container.appendChild(clone);
        }
    </script>
@endsection