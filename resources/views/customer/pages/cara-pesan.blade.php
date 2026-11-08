@extends('customer.pages.layout')

@section('title', 'Cara Pesan')

@section('page_header')
    <!-- Header Lebar Penuh (Widescreen Juanda Airport Standard) -->
    <div class="w-full max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pb-2">
            <!-- Left Header Content -->
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 leading-tight mb-4">
                    Cara Pesan
                </h1>
                
                <p class="text-slate-600 text-sm sm:text-base lg:text-lg leading-relaxed font-normal">
                    Panduan praktis memesan hidangan favorit di Terminal 1 dan Terminal 2 Bandara Internasional Juanda langsung dari ruang tunggu tanpa perlu antre di kasir.
                </p>
            </div>

            <!-- Right Header Quick Highlights (Stat Strip) -->
            <div class="grid grid-cols-3 gap-2 sm:gap-3 lg:w-96 shrink-0">
                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-[#005ea2] mb-0.5">Tanpa Antre</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Pesan dari lounge</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-amber-600 mb-0.5">Sinkron FIDS</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Prioritas antrean</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-emerald-600 mb-0.5">Kode Nota</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Ambil instan</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="w-full max-w-7xl mx-auto -mt-6 sm:-mt-10 relative z-20 mb-20"
     x-data="{
        activeSection: 'langkah-1',
        init() {
            const updateActive = () => {
                const sections = document.querySelectorAll('section[id]');
                const scrollPos = window.scrollY + 180;
                
                if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight - 80)) {
                    if (sections.length > 0) {
                        this.activeSection = sections[sections.length - 1].getAttribute('id');
                        return;
                    }
                }

                sections.forEach(sec => {
                    const top = sec.offsetTop;
                    const height = sec.offsetHeight;
                    const id = sec.getAttribute('id');
                    if (scrollPos >= top && scrollPos < top + height) {
                        this.activeSection = id;
                    }
                });
            };
            window.addEventListener('scroll', updateActive, { passive: true });
            updateActive();
        }
     }">

    <!-- MOBILE QUICK NAV PILL (Navigasi cepat di layar HP dengan Auto Highlight) -->
    <div class="lg:hidden sticky top-16 z-30 bg-white/95 backdrop-blur-md border border-slate-200/80 rounded-2xl shadow-sm mb-6 p-2">
        <div class="flex items-center space-x-2 overflow-x-auto scrollbar-hide text-xs font-semibold whitespace-nowrap px-1 py-1">
            <span class="text-slate-400 font-bold uppercase text-[10px] pl-1">Langkah:</span>
            <a href="#langkah-1" @click="activeSection = 'langkah-1'" :class="activeSection === 'langkah-1' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">1. Lokasi</a>
            <a href="#langkah-2" @click="activeSection = 'langkah-2'" :class="activeSection === 'langkah-2' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">2. Menu</a>
            <a href="#langkah-3" @click="activeSection = 'langkah-3'" :class="activeSection === 'langkah-3' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">3. Penerbangan</a>
            <a href="#langkah-4" @click="activeSection = 'langkah-4'" :class="activeSection === 'langkah-4' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">4. Pembayaran</a>
            <a href="#langkah-5" @click="activeSection = 'langkah-5'" :class="activeSection === 'langkah-5' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">5. Ambil</a>
            <a href="#tips-penumpang" @click="activeSection = 'tips-penumpang'" :class="activeSection === 'tips-penumpang' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">Tips</a>
        </div>
    </div>

    <!-- WIDESCREEN DESKTOP DUAL-COLUMN WORKSPACE (Full di Laptop, Rapi di HP) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- LEFT COLUMN: STICKY DESKTOP SIDEBAR WITH SCROLLSPY HIGHLIGHT -->
        <aside class="hidden lg:block lg:col-span-4 sticky top-24 space-y-5">
            
            <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs">
                <div class="flex items-center space-x-2.5 pb-4 mb-3 border-b border-slate-100 text-slate-800">
                    <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="text-xs font-black tracking-wider uppercase">Langkah Pemesanan</span>
                </div>

                <nav class="space-y-1.5 text-xs font-semibold">
                    <a href="#langkah-1" @click="activeSection = 'langkah-1'" 
                       :class="activeSection === 'langkah-1' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>01. Pilih Terminal &amp; Restoran</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#langkah-2" @click="activeSection = 'langkah-2'" 
                       :class="activeSection === 'langkah-2' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>02. Tentukan Menu Hidangan</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#langkah-3" @click="activeSection = 'langkah-3'" 
                       :class="activeSection === 'langkah-3' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>03. Masukkan Jadwal Penerbangan</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#langkah-4" @click="activeSection = 'langkah-4'" 
                       :class="activeSection === 'langkah-4' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>04. Pembayaran QRIS Cepat</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#langkah-5" @click="activeSection = 'langkah-5'" 
                       :class="activeSection === 'langkah-5' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>05. Lacak &amp; Ambil di Gerai</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#tips-penumpang" @click="activeSection = 'tips-penumpang'" 
                       :class="activeSection === 'tips-penumpang' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>06. Tips Penting Penumpang</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </nav>
            </div>

            <!-- CTA Card -->
            <div class="bg-gradient-to-br from-blue-50/80 via-white to-slate-50 border border-blue-100/90 rounded-3xl p-6 shadow-xs">
                <h3 class="text-sm font-bold text-slate-900 mb-2">Sudah Berada di Bandara?</h3>
                <p class="text-xs text-slate-600 leading-relaxed font-normal mb-4">
                    Pesan makanan sekarang selagi menunggu panggilan boarding di Terminal 1 atau 2.
                </p>
                <a href="{{ route('customer.menu') }}" class="w-full inline-flex items-center justify-center space-x-2 bg-[#005ea2] hover:bg-blue-700 text-white font-bold text-xs py-2.5 px-4 rounded-xl transition-colors shadow-xs">
                    <span>Lihat Daftar Menu</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>

        </aside>

        <!-- RIGHT COLUMN: FULL-WIDTH CONTENT CARDS -->
        <main class="lg:col-span-8 space-y-6">

            <!-- 01. PILIH TERMINAL & RESTORAN -->
            <section id="langkah-1" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            01
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Pilih Terminal &amp; Restoran
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Buka katalog utama FlyDine dan tentukan lokasi terminal keberangkatan Anda:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#005ea2]"></span>
                                <span>Terminal 1 (T1)</span>
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Keberangkatan penerbangan domestik (Lion Air, Citilink, Batik Air, Super Air Jet, Sriwijaya).
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Terminal 2 (T2)</span>
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Keberangkatan penerbangan Garuda Indonesia domestik serta seluruh penerbangan internasional.
                            </p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100 text-xs text-slate-600">
                        <strong class="text-slate-900 font-bold block mb-0.5">Catatan Penting:</strong>
                        Pastikan Anda memilih restoran di terminal yang sama dengan gate keberangkatan tiket Anda agar penjemputan makanan tidak terhalang batas gedung antar terminal.
                    </div>
                </div>
            </section>

            <!-- 02. TENTUKAN MENU HIDANGAN -->
            <section id="langkah-2" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            02
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Tentukan Menu Hidangan
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Jelajahi beragam pilihan hidangan dari restoran tenant yang tersedia:
                    </p>

                    <div class="space-y-3">
                        <div class="flex items-start space-x-3 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <div class="w-6 h-6 rounded-md bg-blue-100 text-[#005ea2] flex items-center justify-center shrink-0 mt-0.5 font-bold text-xs">A</div>
                            <div class="text-xs sm:text-sm">
                                <strong class="text-slate-900 font-bold block mb-0.5">Kustomisasi Pesanan</strong>
                                <p class="text-slate-500 leading-relaxed">Pilih jumlah porsi dan tambahkan catatan rasa khusus (contoh: tidak pedas, saus dipisah, es sedikit) pada kolom catatan.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <div class="w-6 h-6 rounded-md bg-blue-100 text-[#005ea2] flex items-center justify-center shrink-0 mt-0.5 font-bold text-xs">B</div>
                            <div class="text-xs sm:text-sm">
                                <strong class="text-slate-900 font-bold block mb-0.5">Aturan 1 Restoran Per Transaksi</strong>
                                <p class="text-slate-500 leading-relaxed">Keranjang pemesanan berlaku untuk 1 restoran terpilih agar waktu masak dapur sinkron dan hidangan selesai bersamaan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 03. MASUKKAN JADWAL PENERBANGAN -->
            <section id="langkah-3" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            03
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Masukkan Jadwal Penerbangan
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Di halaman konfirmasi pesanan (keranjang), lengkapi data penerbangan Anda:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Nomor Penerbangan</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Masukkan kode penerbangan sesuai boarding pass Anda (contoh: <strong>GA-318</strong>, <strong>JT-690</strong>, <strong>QG-710</strong>).
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Waktu Boarding</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Tentukan jam boarding. Dapur restoran akan menerima indikator prioritas masak agar makanan siap sebelum Anda harus masuk ke gerbang keberangkatan.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 04. PEMBAYARAN QRIS CEPAT -->
            <section id="langkah-4" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            04
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Pembayaran QRIS Cepat
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Selesaikan transaksi secara non-tunai langsung dari layar ponsel Anda:
                    </p>

                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                        <div class="flex items-center space-x-2 text-slate-900 font-bold text-sm">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            <span>Scan QRIS Instan</span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Pindai kode QRIS yang muncul menggunakan aplikasi e-wallet (GoPay, OVO, Dana, ShopeePay) atau mobile banking favorit Anda. Pembayaran diverifikasi otomatis tanpa perlu upload struk transfer manual.
                        </p>
                    </div>
                </div>
            </section>

            <!-- 05. LACAK & AMBIL DI GERAI -->
            <section id="langkah-5" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xs">
                            05
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Lacak &amp; Ambil di Gerai
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Pantau status persiapan pesanan Anda secara langsung di halaman pelacakan (*Live Tracking*):
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 text-xs">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <span class="text-[#005ea2] font-black uppercase text-[10px] block mb-1">Status 1</span>
                            <h3 class="font-bold text-slate-900 mb-1">Dapur Menyiapkan</h3>
                            <p class="text-slate-500 leading-relaxed">Koki gerai sedang meracik hidangan pesanan Anda.</p>
                        </div>

                        <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80">
                            <span class="text-amber-700 font-black uppercase text-[10px] block mb-1">Status 2</span>
                            <h3 class="font-bold text-slate-900 mb-1">Siap Diambil</h3>
                            <p class="text-slate-600 leading-relaxed">Makanan selesai dikemas dan siap di konter pengambilan gerai.</p>
                        </div>

                        <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200/80">
                            <span class="text-emerald-700 font-black uppercase text-[10px] block mb-1">Status 3</span>
                            <h3 class="font-bold text-slate-900 mb-1">Tunjukkan Kode</h3>
                            <p class="text-slate-600 leading-relaxed">Tunjukkan kode nota 4-digit di layar Anda kepada staf kasir.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 06. TIPS PENTING PENUMPANG -->
            <section id="tips-penumpang" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            06
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Tips Penting Penumpang
                        </h2>
                    </div>
                </div>

                <div class="space-y-3.5 text-xs sm:text-sm text-slate-600">
                    <div class="flex items-start space-x-3 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <svg class="w-5 h-5 text-[#005ea2] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <strong class="text-slate-900 font-bold block mb-0.5">Pesan Lebih Awal</strong>
                            <p class="text-slate-500 leading-relaxed">Disarankan memesan minimal 30–45 menit sebelum jadwal boarding agar Anda memiliki waktu santai menikmati hidangan di boarding lounge.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <svg class="w-5 h-5 text-[#005ea2] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <div>
                            <strong class="text-slate-900 font-bold block mb-0.5">Perhatikan Panggilan Gate</strong>
                            <p class="text-slate-500 leading-relaxed">Selalu pantau monitor keberangkatan bandara di sekitar Anda bila terdapat perpindahan pintu boarding dari maskapai.</p>
                        </div>
                    </div>
                </div>

                <!-- Bottom Action -->
                <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-slate-500 text-center sm:text-left">
                        Siap menikmati makanan bandara tanpa antre?
                    </p>
                    <a href="{{ route('customer.menu') }}" class="inline-flex items-center space-x-2 bg-[#005ea2] hover:bg-blue-700 text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition-colors shadow-xs">
                        <span>Mulai Pesan Sekarang</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>
            </section>

        </main>
    </div>
</div>
@endsection
