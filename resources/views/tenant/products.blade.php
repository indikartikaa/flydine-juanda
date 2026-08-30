@extends('layouts.tenant')

@section('title', 'Katalog Produk Tenant')

@section('content')

{{-- ALERT SUCCESS --}}
@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl text-sm font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 focus:outline-none bg-emerald-100/50 hover:bg-emerald-100 p-1.5 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
@endif

{{-- HEADER SECTION --}}
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <div>
        <h1 class="text-2xl font-black text-[#005ea2] tracking-tight">Katalog Produk</h1>
        <p class="text-sm text-gray-500 font-medium mt-1">
            Kelola menu tenant Anda untuk ditampilkan kepada pelanggan.
        </p>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
        <div class="relative w-full sm:w-64">
            <input id="searchProduct" type="text" placeholder="Cari produk..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </div>
        <a href="{{ route('tenant.products.create') }}" class="bg-[#005ea2] hover:bg-blue-800 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all hover:-translate-y-0.5 flex items-center justify-center whitespace-nowrap outline-none focus:ring-4 focus:ring-blue-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Produk
        </a>
    </div>
</div>

{{-- PRODUCT GRID --}}
@if($products->count())
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

    @foreach($products as $product)
    <div class="product-card bg-white rounded-[1.25rem] border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group flex flex-col h-full overflow-hidden">

        {{-- FOTO --}}
        <div class="h-44 w-full bg-gray-50 relative overflow-hidden shrink-0 border-b border-gray-50">
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-gray-100 group-hover:scale-110 transition-transform duration-700">
                    <span class="text-6xl font-black text-blue-200 select-none">
                        {{ strtoupper(substr($product->name, 0, 1)) }}
                    </span>
                </div>
            @endif

            {{-- BADGE STATUS --}}
            <div class="absolute top-3 left-3 flex gap-2">
                <span class="{{ $product->is_available ? 'bg-emerald-500' : 'bg-gray-500' }} text-white text-[9px] font-black px-2.5 py-1 rounded-md shadow-sm tracking-widest uppercase flex items-center gap-1">
                    @if($product->is_available)
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                    @endif
                    {{ $product->is_available ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
        </div>

        {{-- INFORMASI --}}
        <div class="p-5 flex-1 flex flex-col bg-white">
            <div class="flex justify-between items-start mb-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-yellow-500">
                    {{ $product->category ?? 'Tanpa kategori' }}
                </p>
                <p class="text-[10px] font-bold text-gray-400 bg-gray-50 px-2 py-0.5 rounded-md border border-gray-100">
                    Stok: <span class="{{ $product->stock > 0 ? 'text-gray-700' : 'text-red-500' }}">{{ $product->stock }}</span>
                </p>
            </div>

            <h3 class="product-name font-extrabold text-gray-800 text-base leading-snug line-clamp-2 mt-1">
                {{ $product->name }}
            </h3>

            <p class="text-lg font-black text-[#005ea2] mt-2 mb-4">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>

            <div class="mt-auto space-y-2.5">
                {{-- DETAIL BUTTON --}}
                <button type="button" 
                        onclick="showDetail(this)" 
                        data-name="{{ $product->name }}" 
                        data-category="{{ $product->category ?? '-' }}" 
                        data-price="{{ number_format($product->price, 0, ',', '.') }}" 
                        data-description="{{ $product->description ?? '-' }}" 
                        data-note="{{ $product->note ?? '-' }}" 
                        data-image="{{ $product->image ? asset('storage/'.$product->image) : '' }}" 
                        data-status="{{ $product->is_available ? 'Aktif' : 'Tidak tersedia' }}" 
                        class="w-full bg-blue-50 hover:bg-blue-100 text-[#005ea2] py-2.5 rounded-xl text-xs font-bold transition-colors flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    Lihat Detail
                </button>

                {{-- EDIT / HAPUS --}}
                <div class="grid grid-cols-2 gap-2.5">
                    <button type="button" 
                            onclick="editProduct(this)" 
                            data-id="{{ $product->id }}" 
                            data-name="{{ $product->name }}" 
                            data-category="{{ $product->category ?? '' }}" 
                            data-description="{{ $product->description ?? '' }}" 
                            data-note="{{ $product->note ?? '' }}" 
                            data-price="{{ $product->price }}" 
                            data-status="{{ $product->is_available }}" 
                            class="w-full border border-gray-200 hover:border-gray-300 hover:bg-gray-50 text-gray-600 py-2 rounded-xl text-xs font-bold transition-colors">
                        Edit
                    </button>

                    <form method="POST" action="{{ route('tenant.products.destroy', $product) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full border border-rose-100 bg-rose-50 hover:bg-rose-500 hover:text-white text-rose-600 py-2 rounded-xl text-xs font-bold transition-colors">
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

{{-- EMPTY STATE --}}
<div class="bg-white rounded-[1.5rem] border border-dashed border-gray-300 text-center py-24 shadow-sm flex flex-col items-center justify-center">
    <div class="h-24 w-24 bg-gray-50 rounded-full flex items-center justify-center mb-5 border border-gray-100 shadow-inner">
        <span class="text-5xl drop-shadow-sm">🍽️</span>
    </div>
    <h2 class="text-xl font-black text-gray-800">Belum ada produk</h2>
    <p class="text-sm text-gray-500 mt-2 max-w-sm mx-auto font-medium leading-relaxed">
        Katalog produk Anda masih kosong. Ayo tambahkan menu andalan Anda sekarang agar pelanggan bisa mulai memesan.
    </p>
    <a href="{{ route('tenant.products.create') }}" class="inline-flex items-center mt-8 bg-[#005ea2] hover:bg-blue-800 text-white px-8 py-3.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-500/20 transition-all hover:-translate-y-1 outline-none focus:ring-4 focus:ring-blue-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Tambah Produk Pertama
    </a>
</div>

@endif

{{-- DETAIL MODAL --}}
<div id="detailModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 items-center justify-center p-4 sm:p-5 transition-opacity">
    <div class="bg-white rounded-[1.5rem] max-w-md w-full shadow-2xl max-h-[90vh] overflow-hidden flex flex-col transform transition-transform duration-300 scale-95" id="detailModalCard">
        
        <div id="detailImage" class="h-60 bg-gray-50 flex items-center justify-center relative border-b border-gray-100">
            <button onclick="closeModal('detailModal')" class="absolute top-4 right-4 h-8 w-8 bg-black/20 hover:bg-black/40 backdrop-blur-md rounded-full flex items-center justify-center text-white transition-colors z-10 focus:outline-none focus:ring-2 focus:ring-white/50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="p-6 md:p-8 overflow-y-auto">
            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Info Menu</p>
            <h2 id="detailName" class="text-2xl font-black text-gray-800 leading-tight"></h2>
            <p id="detailPrice" class="text-2xl font-black text-[#005ea2] mt-2"></p>

            <div class="mt-6 space-y-4 text-sm bg-gray-50 p-5 rounded-2xl border border-gray-100">
                <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kategori</p>
                    <p id="detailCategory" class="font-bold text-gray-700"></p>
                </div>
                <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status</p>
                    <p id="detailStatus" class="font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md"></p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Deskripsi</p>
                    <p id="detailDescription" class="text-gray-600 font-medium leading-relaxed"></p>
                </div>
                <div class="pt-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Catatan Dapur</p>
                    <p id="detailNote" class="text-gray-600 font-medium italic"></p>
                </div>
            </div>

            <button onclick="closeModal('detailModal')" class="w-full mt-8 bg-gray-100 hover:bg-gray-200 text-gray-800 py-3.5 rounded-xl text-sm font-bold transition-colors outline-none focus:ring-4 focus:ring-gray-200">
                Tutup Jendela
            </button>
        </div>
    </div>
</div>

{{-- EDIT MODAL --}}
<div id="editModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 items-center justify-center p-4 sm:p-5 transition-opacity">
    <div class="bg-white rounded-[1.5rem] max-w-lg w-full max-h-[90vh] overflow-hidden flex flex-col shadow-2xl transform transition-transform duration-300 scale-95" id="editModalCard">
        
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-lg font-black text-gray-800">Edit Produk</h2>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="overflow-y-auto p-6 md:p-8">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Nama Produk</label>
                <input id="editName" name="name" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition-all">
            </div>

            {{-- Kategori & Harga Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Kategori</label>
                    <select id="editCategory" name="category" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition-all appearance-none">
                        <option value="">Pilih kategori</option>
                        <option value="Makanan Utama">Makanan Utama</option>
                        <option value="Snack">Snack</option>
                        <option value="Minuman">Minuman</option>
                        <option value="Dessert">Dessert</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Harga (Rp)</label>
                    <input id="editPrice" type="number" name="price" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition-all">
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Deskripsi (Opsional)</label>
                <textarea id="editDescription" name="description" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition-all"></textarea>
            </div>

            {{-- Catatan --}}
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Catatan Dapur</label>
                <textarea id="editNote" name="note" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#005ea2]/20 focus:border-[#005ea2] transition-all"></textarea>
            </div>

            {{-- Foto --}}
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Perbarui Foto (Opsional)</label>
                <input type="file" name="image" accept="image/png,image/jpeg,image/webp" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#005ea2] hover:file:bg-blue-100 transition-all cursor-pointer">
            </div>

            {{-- Status --}}
            <div class="mb-8 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input id="editStatus" type="checkbox" name="is_available" value="1" class="w-5 h-5 text-[#8dc63f] bg-white border-gray-300 rounded focus:ring-[#8dc63f] focus:ring-2 transition-all">
                    <span class="text-sm font-bold text-gray-700">Tampilkan produk ini di katalog</span>
                </label>
            </div>

            <div class="grid grid-cols-2 gap-3 mt-auto">
                <button type="button" onclick="closeModal('editModal')" class="w-full border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 py-3.5 rounded-xl text-sm font-bold transition-colors outline-none focus:ring-4 focus:ring-gray-100">
                    Batal
                </button>
                <button type="submit" class="w-full bg-[#8dc63f] hover:bg-green-600 text-white py-3.5 rounded-xl text-sm font-bold shadow-md shadow-green-500/20 transition-all hover:-translate-y-0.5 outline-none focus:ring-4 focus:ring-green-100">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Logika Pencarian
const search = document.getElementById('searchProduct');
search?.addEventListener('input', function () {
    const keyword = this.value.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
        const name = card.querySelector('.product-name').textContent.toLowerCase();
        // Cukup ubah style display kartunya secara langsung
        if (name.includes(keyword)) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }
    });
});

// Modal Populator
function showDetail(button) {
    document.getElementById('detailName').textContent = button.dataset.name;
    document.getElementById('detailCategory').textContent = button.dataset.category || '-';
    document.getElementById('detailPrice').textContent = 'Rp ' + button.dataset.price;
    document.getElementById('detailDescription').textContent = button.dataset.description || '-';
    document.getElementById('detailNote').textContent = button.dataset.note || '-';
    document.getElementById('detailStatus').textContent = button.dataset.status;

    const image = document.getElementById('detailImage');
    if (button.dataset.image) {
        image.innerHTML = `<img src="${button.dataset.image}" class="w-full h-full object-cover">
                           <button onclick="closeModal('detailModal')" class="absolute top-4 right-4 h-8 w-8 bg-black/40 hover:bg-black/60 backdrop-blur-md rounded-full flex items-center justify-center text-white transition-colors z-10"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>`;
    } else {
        image.innerHTML = `<div class="w-full h-full bg-gradient-to-br from-blue-50 to-gray-100 flex items-center justify-center"><span class="text-7xl font-black text-blue-200 select-none">${button.dataset.name.charAt(0).toUpperCase()}</span></div>
                           <button onclick="closeModal('detailModal')" class="absolute top-4 right-4 h-8 w-8 bg-black/5 hover:bg-black/10 rounded-full flex items-center justify-center text-gray-500 transition-colors z-10"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>`;
    }

    openModal('detailModal', 'detailModalCard');
}

function editProduct(button) {
    document.getElementById('editName').value = button.dataset.name;
    document.getElementById('editCategory').value = button.dataset.category;
    document.getElementById('editDescription').value = button.dataset.description;
    document.getElementById('editPrice').value = button.dataset.price;
    document.getElementById('editNote').value = button.dataset.note;
    document.getElementById('editStatus').checked = button.dataset.status === '1';
    document.getElementById('editForm').action = `/tenant/products/${button.dataset.id}`;

    openModal('editModal', 'editModalCard');
}

// Interaksi Animasi Modal JS Murni (Vanilla)
function openModal(modalId, cardId) {
    const modal = document.getElementById(modalId);
    const card = document.getElementById(cardId);

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Memberikan delay mikro agar transisi scale berjalan
    requestAnimationFrame(() => {
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
    });
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    const card = modal.querySelector('div[id$="Card"]');

    if (card) {
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
    }

    // Menunggu durasi animasi selesai sebelum hide (300ms sesuai class Tailwind)
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}
</script>
@endsection