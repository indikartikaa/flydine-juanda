@extends('customer.pages.layout')

@section('title', 'Karir & Korporat')

@section('page_header')
    <!-- Header Lebar Penuh (Widescreen Juanda Airport Standard) -->
    <div class="w-full max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 pb-2">
            <!-- Left Header Content -->
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-slate-900 leading-tight mb-4">
                    Karir &amp; Korporat
                </h1>
                
                <p class="text-slate-600 text-sm sm:text-base lg:text-lg leading-relaxed font-normal">
                    Bergabunglah bersama FlyDine dan PT Angkasa Pura Indonesia dalam merevolusi pengalaman digital kuliner bagi jutaan penumpang di Bandara Internasional Juanda Surabaya.
                </p>
            </div>

            <!-- Right Header Quick Highlights (Stat Strip) -->
            <div class="grid grid-cols-3 gap-2 sm:gap-3 lg:w-96 shrink-0">
                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-[#005ea2] mb-0.5">Terminal 1 &amp; 2</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Area Juanda</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-emerald-600 mb-0.5">Inovasi</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">Food-Tech Bandara</div>
                </div>

                <div class="bg-white/90 backdrop-blur-xs border border-slate-200/90 rounded-2xl p-3.5 text-center shadow-xs">
                    <div class="text-xs sm:text-sm font-black text-slate-800 mb-0.5">Rekrutmen Bebas Biaya</div>
                    <div class="text-[10px] sm:text-xs text-slate-500 font-medium">100% Gratis</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="w-full max-w-7xl mx-auto -mt-6 sm:-mt-10 relative z-20 mb-20"
     x-data="{
        activeSection: 'tentang-kami',
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
            <a href="#tentang-kami" @click="activeSection = 'tentang-kami'" :class="activeSection === 'tentang-kami' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">1. Tentang</a>
            <a href="#budaya-kerja" @click="activeSection = 'budaya-kerja'" :class="activeSection === 'budaya-kerja' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">2. Budaya</a>
            <a href="#peluang-karir" @click="activeSection = 'peluang-karir'" :class="activeSection === 'peluang-karir' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">3. Posisi Karir</a>
            <a href="#tata-kelola" @click="activeSection = 'tata-kelola'" :class="activeSection === 'tata-kelola' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">4. Korporat</a>
            <a href="#saluran-rekrutmen" @click="activeSection = 'saluran-rekrutmen'" :class="activeSection === 'saluran-rekrutmen' ? 'bg-[#005ea2] text-white' : 'bg-slate-100 text-slate-600'" class="px-3 py-1.5 rounded-xl transition-colors">5. Pendaftaran</a>
        </div>
    </div>

    <!-- WIDESCREEN DESKTOP DUAL-COLUMN WORKSPACE (Full di Laptop, Rapi di HP) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- LEFT COLUMN: STICKY DESKTOP SIDEBAR WITH SCROLLSPY HIGHLIGHT -->
        <aside class="hidden lg:block lg:col-span-4 sticky top-24 space-y-5">
            
            <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs">
                <div class="flex items-center space-x-2.5 pb-4 mb-3 border-b border-slate-100 text-slate-800">
                    <svg class="w-4 h-4 text-[#005ea2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs font-black tracking-wider uppercase">Informasi Korporat</span>
                </div>

                <nav class="space-y-1.5 text-xs font-semibold">
                    <a href="#tentang-kami" @click="activeSection = 'tentang-kami'" 
                       :class="activeSection === 'tentang-kami' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>01. Tentang FlyDine Juanda</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#budaya-kerja" @click="activeSection = 'budaya-kerja'" 
                       :class="activeSection === 'budaya-kerja' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>02. Budaya Kerja &amp; Nilai Inti</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#peluang-karir" @click="activeSection = 'peluang-karir'" 
                       :class="activeSection === 'peluang-karir' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>03. Peluang Karir &amp; Bidang Kerja</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#tata-kelola" @click="activeSection = 'tata-kelola'" 
                       :class="activeSection === 'tata-kelola' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>04. Tata Kelola &amp; Sinergi BUMN</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="#saluran-rekrutmen" @click="activeSection = 'saluran-rekrutmen'" 
                       :class="activeSection === 'saluran-rekrutmen' ? 'bg-blue-50 text-[#005ea2] font-bold border-l-2 border-[#005ea2]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50'"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-r-xl transition-all">
                        <span>05. Saluran Rekrutmen Resmi</span>
                        <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </nav>
            </div>

            <!-- InJourney Portal Link Card -->
            <div class="bg-gradient-to-br from-blue-50/80 via-white to-slate-50 border border-blue-100/90 rounded-3xl p-6 shadow-xs">
                <h3 class="text-sm font-bold text-slate-900 mb-1">Karir BUMN InJourney Airports</h3>
                <p class="text-xs text-slate-600 leading-relaxed font-normal mb-4">
                    Untuk lowongan formasi resmi PT Angkasa Pura Indonesia, Anda dapat mengunjungi portal karir holding InJourney.
                </p>
                <a href="https://juanda-airport.com/id" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center space-x-2 bg-white border border-slate-200 hover:border-[#005ea2] text-slate-700 hover:text-[#005ea2] font-bold text-xs py-2.5 px-4 rounded-xl transition-colors shadow-2xs">
                    <span>Web Juanda Airport</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                </a>
            </div>

        </aside>

        <!-- RIGHT COLUMN: FULL-WIDTH CONTENT CARDS -->
        <main class="lg:col-span-8 space-y-6">

            <!-- 01. TENTANG FLYDINE JUANDA -->
            <section id="tentang-kami" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            01
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Tentang FlyDine &amp; Sinergi Bandara Juanda
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        <strong>FlyDine</strong> adalah inovasi teknologi pemesanan makanan mandiri terintegrasi pertama di <strong>Bandara Internasional Juanda Surabaya (SUB)</strong>, beroperasi dalam naungan kemitraan komersial PT Angkasa Pura Indonesia (InJourney Airports).
                    </p>
                    <p>
                        Misi kami adalah menghapus hambatan antrean fisik di terminal bandara. Melalui sinkronisasi jadwal penerbangan real-time (FIDS), penumpang dapat menikmati santapan berkualitas tanpa rasa cemas akan waktu keberangkatan.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Visi Layanan</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Mewujudkan bandara kelas dunia yang nyaman, bebas antrean, dan ramah teknologi bagi seluruh penumpang udara.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Jangkauan Wilayah</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Melayani seluruh area keberangkatan Terminal 1 (Domestik) dan Terminal 2 (Internasional &amp; Domestik).
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 02. BUDAYA KERJA & NILAI INTI -->
            <section id="budaya-kerja" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            02
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Budaya Kerja &amp; Nilai Inti
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Kami memadukan standar ketat keamanan aviasi dengan budaya kerja tangkas berbasis teknologi modern:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#005ea2]"></span>
                                <span>Passenger Safety &amp; Time First</span>
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Ketepatan waktu adalah prioritas mutlak di lingkungan penerbangan. Setiap fitur didesain untuk melindungi jadwal terbang penumpang.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Service Excellence &amp; Hospitality</span>
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Keramahan khas Indonesia menyatu dalam interaksi tatap muka di konter tenant maupun layanan bantuan pelanggan.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <span>Innovation &amp; Agility</span>
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Cepat beradaptasi terhadap perubahan ritme penerbangan, lonjakan penumpang di musim liburan, dan teknologi pembayaran baru.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                <span>Integritas &amp; Transparansi</span>
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Keamanan data privasi penumpang dan kejujuran pelaporan operasional tanpa kompromi.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 03. PELUANG KARIR & BIDANG KERJA -->
            <section id="peluang-karir" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            03
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Peluang Karir &amp; Bidang Keahlian
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Kami secara berkala membuka kesempatan bagi talenta berdedikasi tinggi pada divisi operasional maupun teknologi:
                    </p>

                    <div class="space-y-3.5">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="font-bold text-slate-900 text-sm">Airport Field Operations &amp; Quality Control</h3>
                                <span class="text-[10px] font-bold text-[#005ea2] bg-blue-50 px-2 py-0.5 rounded">Terminal 1 &amp; 2</span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Bertanggung jawab memantau kelancaran operasional gerai tenant, verifikasi waktu penyiapan pesanan di dapur, dan pendampingan penumpang di area pengambilan.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="font-bold text-slate-900 text-sm">Software Engineering &amp; Data Integration</h3>
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">Engineering</span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Mengembangkan sistem sinkronisasi penerbangan real-time, arsitektur pemrosesan pembayaran non-tunai, dan antarmuka dapur tenant berkecepatan tinggi.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="font-bold text-slate-900 text-sm">Tenant Relationship &amp; Commercial Specialist</h3>
                                <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded">Kemitraan</span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Menjalin kemitraan dengan gerai kuliner di bandara, melakukan pelatihan staf gerai, dan memastikan kepatuhan standar harga serta kualitas menu.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 04. TATA KELOLA KORPORAT & SINERGI BUMN -->
            <section id="tata-kelola" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            04
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Tata Kelola &amp; Sinergi BUMN
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <p>
                        Seluruh aktivitas kemitraan dan operasional FlyDine berpedoman pada prinsip <em>Good Corporate Governance (GCG)</em>, selaras dengan ketentuan PT Angkasa Pura Indonesia dan regulasi Kementerian BUMN Republik Indonesia:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Akuntabilitas Finansial</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Seluruh pencatatan transaksi terintegrasi dengan sistem perpajakan daerah dan standar audit keuangan negara.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <h3 class="font-bold text-slate-900 text-sm mb-1">Kepatuhan Keamanan Aviasi</h3>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                                Petugas operasional diwajibkan memiliki pass bandara resmi dan mematuhi regulasi Aviation Security (AVSEC).
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 05. SALURAN REKRUTMEN RESMI -->
            <section id="saluran-rekrutmen" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs scroll-mt-28">
                <div class="flex items-center justify-between mb-5 pb-3.5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#005ea2] flex items-center justify-center font-black text-xs">
                            05
                        </div>
                        <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Saluran Rekrutmen Resmi &amp; Kontak HR
                        </h2>
                    </div>
                </div>

                <div class="space-y-4 text-sm text-slate-600 leading-relaxed font-normal">
                    <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 text-xs sm:text-sm text-emerald-900">
                        <strong class="font-bold block mb-1">Peringatan Rekrutmen Resmi Bebas Biaya:</strong>
                        Proses seleksi dan rekrutmen FlyDine Juanda <strong>100% GRATIS</strong>. Kami tidak pernah memungut biaya apapun, tidak meminta pembayaran tiket perjalanan, dan tidak bekerja sama dengan biro travel manapun.
                    </div>

                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 space-y-3 text-xs sm:text-sm text-slate-700">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <span class="text-slate-400 font-bold uppercase text-[10px] block mb-1">Pengiriman CV &amp; Portofolio:</span>
                                <a href="mailto:support@flydine.co.id" class="font-bold text-[#005ea2] hover:underline text-sm block">support@flydine.co.id</a>
                                <p class="text-slate-500 text-xs mt-0.5">Sertakan resume terbaru dan posisi yang diminati.</p>
                            </div>

                            <div>
                                <span class="text-slate-400 font-bold uppercase text-[10px] block mb-1">Kantor Manajemen Operasional:</span>
                                <p class="font-bold text-slate-900 text-sm">Gedung Administrasi Bandara Juanda</p>
                                <p class="text-slate-500 text-xs mt-0.5">Terminal 1 Keberangkatan, Sidoarjo, Jawa Timur 61253</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action -->
                    <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100">
                        <p class="text-xs text-slate-500 text-center sm:text-left">
                            Ingin kembali menikmati sajian kuliner bandara?
                        </p>
                        <a href="{{ route('customer.menu') }}" class="inline-flex items-center space-x-2 bg-[#005ea2] hover:bg-blue-700 text-white font-bold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition-colors shadow-xs">
                            <span>Kembali ke Beranda Pemesanan</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    </div>
                </div>
            </section>

        </main>
    </div>
</div>
@endsection

