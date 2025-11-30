<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SKALAFA - Login</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style> body{font-family:'Inter',sans-serif} </style>
</head>
<body class="bg-white">
  <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

    <!-- KIRI (latar putih, ilustrasi besar di tengah) -->
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
          src="{{ asset('storage/ilus-unib.svg') }}"
          alt="Gedung UNIB"
          class="w-[200%] max-w-[2300px] min-w-[1220px] object-contain select-none"
        >
      </div>
    </div>

<!-- KANAN (panel biru lurus + form) -->
<div class="bg-[#0a3a74] flex items-center justify-center px-6 py-12">
  <div class="w-full max-w-lg text-center">

    <!-- Judul Login -->
    <p class="text-white/80 text-sm font-medium mb-1">Selamat datang di</p>
    <p class="text-white font-medium text-base leading-relaxed mb-12 tracking-wide">
    Sistem Informasi Survei Kepuasan Layanan Fakultas
    </p>
   <h1 class="text-white text-5xl font-extrabold mb-10">Masuk</h1>

    @if (session('status'))
      <div class="mb-4 bg-green-100 text-green-800 text-sm px-4 py-2 rounded-lg">{{ session('status') }}</div>
    @endif
    @if (session('error'))
      <div class="mb-4 bg-red-100 text-red-700 text-sm px-4 py-2 rounded-lg">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5 text-left">
      @csrf

      <!-- Email -->
      <div>
        <label for="email" class="block text-white/90 text-sm mb-2">Alamat Email</label>
        <input
          type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
          class="w-full rounded-xl bg-white text-gray-900 px-4 py-3 shadow-sm outline-none border-0 focus:ring-4 focus:ring-white/30"
          placeholder="you@example.com">
        @error('email') <p class="mt-2 text-sm text-rose-200">{{ $message }}</p> @enderror
      </div>

      <!-- Password + toggle -->
      <div>
        <label for="password" class="block text-white/90 text-sm mb-2">Kata Sandi</label>
        <div class="relative">
          <input
            type="password" id="password" name="password" required autocomplete="current-password"
            class="w-full rounded-xl bg-white text-gray-900 px-4 py-3 pr-12 shadow-sm outline-none border-0 focus:ring-4 focus:ring-white/30"
            placeholder="••••••••">
          <button type="button" onclick="togglePassword()"
            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-700"
            aria-label="Tampilkan/Sembunyikan Password">
            <svg id="eye-open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <svg id="eye-closed" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
            </svg>
          </button>
        </div>
        @error('password') <p class="mt-2 text-sm text-rose-200">{{ $message }}</p> @enderror
      </div>

      <!-- Remember + Forgot -->
      <div class="flex items-center justify-between text-sm">
        <label for="remember" class="inline-flex items-center text-white/90">
          <input id="remember" name="remember" type="checkbox"
                 class="h-4 w-4 rounded border-white/30 text-white focus:ring-white/30"
                 {{ old('remember') ? 'checked' : '' }}>
          <span class="ml-2">Ingat Saya</span>
        </label>
        @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="underline text-white/90 hover:text-white">
            Lupa Kata Sandi?
          </a>
        @endif
      </div>

      <!-- Tombol oranye -->
    <button type="submit"
    class="w-full rounded-full bg-gradient-to-r from-[#c54f35] to-[#b2452d] hover:from-[#b2452d] hover:to-[#963725] 
            active:scale-[.99] text-white font-semibold text-lg py-3 shadow-lg transition-all duration-200">
    Masuk
    </button>
    </form>
  </div>
</div>


  <script>
    function togglePassword(){
      const i=document.getElementById('password');
      const o=document.getElementById('eye-open');
      const c=document.getElementById('eye-closed');
      const isPwd=i.type==='password';
      i.type=isPwd?'text':'password';
      o.classList.toggle('hidden',isPwd);
      c.classList.toggle('hidden',!isPwd);
    }
  </script>
</body>
</html>
