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

        .input-flydine {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
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

<div class="min-h-screen flex">

    {{-- LEFT --}}
    <section class="airport-bg hidden lg:flex lg:w-[59%] relative">
        <div class="w-full flex flex-col justify-center px-14 xl:px-20 text-white">

            <div class="flex items-center gap-6 mb-10">
                <div class="bg-white rounded-xl px-4 py-3 shadow-lg">
                    <img src="{{ asset('images/angkasa-pura.png') }}"
                         alt="Angkasa Pura Indonesia"
                         class="w-32 xl:w-36">
                </div>

                <div class="h-16 border-l border-white/40"></div>

                <div>
                    <img src="{{ asset('images/logo-flydine.png') }}" alt="FlyDine Logo" class="h-28 w-auto object-contain brightness-[10] contrast-[200] drop-shadow-md">
                    <p class="text-blue-100 mt-2 text-lg">Digital Dining Experience</p>
                </div>
            </div>

            <h2 data-i18n="headline"
                class="max-w-2xl text-3xl xl:text-5xl font-bold leading-tight">
                Smart Food Ordering untuk Bandara Internasional Juanda
            </h2>

            <p data-i18n="description"
               class="mt-6 max-w-2xl text-lg leading-8 text-white/90">
                Platform digital yang menghubungkan penumpang dengan tenant makanan melalui layanan pre-order.
            </p>

            <div class="flex gap-4 mt-9">
                <div class="bg-white/15 border border-white/20 rounded-2xl px-5 py-4 min-w-[170px]">
                    <p data-i18n="location"
                       class="text-[10px] tracking-widest text-blue-100">
                        LOKASI
                    </p>
                    <p class="font-bold text-sm mt-1">Terminal 1 Juanda</p>
                </div>

                <div class="bg-white/15 border border-white/20 rounded-2xl px-5 py-4 min-w-[150px]">
                    <p data-i18n="system"
                       class="text-[10px] tracking-widest text-blue-100">
                        SISTEM
                    </p>
                    <p class="font-bold text-sm mt-1">FlyDine MVP</p>
                </div>
            </div>

        </div>

        <div class="absolute bottom-0 inset-x-0 h-1.5 bg-[#8dc63f]"></div>
    </section>


    {{-- RIGHT --}}
    <section class="w-full lg:w-[41%] min-h-screen flex items-center justify-center p-6">

        <div class="w-full max-w-[450px]">

            {{-- Mobile --}}
            <div class="lg:hidden text-center mb-7">
                <img src="{{ asset('images/angkasa-pura.png') }}"
                     alt="Angkasa Pura Indonesia"
                     class="w-32 mx-auto">

                <img src="{{ asset('images/logo-flydine.png') }}" alt="FlyDine Logo" class="h-24 w-auto object-contain mx-auto mt-4">
            </div>


            <div class="bg-white rounded-[26px] shadow-2xl overflow-hidden border border-gray-100">

                {{-- HEADER --}}
                <header class="bg-gradient-to-br from-[#004c80] via-[#0069a9] to-[#0787c7] px-7 py-7 text-white">

                    <div class="flex justify-between items-start">

                        <div class="bg-white rounded-xl px-3 py-2">
                            <img src="{{ asset('images/angkasa-pura.png') }}"
                                 alt="Angkasa Pura Indonesia"
                                 class="w-28 h-10 object-contain">
                        </div>

                        <div class="flex bg-white/10 border border-white/20 rounded-full p-1 text-xs font-semibold">
                            <button id="btn-id"
                                    type="button"
                                    onclick="setLanguage('id')"
                                    class="lang-btn px-3 py-1.5 rounded-full">
                                ID
                            </button>

                            <button id="btn-en"
                                    type="button"
                                    onclick="setLanguage('en')"
                                    class="lang-btn px-3 py-1.5 rounded-full">
                                EN
                            </button>
                        </div>

                    </div>

                    <h2 data-i18n="portalTitle"
                        class="mt-7 text-3xl font-extrabold">
                        Portal Login
                    </h2>

                    <p data-i18n="portalSubtitle"
                       class="mt-1 text-sm text-blue-100">
                        Manajemen Admin & Tenant
                    </p>

                </header>


                {{-- CONTENT --}}
                <main class="px-7 sm:px-9 py-8">

                    <div class="text-center mb-7">
                        <h3 class="text-3xl font-extrabold text-[#005ea2]">
                            Fly<span class="text-[#8dc63f]">Dine</span>
                        </h3>

                        <p data-i18n="portalDesc"
                           class="mt-1 text-xs text-gray-400">
                            Portal Admin & Mitra Tenant
                        </p>
                    </div>


                    {{-- RESET PASSWORD SUCCESS --}}
                    @if (session('reset_success'))
                        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3">
                            <p data-i18n="resetSuccess"
                               class="text-sm font-medium text-green-700">
                                Kata sandi berhasil diubah. Silakan login kembali.
                            </p>
                        </div>
                    @endif


                    <x-auth-session-status
                        class="mb-5"
                        :status="session('status')"
                    />


                    {{-- FORM --}}
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <label for="email"
                               class="block text-sm font-semibold text-gray-700 mb-2">
                            Email
                        </label>

                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Contoh: staff.killiney@flydine.test"
                               required
                               autofocus
                               autocomplete="username"
                               class="input-flydine">

                        <x-input-error
                            :messages="$errors->get('email')"
                            class="mt-2"
                        />


                        <div class="mt-5">
                            <label data-i18n="password"
                                   for="password"
                                   class="block text-sm font-semibold text-gray-700 mb-2">
                                Kata Sandi
                            </label>

                            <div class="relative">
                                <input id="password"
                                       type="password"
                                       name="password"
                                       data-placeholder="passwordPlaceholder"
                                       placeholder="Masukkan kata sandi"
                                       required
                                       autocomplete="current-password"
                                       class="input-flydine pr-12">

                                <button type="button"
                                        onclick="togglePassword()"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    👁
                                </button>
                            </div>

                            <x-input-error
                                :messages="$errors->get('password')"
                                class="mt-2"
                            />
                        </div>


                        <div class="mt-5 flex items-center justify-between">

                            <label class="flex items-center">
                                <input type="checkbox"
                                       name="remember"
                                       class="rounded border-gray-300 text-[#005ea2]">

                                <span data-i18n="remember"
                                      class="ml-2 text-sm text-gray-600">
                                    Ingat saya
                                </span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                   data-i18n="forgot"
                                   class="text-sm font-semibold text-[#005ea2] hover:underline">
                                    Lupa Kata Sandi?
                                </a>
                            @endif

                        </div>


                        <button type="submit"
                                class="mt-7 w-full bg-[#006bac] hover:bg-[#004d80]
                                       text-white font-bold py-3.5 rounded-xl shadow-lg transition">
                            <span data-i18n="login">MASUK</span> →
                        </button>

                    </form>


                    <div class="mt-6 bg-[#f7fafc] border border-gray-100 rounded-xl p-3">
                        <p data-i18n="securityTitle"
                           class="text-xs font-semibold text-gray-700">
                            Portal Internal FlyDine
                        </p>

                        <p data-i18n="securityDesc"
                           class="mt-1 text-[11px] text-gray-400">
                            Akses untuk Admin Operasional dan Staff Tenant.
                        </p>
                    </div>

                </main>


                <footer class="border-t bg-[#fafbfc] text-center py-4">
                    <p class="text-[11px] text-gray-400">
                        © 2026 FlyDine System
                    </p>
                    <p class="text-[10px] text-gray-400 mt-1">
                        Juanda International Airport
                    </p>
                </footer>

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
        portalTitle: 'Portal Login',
        portalSubtitle: 'Manajemen Admin & Tenant',
        portalDesc: 'Portal Admin & Mitra Tenant',
        password: 'Kata Sandi',
        passwordPlaceholder: 'Masukkan kata sandi',
        remember: 'Ingat saya',
        forgot: 'Lupa Kata Sandi?',
        login: 'MASUK',
        securityTitle: 'Portal Internal FlyDine',
        securityDesc: 'Akses untuk Admin Operasional dan Staff Tenant.',
        resetSuccess: 'Kata sandi berhasil diubah. Silakan login kembali.'
    },

    en: {
        headline: 'Smart Food Ordering for Juanda International Airport',
        description: 'A digital platform connecting passengers with airport food tenants through pre-order services.',
        location: 'LOCATION',
        system: 'SYSTEM',
        portalTitle: 'Login Portal',
        portalSubtitle: 'Admin & Tenant Management',
        portalDesc: 'Admin & Tenant Partner Portal',
        password: 'Password',
        passwordPlaceholder: 'Enter your password',
        remember: 'Remember me',
        forgot: 'Forgot Password?',
        login: 'LOGIN',
        securityTitle: 'FlyDine Internal Portal',
        securityDesc: 'Access for Operations Admins and Tenant Staff.',
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

    document.querySelectorAll('.lang-btn').forEach(btn => {
        btn.className = 'lang-btn px-3 py-1.5 rounded-full text-white';
    });

    document.getElementById(`btn-${lang}`).className =
        'lang-btn px-3 py-1.5 rounded-full bg-white text-[#005ea2]';

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
