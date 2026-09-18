<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - FlyDine Juanda</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="text-slate-800 antialiased min-h-screen flex flex-col bg-slate-50">

    <!-- Navbar / Header Elegan & Minimalis -->
    <header class="bg-white sticky top-0 z-50 w-full border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            
            <!-- Kiri: Tombol Kembali -->
            <div class="flex items-center">
                <a href="{{ url('/dashboard') }}" class="w-9 h-9 rounded-full bg-white border border-slate-200 flex items-center justify-center hover:bg-slate-50 hover:text-slate-700 transition-colors text-slate-500 shadow-sm" title="Kembali">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                </a>
            </div>
            
            <!-- Kanan: Identitas User (Dipindah ke sini) -->
            <div class="flex items-center gap-3.5">
                <!-- Teks rata kanan (Disembunyikan di layar HP yg terlalu kecil agar tidak sesak) -->
                <div class="text-right hidden sm:block">
                    <h1 class="text-sm font-bold text-slate-800 tracking-tight leading-tight">{{ auth()->user()->name }}</h1>
                    <p class="text-[10px] font-bold text-slate-500 mt-0.5 uppercase tracking-wider">
                        {{ auth()->user()->role === 'tenant_staff' ? 'Mitra Tenant' : 'Admin Sistem' }}
                    </p>
                </div>
                <!-- Avatar -->
                <div class="relative cursor-pointer hover:opacity-80 transition-opacity">
                    <div class="h-10 w-10 bg-[#005ea2] rounded-full flex items-center justify-center text-white font-bold text-sm shadow-inner">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <!-- Titik hijau online -->
                    <div class="absolute bottom-0 right-0 h-3 w-3 bg-emerald-500 rounded-full border-2 border-white"></div>
                </div>
            </div>
            
        </div>
    </header>

    <!-- Page Header -->
    <div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-6">
        <div class="flex items-center space-x-2 text-xs font-semibold text-slate-400 mb-2">
            <a href="{{ url('/dashboard') }}" class="hover:text-[#005ea2] transition-colors">Dashboard</a>
            <span>/</span>
            <span class="text-slate-600">Pengaturan Akun</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Profil Saya</h2>
        <p class="text-sm text-slate-500 font-medium mt-1">Kelola informasi data diri, keamanan, dan preferensi akun Anda.</p>
    </div>

    <!-- Main Content Grid -->
    <main class="flex-grow w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
            
            <!-- Kolom Kiri: Informasi Profil (Lebar 7) -->
            <div class="lg:col-span-7 flex flex-col space-y-6">
                <!-- Card Informasi Profil -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Keamanan & Hapus Akun (Lebar 5) -->
            <div class="lg:col-span-5 flex flex-col space-y-6">
                
                <!-- Card Ubah Password -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
                
                <!-- Card Hapus Akun (Danger Zone) -->
                <div class="bg-white rounded-2xl shadow-sm border border-rose-200 overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-rose-500"></div>
                    <div class="p-6 sm:p-8">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
            
        </div>
    </main>

</body>
</html>