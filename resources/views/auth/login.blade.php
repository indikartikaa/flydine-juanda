<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlyDine Portal - Juanda International Airport</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .airport-bg {
            background:
                linear-gradient(90deg,
                    rgba(0,59,102,.96),
                    rgba(0,94,162,.77),
                    rgba(0,94,162,.30)
                ),
                url("{{ asset('images/juanda.jpg') }}") center/cover no-repeat;
        }

        /* Gaya Textbox Aslimu */
        .input-flydine {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            outline: none;
            transition: .2s;
            background-color: #ffffff;
            color: #1e293b;
        }

        .input-flydine::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        /* Focus ring diubah sedikit agar cocok di atas background biru */
        .input-flydine:focus {
            border-color: #38bdf8; 
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.2);
        }
    </style>
</head>

<body class="bg-[#f8fafc]">

<div class="min-h-screen flex w-full relative">

    {{-- Mobile Background --}}
    <div class="absolute inset-0 lg:hidden z-0 bg-[#004c80]">
        <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('{{ asset('images/juanda.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#001f3f]/90"></div>
    </div>

    {{-- ==========================================
         KIRI: VISUAL BRANDING (59%)
         ========================================== --}}
    <section class="airport-bg hidden lg:flex lg:w-[59%] relative z-10">

        <div class="w-full flex flex-col justify-center px-14 xl:px-20 text-white relative z-10">

            <!-- Teks Utama -->
            <h2 data-i18n="headline" class="max-w-2xl text-4xl xl:text-5xl font-bold leading-tight">
                Smart Food Ordering untuk Bandara Internasional Juanda
            </h2>

            <p data-i18n="description" class="mt-6 max-w-2xl text-lg leading-8 text-white/90">
                Platform digital yang menghubungkan penumpang dengan tenant makanan melalui layanan pre-order.
            </p>

            <!-- Badge Lokasi & Sistem -->
            <div class="flex gap-4 mt-10">
                <div class="bg-white/15 border border-white/20 rounded-2xl px-5 py-4 min-w-[170px] backdrop-blur-sm">
                    <p data-i18n="location" class="text-[10px] tracking-widest text-blue-100 uppercase font-semibold">
                        Lokasi
                    </p>
                    <p class="font-bold text-sm mt-1">T1, T2 Bandara Juanda</p>
                </div>

                <div class="bg-white/15 border border-white/20 rounded-2xl px-5 py-4 min-w-[150px] backdrop-blur-sm">
                    <p data-i18n="system" class="text-[10px] tracking-widest text-blue-100 uppercase font-semibold">
                        Sistem
                    </p>
                    <p class="font-bold text-sm mt-1">FlyDine</p>
                </div>
            </div>

        </div>
    </section>


    {{-- ==========================================
         KANAN: AREA FORM (41%)
         ========================================== --}}
    <section class="w-full lg:w-[41%] min-h-screen flex items-center justify-center p-6 relative z-10 lg:bg-[#f8fafc]">

        <!-- Tombol Bahasa (Responsif: Turun sedikit di mobile agar tidak tertutup logo) -->
        <div class="absolute top-4 right-4 sm:top-8 sm:right-8 flex bg-white/30 backdrop-blur-md p-1 rounded-lg border border-white/40 shadow-sm z-20">
            <button id="btn-id" type="button" onclick="setLanguage('id')" class="px-3 py-1.5 rounded-md text-xs font-bold bg-white text-[#005ea2] shadow-sm transition-all">ID</button>
            <button id="btn-en" type="button" onclick="setLanguage('en')" class="px-3 py-1.5 rounded-md text-xs font-bold text-slate-600 hover:text-slate-900 transition-all">EN</button>
        </div>

        <div class="w-full max-w-[420px] flex flex-col items-center mt-16 sm:mt-6">

            {{-- 1. LOGO AREA: Responsif --}}
            <div class="flex items-center gap-5 sm:gap-8 bg-white border border-slate-200/80 rounded-2xl px-6 sm:px-8 py-3 sm:py-3.5 shadow-md shadow-slate-200/50 mb-7 relative z-10 scale-90 sm:scale-100">
                <img src="{{ asset('images/angkasa-pura.png') }}" alt="Angkasa Pura" class="h-9 sm:h-14 w-auto object-contain transform scale-[1.15] sm:scale-[1.35] origin-center transition-transform">
                <!-- Garis Pemisah (Divider) -->
                <div class="w-px h-9 sm:h-11 bg-slate-300 rounded-full"></div>
                <img src="{{ asset('images/logo-flydine.png') }}" alt="FlyDine" class="h-8 sm:h-13 w-auto object-contain transform scale-150 sm:scale-[1.85] origin-center transition-transform">
            </div>

            {{-- 2. BLUE CARD: Form Area --}}
            <div class="w-full bg-[#005ea2] rounded-[2rem] shadow-2xl shadow-blue-900/20 p-8 sm:p-10 relative overflow-hidden">
                
                <!-- Aksen cahaya tipis di dalam card biru -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>

                {{-- HEADER TEKS (Di Dalam Card) --}}
                <div class="mb-8 relative z-10 text-center">
                    <h2 data-i18n="portalTitle" class="text-3xl font-extrabold text-white tracking-tight">
                        Welcome Back!
                    </h2>
                    <p data-i18n="portalSubtitle" class="mt-2 text-sm text-blue-100 font-medium">
                        Login to Admin & Tenant Portal
                    </p>
                </div>

                {{-- NOTIFIKASI --}}
                @if (session('reset_success'))
                    <div class="mb-6 rounded-xl border border-green-400/50 bg-green-500/20 px-4 py-3 flex items-center justify-center gap-2 backdrop-blur-sm relative z-10 text-center">
                        <svg class="w-5 h-5 text-green-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p data-i18n="resetSuccess" class="text-sm font-medium text-white">
                            Kata sandi berhasil diubah. Silakan login.
                        </p>
                    </div>
                @endif

                <x-auth-session-status class="mb-5 relative z-10" :status="session('status')" />

                {{-- FORM --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-5 relative z-10">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-bold text-blue-50 mb-2">
                            Email
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" 
                               placeholder="Contoh: staff.killiney@flydine.test" required autofocus autocomplete="username"
                               class="input-flydine shadow-inner">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-300" />
                    </div>

                    <div>
                        <label data-i18n="password" for="password" class="block text-sm font-bold text-blue-50 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input id="password" type="password" name="password" 
                                   data-placeholder="passwordPlaceholder" placeholder="Enter your password" required autocomplete="current-password"
                                   class="input-flydine shadow-inner pr-12">
                            
                            <!-- Eye Icon -->
                            <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-300" />
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-blue-300 bg-white/10 text-[#005ea2] focus:ring-white/30 cursor-pointer transition-colors">
                            <span data-i18n="remember" class="ml-2 text-sm font-medium text-blue-100 group-hover:text-white transition-colors">
                                Remember me
                            </span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" data-i18n="forgot" class="text-sm font-bold text-white hover:text-blue-200 transition-colors underline decoration-white/40 hover:decoration-white">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <!-- Tombol Login (Putih, teks Biru) -->
                    <button type="submit" class="mt-6 w-full bg-white hover:bg-slate-50 text-[#005ea2] font-black py-4 rounded-xl transition-all flex items-center justify-center group shadow-md hover:-translate-y-0.5">
                        <span data-i18n="login" class="tracking-wide uppercase text-sm">Login</span>
                        <svg class="h-4 w-4 ml-2 opacity-80 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </button>
                </form>
            </div>

            <!-- Footer Small -->
            <div class="text-center mt-10">
                <p class="text-xs font-semibold text-slate-400">
                    &copy; {{ date('Y') }} FlyDine PT Angkasa Pura Indonesia.
                </p>
            </div>

        </div>
        </section>

</div>

<script>
const translations = {
    id: {
        headline: 'Smart Food Ordering untuk Bandara Internasional Juanda',
        description: 'Platform digital yang menghubungkan penumpang dengan tenant makanan melalui layanan pre-order.',
        location: 'LOKASI',
        system: 'SISTEM',
        portalTitle: 'Selamat Datang!',
        portalSubtitle: 'Login ke Portal Admin & Tenant',
        password: 'Kata Sandi',
        passwordPlaceholder: 'Masukkan kata sandi',
        remember: 'Ingat saya',
        forgot: 'Lupa Sandi?',
        login: 'Masuk',
        resetSuccess: 'Kata sandi berhasil diubah. Silakan login kembali.'
    },
    en: {
        headline: 'Smart Food Ordering for Juanda International Airport',
        description: 'A digital platform connecting passengers with airport food tenants through pre-order services.',
        location: 'LOCATION',
        system: 'SYSTEM',
        portalTitle: 'Welcome Back!',
        portalSubtitle: 'Login to Admin & Tenant Portal',
        password: 'Password',
        passwordPlaceholder: 'Enter your password',
        remember: 'Remember me',
        forgot: 'Forgot Password?',
        login: 'Login',
        resetSuccess: 'Password changed successfully. Please login again.'
    }
};

function setLanguage(lang) {
    const t = translations[lang];
    document.documentElement.lang = lang;

    document.querySelectorAll('[data-i18n]').forEach(el => {
        el.textContent = t[el.dataset.i18n] ?? el.textContent;
    });

    document.querySelectorAll('[data-placeholder]').forEach(el => {
        el.placeholder = t[el.dataset.placeholder] ?? el.placeholder;
    });

    const activeClass = 'px-3 py-1.5 rounded-md text-xs font-bold bg-white text-[#005ea2] shadow-sm transition-all';
    const inactiveClass = 'px-3 py-1.5 rounded-md text-xs font-bold text-slate-600 hover:text-slate-900 transition-all';

    document.getElementById('btn-id').className = lang === 'id' ? activeClass : inactiveClass;
    document.getElementById('btn-en').className = lang === 'en' ? activeClass : inactiveClass;

    localStorage.setItem('flydineLanguage', lang);
}

function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}

document.addEventListener('DOMContentLoaded', () => {
    setLanguage(localStorage.getItem('flydineLanguage') || 'id');
});
</script>

</body>
</html>