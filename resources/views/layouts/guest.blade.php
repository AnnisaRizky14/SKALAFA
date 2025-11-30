<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Left Column (Image/Illustration) -->
            <div class="relative bg-white overflow-hidden">
      <!-- Logo kiri-atas -->
      <div class="absolute top-8 left-8 z-10 flex items-center space-x-4">
        <img src="{{ asset('unib-logo.png') }}" 
            alt="UNIB" 
            class="w-16 h-16 object-contain"> <!-- ukuran logo diperbesar -->
        <div class="leading-tight">
            <p class="text-base font-semibold text-[#0a3167] tracking-wide uppercase">UNIVERSITAS</p>
            <p class="text-base font-semibold text-[#0a3167] tracking-wide uppercase">BENGKULU</p>
        </div>
    </div>

      <!-- Dekorasi gelembung ringan -->
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -left-6 top-20 w-28 h-28 bg-slate-200/50 rounded-full"></div>
        <div class="absolute left-16 bottom-24 w-24 h-24 bg-slate-200/50 rounded-full"></div>
        <div class="absolute right-24 top-40 w-16 h-16 bg-slate-200/50 rounded-full"></div>
        <div class="absolute right-16 bottom-16 w-20 h-20 bg-slate-200/50 rounded-full"></div>
      </div>

      <!-- Ilustrasi: benar-benar di tengah -->
      <div class="min-h-screen flex items-center justify-center px-6">
        <img
          src="{{ asset('ilus-unib.svg') }}"
          alt="Gedung UNIB"
          class="w-[200%] max-w-[2300px] min-w-[1220px] object-contain select-none"
        >
      </div>
    </div>

        <!-- Right Column (Primary background + Login Card) -->
        <div class="w-full lg:w-1/2 bg-primary-600 flex flex-col justify-center items-center p-6 md:p-12 text-white relative">
            <!-- Decorative bubbles (opsional) -->
            <div class="pointer-events-none absolute inset-0 opacity-20">
                <div class="absolute w-24 h-24 bg-white rounded-full -top-6 -right-6"></div>
                <div class="absolute w-16 h-16 bg-white rounded-full bottom-10 right-12"></div>
                <div class="absolute w-20 h-20 bg-white rounded-full top-20 left-10"></div>
            </div>

            <!-- Login Card -->
            <div class="w-full max-w-md relative z-10">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
