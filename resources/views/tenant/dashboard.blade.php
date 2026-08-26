<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Tenant Dashboard - FlyDine</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
}
</style>

</head>


<body class="bg-gray-100">


<!-- HEADER -->

<header class="bg-[#005ea2] text-white px-6 py-4 flex justify-between items-center">

<div>
<h1 class="font-bold text-xl">
FlyDine Tenant
</h1>

<p class="text-xs text-blue-100">
Merchant Dashboard
</p>

</div>


<div class="text-right">

<p class="font-semibold">
A&W
</p>

<p class="text-xs">
Terminal 1 - Boarding Lounge
</p>

</div>


</header>



<main class="container mx-auto px-6 py-8">


<h2 class="text-2xl font-bold text-gray-800 mb-6">
Dashboard
</h2>



<!-- CARD -->

<div class="grid grid-cols-1 md:grid-cols-4 gap-5">


<div class="bg-white rounded-xl shadow p-5">

<p class="text-gray-500 text-sm">
Total Menu
</p>

<h3 class="text-3xl font-bold text-[#005ea2]">
12
</h3>

</div>



<div class="bg-white rounded-xl shadow p-5">

<p class="text-gray-500 text-sm">
Pesanan Baru
</p>

<h3 class="text-3xl font-bold text-yellow-500">
5
</h3>

</div>



<div class="bg-white rounded-xl shadow p-5">

<p class="text-gray-500 text-sm">
Sedang Diproses
</p>

<h3 class="text-3xl font-bold text-blue-500">
8
</h3>

</div>



<div class="bg-white rounded-xl shadow p-5">

<p class="text-gray-500 text-sm">
Selesai Hari Ini
</p>

<h3 class="text-3xl font-bold text-green-500">
20
</h3>

</div>


</div>




<!-- MENU AKSI -->

<div class="mt-8 grid md:grid-cols-2 gap-6">


<a href="#"
class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">


<h3 class="font-bold text-lg text-[#005ea2]">
Kelola Produk
</h3>

<p class="text-sm text-gray-500 mt-2">
Tambah, edit, dan hapus menu makanan
</p>


</a>



<a href="#"
class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">


<h3 class="font-bold text-lg text-[#005ea2]">
Pesanan Masuk
</h3>

<p class="text-sm text-gray-500 mt-2">
Kelola status pesanan customer
</p>


</a>


</div>


</main>


</body>
</html>
