<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SKALAFA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
    <div class="min-h-screen flex">
        <!-- Left: Branding & Illustration -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-indigo-800 via-indigo-900 to-blue-950 flex-col justify-start p-16">
            <div class="mb-12">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('unib-logo.png'))) }}" alt="UNIB" class="w-40 object-contain mb-12">
                <h1 class="text-5xl font-bold text-white mb-6">Selamat Datang di SKALAFA</h1>
                <p class="text-indigo-100 text-xl leading-relaxed">Kelola kuisioner dengan mudah, lihat respon real-time, dan analisis hasil secara mendalam bersama UNIB.</p>
            </div>
            
            <div class="flex justify-center items-center">
                <div class="w-full" style="max-height: 6000px; overflow: hidden;">
                    {!! file_get_contents(public_path('ilus-unib.svg')) !!}
                </div>
            </div>
        </div>

        <!-- Right: Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-8 py-16 bg-gray-50">
            <div class="w-full max-w-lg">
                <div class="mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-3">Daftar Akun Baru</h2>
                    <p class="text-gray-600 text-lg">Bergabunglah dengan UNIB SKALAFA sekarang</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-base font-semibold text-gray-800 mb-3">Nama Lengkap</label>
                        <input 
                            id="name" 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            autocomplete="name"
                            class="w-full px-5 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 transition text-base @error('name') border-red-500 @enderror"
                            placeholder="Cth: Budi Santoso"
                        >
                        @error('name')
                            <span class="text-red-600 text-sm mt-2 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-base font-semibold text-gray-800 mb-3">Email</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="username"
                            class="w-full px-5 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 transition text-base @error('email') border-red-500 @enderror"
                            placeholder="Cth: budi@email.com"
                        >
                        @error('email')
                            <span class="text-red-600 text-sm mt-2 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-base font-semibold text-gray-800 mb-3">Password</label>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            class="w-full px-5 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 transition text-base @error('password') border-red-500 @enderror"
                            placeholder="Minimal 8 karakter"
                        >
                        @error('password')
                            <span class="text-red-600 text-sm mt-2 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="block text-base font-semibold text-gray-800 mb-3">Konfirmasi Password</label>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            class="w-full px-5 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 transition text-base @error('password_confirmation') border-red-500 @enderror"
                            placeholder="Ulangi password Anda"
                        >
                        @error('password_confirmation')
                            <span class="text-red-600 text-sm mt-2 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold py-3 px-6 rounded-xl transition duration-300 transform hover:scale-105 text-lg mt-8"
                    >
                        Daftar Sekarang
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-8 text-center">
                    <p class="text-gray-700">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-bold hover:underline">
                            Masuk di sini
                        </a>
                    </p>
                </div>

                <!-- Terms -->
                <div class="mt-8 text-sm text-gray-600 text-center leading-relaxed">
                    Dengan mendaftar, Anda menyetujui 
                    <a href="#" class="text-indigo-600 hover:underline font-medium">kebijakan privasi</a> dan 
                    <a href="#" class="text-indigo-600 hover:underline font-medium">syarat penggunaan</a> UNIB.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
