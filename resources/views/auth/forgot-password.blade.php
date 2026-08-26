<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lupa Kata Sandi - FlyDine</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .airport-bg {
            background:
                linear-gradient(
                    90deg,
                    rgba(0, 59, 102, .96),
                    rgba(0, 94, 162, .75),
                    rgba(0, 94, 162, .30)
                ),
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
            box-shadow: 0 0 0 3px rgba(0, 94, 162, .12);
        }
    </style>
</head>

<body class="bg-[#f4f7fa]">

<div class="min-h-screen flex">

    {{-- ================= LEFT ================= --}}
    <section class="airport-bg hidden lg:flex lg:w-[59%] relative">

        <div class="w-full flex flex-col justify-center px-14 xl:px-20 text-white">

            {{-- Brand --}}
            <div class="flex items-center gap-6 mb-10">

                <div class="bg-white rounded-xl px-4 py-3 shadow-lg">
                    <img
                        src="{{ asset('images/angkasa-pura.png') }}"
                        alt="Angkasa Pura Indonesia"
                        class="w-32 xl:w-36 object-contain"
                    >
                </div>

                <div class="h-16 border-l border-white/40"></div>

                <div>
                    <h1 class="text-4xl xl:text-5xl font-extrabold">
                        Fly<span class="text-[#8dc63f]">Dine</span>
                    </h1>

                    <p class="mt-1 text-blue-100">
                        Digital Dining Experience
                    </p>
                </div>

            </div>

            <h2
                data-i18n="headline"
                class="max-w-xl text-3xl xl:text-5xl font-bold leading-tight"
            >
                Pulihkan Akses Portal FlyDine
            </h2>

            <p
                data-i18n="description"
                class="mt-6 max-w-xl text-base xl:text-lg leading-8 text-white/90"
            >
                Gunakan email akun Admin atau Staff Tenant yang terdaftar
                untuk membuat kata sandi baru.
            </p>

            <div class="flex gap-4 mt-9">

                <div class="bg-white/15 border border-white/20 backdrop-blur-md rounded-2xl px-5 py-4 min-w-[175px]">
                    <p data-i18n="locationLabel" class="text-[10px] tracking-widest text-blue-100">
                        LOKASI
                    </p>
                    <p class="font-bold text-sm mt-1">
                        Terminal 1 Juanda
                    </p>
                </div>

                <div class="bg-white/15 border border-white/20 backdrop-blur-md rounded-2xl px-5 py-4 min-w-[160px]">
                    <p data-i18n="securityLabel" class="text-[10px] tracking-widest text-blue-100">
                        KEAMANAN
                    </p>
                    <p data-i18n="recovery" class="font-bold text-sm mt-1">
                        Pemulihan Akun
                    </p>
                </div>

            </div>

        </div>

        <div class="absolute bottom-0 inset-x-0 h-1.5 bg-[#8dc63f]"></div>

    </section>


    {{-- ================= RIGHT ================= --}}
    <section class="w-full lg:w-[41%] min-h-screen flex items-center justify-center p-5 sm:p-8">

        <div class="w-full max-w-[450px]">

            {{-- Mobile --}}
            <div class="lg:hidden text-center mb-7">
                <img
                    src="{{ asset('images/angkasa-pura.png') }}"
                    alt="Angkasa Pura Indonesia"
                    class="w-32 mx-auto"
                >

                <h1 class="mt-3 text-4xl font-extrabold text-[#005ea2]">
                    Fly<span class="text-[#8dc63f]">Dine</span>
                </h1>
            </div>


            <div class="bg-white rounded-[26px] shadow-2xl overflow-hidden border border-gray-100">

                {{-- Header --}}
                <header class="bg-gradient-to-br from-[#004c80] via-[#0069a9] to-[#0787c7] px-7 py-7 text-white">

                    <div class="flex items-start justify-between gap-4">

                        <div class="bg-white rounded-xl px-3 py-2 shadow-sm">
                            <img
                                src="{{ asset('images/angkasa-pura.png') }}"
                                alt="Angkasa Pura Indonesia"
                                class="w-28 h-10 object-contain"
                            >
                        </div>

                        <div class="flex bg-white/10 border border-white/20 rounded-full p-1 text-xs font-semibold">

                            <button
                                id="btn-id"
                                type="button"
                                onclick="setLanguage('id')"
                                class="lang-btn px-3 py-1.5 rounded-full"
                            >
                                ID
                            </button>

                            <button
                                id="btn-en"
                                type="button"
                                onclick="setLanguage('en')"
                                class="lang-btn px-3 py-1.5 rounded-full"
                            >
                                EN
                            </button>

                        </div>

                    </div>

                    <div class="mt-7">

                        <p class="text-[11px] tracking-[.18em] text-blue-100 font-semibold">
                            FLYDINE
                        </p>

                        <h2
                            data-i18n="pageTitle"
                            class="mt-1 text-3xl font-extrabold"
                        >
                            Lupa Kata Sandi?
                        </h2>

                        <p
                            data-i18n="pageSubtitle"
                            class="mt-1 text-sm text-blue-100"
                        >
                            Pulihkan akses akun Anda
                        </p>

                    </div>

                </header>


                {{-- Content --}}
                <main class="px-7 sm:px-9 py-8">

                    {{-- Icon --}}
                    <div class="flex justify-center mb-5">

                        <div class="w-14 h-14 rounded-2xl bg-blue-50 text-[#005ea2] flex items-center justify-center">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-7 h-7"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M3 8l9 6 9-6M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"
                                />
                            </svg>

                        </div>

                    </div>

                    <div class="text-center mb-6">

                        <h3
                            data-i18n="formTitle"
                            class="text-xl font-bold text-gray-800"
                        >
                            Reset Kata Sandi
                        </h3>

                        <p
                            data-i18n="formDescription"
                            class="mt-2 text-sm leading-6 text-gray-500"
                        >
                            Masukkan email terdaftar. Kami akan mengirim link reset kata sandi.
                        </p>

                    </div>


                    <x-auth-session-status
                        class="mb-5"
                        :status="session('status')"
                    />


                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div>

                            <label
                                data-i18n="emailLabel"
                                for="email"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Email Terdaftar
                            </label>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                data-i18n-placeholder="emailPlaceholder"
                                placeholder="contoh@flydine.com"
                                autocomplete="email"
                                required
                                autofocus
                                class="input-flydine"
                            >

                            <x-input-error
                                :messages="$errors->get('email')"
                                class="mt-2"
                            />

                        </div>


                        <button
                            type="submit"
                            class="group mt-7 w-full bg-[#006bac] hover:bg-[#004d80]
                                   text-white font-bold py-3.5 rounded-xl shadow-lg
                                   flex items-center justify-center gap-2 transition"
                        >
                            <span data-i18n="submit">
                                KIRIM LINK RESET
                            </span>

                            <span class="group-hover:translate-x-1 transition">
                                →
                            </span>
                        </button>

                    </form>


                    {{-- Back --}}
                    <div class="mt-6 text-center">

                        <a
                            href="{{ route('login') }}"
                            class="text-sm font-semibold text-[#005ea2] hover:underline"
                        >
                            ←
                            <span data-i18n="back">
                                Kembali ke Login
                            </span>
                        </a>

                    </div>


                    {{-- Info --}}
                    <div class="mt-6 bg-[#f7fafc] border border-gray-100 rounded-xl p-3">

                        <p
                            data-i18n="helpTitle"
                            class="text-xs font-semibold text-gray-700"
                        >
                            Gunakan email yang terdaftar
                        </p>

                        <p
                            data-i18n="helpDesc"
                            class="mt-1 text-[11px] leading-5 text-gray-400"
                        >
                            Reset password hanya tersedia untuk Admin dan Staff Tenant.
                        </p>

                    </div>

                </main>


                {{-- Footer --}}
                <footer class="bg-[#fafbfc] border-t border-gray-100 text-center px-5 py-4">

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
        headline: 'Pulihkan Akses Portal FlyDine',
        description: 'Gunakan email akun Admin atau Staff Tenant yang terdaftar untuk membuat kata sandi baru.',
        locationLabel: 'LOKASI',
        securityLabel: 'KEAMANAN',
        recovery: 'Pemulihan Akun',
        pageTitle: 'Lupa Kata Sandi?',
        pageSubtitle: 'Pulihkan akses akun Anda',
        formTitle: 'Reset Kata Sandi',
        formDescription: 'Masukkan email terdaftar. Kami akan mengirim link reset kata sandi.',
        emailLabel: 'Email Terdaftar',
        emailPlaceholder: 'contoh@flydine.com',
        submit: 'KIRIM LINK RESET',
        back: 'Kembali ke Login',
        helpTitle: 'Gunakan email yang terdaftar',
        helpDesc: 'Reset password hanya tersedia untuk Admin dan Staff Tenant.'
    },

    en: {
        headline: 'Recover Your FlyDine Access',
        description: 'Use the registered Admin or Tenant Staff email to create a new password.',
        locationLabel: 'LOCATION',
        securityLabel: 'SECURITY',
        recovery: 'Account Recovery',
        pageTitle: 'Forgot Password?',
        pageSubtitle: 'Recover access to your account',
        formTitle: 'Reset Password',
        formDescription: 'Enter your registered email. We will send you a password reset link.',
        emailLabel: 'Registered Email',
        emailPlaceholder: 'example@flydine.com',
        submit: 'SEND RESET LINK',
        back: 'Back to Login',
        helpTitle: 'Use your registered email',
        helpDesc: 'Password reset is available only for Admins and Tenant Staff.'
    }
};


function setLanguage(lang) {
    const t = translations[lang];

    document.documentElement.lang = lang;

    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.dataset.i18n;
        if (t[key]) el.textContent = t[key];
    });

    document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
        const key = el.dataset.i18nPlaceholder;
        if (t[key]) el.placeholder = t[key];
    });

    document.querySelectorAll('.lang-btn').forEach(btn => {
        btn.classList.remove('bg-white', 'text-[#005ea2]');
        btn.classList.add('text-white');
    });

    const active = document.getElementById(`btn-${lang}`);
    active.classList.add('bg-white', 'text-[#005ea2]');
    active.classList.remove('text-white');

    document.title =
        lang === 'id'
            ? 'Lupa Kata Sandi - FlyDine'
            : 'Forgot Password - FlyDine';

    localStorage.setItem('flydineLanguage', lang);
}


document.addEventListener('DOMContentLoaded', () => {
    setLanguage(
        localStorage.getItem('flydineLanguage') || 'id'
    );
});
</script>

</body>
</html>
