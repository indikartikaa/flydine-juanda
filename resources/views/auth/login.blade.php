<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>FlyDine Portal</title>


<script src="https://cdn.tailwindcss.com"></script>


<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">


<style>

body{
    font-family:'Poppins',sans-serif;
}

</style>


</head>


<body>


<div class="min-h-screen flex bg-gray-100">


<!-- ================= LEFT ================= -->

<div class="hidden lg:flex lg:w-7/12 relative overflow-hidden">


<img

src="{{ asset('images/juanda.jfif') }}"

class="absolute inset-0 w-full h-full object-cover"

/>



<!-- overlay -->

<div class="absolute inset-0 bg-gradient-to-r from-[#003b66]/90 via-[#005ea2]/70 to-transparent"></div>




<div class="relative z-10 flex flex-col justify-center px-20 text-white">



<!-- BRAND -->


<div class="mb-10">


<h1 class="text-6xl font-extrabold tracking-tight">

FlyDine

</h1>


<p class="text-lg text-blue-100">

<span id="brand">

Digital Dining Experience

</span>

</p>


</div>





<h2 id="headline"

class="text-4xl font-bold leading-tight max-w-xl">

Smart Food Ordering
for Juanda International Airport

</h2>





<p id="description"

class="mt-6 max-w-xl text-lg leading-relaxed text-gray-200">

Platform digital multi-tenant yang menghubungkan
penumpang dengan tenant makanan melalui layanan
pre-order yang cepat dan praktis.

</p>







<div class="flex gap-5 mt-10">



<div class="bg-white/20 backdrop-blur-md rounded-2xl px-6 py-4">


<p class="text-xs text-blue-100">

LOCATION

</p>


<p id="location"

class="font-bold">

Terminal 1 Juanda

</p>


</div>




<div class="bg-white/20 backdrop-blur-md rounded-2xl px-6 py-4">


<p class="text-xs text-blue-100">

SYSTEM

</p>


<p id="system"

class="font-bold">

FlyDine MVP

</p>


</div>



</div>





</div>


</div>







<!-- ================= LOGIN ================= -->

<div class="w-full lg:w-5/12 flex items-center justify-center p-8">



<div class="w-full max-w-md">



<div class="bg-white rounded-3xl shadow-2xl overflow-hidden">





<!-- HEADER -->

<div class="bg-gradient-to-br from-[#003b66] to-[#007ac3] p-8 text-white">





<div class="flex justify-between items-center">



<img

src="{{ asset('images/angkasa-pura.jfif') }}"

class="w-28 bg-white p-2 rounded-lg"

/>



<div class="text-sm">


<button onclick="changeLanguage('id')"

class="font-bold hover:text-green-300">

ID

</button>


<span class="mx-2">

|

</span>


<button onclick="changeLanguage('en')"

class="hover:text-green-300">

EN

</button>



</div>


</div>






<h2 id="portal"

class="mt-8 text-3xl font-bold">

Portal Login

</h2>



<p id="subtitle"

class="text-blue-100 mt-2">

Manajemen Admin & Tenant

</p>



</div>







<!-- FORM -->


<div class="p-10">





<div class="text-center mb-8">


<h3 class="text-4xl font-extrabold text-[#005ea2]">

FlyDine

</h3>


<p class="text-gray-400 text-sm">

Merchant & Admin Portal

</p>


</div>








<form method="POST" action="{{ route('login') }}">

@csrf




<div>


<label id="emailLabel"

class="font-semibold text-gray-700">

Email

</label>



<input

type="email"

name="email"

placeholder="email@flydine.com"

required

class="mt-2 w-full rounded-xl border-gray-300 focus:ring-[#005ea2] focus:border-[#005ea2]"

>


</div>






<div class="mt-5">


<label id="passwordLabel"

class="font-semibold text-gray-700">

Kata Sandi

</label>



<input

type="password"

name="password"

placeholder="********"

required

class="mt-2 w-full rounded-xl border-gray-300 focus:ring-[#005ea2] focus:border-[#005ea2]"

>


</div>







<div class="mt-5">


<label class="flex items-center">


<input

type="checkbox"

name="remember"

class="rounded text-[#005ea2]"



>


<span id="remember"

class="ml-2 text-sm text-gray-600">

Ingat saya

</span>


</label>


</div>







<button

class="mt-8 w-full bg-[#005ea2] hover:bg-[#003b66] text-white font-bold py-3 rounded-xl shadow-lg transition"


>


<span id="loginButton">

MASUK

</span>


</button>





</form>




</div>







<div class="bg-gray-50 text-center py-5">


<p class="text-xs text-gray-400">

© 2026 FlyDine System

</p>


<p class="text-xs text-gray-400">

PT Angkasa Pura - Juanda International Airport

</p>



</div>





</div>


</div>


</div>










<script>


function changeLanguage(lang){



if(lang==="id"){


document.getElementById("headline").innerHTML =
"Smart Food Ordering untuk Bandara Internasional Juanda";


document.getElementById("description").innerHTML =
"Platform digital multi-tenant yang menghubungkan penumpang dengan tenant makanan melalui layanan pre-order yang cepat dan praktis.";


document.getElementById("portal").innerHTML =
"Portal Login";


document.getElementById("subtitle").innerHTML =
"Manajemen Admin & Tenant";


document.getElementById("passwordLabel").innerHTML =
"Kata Sandi";


document.getElementById("remember").innerHTML =
"Ingat saya";


document.getElementById("loginButton").innerHTML =
"MASUK";


}



else{


document.getElementById("headline").innerHTML =
"Smart Food Ordering for Juanda International Airport";


document.getElementById("description").innerHTML =
"Digital multi-tenant platform connecting passengers with airport food tenants through fast and practical pre-order services.";


document.getElementById("portal").innerHTML =
"Login Portal";


document.getElementById("subtitle").innerHTML =
"Admin & Tenant Management";


document.getElementById("passwordLabel").innerHTML =
"Password";


document.getElementById("remember").innerHTML =
"Remember me";


document.getElementById("loginButton").innerHTML =
"LOGIN";


}


}


</script>



</body>

</html>
