<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi - FlyDine</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .airport-bg {
            background:
                linear-gradient(90deg, rgba(0,59,102,.96), rgba(0,94,162,.75), rgba(0,94,162,.30)),
                url("{{ asset('images/juanda.jpg') }}") center/cover no-repeat;
        }

        .input-flydine {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 14px;
            outline: none;
            transition: .2s;
        }

        .input-flydine:focus {
            border-color: #005ea2;
            box-shadow: 0 0 0 3px rgba(0,94,162,.12);
        }
    </style>
</head>

<body class="bg-[#f4f7fa]">

<div class="min-h-screen flex relative">

    {{-- Mobile Background --}}
    <div class="absolute inset-0 lg:hidden z-0 bg-[#004c80]">
        <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('{{ asset('images/juanda.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#001f3f]/90"></div>
    </div>

    {{-- LEFT --}}
    <section class="airport-bg hidden lg:flex lg:w-[59%] relative z-10">
        
        <div class="w-full flex flex-col justify-center px-14 xl:px-20 text-white relative z-10">

            <!-- Teks Utama Tanpa Logo -->
            <h2
                data-i18n="headline"
                class="max-w-xl text-3xl xl:text-5xl font-bold leading-tight"
            >
                Buat Kata Sandi Baru
            </h2>

            <p
                data-i18n="description"
                class="mt-6 max-w-xl text-lg leading-8 text-white/90"
            >
                Atur kata sandi baru untuk mengamankan kembali akses akun FlyDine Anda.
            </p>

            <div class="flex gap-4 mt-9">

                <div class="bg-white/15 border border-white/20 rounded-2xl px-5 py-4 min-w-[170px]">
                    <p
                        data-i18n="location"
                        class="text-[10px] tracking-widest text-blue-100"
                    >
                        LOKASI
                    </p>

                    <p class="font-bold text-sm mt-1">
                        T1, T2 Bandara Juanda
                    </p>
                </div>

                <div class="bg-white/15 border border-white/20 rounded-2xl px-5 py-4 min-w-[160px]">
                    <p
                        data-i18n="security"
                        class="text-[10px] tracking-widest text-blue-100"
                    >
                        KEAMANAN
                    </p>

                    <p class="font-bold text-sm mt-1">
                        Password Reset
                    </p>
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
                    <h2 data-i18n="pageTitle" class="text-3xl font-extrabold text-white tracking-tight">
                        Reset Kata Sandi
                    </h2>
                    <p data-i18n="pageSubtitle" class="mt-2 text-sm text-blue-100 font-medium">
                        Buat kata sandi baru untuk akun Anda
                    </p>
                </div>

                {{-- FORM --}}
                <form method="POST" action="{{ route('password.store') }}" class="space-y-5 relative z-10">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    {{-- EMAIL --}}
                    <div>
                        <label data-i18n="emailLabel" for="email" class="block text-sm font-bold text-blue-50 mb-2">
                            Email Terdaftar
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" 
                               required autofocus autocomplete="username"
                               data-placeholder="emailPlaceholder" placeholder="contoh@flydine.com"
                               class="input-flydine shadow-inner">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-300" />
                    </div>

                    {{-- KATA SANDI BARU --}}
                    <div>
                        <label data-i18n="passwordLabel" for="password" class="block text-sm font-bold text-blue-50 mb-2">
                            Kata Sandi Baru
                        </label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                   data-placeholder="passwordPlaceholder" placeholder="Masukkan kata sandi baru"
                                   class="input-flydine shadow-inner pr-12">
                            <button type="button" onclick="togglePassword('password', 'eye-icon-1')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="eye-icon-1"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-300" />
                    </div>

                    {{-- KONFIRMASI --}}
                    <div>
                        <label data-i18n="confirmLabel" for="password_confirmation" class="block text-sm font-bold text-blue-50 mb-2">
                            Konfirmasi Kata Sandi
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                   data-placeholder="confirmPlaceholder" placeholder="Ulangi kata sandi baru"
                                   class="input-flydine shadow-inner pr-12">
                            <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-2')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="eye-icon-2"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-red-300" />
                    </div>

                    <!-- Tombol (Putih, teks Biru) -->
                    <button type="submit" class="mt-6 w-full bg-white hover:bg-slate-50 text-[#005ea2] font-black py-4 rounded-xl transition-all flex items-center justify-center group shadow-md hover:-translate-y-0.5">
                        <span data-i18n="submit" class="tracking-wide uppercase text-sm">RESET KATA SANDI</span>
                        <svg class="h-4 w-4 ml-2 opacity-80 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </button>
                </form>
            </div>

            <!-- Footer Small -->
            <div class="text-center mt-8">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-full hover:text-[#005ea2] hover:border-blue-200 hover:bg-blue-50/50 shadow-sm transition-all group">
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-[#005ea2] group-hover:-translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span data-i18n="back">Kembali ke Login</span>
                </a>
                <p class="text-xs font-semibold text-slate-400 mt-6">
                    &copy; {{ date('Y') }} FlyDine PT Angkasa Pura Indonesia.
                </p>
            </div>

        </div>
</div>


<script>
const translations = {
    id: {
        headline: 'Buat Kata Sandi Baru',
        description: 'Atur kata sandi baru untuk mengamankan kembali akses akun FlyDine Anda.',
        location: 'LOKASI',
        security: 'KEAMANAN',
        pageTitle: 'Reset Kata Sandi',
        pageSubtitle: 'Buat kata sandi baru untuk akun Anda',
        formDescription: 'Masukkan email dan kata sandi baru Anda.',
        passwordLabel: 'Kata Sandi Baru',
        passwordPlaceholder: 'Masukkan kata sandi baru',
        confirmLabel: 'Konfirmasi Kata Sandi',
        confirmPlaceholder: 'Ulangi kata sandi baru',
        submit: 'RESET KATA SANDI',
        back: 'Kembali ke Login',
        resetError: 'Reset kata sandi gagal. Periksa kembali data Anda.'
    },

    en: {
        headline: 'Create a New Password',
        description: 'Set a new password to secure your FlyDine account.',
        location: 'LOCATION',
        security: 'SECURITY',
        pageTitle: 'Reset Password',
        pageSubtitle: 'Create a new password for your account',
        formDescription: 'Enter your email and new password.',
        passwordLabel: 'New Password',
        passwordPlaceholder: 'Enter your new password',
        confirmLabel: 'Confirm Password',
        confirmPlaceholder: 'Repeat your new password',
        submit: 'RESET PASSWORD',
        back: 'Back to Login',
        resetError: 'Password reset failed. Please check your information.'
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

    document.title =
        lang === 'id'
            ? 'Reset Kata Sandi - FlyDine'
            : 'Reset Password - FlyDine';

    localStorage.setItem('flydineLanguage', lang);
}

function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}

document.addEventListener('DOMContentLoaded', () => {
    setLanguage(
        localStorage.getItem('flydineLanguage') || 'id'
    );
});
</script>

</body>
</html>
