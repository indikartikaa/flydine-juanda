<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FlyDine - Juanda International Airport</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">


    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }

        .menu-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .menu-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .menu-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>


    <script>
        function changeLanguage(lang) {

            document.querySelectorAll('[data-id]').forEach(function(element) {

                if (lang === 'en') {
                    element.innerHTML = element.getAttribute('data-en');
                } else {
                    element.innerHTML = element.getAttribute('data-id');
                }

            });

        }


        function searchTenant() {

            let input = document
                .getElementById('searchInput')
                .value
                .toLowerCase();


            document.querySelectorAll('.tenant-card')
                .forEach(function(card) {


                    let name = card
                        .getAttribute('data-name')
                        .toLowerCase();


                    if (name.includes(input)) {

                        card.style.display = "";

                    } else {

                        card.style.display = "none";

                    }

                });

        }
    </script>


</head>


<body class="text-gray-800 flex flex-col min-h-screen">



<header class="bg-white border-b-4 border-[#8dc63f] shadow-sm sticky top-0 z-50">


    <div class="flex flex-col md:flex-row">


        <div class="w-full md:w-1/3 py-3 px-6 flex justify-between items-center">


            <div>

                <h1 class="text-xl font-extrabold text-gray-800">
                    Juanda
                </h1>


                <p class="text-xs text-gray-500 font-semibold tracking-wider">
                    International Airport
                </p>


            </div>


            <span class="text-[10px] font-bold bg-[#005ea2] text-white px-3 py-1 rounded">
                FlyDine T1
            </span>


        </div>




        <div class="w-full md:w-2/3 bg-[#005ea2] text-white flex items-center justify-end px-6 py-3 space-x-5">


            <div class="text-sm font-semibold">

                <button onclick="changeLanguage('en')">
                    EN
                </button>

                |

                <button onclick="changeLanguage('id')">
                    ID
                </button>

            </div>



            <span class="hidden md:inline text-sm border-l pl-5">

                <span data-id="PILIH BANDARA"
                    data-en="CHOOSE AIRPORT">

                    CHOOSE AIRPORT

                </span>

            </span>



            <span class="hidden md:inline text-sm">

                <span data-id="KORPORAT"
                    data-en="CORPORATE">

                    CORPORATE

                </span>

            </span>




            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-8 w-8"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />

            </svg>


        </div>


    </div>


</header>





<main class="container mx-auto px-6 py-10 flex-grow">



<section class="mb-10">


<p class="text-[#8dc63f] font-bold text-sm tracking-widest uppercase mb-1"
data-id="DAPATKAN LOKASI TERBAIK"
data-en="GET THE RIGHT LOCATION">

GET THE RIGHT LOCATION

</p>




<h2 class="text-4xl md:text-5xl font-extrabold text-[#005ea2] uppercase leading-none"
data-id="DIREKTORI MAKANAN"
data-en="DINING DIRECTORY">

DINING
<br>
DIRECTORY

</h2>



<div class="w-16 h-1 bg-[#8dc63f] mt-4"></div>



</section>





<section class="flex flex-col md:flex-row gap-4 mb-8">


<div class="w-full md:w-1/4">


<select class="w-full bg-white border border-gray-200 py-3 px-4 text-sm rounded">

<option data-id="Semua Terminal"
data-en="All Terminal">

All Terminal

</option>

<option>

Terminal 1

</option>

</select>


</div>





<div class="w-full md:w-1/4">


<select class="w-full bg-white border border-gray-200 py-3 px-4 text-sm rounded">


<option data-id="Semua Lokasi"
data-en="All Location">

All Location

</option>


<option>

Boarding Lounge

</option>


</select>


</div>





<div class="w-full md:w-2/4 flex">


<input id="searchInput"
onkeyup="searchTenant()"
type="text"
placeholder="Enter Shop Name"
class="flex-1 bg-white border border-gray-200 px-4 py-3 text-sm rounded-l">



<button class="bg-[#76bce4] text-white px-6 rounded-r hover:bg-[#005ea2]">

🔍

</button>


</div>


</section>
</section>


<!-- TERMINAL TAB -->

<div class="flex space-x-6 border-b border-gray-200 mb-8 text-xs font-bold tracking-wider">


    <span class="text-gray-800 border-b-2 border-[#8dc63f] pb-2 flex items-center">

        <span class="text-yellow-500 text-lg mr-2">
            •
        </span>


        <span data-id="TERMINAL DOMESTIK"
              data-en="DOMESTIC TERMINAL">

            DOMESTIC TERMINAL

        </span>


    </span>




    <span class="text-gray-400 pb-2 flex items-center">


        <span class="text-purple-500 text-lg mr-2">
            •
        </span>


        <span data-id="TERMINAL INTERNASIONAL"
              data-en="INTERNATIONAL TERMINAL">

            INTERNATIONAL TERMINAL

        </span>


    </span>


