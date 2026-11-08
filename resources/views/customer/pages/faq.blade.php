@extends('customer.pages.layout')

@section('title', 'Pusat Bantuan (FAQ)')

@section('page_header')
    <!-- Header Lebar Penuh (Widescreen Juanda Airport Standard) -->
    <div class="w-full max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pb-2">
            <!-- Left Header Content -->
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 leading-tight mb-4">
                    Pusat Bantuan &amp; FAQ
                </h1>
                
                <p class="text-slate-600 text-sm sm:text-base lg:text-lg leading-relaxed font-normal">
                    Temukan panduan cepat seputar pemesanan mandiri, integrasi waktu boarding, pembayaran QRIS, hingga layanan pelacakan tiket pengaduan di Bandara Internasional Juanda.
                </p>
            </div>

            <!-- Right Header Quick Highlights (Stat Strip) -->
            <div class="grid grid-cols-3 gap-2 sm:gap-3 lg:w-96 shrink-0">
                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-[#005ea2] mb-0.5">CS Siaga</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Bantuan cepat</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-amber-600 mb-0.5">Boarding</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Sinkron antrean</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-emerald-600 mb-0.5">QRIS</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Bayar instan</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="w-full max-w-7xl mx-auto -mt-6 sm:-mt-10 relative z-20 mb-20"
     x-data="{
        activeSection: 'umum',
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
            <span class="text-slate-400 font-bold uppercase text-[10px] pl-1">Menu:</span>
            <a href="#umum" @click="activeSection = 'umum'" :class="activeSection === 'umum' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">1. Umum</a>
            <a href="#pemesanan" @click="activeSection = 'pemesanan'" :class="activeSection === 'pemesanan' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">2. Pemesanan</a>
            <a href="#boarding" @click="activeSection = 'boarding'" :class="activeSection === 'boarding' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">3. Boarding</a>
            <a href="#pengambilan" @click="activeSection = 'pengambilan'" :class="activeSection === 'pengambilan' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">4. Pengambilan</a>
            <a href="#lacak-tiket" @click="activeSection = 'lacak-tiket'" :class="activeSection === 'lacak-tiket' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">5. Lacak Tiket</a>
            <a href="#hubungi-kami" @click="activeSection = 'hubungi-kami'" :class="activeSection === 'hubungi-kami' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">6. Kontak CS</a>
        </div>
    </div>

    <!-- WIDESCREEN DESKTOP DUAL-COLUMN WORKSPACE (Full di Laptop, Rapi di HP) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- LEFT COLUMN: STICKY DESKTOP SIDEBAR WITH SCROLLSPY HIGHLIGHT -->
        <aside class="hidden lg:block lg:col-span-4 sticky top-24 space-y-5">
            
            <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs">
                <div class="flex items-center space-x-2.5 pb-4 mb-3 border-b border-slate-100 text-slate-800">
                    <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xs font-black tracking-wider uppercase">Kategori Bantuan</span>
                </div>

                <nav class="space-y-1.5 text-xs font-semibold">
                    <a href="#umum" @click="activeSection = 'umum'" 
                       :class="activeSection === 'umum' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>01. Pertanyaan Umum</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#pemesanan" @click="activeSection = 'pemesanan'" 
                       :class="activeSection === 'pemesanan' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>02. Pemesanan &amp; Pembayaran</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#boarding" @click="activeSection = 'boarding'" 
                       :class="activeSection === 'boarding' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>03. Waktu Boarding &amp; Pesawat</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#pengambilan" @click="activeSection = 'pengambilan'" 
                       :class="activeSection === 'pengambilan' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>04. Pengambilan di Gerai</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#lacak-tiket" @click="activeSection = 'lacak-tiket'" 
                       :class="activeSection === 'lacak-tiket' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>05. Lacak Status Komplain</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#hubungi-kami" @click="activeSection = 'hubungi-kami'" 
                       :class="activeSection === 'hubungi-kami' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>06. Formulir Pengaduan CS</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </nav>
            </div>

            <!-- Sticky Support Banner -->
            <div class="bg-gradient-to-br from-[#09131f] to-[#005ea2] rounded-3xl p-6 text-white shadow-md relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                <div class="relative z-10">
                    <span class="text-[10px] font-black uppercase tracking-wider text-blue-200 block mb-1">Dukungan Penumpang</span>
                    <h4 class="text-base font-black leading-tight mb-2">Butuh Bantuan Mendesak?</h4>
                    <p class="text-xs text-blue-100/90 leading-relaxed font-normal mb-4">
                        Jika waktu boarding Anda tersisa kurang dari 20 menit dan memerlukan klarifikasi pesanan, segera hubungi Helpdesk Resmi Bandara.
                    </p>
                    <a href="#hubungi-kami" class="inline-flex items-center text-xs font-bold text-amber-300 hover:text-amber-200 transition-colors">
                        <span>Kirim laporan kendala</span>
                        <svg class="w-3.5 h-3.5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>
            </div>

        </aside>

        <!-- RIGHT COLUMN: FULL-WIDTH CONTENT CARDS -->
        <main class="lg:col-span-8 space-y-6">

            <!-- 01. PERTANYAAN UMUM -->
            <section id="umum" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            01
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Pertanyaan Umum
                        </h2>
                    </div>
                </div>

                <div class="space-y-3.5">
                    <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/60">
                        <h3 class="font-bold text-slate-900 text-sm mb-1.5">
                            Apa itu FlyDine dan bagaimana cara kerjanya di Bandara Juanda?
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            FlyDine adalah platform pemesanan makanan digital resmi di Bandara Internasional Juanda (Terminal 1 &amp; Terminal 2). Anda dapat melihat menu restoran bandara, memesan, dan membayar langsung dari ruang tunggu (boarding lounge), lalu mengambil pesanan saat makanan selesai tanpa perlu mengantre di kasir gerai.
                        </p>
                    </div>

                    <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/60">
                        <h3 class="font-bold text-slate-900 text-sm mb-1.5">
                            Apakah saya wajib membuat akun untuk memesan makanan?
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Tidak wajib. FlyDine didesain ramah penumpang transit sehingga Anda dapat langsung memesan sebagai tamu (guest) cukup dengan memasukkan nama pemesan dan nomor telepon Anda.
                        </p>
                    </div>

                    <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/60">
                        <h3 class="font-bold text-slate-900 text-sm mb-1.5">
                            Di mana saja restoran yang melayani pesanan FlyDine?
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Seluruh gerai makanan dan minuman resmi yang berada di concourse dan area ruang tunggu keberangkatan Terminal 1 (Domestik) dan Terminal 2 (Internasional &amp; Domestik) Bandara Internasional Juanda.
                        </p>
                    </div>
                </div>
            </section>

            <!-- 02. PEMESANAN & PEMBAYARAN -->
            <section id="pemesanan" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            02
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Pemesanan &amp; Pembayaran QRIS
                        </h2>
                    </div>
                </div>

                <div class="space-y-3.5">
                    <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/60">
                        <h3 class="font-bold text-slate-900 text-sm mb-1.5">
                            Metode pembayaran apa saja yang dapat digunakan?
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Kami mendukung QRIS nasional yang dapat dibayar melalui seluruh aplikasi e-wallet (GoPay, OVO, Dana, ShopeePay) serta aplikasi perbankan (BCA, Mandiri, BRI, BNI, dan bank lainnya). Pembayaran terkonfirmasi otomatis dalam hitungan detik.
                        </p>
                    </div>

                    <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/60">
                        <h3 class="font-bold text-slate-900 text-sm mb-1.5">
                            Bisakah memesan dari dua restoran berbeda sekaligus?
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Setiap satu transaksi pemesanan berlaku untuk satu restoran terpilih agar alur penyiapan dapur fokus dan makanan siap bersamaan. Anda dapat memesan dari restoran lain setelah menyelesaikan transaksi pertama.
                        </p>
                    </div>

                    <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/60">
                        <h3 class="font-bold text-slate-900 text-sm mb-1.5">
                            Apakah harga di FlyDine ada biaya tambahan tersembunyi?
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Tidak ada. Harga yang tertera pada katalog sudah bersifat final dan sudah mencakup pajak restoran resmi (PB1) di kawasan Bandara Internasional Juanda.
                        </p>
                    </div>
                </div>
            </section>

            <!-- 03. BOARDING & JADWAL PESAWAT -->
            <section id="boarding" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            03
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Waktu Boarding &amp; Keselamatan Penerbangan
                        </h2>
                    </div>
                </div>

                <div class="space-y-3.5">
                    <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/60">
                        <h3 class="font-bold text-slate-900 text-sm mb-1.5">
                            Mengapa sistem meminta waktu boarding saya saat checkout?
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Ini adalah fitur keselamatan eksklusif FlyDine Juanda. Sistem secara otomatis mencocokkan waktu persiapan makanan dengan jam boarding Anda. Jika waktu tersisa kurang dari batas minimal dapur (15 menit), pesanan akan ditolak otomatis agar Anda tidak tertinggal penerbangan.
                        </p>
                    </div>

                    <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/60">
                        <h3 class="font-bold text-slate-900 text-sm mb-1.5">
                            Bagaimana jika penerbangan saya mengalami delay / penundaan?
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Dapur tetap menyiapkan pesanan sesuai jadwal checkout awal Anda. Anda tetap dapat mengambil hidangan di gerai terkait sebelum naik pesawat.
                        </p>
                    </div>
                </div>
            </section>

            <!-- 04. PENGAMBILAN DI GERAI -->
            <section id="pengambilan" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            04
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Pengambilan Pesanan di Gerai
                        </h2>
                    </div>
                </div>

                <div class="space-y-3.5">
                    <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/60">
                        <h3 class="font-bold text-slate-900 text-sm mb-1.5">
                            Bagaimana cara mengambil pesanan di gerai restoran?
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Saat status pesanan di layar Anda berubah menjadi "Siap Diambil", kunjungi konter restoran yang Anda tuju dan tunjukkan kode pesanan atau bukti nota digital kepada kasir/staf gerai.
                        </p>
                    </div>

                    <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/60">
                        <h3 class="font-bold text-slate-900 text-sm mb-1.5">
                            Bisakah pesanan dibatalkan jika saya terburu-buru masuk gate?
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Pesanan yang telah dibayar dan statusnya telah diproses dapur restoran tidak dapat dibatalkan atau di-refund karena hidangan telah diracik khusus untuk Anda.
                        </p>
                    </div>
                </div>
            </section>

            <!-- 05. LACAK STATUS KOMPLAIN PELANGGAN (TC-COMP-03) -->
            <section id="lacak-tiket" x-data="complaintTracker()" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28 space-y-6">
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            05
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Lacak Status Tiket Pengaduan
                        </h2>
                    </div>
                </div>

                <p class="text-xs sm:text-sm text-slate-500 font-medium">
                    Masukkan Nomor Tiket (misal: <strong class="text-slate-800">CMP-XXXXXX</strong>) atau Nomor WhatsApp untuk melacak progres penyelesaian masalah Anda secara transparan.
                </p>

                <!-- Search Box -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <input type="text" 
                               x-model="searchQuery" 
                               @keydown.enter.prevent="searchTicket()"
                               placeholder="Masukkan Nomor Tiket (CMP-...) atau No. WhatsApp"
                               class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-bold text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="button" 
                            @click="searchTicket()" 
                            :disabled="isLoading"
                            class="bg-[#005ea2] hover:bg-[#004a82] active:scale-95 text-white font-extrabold px-6 py-3.5 rounded-2xl text-xs sm:text-sm transition-all shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60">
                        <span x-show="!isLoading">Lacak Status</span>
                        <span x-show="isLoading" class="flex items-center gap-2" style="display:none;">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            Mencari...
                        </span>
                    </button>
                </div>

                <!-- Error Message -->
                <div x-show="errorMessage" x-cloak class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-text="errorMessage"></span>
                </div>

                <!-- Ticket Result Card -->
                <div x-show="ticketData" x-cloak class="bg-gradient-to-br from-slate-50 to-blue-50/40 rounded-2xl p-5 sm:p-6 border border-slate-200/80 space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200/60 gap-3">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Nomor Tiket Komplain</span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="font-mono text-lg font-black text-slate-900" x-text="ticketData ? ticketData.complaint_code : ''"></span>
                                <template x-if="ticketData && ticketData.order_code">
                                    <span class="text-xs font-bold text-slate-500 bg-white px-2 py-0.5 rounded-md border border-slate-200" x-text="'Pesanan: ' + ticketData.order_code"></span>
                                </template>
                            </div>
                        </div>
                        
                        <div>
                            <!-- Badge Status -->
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-black shadow-2xs"
                                 :class="{
                                     'bg-rose-50 text-rose-700 border-rose-200': ticketData && ticketData.status === 'open',
                                     'bg-blue-50 text-blue-700 border-blue-200': ticketData && ticketData.status === 'in_progress',
                                     'bg-emerald-50 text-emerald-700 border-emerald-200': ticketData && (ticketData.status === 'resolved' || ticketData.status === 'closed')
                                 }">
                                <span class="w-2 h-2 rounded-full"
                                      :class="{
                                          'bg-rose-500 animate-pulse': ticketData && ticketData.status === 'open',
                                          'bg-blue-500 animate-ping': ticketData && ticketData.status === 'in_progress',
                                          'bg-emerald-500': ticketData && (ticketData.status === 'resolved' || ticketData.status === 'closed')
                                      }"></span>
                                <span x-text="ticketData ? ticketData.status_label : ''"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Stepper Lifecycle -->
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-3">Alur Penanganan Komplain</span>
                        <div class="grid grid-cols-3 gap-2 text-center text-xs">
                            <!-- Step 1: Open -->
                            <div class="p-3 rounded-xl border transition-all"
                                 :class="ticketData ? 'bg-white border-blue-200 shadow-2xs' : 'bg-slate-100 border-slate-200 opacity-50'">
                                <div class="w-6 h-6 rounded-full mx-auto mb-1 flex items-center justify-center font-black text-[11px] bg-blue-600 text-white">1</div>
                                <div class="font-extrabold text-slate-800 text-[11px]">Diterima</div>
                                <div class="text-[10px] text-slate-400 font-medium">Tiket Tercatat</div>
                            </div>

                            <!-- Step 2: In Progress -->
                            <div class="p-3 rounded-xl border transition-all"
                                 :class="ticketData && (ticketData.status === 'in_progress' || ticketData.status === 'resolved' || ticketData.status === 'closed') ? 'bg-white border-blue-300 shadow-2xs' : 'bg-slate-100/60 border-slate-200 opacity-50'">
                                <div class="w-6 h-6 rounded-full mx-auto mb-1 flex items-center justify-center font-black text-[11px]"
                                     :class="ticketData && (ticketData.status === 'in_progress' || ticketData.status === 'resolved' || ticketData.status === 'closed') ? 'bg-[#005ea2] text-white' : 'bg-slate-200 text-slate-500'">2</div>
                                <div class="font-extrabold text-slate-800 text-[11px]">Investigasi</div>
                                <div class="text-[10px] text-slate-400 font-medium">Koordinasi Tenant</div>
                            </div>

                            <!-- Step 3: Resolved -->
                            <div class="p-3 rounded-xl border transition-all"
                                 :class="ticketData && (ticketData.status === 'resolved' || ticketData.status === 'closed') ? 'bg-white border-emerald-300 shadow-2xs' : 'bg-slate-100/60 border-slate-200 opacity-50'">
                                <div class="w-6 h-6 rounded-full mx-auto mb-1 flex items-center justify-center font-black text-[11px]"
                                     :class="ticketData && (ticketData.status === 'resolved' || ticketData.status === 'closed') ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500'">3</div>
                                <div class="font-extrabold text-slate-800 text-[11px]">Selesai</div>
                                <div class="text-[10px] text-slate-400 font-medium">Solusi Diberikan</div>
                            </div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="bg-white rounded-xl p-4 border border-slate-200/70 space-y-2 text-xs">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between text-slate-500 gap-1">
                            <span>Waktu Laporan: <strong class="text-slate-700 font-bold" x-text="ticketData ? ticketData.created_at : ''"></strong></span>
                            <template x-if="ticketData && ticketData.resolved_at">
                                <span class="text-emerald-700 font-bold">Waktu Penyelesaian: <strong x-text="ticketData.resolved_at"></strong></span>
                            </template>
                        </div>
                        <template x-if="ticketData && ticketData.tenant_name">
                            <div class="text-slate-600">Restoran / Tenant Terkait: <strong class="text-slate-800 font-bold" x-text="ticketData.tenant_name"></strong></div>
                        </template>
                        <div class="pt-2 border-t border-slate-100">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Rincian Keluhan:</span>
                            <p class="text-slate-700 font-medium italic" x-text="'&ldquo;' + (ticketData ? ticketData.description : '') + '&rdquo;'"></p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 06. FORMULIR PENGADUAN & HUBUNGI KAMI -->
            <section id="hubungi-kami" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            06
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Kirim Laporan / Hubungi Kami
                        </h2>
                    </div>
                </div>

                <p class="text-sm text-slate-600 leading-relaxed font-normal mb-6">
                    Mengalami kendala pada pesanan Anda di Bandara Juanda? Tim Support FlyDine siap membantu. Silakan lengkapi formulir di bawah ini dan kami akan segera menindaklanjutinya:
                </p>

                <!-- Feedback Notifications -->
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-xs sm:text-sm font-bold flex items-center gap-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl text-xs font-bold flex items-start gap-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div class="space-y-0.5">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('customer.complaint') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="reporter_name" value="{{ old('reporter_name') }}" required placeholder="Contoh: Budi Santoso" class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">No. WhatsApp <span class="text-rose-500">*</span></label>
                            <input type="text" name="reporter_contact" value="{{ old('reporter_contact') }}" required placeholder="Contoh: 08123456789" class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">ID Pesanan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                            <input type="text" name="order_code" value="{{ old('order_code', preg_replace('/[^A-Za-z0-9\-]/', '', (string) request('order', ''))) }}" placeholder="Contoh: ORD-ABCDEF" class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Kategori Masalah <span class="text-rose-500">*</span></label>
                            <select name="category" required class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all appearance-none">
                                <option value="pesanan_salah" {{ old('category', request('order') ? 'pesanan_salah' : 'lainnya') === 'pesanan_salah' ? 'selected' : '' }}>Pesanan Tidak Sesuai / Terlambat</option>
                                <option value="status_tidak_update" {{ old('category') === 'status_tidak_update' ? 'selected' : '' }}>Status Aplikasi Tidak Sesuai</option>
                                <option value="lainnya" {{ old('category', request('order') ? 'pesanan_salah' : 'lainnya') === 'lainnya' ? 'selected' : '' }}>Pertanyaan Umum / Saran / Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Pesan atau Detail Kendala <span class="text-rose-500">*</span></label>
                        <textarea name="description" rows="4" required placeholder="Tuliskan secara jelas apa yang bisa kami bantu..." class="w-full bg-slate-50 border border-slate-200/80 rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:border-[#005ea2] focus:ring-2 focus:ring-blue-500/20 outline-none transition-all resize-none">{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <button type="submit" class="bg-[#005ea2] hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl text-sm transition-all shadow-xs w-full sm:w-auto flex items-center justify-center space-x-2">
                            <span>Kirim Laporan</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </button>
                        <span class="text-xs text-slate-500 text-center sm:text-right">
                            Email darurat: <a href="mailto:support@flydine.co.id" class="text-[#005ea2] font-semibold hover:underline">support@flydine.co.id</a>
                        </span>
                    </div>
                </form>
            </section>

        </main>
    </div>
</div>

<script>
function complaintTracker() {
    return {
        searchQuery: '{{ addslashes(request('ticket', session('complaint_ticket', $trackQuery ?? ''))) }}',
        isLoading: false,
        errorMessage: '',
        ticketData: @json($trackedData ?? null),

        init() {
            if (this.searchQuery && !this.ticketData) {
                this.searchTicket();
            }
        },

        searchTicket() {
            const q = (this.searchQuery || '').trim();
            if (!q) {
                this.errorMessage = 'Silakan masukkan nomor tiket atau nomor WhatsApp Anda.';
                return;
            }
            this.isLoading = true;
            this.errorMessage = '';
            
            fetch('{{ route('api.complaint.track') }}?query=' + encodeURIComponent(q))
                .then(res => res.json())
                .then(data => {
                    if (data && data.found) {
                        this.ticketData = data;
                        this.errorMessage = '';
                    } else {
                        this.ticketData = null;
                        this.errorMessage = data.message || 'Tiket komplain tidak ditemukan. Periksa kembali nomor tiket atau kontak Anda.';
                    }
                })
                .catch(() => {
                    this.ticketData = null;
                    this.errorMessage = 'Terjadi kendala jaringan saat mencari tiket. Silakan coba sesaat lagi.';
                })
                .finally(() => {
                    this.isLoading = false;
                });
        }
    };
}
</script>
@endsection
