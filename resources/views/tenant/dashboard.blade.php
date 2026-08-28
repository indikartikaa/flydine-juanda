<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tenant Dashboard - FlyDine</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-[#f4f7fa]">

@php
    $user = auth()->user();
    $tenant = $user->tenant;

    $activeProducts = $tenant?->products
        ? $tenant->products->where('is_available', true)->count()
        : 0;
@endphp

<div class="min-h-screen flex">

    {{-- SIDEBAR --}}
    <aside class="hidden md:flex w-64 bg-gradient-to-b
                  from-[#004d7e] to-[#006faa]
                  text-white flex-col">

        {{-- LOGO --}}
        <div class="px-6 py-7 border-b border-white/10">
            <h1 class="text-2xl font-extrabold">
                Fly<span class="text-[#8dc63f]">Dine</span>
            </h1>

            <p class="text-xs text-blue-100 mt-1">
                Tenant Partner Portal
            </p>
        </div>


        {{-- MENU --}}
        <nav class="flex-1 p-4 space-y-2">

            {{-- Dashboard --}}
            <a href="{{ route('tenant.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl
                      bg-white/15 border-l-4 border-[#8dc63f]
                      font-semibold">
                <span>▦</span>
                Dashboard
            </a>

            {{-- Pesanan --}}
            <a href="{{ route('tenant.orders') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl
                      text-blue-100 hover:bg-white/10">
                <span>☷</span>
                Pesanan
            </a>

            {{-- Produk --}}
            <a href="{{ route('tenant.products') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl
                      text-blue-100 hover:bg-white/10">
                <span>▣</span>
                Produk
            </a>

            {{-- Profil Tenant --}}
            <a href="{{ route('tenant.profile') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl
                      text-blue-100 hover:bg-white/10">
                <span>⚙</span>
                Pengaturan Profil
            </a>

        </nav>


        {{-- LOGOUT --}}
        <div class="p-4 border-t border-white/10">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                        class="w-full bg-red-500 hover:bg-red-600
                               py-2.5 rounded-xl text-sm font-semibold">
                    Keluar
                </button>
            </form>

        </div>

    </aside>


    {{-- MAIN --}}
    <main class="flex-1">

        {{-- HEADER --}}
        <header class="bg-white border-b px-6 lg:px-8 h-20
                       flex items-center justify-between">

            <div>
                <p class="text-xs font-semibold text-[#0072ad]
                          uppercase tracking-wider">
                    Tenant Dashboard
                </p>

                <h2 class="text-xl font-bold text-gray-800">
                    {{ $tenant?->name ?? 'Tenant FlyDine' }}
                </h2>
            </div>

            <div class="text-right">

                <p class="font-semibold text-gray-700">
                    {{ $user->name }}
                </p>

                <p class="text-xs text-gray-400">
                    {{ $user->email }}
                </p>

            </div>

        </header>


        <div class="p-6 lg:p-8">

            {{-- WELCOME --}}
            <section class="bg-gradient-to-r
                            from-[#005e9e] to-[#0b86c4]
                            rounded-2xl p-6 text-white
                            mb-7 shadow-lg">

                <p class="text-sm text-blue-100">
                    Selamat datang,
                </p>

                <h1 class="text-2xl lg:text-3xl font-extrabold mt-1">
                    {{ $tenant?->name ?? $user->name }}
                </h1>

                <p class="text-sm text-blue-100 mt-2">
                    Kelola pesanan dan produk tenant FlyDine
                    dari satu dashboard.
                </p>


                <div class="flex flex-wrap gap-3 mt-5">

                    {{-- LOKASI --}}
                    <span class="bg-white/15 px-4 py-2 rounded-xl text-xs">
                        📍
                        {{ $tenant?->floor_location ?? 'Lokasi belum diatur' }}
                    </span>


                    {{-- JAM --}}
                    <span class="bg-white/15 px-4 py-2 rounded-xl text-xs">

                        🕒

                        {{ $tenant?->opening_time
                            ? substr($tenant->opening_time, 0, 5)
                            : '--:--' }}

                        -

                        {{ $tenant?->closing_time
                            ? substr($tenant->closing_time, 0, 5)
                            : '--:--' }}

                    </span>


                    {{-- STATUS --}}
                    <span class="{{ $tenant?->is_active
                        ? 'bg-[#8dc63f]'
                        : 'bg-gray-500' }}
                        px-4 py-2 rounded-xl text-xs font-semibold">

                        ●

                        {{ $tenant?->is_active
                            ? 'Tenant Aktif'
                            : 'Tenant Nonaktif' }}

                    </span>

                </div>


                {{-- LOKASI GPS --}}
                @if($tenant?->latitude && $tenant?->longitude)

                    <a href="https://www.google.com/maps?q={{ $tenant->latitude }},{{ $tenant->longitude }}"
                       target="_blank"
                       class="inline-block mt-5 bg-white
                              text-[#006ca8] px-4 py-2
                              rounded-xl text-xs font-bold">
                        📍 Lihat Lokasi Tenant
                    </a>

                @else

                    <a href="{{ route('tenant.profile') }}"
                       class="inline-block mt-5 bg-white
                              text-[#006ca8] px-4 py-2
                              rounded-xl text-xs font-bold">
                        📍 Atur Lokasi Tenant
                    </a>

                @endif

            </section>


            {{-- STATISTICS --}}
            <section class="grid sm:grid-cols-2 xl:grid-cols-4
                            gap-5 mb-7">

                {{-- PESANAN --}}
                <div class="bg-white rounded-2xl border
                            p-5 shadow-sm">

                    <p class="text-xs text-gray-400
                              font-semibold uppercase">
                        Pesanan Masuk
                    </p>

                    <h3 class="text-3xl font-extrabold
                               text-[#006dac] mt-2">
                        0
                    </h3>

                    <p class="text-xs text-gray-400 mt-1">
                        Hari ini
                    </p>

                </div>


                {{-- DIPROSES --}}
                <div class="bg-white rounded-2xl border
                            p-5 shadow-sm">

                    <p class="text-xs text-gray-400
                              font-semibold uppercase">
                        Diproses
                    </p>

                    <h3 class="text-3xl font-extrabold
                               text-orange-500 mt-2">
                        0
                    </h3>

                    <p class="text-xs text-gray-400 mt-1">
                        Pesanan aktif
                    </p>

                </div>


                {{-- SELESAI --}}
                <div class="bg-white rounded-2xl border
                            p-5 shadow-sm">

                    <p class="text-xs text-gray-400
                              font-semibold uppercase">
                        Selesai
                    </p>

                    <h3 class="text-3xl font-extrabold
                               text-[#8dc63f] mt-2">
                        0
                    </h3>

                    <p class="text-xs text-gray-400 mt-1">
                        Hari ini
                    </p>

                </div>


                {{-- PRODUK --}}
                <div class="bg-white rounded-2xl border
                            p-5 shadow-sm">

                    <p class="text-xs text-gray-400
                              font-semibold uppercase">
                        Produk Aktif
                    </p>

                    <h3 class="text-3xl font-extrabold
                               text-gray-800 mt-2">

                        {{ $activeProducts }}

                    </h3>

                    <p class="text-xs text-gray-400 mt-1">
                        Dalam katalog
                    </p>

                </div>

            </section>


            {{-- CONTENT --}}
            <section class="grid lg:grid-cols-3 gap-6">

                {{-- ORDERS --}}
                <div class="lg:col-span-2 bg-white
                            rounded-2xl border shadow-sm">

                    <div class="p-5 border-b
                                flex items-center justify-between">

                        <div>

                            <h3 class="font-bold text-gray-800">
                                Pesanan Terbaru
                            </h3>

                            <p class="text-xs text-gray-400">
                                Pesanan customer terbaru
                            </p>

                        </div>

                        <a href="{{ route('tenant.orders') }}"
                           class="text-sm font-semibold
                                  text-[#006dac]">
                            Lihat Semua →
                        </a>

                    </div>


                    <div class="p-8 text-center">

                        <div class="text-4xl mb-3">
                            🛍️
                        </div>

                        <p class="font-semibold text-gray-700">
                            Belum ada pesanan
                        </p>

                        <p class="text-sm text-gray-400 mt-1">
                            Pesanan customer akan muncul di sini.
                        </p>

                    </div>

                </div>


                {{-- TENANT INFO --}}
                <div class="bg-white rounded-2xl border
                            shadow-sm p-5">

                    <div class="flex items-center
                                justify-between mb-5">

                        <h3 class="font-bold text-gray-800">
                            Informasi Tenant
                        </h3>

                        <a href="{{ route('tenant.profile') }}"
                           class="text-xs font-semibold
                                  text-[#006dac]">
                            Edit
                        </a>

                    </div>


                    <div class="space-y-4 text-sm">

                        <div>
                            <p class="text-xs text-gray-400">
                                Kode Tenant
                            </p>

                            <p class="font-semibold">
                                {{ $tenant?->tenant_code ?? '-' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs text-gray-400">
                                Nama Tenant
                            </p>

                            <p class="font-semibold">
                                {{ $tenant?->name ?? '-' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs text-gray-400">
                                Lokasi
                            </p>

                            <p class="font-semibold">
                                {{ $tenant?->floor_location ?? '-' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs text-gray-400">
                                Nomor Telepon
                            </p>

                            <p class="font-semibold">
                                {{ $tenant?->phone ?? '-' }}
                            </p>
                        </div>


                        <div>
                            <p class="text-xs text-gray-400">
                                Jam Operasional
                            </p>

                            <p class="font-semibold">

                                {{ $tenant?->opening_time
                                    ? substr($tenant->opening_time, 0, 5)
                                    : '--:--' }}

                                -

                                {{ $tenant?->closing_time
                                    ? substr($tenant->closing_time, 0, 5)
                                    : '--:--' }}

                            </p>
                        </div>

                    </div>


                    <div class="grid grid-cols-2 gap-2 mt-6">

                        <a href="{{ route('tenant.products') }}"
                           class="block text-center
                                  bg-[#006dac]
                                  hover:bg-[#005485]
                                  text-white py-3 rounded-xl
                                  text-xs font-semibold">
                            Kelola Produk
                        </a>

                        <a href="{{ route('tenant.profile') }}"
                           class="block text-center
                                  bg-gray-100
                                  hover:bg-gray-200
                                  text-gray-700 py-3 rounded-xl
                                  text-xs font-semibold">
                            Profil Tenant
                        </a>

                    </div>

                </div>

            </section>

        </div>

    </main>

</div>

</body>
</html>
