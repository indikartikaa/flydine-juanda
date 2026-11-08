@extends('customer.pages.layout')

@section('title', 'Daftar Menjadi Tenant')

@section('page_header')
    <!-- Header Lebar Penuh (Widescreen Juanda Airport Standard) -->
    <div class="w-full max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pb-2">
            <!-- Left Header Content -->
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 leading-tight mb-4">
                    Daftar Menjadi Tenant
                </h1>
                
                <p class="text-slate-600 text-sm sm:text-base lg:text-lg leading-relaxed font-normal">
                    Tingkatkan omset bisnis kuliner Anda dengan menjangkau ribuan penumpang di Terminal 1 dan Terminal 2 Bandara Internasional Juanda langsung dari ruang tunggu keberangkatan.
                </p>
            </div>

            <!-- Right Header Quick Highlights (Stat Strip) -->
            <div class="grid grid-cols-3 gap-2 sm:gap-3 lg:w-96 shrink-0">
                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-emerald-600 mb-0.5">Trafik Tinggi</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Penumpang bandara</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-[#005ea2] mb-0.5">Sistem Dapur</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Order Management</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-slate-800 mb-0.5">Cepat Siap</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Sinkron boarding</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="w-full max-w-7xl mx-auto -mt-6 sm:-mt-10 relative z-20 mb-20"
     x-data="{
        activeSection: 'keuntungan-kemitraan',
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
            <a href="#keuntungan-kemitraan" @click="activeSection = 'keuntungan-kemitraan'" :class="activeSection === 'keuntungan-kemitraan' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">1. Keuntungan</a>
            <a href="#syarat-kualifikasi" @click="activeSection = 'syarat-kualifikasi'" :class="activeSection === 'syarat-kualifikasi' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">2. Syarat Gerai</a>
            <a href="#alur-pendaftaran" @click="activeSection = 'alur-pendaftaran'" :class="activeSection === 'alur-pendaftaran' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">3. Alur Daftar</a>
            <a href="#sistem-dapur" @click="activeSection = 'sistem-dapur'" :class="activeSection === 'sistem-dapur' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">4. Sistem Dapur</a>
            <a href="#kontak-kemitraan" @click="activeSection = 'kontak-kemitraan'" :class="activeSection === 'kontak-kemitraan' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">5. Pengajuan</a>
        </div>
    </div>

    <!-- WIDESCREEN DESKTOP DUAL-COLUMN WORKSPACE (Full di Laptop, Rapi di HP) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- LEFT COLUMN: STICKY DESKTOP SIDEBAR WITH SCROLLSPY HIGHLIGHT -->
        <aside class="hidden lg:block lg:col-span-4 sticky top-24 space-y-5">
            
            <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs">
                <div class="flex items-center space-x-2.5 pb-4 mb-3 border-b border-slate-100 text-slate-800">
                    <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="text-xs font-black tracking-wider uppercase">Kemitraan Tenant</span>
                </div>

                <nav class="space-y-1.5 text-xs font-semibold">
                    <a href="#keuntungan-kemitraan" @click="activeSection = 'keuntungan-kemitraan'" 
                       :class="activeSection === 'keuntungan-kemitraan' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>01. Keuntungan Kemitraan</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#syarat-kualifikasi" @click="activeSection = 'syarat-kualifikasi'" 
                       :class="activeSection === 'syarat-kualifikasi' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>02. Syarat &amp; Kualifikasi Gerai</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#alur-pendaftaran" @click="activeSection = 'alur-pendaftaran'" 
                       :class="activeSection === 'alur-pendaftaran' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>03. Alur Pendaftaran &amp; Aktivasi</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#sistem-dapur" @click="activeSection = 'sistem-dapur'" 
                       :class="activeSection === 'sistem-dapur' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>04. Sistem Dapur &amp; Pembayaran</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#kontak-kemitraan" @click="activeSection = 'kontak-kemitraan'" 
                       :class="activeSection === 'kontak-kemitraan' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>05. Pengajuan Kemitraan</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </nav>
            </div>

            <!-- Portal Login Shortcut -->
            <div class="bg-gradient-to-br from-blue-50/80 via-white to-slate-50 border border-blue-100/90 rounded-3xl p-6 shadow-xs">
                <h3 class="text-sm font-bold text-slate-900 mb-1">Sudah Menjadi Mitra?</h3>
                <p class="text-xs text-slate-600 leading-relaxed font-normal mb-4">
                    Akses dasbor pesanan dan laporan penjualan gerai Anda melalui portal resmi tenant.
                </p>
                <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center space-x-2 bg-[#005ea2] hover:bg-blue-700 text-white font-bold text-xs py-2.5 px-4 rounded-xl transition-colors shadow-xs">
                    <span>Portal Login Tenant</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>

        </aside>

        <!-- RIGHT COLUMN: FULL-WIDTH CONTENT CARDS -->
        <main class="lg:col-span-8 space-y-6">

            <!-- 01. KEUNTUNGAN KEMITRAAN -->
            <section id="keuntungan-kemitraan" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            01
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Keuntungan Kemitraan Bersama FlyDine
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Sebagai platform pemesanan makanan mandiri resmi di Bandara Internasional Juanda, FlyDine menghadirkan ekosistem digital terintegrasi untuk mendukung pertumbuhan gerai Anda:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <div class="w-9 h-9 rounded-xl bg-white border border-slate-200/80 flex items-center justify-center text-[#005ea2] mb-3">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Tingkatkan Omset</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Raih pesanan dari penumpang yang bersantai di boarding lounge dan enggan mengantre di konter fisik.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <div class="w-9 h-9 rounded-xl bg-white border border-slate-200/80 flex items-center justify-center text-[#005ea2] mb-3">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Sistem Dapur Khusus</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Antarmuka dapur (KDS) yang mudah diakses staf gerai via tablet atau gawai tanpa perlu instalasi perangkat rumit.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <div class="w-9 h-9 rounded-xl bg-white border border-slate-200/80 flex items-center justify-center text-[#005ea2] mb-3">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Laporan Real-Time</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Rekap omset harian, produk terlaris, dan analitik jam sibuk penerbangan langsung di portal Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 02. SYARAT & KUALIFIKASI GERAI -->
            <section id="syarat-kualifikasi" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            02
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Syarat &amp; Kualifikasi Gerai Tenant
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Untuk menjaga kualitas layanan penumpang di bandara internasional, mitra tenant wajib memenuhi ketentuan berikut:
                    </p>

                    <div class="space-y-3">
                        <div class="flex items-start space-x-3 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <span class="w-2 h-2 rounded-full bg-[#005ea2] mt-2 shrink-0"></span>
                            <div class="text-xs sm:text-sm">
                                <strong class="text-slate-900 font-bold block mb-0.5">Lokasi Gerai Aktif di Bandara Juanda</strong>
                                <p class="text-slate-500 leading-relaxed">Memiliki izin operasional dan kontrak sewa gerai komersial resmi yang masih berlaku di Terminal 1 atau Terminal 2 Bandara Internasional Juanda.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <span class="w-2 h-2 rounded-full bg-[#005ea2] mt-2 shrink-0"></span>
                            <div class="text-xs sm:text-sm">
                                <strong class="text-slate-900 font-bold block mb-0.5">Standar Waktu Penyajian Makanan (Quick Service)</strong>
                                <p class="text-slate-500 leading-relaxed">Sanggup menyiapkan hidangan dalam estimasi waktu 10–20 menit untuk mengakomodasi jadwal penerbangan penumpang yang ketat.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <span class="w-2 h-2 rounded-full bg-[#005ea2] mt-2 shrink-0"></span>
                            <div class="text-xs sm:text-sm">
                                <strong class="text-slate-900 font-bold block mb-0.5">Kesiapan Perangkat &amp; Koneksi</strong>
                                <p class="text-slate-500 leading-relaxed">Menyediakan minimal 1 unit tablet atau smartphone di konter dapur dengan koneksi internet untuk memantau notifikasi pesanan masuk.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 03. ALUR PENDAFTARAN & AKTIVASI -->
            <section id="alur-pendaftaran" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            03
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Alur Pendaftaran &amp; Aktivasi
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Proses integrasi gerai Anda ke sistem FlyDine berjalan cepat dalam 4 tahapan:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 text-xs">
                        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4">
                            <span class="text-[#005ea2] font-black uppercase text-[10px] block mb-1">Tahap 1</span>
                            <h3 class="font-bold text-slate-900 mb-1">Kirim Pengajuan</h3>
                            <p class="text-slate-500 leading-relaxed">Kirimkan profil gerai, lokasi terminal, dan daftar menu hidangan ke tim kemitraan kami.</p>
                        </div>

                        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4">
                            <span class="text-[#005ea2] font-black uppercase text-[10px] block mb-1">Tahap 2</span>
                            <h3 class="font-bold text-slate-900 mb-1">Verifikasi Lokasi</h3>
                            <p class="text-slate-500 leading-relaxed">Tim komersial memverifikasi titik gerai Anda di area T1 atau T2 Bandara Juanda.</p>
                        </div>

                        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4">
                            <span class="text-[#005ea2] font-black uppercase text-[10px] block mb-1">Tahap 3</span>
                            <h3 class="font-bold text-slate-900 mb-1">Setup Menu &amp; Akun</h3>
                            <p class="text-slate-500 leading-relaxed">Input data menu, penetapan harga resmi, dan pembuatan akun login portal dapur gerai.</p>
                        </div>

                        <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-2xl p-4">
                            <span class="text-emerald-700 font-black uppercase text-[10px] block mb-1">Tahap 4</span>
                            <h3 class="font-bold text-slate-900 mb-1">Mulai Menerima Order</h3>
                            <p class="text-slate-600 leading-relaxed">Gerai Anda resmi aktif dan dapat langsung menerima pesanan digital dari ribuan penumpang.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 04. SISTEM DAPUR & PEMBAYARAN -->
            <section id="sistem-dapur" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            04
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Sistem Dapur &amp; Pembayaran Terpadu
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1.5 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#005ea2]"></span>
                                <span>Notifikasi Suara &amp; Visual</span>
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Saat penumpang memesan, layar dapur akan berbunyi dan menampilkan detail menu, catatan rasa, serta waktu boarding penerbangan pemesan.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1.5 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Pencairan Dana Terjadwal</span>
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Seluruh pembayaran QRIS pelanggan dikonsolidasi dan ditransfer ke rekening usaha tenant secara berkala dan transparan.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 05. PENGAJUAN KEMITRAAN -->
            <section id="kontak-kemitraan" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            05
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Pengajuan Kemitraan Tenant
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Tertarik mengintegrasikan restoran Anda ke platform FlyDine? Hubungi tim kemitraan kami dengan melampirkan profil gerai dan daftar menu hidangan Anda:
                    </p>

                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 space-y-3 text-xs sm:text-sm text-slate-700">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <span class="text-slate-400 font-bold uppercase text-[10px] block mb-1">Email Kemitraan Tenant:</span>
                                <a href="mailto:support@flydine.co.id" class="font-bold text-[#005ea2] hover:underline text-sm block">support@flydine.co.id</a>
                                <p class="text-slate-500 text-xs mt-0.5">Subjek: Pengajuan Kemitraan Tenant - [Nama Gerai Anda]</p>
                            </div>

                            <div>
                                <span class="text-slate-400 font-bold uppercase text-[10px] block mb-1">Lokasi Kantor Manajemen Komersial:</span>
                                <p class="font-bold text-slate-900 text-sm">Gedung Administrasi Bandara Juanda</p>
                                <p class="text-slate-500 text-xs mt-0.5">Terminal 1 Keberangkatan, Sidoarjo, Jawa Timur</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action -->
                    <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100">
                        <p class="text-xs text-slate-500 text-center sm:text-left">
                            Sudah memiliki akun kemitraan aktif?
                        </p>
                        <a href="{{ route('login') }}" class="inline-flex items-center space-x-2 bg-[#005ea2] hover:bg-blue-700 text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition-colors shadow-xs">
                            <span>Masuk ke Portal Login Tenant</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    </div>
                </div>
            </section>

        </main>
    </div>
</div>
@endsection
