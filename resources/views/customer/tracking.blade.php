<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelacakan - FlyDine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }</style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50 px-4 py-3 text-center">
        <h1 class="text-xl font-extrabold text-[#005ea2]">FlyDine</h1>
    </header>

    <main class="container mx-auto px-4 py-8 max-w-md flex-grow">
        
        <!-- Header Status -->
        <div class="text-center mb-8">
            <p class="text-xs text-gray-500 font-semibold mb-1">ID Pesanan: #ORD-98765</p>
            <h2 class="text-2xl font-bold text-[#8dc63f]">Sedang Dimasak</h2>
            <p class="text-sm text-gray-600 mt-2">Mohon tunggu di Gate 8, pesanan Anda sedang disiapkan oleh A&W.</p>
        </div>

        <!-- Progress Bar Status -->
        <div class="relative mb-10 px-4">
            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                <div style="width: 50%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-[#8dc63f]"></div>
            </div>
            <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                <span class="text-[#8dc63f]">Diterima</span>
                <span class="text-[#8dc63f]">Dimasak</span>
                <span>Siap Diambil</span>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center space-x-3 border-b border-gray-100 pb-4 mb-4">
                <div class="bg-blue-100 p-2 rounded-full text-[#005ea2]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Waktu Boarding</p>
                    <p class="font-bold text-gray-800">14:45 WIB</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3 pb-2">
                <div class="bg-green-100 p-2 rounded-full text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Lokasi Pengambilan</p>
                    <p class="font-bold text-gray-800 text-sm">A&W - FB-01-01</p>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center">
             <a href="/" class="text-sm font-semibold text-[#005ea2] hover:underline">Kembali ke Katalog</a>
        </div>
    </main>

</body>
</html>