</div>





<!-- TENANT LIST -->

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">



@forelse($tenants as $tenant)



<div class="tenant-card bg-white group shadow-sm hover:shadow-xl transition-shadow duration-300 rounded-lg overflow-hidden"
data-name="{{ $tenant->name }}">



    <!-- IMAGE -->

    <div class="h-48 bg-gray-200 relative overflow-hidden">


        <img src="https://ui-avatars.com/api/?name={{ urlencode($tenant->name) }}&background=f1f5f9&color=005ea2&size=500"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
        alt="{{ $tenant->name }}">





        <!-- STATUS -->

        @php

        $open = false;

        if($tenant->opening_time && $tenant->closing_time){

            $now = now()->format('H:i:s');

            $open = $now >= $tenant->opening_time
                    &&
                    $now <= $tenant->closing_time;

        }

        @endphp




        @if($open)

        <div class="absolute top-3 right-3 bg-white/90 text-green-600 text-[10px] font-bold px-3 py-1 rounded shadow">

            OPEN

        </div>


        @else


        <div class="absolute top-3 right-3 bg-white/90 text-red-500 text-[10px] font-bold px-3 py-1 rounded shadow">

            CLOSED

        </div>


        @endif



    </div>







    <!-- CONTENT -->

    <div class="p-5">



        <p class="text-[10px] text-gray-500 font-semibold tracking-wider uppercase mb-2 flex items-center">


            <span class="text-yellow-500 text-xl mr-2">
                •
            </span>


            T1 - {{ $tenant->floor_location }}


        </p>





        <h3 class="font-bold text-xl text-[#005ea2] uppercase mb-4">

            {{ $tenant->name }}

        </h3>






        <!-- PRODUCT LIST -->

        <div class="space-y-3 mb-6 menu-scroll pr-2"
        style="max-height:120px; overflow-y:auto;">



            @forelse($tenant->products as $product)



            <div class="flex justify-between items-center border-b border-dashed border-gray-200 pb-2">


                <span class="text-sm text-gray-600 font-medium truncate pr-4">

                    {{ $product->name }}

                </span>



                <span class="text-sm font-bold text-gray-800 whitespace-nowrap">


                    Rp {{ number_format($product->price,0,',','.') }}


                </span>



            </div>




            @empty



            <div class="text-xs text-gray-400 italic text-center py-4 bg-gray-50 rounded">


                <span data-id="Katalog menu belum tersedia."
                      data-en="Menu catalog is not available.">

                    Katalog menu belum tersedia.

                </span>


            </div>



            @endforelse




        </div>






        <!-- BUTTON -->

        <a href="{{ route('customer.menu',$tenant->id) }}"
        class="block text-center w-full border-2 border-[#005ea2] text-[#005ea2] hover:bg-[#005ea2] hover:text-white font-bold py-2.5 text-sm uppercase tracking-wide transition-colors rounded">


            <span data-id="Pesan Sekarang"
                  data-en="Order Now">

                Pesan Sekarang

            </span>


        </a>




    </div>



</div>




@empty




<div class="col-span-full text-center py-16 bg-white border border-dashed border-gray-300 rounded">


<svg xmlns="http://www.w3.org/2000/svg"
class="h-12 w-12 text-gray-300 mx-auto mb-3"
fill="none"
viewBox="0 0 24 24"
stroke="currentColor">

<path stroke-linecap="round"
stroke-linejoin="round"
stroke-width="2"
d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />

</svg>



<p class="text-gray-500 font-medium"
data-id="Belum ada data restoran F&B yang aktif."
data-en="No active F&B tenant available.">


Belum ada data restoran F&B yang aktif.


</p>


</div>



@endforelse



</div>


</main>






<footer class="bg-[#003b66] text-white text-center py-6 mt-auto">


<p class="text-xs text-gray-400">

<span data-id="© 2026 PT Angkasa Pura I (Persero) - Bandara Internasional Juanda."
data-en="© 2026 PT Angkasa Pura I (Persero) - Juanda International Airport.">

© 2026 PT Angkasa Pura I (Persero) - Bandara Internasional Juanda.

</span>

</p>



<p class="text-xs text-gray-400 mt-1">

FlyDine System MVP v1.0

</p>


</footer>




</body>
</html>
