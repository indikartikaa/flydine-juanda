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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="text-slate-800 antialiased bg-slate-100 min-h-screen flex flex-col items-center">

    <!-- Navbar / Header (App-like) -->
    <header class="bg-white/90 backdrop-blur-md sticky top-0 z-50 w-full border-b border-slate-200/50 shadow-sm">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ url('/dashboard') }}" class="p-2 -ml-2 rounded-full hover:bg-slate-100 transition-colors text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <div class="ml-2">
                    <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">Pengaturan Akun</h1>
                </div>
            </div>
            
            <div class="h-8 w-8 bg-gradient-to-tr from-[#005ea2] to-blue-600 rounded-full flex items-center justify-center text-white font-extrabold text-xs shadow-md shadow-blue-600/20">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="w-full max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8 pb-32">
        
        <!-- Profile Update Form Container -->
        <div class="bg-white p-6 sm:p-8 rounded-[2rem] shadow-sm border border-slate-100">
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- Password Update Form Container -->
        <div class="bg-white p-6 sm:p-8 rounded-[2rem] shadow-sm border border-slate-100">
            @include('profile.partials.update-password-form')
        </div>

        <!-- Delete User Form Container -->
        <div class="bg-rose-50/50 p-6 sm:p-8 rounded-[2rem] shadow-sm border border-rose-100/50">
            @include('profile.partials.delete-user-form')
        </div>

    </main>

</body>
</html>
