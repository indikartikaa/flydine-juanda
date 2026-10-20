<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - FlyDine Juanda</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="text-slate-800 antialiased min-h-screen flex flex-col bg-slate-50">

    @php
        $user = auth()->user();
        $isTenant = $user && $user->role === 'tenant_staff';
        $dashboardUrl = $isTenant ? url('/dashboard') : route('admin.dashboard');
    @endphp

    <!-- Header Atas: Glassmorphic dengan Logo Brand di Kiri & Aksi Navigasi di Kanan -->
    <header class="bg-white/95 backdrop-blur-md sticky top-0 z-50 w-full border-b border-slate-200/80 shadow-xs h-20 flex items-center">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex items-center justify-between">
            
            <!-- Sisi Kiri: Logo Asli FlyDine (Brand Anchor Utama di Kiri) -->
            <a href="{{ $dashboardUrl }}" class="flex items-center group" title="Kembali ke Dashboard">
                <img src="{{ asset('images/logo-flydine.png') }}" alt="FlyDine Logo" class="w-44 sm:w-48 object-contain group-hover:scale-105 transition-transform drop-shadow-sm">
            </a>
            
            <!-- Sisi Kanan: Tombol Kembali ke Dashboard & Profil User -->
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="{{ $dashboardUrl }}" 
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200/80 active:scale-95 text-slate-700 hover:text-slate-900 transition-all text-xs font-bold shadow-2xs group cursor-pointer" 
                   title="Kembali ke Dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="hidden sm:inline">Kembali ke Dashboard</span>
                    <span class="sm:hidden">Dashboard</span>
                </a>

                <div class="h-7 w-px bg-slate-200 hidden sm:block"></div>

                <div class="flex items-center gap-2.5 sm:gap-3">
                    <div class="hidden md:block text-right">
                        <span class="block text-xs font-bold text-slate-800 leading-tight truncate max-w-[150px]">{{ $user->name }}</span>
                        <span class="block text-[11px] font-semibold text-slate-400 mt-0.5">
                            {{ $isTenant ? 'Mitra Tenant' : 'Admin Ops' }}
                        </span>
                    </div>

                    <div class="relative shrink-0">
                        @if($isTenant && $user->tenant?->logo)
                            <img src="{{ asset($user->tenant->logo) }}" alt="Avatar" class="h-9 w-9 sm:h-10 sm:w-10 rounded-xl sm:rounded-2xl object-cover shadow-sm border border-slate-200">
                        @else
                            <div class="h-9 w-9 sm:h-10 sm:w-10 bg-gradient-to-tr from-[#005ea2] to-blue-600 rounded-xl sm:rounded-2xl flex items-center justify-center text-white font-extrabold text-xs sm:text-sm shadow-md shadow-blue-600/20">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        @endif
                        <span class="absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 sm:h-3 sm:w-3 bg-[#8dc63f] border-2 border-white rounded-full"></span>
                    </div>
                </div>
            </div>
            
        </div>
    </header>

    <!-- Page Header & Alert Messages -->
    <div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-4">
        @if (session('success'))
            <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm font-bold flex items-center gap-3 shadow-2xs">
                <div class="w-7 h-7 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm font-bold flex items-center gap-3 shadow-2xs">
                <div class="w-7 h-7 rounded-lg bg-rose-500 text-white flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-2">
            <a href="{{ $dashboardUrl }}" class="hover:text-slate-600 transition">Dashboard</a>
            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-[#005ea2] font-extrabold">Pengaturan Akun</span>
        </nav>

        <!-- Page Title & Subtitle -->
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Profil Saya</h1>
            <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-1">
                Kelola informasi data diri, akun, dan keamanan kata sandi Anda.
            </p>
        </div>
    </div>

    <!-- Main Content Grid: 2 Kartu Bersih Bersebelahan Simetris -->
    <main class="flex-grow w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-2 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 items-start">
            
            <!-- Kartu 1: Informasi Profil -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="p-6 sm:p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Kartu 2: Keamanan Password -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="p-6 sm:p-8">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
            
        </div>
    </main>

</body>
</html>