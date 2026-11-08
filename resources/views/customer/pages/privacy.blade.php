@extends('customer.pages.layout')

@section('title', 'Kebijakan Privasi')

@section('page_header')
    <!-- Header Lebar Penuh (Widescreen Juanda Airport Standard) -->
    <div class="w-full max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pb-2">
            <!-- Left Header Content -->
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 leading-tight mb-4">
                    Kebijakan Privasi &amp; Data
                </h1>
                
                <p class="text-slate-600 text-sm sm:text-base lg:text-lg leading-relaxed font-normal">
                    Transparansi penuh tata kelola data pemesanan makanan FlyDine di Terminal 1 &amp; Terminal 2. Kami hanya memproses informasi yang mutlak diperlukan untuk memastikan pesanan Anda siap tepat waktu sebelum jadwal keberangkatan.
                </p>
            </div>

            <!-- Right Header Quick Guarantees (Stat Strip) -->
            <div class="grid grid-cols-3 gap-2 sm:gap-3 lg:w-96 shrink-0">
                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-[#005ea2] mb-0.5">Operasional</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Hanya data pesan</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-emerald-600 mb-0.5">24 Jam</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Auto-purge data</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-slate-800 mb-0.5">Nol Spam</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">100% tanpa iklan</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="w-full max-w-7xl mx-auto -mt-6 sm:-mt-10 relative z-20 mb-20" 
     x-data="{ 
        activeSection: 'info-dibutuhkan',
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

    <!-- MOBILE QUICK NAV PILL (Memudahkan navigasi cepat di layar HP) -->
    <div class="lg:hidden sticky top-16 z-30 bg-white/95 backdrop-blur-md border border-slate-200/80 rounded-2xl shadow-sm mb-6 p-2">
        <div class="flex items-center space-x-2 overflow-x-auto scrollbar-hide text-xs font-semibold whitespace-nowrap px-1 py-1">
            <span class="text-slate-400 font-bold uppercase text-[10px] pl-1">Menu:</span>
            <a href="#info-dibutuhkan" @click="activeSection = 'info-dibutuhkan'" :class="activeSection === 'info-dibutuhkan' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">1. Data Dibutuhkan</a>
            <a href="#data-dikecualikan" @click="activeSection = 'data-dikecualikan'" :class="activeSection === 'data-dikecualikan' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">2. Yang Tidak Diminta</a>
            <a href="#fids-penerbangan" @click="activeSection = 'fids-penerbangan'" :class="activeSection === 'fids-penerbangan' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">3. Sinkronisasi FIDS</a>
            <a href="#keamanan-pembayaran" @click="activeSection = 'keamanan-pembayaran'" :class="activeSection === 'keamanan-pembayaran' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">4. Keamanan QRIS</a>
            <a href="#penghapusan-data" @click="activeSection = 'penghapusan-data'" :class="activeSection === 'penghapusan-data' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">5. Alur Hapus Data</a>
            <a href="#faq-privasi" @click="activeSection = 'faq-privasi'" :class="activeSection === 'faq-privasi' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">6. Tanya Jawab</a>
        </div>
    </div>

    <!-- WIDESCREEN DESKTOP DUAL-COLUMN WORKSPACE (Full di Laptop, Rapi di Mobile) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- LEFT COLUMN: STICKY DESKTOP SIDEBAR -->
        <aside class="hidden lg:block lg:col-span-4 sticky top-24 space-y-5">
            
            <!-- Daftar Navigasi Interaktif -->
            <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs">
                <div class="flex items-center space-x-2.5 pb-4 mb-3 border-b border-slate-100 text-slate-800">
                    <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <span class="text-xs font-black tracking-wider uppercase">Daftar Isi Kebijakan</span>
                </div>

                <nav class="space-y-1.5 text-xs font-semibold">
                    <a href="#info-dibutuhkan" @click="activeSection = 'info-dibutuhkan'" 
                       :class="activeSection === 'info-dibutuhkan' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>01. Data yang Dibutuhkan</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#data-dikecualikan" @click="activeSection = 'data-dikecualikan'" 
                       :class="activeSection === 'data-dikecualikan' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>02. Data yang TIDAK Diminta</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#fids-penerbangan" @click="activeSection = 'fids-penerbangan'" 
                       :class="activeSection === 'fids-penerbangan' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>03. Sinkronisasi Penerbangan</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#keamanan-pembayaran" @click="activeSection = 'keamanan-pembayaran'" 
                       :class="activeSection === 'keamanan-pembayaran' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>04. Keamanan Pembayaran QRIS</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#penghapusan-data" @click="activeSection = 'penghapusan-data'" 
                       :class="activeSection === 'penghapusan-data' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>05. Alur Penghapusan Data</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#faq-privasi" @click="activeSection = 'faq-privasi'" 
                       :class="activeSection === 'faq-privasi' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>06. Tanya Jawab Privasi (FAQ)</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </nav>
            </div>

        </aside>

        <!-- RIGHT COLUMN: FULL-WIDTH CONTENT CARDS -->
        <main class="lg:col-span-8 space-y-6">

            <!-- 01. DATA YANG DIBUTUHKAN -->
            <section id="info-dibutuhkan" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            01
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Informasi yang Kami Butuhkan
                        </h2>
                    </div>
                </div>

                <p class="text-sm text-slate-600 leading-relaxed font-normal mb-6">
                    FlyDine menerapkan prinsip <em>data minimization</em>. Setiap informasi yang kami minta memiliki fungsi langsung untuk kelancaran pesanan Anda di gerai restoran bandara:
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kartu 1: Identitas Pemesan -->
                    <div class="bg-slate-50/70 border border-slate-200/80 rounded-2xl p-5 hover:border-[#005ea2]/30 transition-colors">
                        <div class="flex items-center space-x-3 mb-2.5">
                            <div class="w-9 h-9 rounded-xl bg-white border border-slate-200/80 flex items-center justify-center text-[#005ea2] shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm">Nama Pemesan / Panggilan</h3>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Diperlukan untuk mencocokkan identitas dan memanggil Anda saat mengambil hidangan di konter restoran bandara.
                        </p>
                    </div>

                    <!-- Kartu 2: Data Penerbangan -->
                    <div class="bg-slate-50/70 border border-slate-200/80 rounded-2xl p-5 hover:border-[#005ea2]/30 transition-colors">
                        <div class="flex items-center space-x-3 mb-2.5">
                            <div class="w-9 h-9 rounded-xl bg-white border border-slate-200/80 flex items-center justify-center text-[#005ea2] shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm">Nomor Penerbangan &amp; Boarding</h3>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Digunakan sistem untuk mengatur prioritas antrean dapur tenant agar pesanan selesai sebelum waktu keberangkatan Anda.
                        </p>
                    </div>

                    <!-- Kartu 3: Menu & Catatan -->
                    <div class="bg-slate-50/70 border border-slate-200/80 rounded-2xl p-5 hover:border-[#005ea2]/30 transition-colors">
                        <div class="flex items-center space-x-3 mb-2.5">
                            <div class="w-9 h-9 rounded-xl bg-white border border-slate-200/80 flex items-center justify-center text-[#005ea2] shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm">Daftar Menu &amp; Catatan Rasa</h3>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Rincian pesanan diteruskan langsung ke dapur gerai terkait agar makanan diracik dengan tepat sesuai keinginan Anda.
                        </p>
                    </div>

                    <!-- Kartu 4: Kontak Bukti Transaksi -->
                    <div class="bg-slate-50/70 border border-slate-200/80 rounded-2xl p-5 hover:border-[#005ea2]/30 transition-colors">
                        <div class="flex items-center space-x-3 mb-2.5">
                            <div class="w-9 h-9 rounded-xl bg-white border border-slate-200/80 flex items-center justify-center text-[#005ea2] shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm">WhatsApp / Email (Opsional)</h3>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Hanya digunakan bila Anda meminta pengiriman bukti transaksi digital (e-receipt) atau pemberitahuan saat hidangan siap diambil.
                        </p>
                    </div>
                </div>
            </section>

            <!-- 02. DATA YANG TIDAK PERNAH DIMINTA (Matriks Jaminan Keamanan) -->
            <section id="data-dikecualikan" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-black text-xs">
                            02
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Informasi yang TIDAK Pernah Kami Minta
                        </h2>
                    </div>
                </div>

                <p class="text-sm text-slate-600 leading-relaxed font-normal mb-5">
                    Demi keamanan dan kenyamanan penumpang bandara, FlyDine tidak pernah meminta atau menyimpan data sensitif berikut:
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs sm:text-sm">
                    <div class="flex items-start space-x-3 p-4 rounded-2xl bg-rose-50/50 border border-rose-100">
                        <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <div>
                            <strong class="text-slate-900 font-bold block mb-0.5">Tidak Ada NIK, KTP, atau Paspor</strong>
                            <p class="text-slate-500 leading-relaxed text-xs">Pemesanan makanan di bandara tidak membutuhkan dokumen identitas kependudukan negara.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 p-4 rounded-2xl bg-rose-50/50 border border-rose-100">
                        <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <div>
                            <strong class="text-slate-900 font-bold block mb-0.5">Tidak Ada Nomor Kartu &amp; PIN</strong>
                            <p class="text-slate-500 leading-relaxed text-xs">Kami tidak merekam kredensial perbankan; transaksi diproses langsung via QRIS resmi.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 p-4 rounded-2xl bg-rose-50/50 border border-rose-100">
                        <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <div>
                            <strong class="text-slate-900 font-bold block mb-0.5">Tidak Melacak Lokasi GPS Terus-menerus</strong>
                            <p class="text-slate-500 leading-relaxed text-xs">Kami tidak melacak koordinat posisi perangkat Anda di dalam maupun di luar terminal.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 p-4 rounded-2xl bg-rose-50/50 border border-rose-100">
                        <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <div>
                            <strong class="text-slate-900 font-bold block mb-0.5">Nol Iklan &amp; Penjualan Data Pihak Ketiga</strong>
                            <p class="text-slate-500 leading-relaxed text-xs">Data Anda tidak akan pernah dijual atau diserahkan ke broker pengiklan luar.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 03. SINKRONISASI JADWAL PENERBANGAN FIDS -->
            <section id="fids-penerbangan" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            03
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Sinkronisasi Jadwal Penerbangan (FIDS)
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        FlyDine terhubung dengan sistem jadwal penerbangan Bandara Internasional Juanda secara <em>real-time</em> untuk menjaga kenyamanan perjalanan Anda:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center space-x-2 text-[#005ea2] font-bold text-xs uppercase tracking-wider mb-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                <span>Prioritas Masak Dapur</span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Dapur restoran akan mengetahui jika jam boarding Anda sudah dekat, sehingga pesanan Anda dapat diprioritaskan agar tidak tertinggal pesawat.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center space-x-2 text-[#005ea2] font-bold text-xs uppercase tracking-wider mb-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <span>Peringatan Waktu Boarding</span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Sistem memberikan pemberitahuan waktu aman menuju ruang tunggu gerbang keberangkatan saat pesanan Anda selesai disiapkan.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 04. KEAMANAN PEMBAYARAN & TRANSAKSI QRIS -->
            <section id="keamanan-pembayaran" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            04
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Keamanan Pembayaran &amp; Transaksi
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Seluruh transaksi keuangan di FlyDine menggunakan standar keamanan sistem pembayaran digital terlisensi:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1.5 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Gerbang Resmi Bank Indonesia</span>
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Pembayaran diproses melalui QRIS dan rekanan Payment Gateway berlisensi Bank Indonesia dengan standar proteksi PCI-DSS.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1.5 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#005ea2]"></span>
                                <span>Isolasi Data Finansial</span>
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                FlyDine hanya menerima konfirmasi kode status sukses pembayaran, tanpa pernah mengakses rekening maupun saldo bank Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 05. ALUR PENGHAPUSAN DATA (DATA RETENTION LIFECYCLE) -->
            <section id="penghapusan-data" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xs">
                            05
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Siklus Hidup &amp; Penghapusan Data Otomatis
                        </h2>
                    </div>
                </div>

                <p class="text-sm text-slate-600 leading-relaxed font-normal mb-6">
                    Data operasional penerbangan Anda tidak disimpan secara permanen. Sistem secara otomatis membersihkan data transaksi harian melalui alur berikut:
                </p>

                <!-- Clean Horizontal Timeline / Steps (Grid 4 Kolom di Laptop) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 text-xs">
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 relative">
                        <div class="text-[10px] font-black text-[#005ea2] uppercase tracking-wider mb-1">Tahap 1</div>
                        <h3 class="font-bold text-slate-900 mb-1">Pemesanan</h3>
                        <p class="text-slate-500 leading-relaxed">Pesanan diterima dan diteruskan ke dapur restoran di terminal.</p>
                    </div>

                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 relative">
                        <div class="text-[10px] font-black text-[#005ea2] uppercase tracking-wider mb-1">Tahap 2</div>
                        <h3 class="font-bold text-slate-900 mb-1">Pengambilan</h3>
                        <p class="text-slate-500 leading-relaxed">Verifikasi kode nota di konter dan status pesanan menjadi Selesai.</p>
                    </div>

                    <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-2xl p-4 relative">
                        <div class="text-[10px] font-black text-emerald-700 uppercase tracking-wider mb-1">Tahap 3</div>
                        <h3 class="font-bold text-slate-900 mb-1">Lepas Landas</h3>
                        <p class="text-slate-600 leading-relaxed">Penerbangan Anda berangkat menuju destinasi tujuan.</p>
                    </div>

                    <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-2xl p-4 relative">
                        <div class="text-[10px] font-black text-emerald-700 uppercase tracking-wider mb-1">Tahap 4</div>
                        <h3 class="font-bold text-slate-900 mb-1">Pembersihan</h3>
                        <p class="text-slate-600 leading-relaxed">Dalam 24 jam, data nomor penerbangan dihapus permanen dari server.</p>
                    </div>
                </div>
            </section>

            <!-- 06. TANYA JAWAB PRIVASI PENUMPANG (FAQ) -->
            <section id="faq-privasi" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            06
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Pertanyaan Umum Seputar Privasi
                        </h2>
                    </div>
                </div>

                <div class="space-y-3.5">
                    <!-- FAQ 1 -->
                    <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/60">
                        <h3 class="font-bold text-slate-900 text-sm mb-1.5">
                            Apakah pihak restoran mengetahui nomor telepon atau email saya?
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Tidak. Pihak restoran hanya melihat nama pemesan, rincian menu hidangan, dan estimasi jam boarding. Kontak nomor telepon atau email tidak pernah diberikan ke pihak tenant.
                        </p>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/60">
                        <h3 class="font-bold text-slate-900 text-sm mb-1.5">
                            Bagaimana jika penerbangan saya mengalami perubahan jadwal (delay)?
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Karena terhubung dengan sistem jadwal penerbangan bandara, perubahan jam akan menyesuaikan estimasi persiapan makanan Anda. Anda tetap dapat mengambil pesanan di konter gerai pada hari yang sama.
                        </p>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/60">
                        <h3 class="font-bold text-slate-900 text-sm mb-1.5">
                            Apakah saya wajib membuat akun untuk memesan makanan di FlyDine?
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Tidak wajib. FlyDine didesain ramah penumpang bandara sehingga Anda dapat langsung memesan sebagai tamu (guest) tanpa proses registrasi akun yang panjang.
                        </p>
                    </div>
                </div>

                <!-- Bottom Action -->
                <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-slate-500 text-center sm:text-left">
                        Pertanyaan lebih lanjut mengenai tata kelola privasi? Hubungi kami di <a href="mailto:support@flydine.co.id" class="text-[#005ea2] font-semibold hover:underline">support@flydine.co.id</a>
                    </p>
                    <a href="{{ route('customer.menu') }}" class="inline-flex items-center space-x-2 bg-[#005ea2] hover:bg-blue-700 text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition-colors shadow-xs">
                        <span>Pesan Makanan</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>
            </section>

        </main>
    </div>
</div>
@endsection
