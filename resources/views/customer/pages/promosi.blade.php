@extends('customer.pages.layout')

@section('title', 'Promosi Kolaborasi')

@section('page_header')
    <!-- Header Lebar Penuh (Widescreen Juanda Airport Standard) -->
    <div class="w-full max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pb-2">
            <!-- Left Header Content -->
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 leading-tight mb-4">
                    Promosi &amp; Kolaborasi
                </h1>
                
                <p class="text-slate-600 text-sm sm:text-base lg:text-lg leading-relaxed font-normal">
                    Peluang kemitraan strategis, penempatan media promosi digital, dan program co-branding bersama FlyDine untuk menjangkau ribuan penumpang potensial di Bandara Internasional Juanda setiap hari.
                </p>
            </div>

            <!-- Right Header Quick Highlights (Stat Strip) -->
            <div class="grid grid-cols-3 gap-2 sm:gap-3 lg:w-96 shrink-0">
                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-[#005ea2] mb-0.5">Audiens</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Penumpang aktif</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-rose-600 mb-0.5">Co-Branding</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Bank &amp; E-Wallet</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-emerald-600 mb-0.5">Bundling</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Tiket &amp; Lounge</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="w-full max-w-7xl mx-auto -mt-6 sm:-mt-10 relative z-20 mb-20"
     x-data="{
        activeSection: 'peluang-kemitraan',
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
            <a href="#peluang-kemitraan" @click="activeSection = 'peluang-kemitraan'" :class="activeSection === 'peluang-kemitraan' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">1. Peluang</a>
            <a href="#format-promosi" @click="activeSection = 'format-promosi'" :class="activeSection === 'format-promosi' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">2. Format Iklan</a>
            <a href="#co-branding" @click="activeSection = 'co-branding'" :class="activeSection === 'co-branding' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">3. Bank &amp; Fintech</a>
            <a href="#bundling-maskapai" @click="activeSection = 'bundling-maskapai'" :class="activeSection === 'bundling-maskapai' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">4. Bundling</a>
            <a href="#kontak-bd" @click="activeSection = 'kontak-bd'" :class="activeSection === 'kontak-bd' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">5. Kontak BD</a>
        </div>
    </div>

    <!-- WIDESCREEN DESKTOP DUAL-COLUMN WORKSPACE (Full di Laptop, Rapi di HP) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- LEFT COLUMN: STICKY DESKTOP SIDEBAR WITH SCROLLSPY HIGHLIGHT -->
        <aside class="hidden lg:block lg:col-span-4 sticky top-24 space-y-5">
            
            <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs">
                <div class="flex items-center space-x-2.5 pb-4 mb-3 border-b border-slate-100 text-slate-800">
                    <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    <span class="text-xs font-black tracking-wider uppercase">Program Kolaborasi</span>
                </div>

                <nav class="space-y-1.5 text-xs font-semibold">
                    <a href="#peluang-kemitraan" @click="activeSection = 'peluang-kemitraan'" 
                       :class="activeSection === 'peluang-kemitraan' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>01. Peluang Kemitraan Strategis</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#format-promosi" @click="activeSection = 'format-promosi'" 
                       :class="activeSection === 'format-promosi' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>02. Format Promosi Digital</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#co-branding" @click="activeSection = 'co-branding'" 
                       :class="activeSection === 'co-branding' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>03. Program Bank &amp; E-Wallet</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#bundling-maskapai" @click="activeSection = 'bundling-maskapai'" 
                       :class="activeSection === 'bundling-maskapai' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>04. Paket Bundling Maskapai</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#kontak-bd" @click="activeSection = 'kontak-bd'" 
                       :class="activeSection === 'kontak-bd' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>05. Hubungi Business Development</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </nav>
            </div>

            <!-- Quick Inquiry Box -->
            <div class="bg-gradient-to-br from-blue-50/80 via-white to-slate-50 border border-blue-100/90 rounded-3xl p-6 shadow-xs">
                <h3 class="text-sm font-bold text-slate-900 mb-2">Tertarik Berkolaborasi?</h3>
                <p class="text-xs text-slate-600 leading-relaxed font-normal mb-4">
                    Kirimkan brief atau proposal kerja sama perusahaan Anda langsung ke tim Business Development kami.
                </p>
                <a href="mailto:support@flydine.co.id" class="w-full inline-flex items-center justify-center space-x-2 bg-[#005ea2] hover:bg-blue-700 text-white font-bold text-xs py-2.5 px-4 rounded-xl transition-colors shadow-xs">
                    <span>Email: support@flydine.co.id</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </a>
            </div>

        </aside>

        <!-- RIGHT COLUMN: FULL-WIDTH CONTENT CARDS -->
        <main class="lg:col-span-8 space-y-6">

            <!-- 01. PELUANG KEMITRAAN STRATEGIS -->
            <section id="peluang-kemitraan" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            01
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Peluang Kemitraan Strategis
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        FlyDine membuka peluang kolaborasi seluas-luasnya bagi perbankan nasional, penyedia dompet digital (e-wallet), maskapai penerbangan, serta jenama ritel terkemuka untuk terhubung dengan audiens berkualitas tinggi di Bandara Internasional Juanda:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Jangkauan Penumpang Terfokus</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Audiens terdiri dari penumpang berjadwal, pelancong bisnis, serta wisatawan domestik dan internasional dengan daya beli tinggi di area ruang tunggu (boarding lounge).
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Momen Konversi Alami</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Penumpang memiliki waktu tunggu rata-rata 45–90 menit sebelum jadwal keberangkatan, momen ideal untuk interaksi pesan dan transaksi promosi.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 02. FORMAT PROMOSI DIGITAL -->
            <section id="format-promosi" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            02
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Format Promosi Digital yang Tersedia
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Format 1 -->
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <div class="w-9 h-9 rounded-xl bg-white border border-slate-200/80 flex items-center justify-center text-[#005ea2] mb-3">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Banner Utama Aplikasi</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Penempatan banner visual premium pada beranda katalog FlyDine yang dilihat oleh setiap pengunjung saat mencari restoran di terminal.
                            </p>
                        </div>

                        <!-- Format 2 -->
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <div class="w-9 h-9 rounded-xl bg-white border border-slate-200/80 flex items-center justify-center text-[#005ea2] mb-3">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Voucher pada E-Receipt</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Pemasangan kode promo atau kupon digital eksklusif jenama Anda di lembar bukti transaksi dan pelacakan pesanan penumpang.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 03. PROGRAM BANK & E-WALLET -->
            <section id="co-branding" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-black text-xs">
                            03
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Program Co-Branding Perbankan &amp; Fintech
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Tingkatkan volume transaksi pengguna kartu dan aplikasi perbankan Anda dengan skema promosi terarah:
                    </p>

                    <div class="space-y-3">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <strong class="text-slate-900 font-bold block mb-1">Diskon Khusus QRIS / Kartu Debit &amp; Kredit</strong>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Program potongan harga atau cashback bagi penumpang yang melakukan pembayaran menggunakan rekening bank atau dompet digital mitra tertentu saat bertransaksi di FlyDine.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <strong class="text-slate-900 font-bold block mb-1">Penukaran Poin Loyalitas</strong>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Integrasi penukaran reward points kartu kredit atau poin perbankan untuk voucher makan gratis di kawasan gerai Bandara Juanda.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 04. PAKET BUNDLING MASKAPAI -->
            <section id="bundling-maskapai" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            04
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Paket Bundling Maskapai &amp; Tiket Perjalanan
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Beri nilai tambah bagi penumpang maskapai penerbangan dan pengguna aplikasi pemesanan tiket perjalanan (OTA):
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Pre-Book Meal Bundling</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Penumpang dapat menyertakan voucher makan bandara saat memesan tiket penerbangan, lalu menukarkannya langsung di gerai bandara sebelum keberangkatan.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Kolaborasi Airport Lounge</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Penyediaan hidangan eksklusif dari gerai tenant terpilih yang dapat dipesan langsung oleh pengunjung executive lounge bandara.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 05. HUBUNGI BUSINESS DEVELOPMENT -->
            <section id="kontak-bd" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            05
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Hubungi Tim Business Development
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Kami menyambut baik berbagai gagasan kerja sama yang saling menguntungkan. Untuk diskusi lebih lanjut mengenai proposal promosi, ketersediaan slot media, atau skema komersial, silakan hubungi tim kami:
                    </p>

                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 space-y-3 text-xs sm:text-sm text-slate-700">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <span class="text-slate-400 font-bold uppercase text-[10px] block mb-1">Email Resmi Kemitraan:</span>
                                <a href="mailto:support@flydine.co.id" class="font-bold text-[#005ea2] hover:underline text-sm block">support@flydine.co.id</a>
                                <p class="text-slate-500 text-xs mt-0.5">Respons maksimal 1–2 hari kerja.</p>
                            </div>

                            <div>
                                <span class="text-slate-400 font-bold uppercase text-[10px] block mb-1">Wilayah Operasional:</span>
                                <p class="font-bold text-slate-900 text-sm">Bandara Internasional Juanda</p>
                                <p class="text-slate-500 text-xs mt-0.5">Terminal 1 (Domestik) &amp; Terminal 2 (Internasional / Domestik)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action -->
                    <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100">
                        <p class="text-xs text-slate-500 text-center sm:text-left">
                            Tertarik beriklan atau bermitra di ekosistem komersial Bandara Juanda?
                        </p>
                        <a href="mailto:support@flydine.co.id" class="inline-flex items-center space-x-2 bg-[#005ea2] hover:bg-blue-700 text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition-colors shadow-xs">
                            <span>Hubungi support@flydine.co.id</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    </div>
                </div>
            </section>

        </main>
    </div>
</div>
@endsection
