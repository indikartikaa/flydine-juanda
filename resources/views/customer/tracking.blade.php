<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelacakan Pesanan - FlyDine Juanda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }</style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    <!-- Header Pelacakan -->
    <header class="bg-white border-b-4 border-[#8dc63f] shadow-sm sticky top-0 z-50 px-6 py-4 text-center">
        <h1 class="text-xl font-black text-[#005ea2] tracking-wider">FlyDine <span class="text-xs font-semibold text-gray-400 block tracking-normal">Juanda International Airport</span></h1>
    </header>

    <!-- Main Container -->
    <main class="container mx-auto px-4 py-10 max-w-md flex-grow flex flex-col justify-center">
        
        <!-- Header Status (Animasi Pulsa pada Status Aktif) -->
        <div class="text-center mb-10 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#005ea2] to-[#8dc63f]"></div>
            
            <span class="inline-block text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">ID Pesanan: #ORD-98765</span>
            <h2 class="text-3xl font-black text-[#8dc63f] tracking-tight mb-3 flex items-center justify-center gap-2">
                <span class="w-3 h-3 rounded-full bg-[#8dc63f] animate-ping"></span>
                Sedang Dimasak
            </h2>
            <p class="text-sm text-gray-500 font-medium leading-relaxed px-2">
                Mohon tunggu di <span class="font-bold text-gray-800">Gate 8</span>, pesanan Anda sedang disiapkan dengan higienis oleh <span class="font-bold text-[#005ea2]">A&W</span>.
            </p>
        </div>

        <!-- Progress Bar Status Modern -->
        <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100 mb-6">
            <div class="relative mb-6 px-2">
                <!-- Bar Latar Belakang -->
                <div class="overflow-hidden h-2.5 mb-3 text-xs flex rounded-full bg-gray-100">
                    <div style="width: 50%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-[#005ea2] to-[#8dc63f] rounded-full transition-all duration-500"></div>
                </div>
                <!-- Label Progress -->
                <div class="flex justify-between text-xs font-extrabold tracking-wider">
                    <span class="text-[#005ea2]">Diterima</span>
                    <span class="text-[#8dc63f]">Dimasak</span>
                    <span class="text-gray-300">Siap Diambil</span>
                </div>
            </div>
        </div>

        <!-- Info Card Detail Pengambilan -->
        <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100 space-y-4 mb-8">
            <div class="flex items-center space-x-4 border-b border-gray-100 pb-4">
                <div class="bg-blue-50 p-3 rounded-2xl text-[#005ea2] shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">Waktu Boarding Pesawat</p>
                    <p class="font-extrabold text-gray-800 text-base">14:45 WIB</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4 pt-1">
                <div class="bg-green-50 p-3 rounded-2xl text-green-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <div>
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">Lokasi Counter Tenant</p>
                    <p class="font-extrabold text-gray-800 text-base">A&W - T1 Boarding Lounge (FB-01-01)</p>
                </div>
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="text-center">
             <a href="/" class="inline-flex items-center justify-center w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3.5 rounded-xl text-sm uppercase tracking-wider transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Katalog Utama
             </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center text-gray-400 text-xs mt-auto">
        <p>FlyDine System MVP v1.0 • Juanda International Airport</p>
    </footer>

</body>
</html>