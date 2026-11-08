@extends('customer.pages.layout')

@section('title', 'Syarat & Ketentuan')

@section('page_header')
    <!-- Header Lebar Penuh (Widescreen Juanda Airport Standard) -->
    <div class="w-full max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pb-2">
            <!-- Left Header Content -->
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 leading-tight mb-4">
                    Syarat &amp; Ketentuan
                </h1>
                
                <p class="text-slate-600 text-sm sm:text-base lg:text-lg leading-relaxed font-normal">
                    Pahami ketentuan operasional dan pedoman pemesanan makanan serta minuman di seluruh gerai mitra FlyDine di Terminal 1 dan Terminal 2 Bandara Internasional Juanda demi kelancaran perjalanan Anda.
                </p>
            </div>

            <!-- Right Header Quick Highlights (Stat Strip) -->
            <div class="grid grid-cols-3 gap-2 sm:gap-3 lg:w-96 shrink-0">
                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-[#005ea2] mb-0.5">Harga Final</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Termasuk PB1</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-amber-600 mb-0.5">Boarding</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Sinkron antrean</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-slate-800 mb-0.5">Non-Refund</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Saat diproses</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="w-full max-w-7xl mx-auto -mt-6 sm:-mt-10 relative z-20 mb-20" 
     x-data="{ 
        activeSection: 'platform-perantara',
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

    <!-- MOBILE QUICK NAV PILL (Navigasi cepat di layar HP) -->
    <div class="lg:hidden sticky top-16 z-30 bg-white/95 backdrop-blur-md border border-slate-200/80 rounded-2xl shadow-sm mb-6 p-2">
        <div class="flex items-center space-x-2 overflow-x-auto scrollbar-hide text-xs font-semibold whitespace-nowrap px-1 py-1">
            <span class="text-slate-400 font-bold uppercase text-[10px] pl-1">Menu:</span>
            <a href="#platform-perantara" @click="activeSection = 'platform-perantara'" :class="activeSection === 'platform-perantara' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">1. Perantara</a>
            <a href="#waktu-boarding" @click="activeSection = 'waktu-boarding'" :class="activeSection === 'waktu-boarding' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">2. Jadwal Boarding</a>
            <a href="#transparansi-harga" @click="activeSection = 'transparansi-harga'" :class="activeSection === 'transparansi-harga' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">3. Harga &amp; Bayar</a>
            <a href="#kebijakan-pembatalan" @click="activeSection = 'kebijakan-pembatalan'" :class="activeSection === 'kebijakan-pembatalan' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">4. Pembatalan</a>
            <a href="#pengambilan-pesanan" @click="activeSection = 'pengambilan-pesanan'" :class="activeSection === 'pengambilan-pesanan' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">5. Pengambilan</a>
            <a href="#batasan-tanggung-jawab" @click="activeSection = 'batasan-tanggung-jawab'" :class="activeSection === 'batasan-tanggung-jawab' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">6. Batasan</a>
        </div>
    </div>

    <!-- WIDESCREEN DESKTOP DUAL-COLUMN WORKSPACE (Full di Laptop, Rapi di HP) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- LEFT COLUMN: STICKY DESKTOP SIDEBAR -->
        <aside class="hidden lg:block lg:col-span-4 sticky top-24 space-y-5">
            
            <!-- Daftar Navigasi Ketentuan -->
            <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs">
                <div class="flex items-center space-x-2.5 pb-4 mb-3 border-b border-slate-100 text-slate-800">
                    <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-xs font-black tracking-wider uppercase">Daftar Isi Ketentuan</span>
                </div>

                <nav class="space-y-1.5 text-xs font-semibold">
                    <a href="#platform-perantara" @click="activeSection = 'platform-perantara'" 
                       :class="activeSection === 'platform-perantara' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>01. Platform Perantara Layanan</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#waktu-boarding" @click="activeSection = 'waktu-boarding'" 
                       :class="activeSection === 'waktu-boarding' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>02. Jadwal &amp; Waktu Boarding</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#transparansi-harga" @click="activeSection = 'transparansi-harga'" 
                       :class="activeSection === 'transparansi-harga' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>03. Transparansi Harga &amp; Pembayaran</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#kebijakan-pembatalan" @click="activeSection = 'kebijakan-pembatalan'" 
                       :class="activeSection === 'kebijakan-pembatalan' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>04. Kebijakan Pembatalan &amp; Refund</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#pengambilan-pesanan" @click="activeSection = 'pengambilan-pesanan'" 
                       :class="activeSection === 'pengambilan-pesanan' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>05. Prosedur Pengambilan di Gerai</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#batasan-tanggung-jawab" @click="activeSection = 'batasan-tanggung-jawab'" 
                       :class="activeSection === 'batasan-tanggung-jawab' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>06. Batasan Tanggung Jawab</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </nav>
            </div>

        </aside>

        <!-- RIGHT COLUMN: FULL-WIDTH CONTENT CARDS -->
        <main class="lg:col-span-8 space-y-6">

            <!-- 01. PLATFORM PERANTARA LAYANAN -->
            <section id="platform-perantara" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            01
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Platform Perantara Layanan
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        FlyDine bertindak murni sebagai platform teknologi perantara pemesanan digital mandiri antara pengguna (penumpang atau pengunjung) dan mitra restoran/tenant resmi di Bandara Internasional Juanda.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Peran FlyDine</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Memfasilitasi katalog menu digital, estimasi antrean dapur, pemrosesan pembayaran QRIS resmi, dan pelacakan status pesanan secara waktu nyata.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Tanggung Jawab Restoran</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Persiapan hidangan, mutu rasa, ketersediaan porsi, higienitas kemasan, dan penyerahan makanan di konter sepenuhnya dikelola oleh pihak restoran (tenant).
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 02. JADWAL & WAKTU BOARDING -->
            <section id="waktu-boarding" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            02
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Jadwal Penerbangan &amp; Waktu Boarding
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Operasional FlyDine dirancang khusus untuk lingkungan bandara guna meminimalkan risiko keterlambatan penumpang:
                    </p>

                    <div class="space-y-3.5">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <strong class="text-slate-900 font-bold block mb-1">Akurasi Input Penerbangan</strong>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Pengguna wajib memasukkan nomor penerbangan dan jam boarding yang benar agar sistem dapat menyelaraskan antrean pesanan di dapur tenant.
                            </p>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <strong class="text-slate-900 font-bold block mb-1">Tanggung Jawab Menuju Pintu Keberangkatan</strong>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Sistem FlyDine memberikan estimasi waktu persiapan makanan. Namun, <strong>setiap keterlambatan pengambilan makanan yang mengakibatkan penumpang tertinggal penerbangan sepenuhnya merupakan tanggung jawab pribadi pengguna</strong>. Harap selalu perhatikan pengumuman boarding maskapai Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 03. TRANSPARANSI HARGA & PEMBAYARAN -->
            <section id="transparansi-harga" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            03
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Transparansi Harga &amp; Sistem Pembayaran
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Harga Final Termasuk Pajak</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Seluruh harga yang tertera pada katalog menu FlyDine merupakan harga final (sudah mencakup pajak restoran PB1) sesuai ketentuan yang berlaku di Bandara Juanda.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Pembayaran Non-Tunai Resmi</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Pembayaran diselesaikan langsung saat pemesanan menggunakan QRIS atau gerbang pembayaran resmi untuk memangkas waktu antre di kasir restoran.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 04. KEBIJAKAN PEMBATALAN & REFUND -->
            <section id="kebijakan-pembatalan" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-black text-xs">
                            04
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Kebijakan Pembatalan &amp; Pengembalian Dana
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Demi menjaga kelancaran operasional dapur cepat saji di bandara:
                    </p>

                    <div class="p-5 rounded-2xl bg-amber-50/60 border border-amber-200/80">
                        <strong class="text-slate-900 font-bold block mb-1">Ketentuan Non-Refund</strong>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Pesanan yang telah berhasil dibayar dan statusnya telah berubah menjadi <strong>"Sedang Disiapkan / Diproses"</strong> oleh dapur restoran <strong>tidak dapat dibatalkan atau dikembalikan dananya (non-refundable)</strong> dengan alasan apapun, termasuk jika pengguna memutuskan membatalkan perjalanan.
                        </p>
                    </div>

                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <strong class="text-slate-900 font-bold block mb-1">Pengecualian Pembatalan oleh Pihak Tenant</strong>
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Apabila menu yang dipesan mendadak habis atau dapur tenant mengalami kendala teknis sebelum pesanan mulai dimasak, pesanan akan dibatalkan otomatis dan dana akan dikembalikan penuh.
                        </p>
                    </div>
                </div>
            </section>

            <!-- 05. PROSEDUR PENGAMBILAN DI GERAI -->
            <section id="pengambilan-pesanan" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            05
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Prosedur Pengambilan Pesanan di Gerai
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Verifikasi Kode Pengambilan</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Pengguna cukup menunjukkan nomor nota atau kode pengambilan 4-digit di layar ponsel kepada staf kasir/konter gerai untuk mengambil hidangan.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Batas Waktu Pengambilan</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Makanan yang telah siap akan disimpan di area pengambilan gerai hingga batas jam operasional gerai pada hari yang sama.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 06. BATASAN TANGGUNG JAWAB OPERASIONAL -->
            <section id="batasan-tanggung-jawab" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            06
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Batasan Tanggung Jawab Operasional
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        FlyDine dibebaskan dari segala bentuk tuntutan kerugian akibat:
                    </p>

                    <ul class="space-y-2.5 text-xs sm:text-sm text-slate-600">
                        <li class="flex items-start space-x-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#005ea2] mt-2 shrink-0"></span>
                            <span>Keterlambatan penumpang menuju boarding gate akibat kelalaian pribadi dalam memperkirakan waktu perjalanan antar terminal/gate.</span>
                        </li>
                        <li class="flex items-start space-x-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#005ea2] mt-2 shrink-0"></span>
                            <span>Perubahan jadwal mendadak oleh maskapai penerbangan atau pengalihan gate oleh otoritas bandara di luar kendali sistem FlyDine.</span>
                        </li>
                        <li class="flex items-start space-x-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#005ea2] mt-2 shrink-0"></span>
                            <span>Reaksi alergi atau ketidaksesuaian selera rasa makanan yang sepenuhnya merupakan ranah racikan masing-masing pihak restoran.</span>
                        </li>
                    </ul>

                    <!-- Bottom Action -->
                    <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-xs text-slate-500 text-center sm:text-left">
                            Butuh bantuan seputar pesanan Anda? Hubungi <a href="mailto:support@flydine.co.id" class="text-[#005ea2] font-semibold hover:underline">support@flydine.co.id</a>
                        </p>
                        <a href="{{ route('customer.menu') }}" class="inline-flex items-center space-x-2 bg-[#005ea2] hover:bg-blue-700 text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition-colors shadow-xs">
                            <span>Mulai Pesan</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    </div>
                </div>
            </section>

        </main>
    </div>
</div>
@endsection
