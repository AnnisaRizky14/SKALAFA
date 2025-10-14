@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 overflow-hidden flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-center">
                <img src="{{ asset('storage/unib-logo.png') }}" alt="Logo UNIB" class="h-10 w-10 mr-3">
                <div class="text-center">
                    <h1 class="text-xl font-bold text-primary">SKALAFA</h1>
                    <p class="text-sm text-gray-600">Survei Kepuasan Layanan Fakultas</p>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-16 flex-grow overflow-auto">
        <div class="max-w-2xl mx-auto text-center">
            <!-- Success Animation -->
            <div class="mb-8">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <!-- Confetti Animation -->
                <div class="confetti-container">
                    <div class="confetti"></div>
                    <div class="confetti"></div>
                    <div class="confetti"></div>
                    <div class="confetti"></div>
                    <div class="confetti"></div>
                    <div class="confetti"></div>
                    <div class="confetti"></div>
                    <div class="confetti"></div>
                    <div class="confetti"></div>
                    <div class="confetti"></div>
                    <div class="confetti"></div>
                    <div class="confetti"></div>
                    <div class="confetti"></div>
                    <div class="confetti"></div>
                    <div class="confetti"></div>
                </div>
            </div>

            <!-- Thank You Message -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Terima Kasih atas Pengaduannya!</h2>
                <p class="text-xl text-gray-600 mb-6">
                    Pengaduan Anda telah berhasil dikirim dan akan kami tindaklanjuti segera.
                </p>

                <!-- Status Info -->
                <div class="bg-green-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-lg font-semibold text-gray-800">Pengaduan Berhasil Dikirim</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('survey.dashboard') }}"
                       class="inline-flex items-center px-8 py-3 bg-primary text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>

                <p class="text-sm text-gray-500">
                    Ingin memberikan pengaduan lainnya?
                    <a href="{{ route('complaints.index') }}" class="text-primary hover:underline font-medium">
                        Kirim Pengaduan Lainnya
                    </a>
                </p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white py-8 mt-16">
        <div class="container mx-auto px-4 text-center">
            <div class="flex items-center justify-center mb-4">
                <img src="{{ asset('storage/unib-logo.png') }}" alt="Logo UNIB" class="h-8 w-8 mr-3">
                <span class="text-lg font-semibold">Universitas Bengkulu</span>
            </div>
            <p class="text-sm opacity-75 mb-2">
                Terima kasih telah memberikan masukan untuk SKALAFA
            </p>
            <p class="text-xs opacity-50">
                © {{ date('Y') }} Universitas Bengkulu. Semua hak dilindungi.
            </p>
        </div>
    </footer>
</div>

<style>
.confetti-container {
    position: relative;
    width: 100%;
    height: 120px;
    overflow: hidden;
}

.confetti {
    position: absolute;
    width: 8px;
    height: 8px;
    background-color: #ff6b6b;
    animation: fallRandom 4s linear infinite;
    opacity: 0.8;
}

.confetti:nth-child(1) {
    left: 15%;
    animation-delay: 0s;
    background-color: #ff6b6b;
}

.confetti:nth-child(2) {
    left: 25%;
    animation-delay: 0.3s;
    background-color: #4ecdc4;
}

.confetti:nth-child(3) {
    left: 35%;
    animation-delay: 0.6s;
    background-color: #45b7d1;
}

.confetti:nth-child(4) {
    left: 45%;
    animation-delay: 0.9s;
    background-color: #f9ca24;
}

.confetti:nth-child(5) {
    left: 55%;
    animation-delay: 1.2s;
    background-color: #f0932b;
}

.confetti:nth-child(6) {
    left: 65%;
    animation-delay: 1.5s;
    background-color: #eb4d4b;
}

.confetti:nth-child(7) {
    left: 75%;
    animation-delay: 1.8s;
    background-color: #6c5ce7;
}

.confetti:nth-child(8) {
    left: 20%;
    animation-delay: 2.1s;
    background-color: #fd79a8;
}

.confetti:nth-child(9) {
    left: 30%;
    animation-delay: 2.4s;
    background-color: #00b894;
}

.confetti:nth-child(10) {
    left: 40%;
    animation-delay: 2.7s;
    background-color: #e17055;
}

.confetti:nth-child(11) {
    left: 50%;
    animation-delay: 3.0s;
    background-color: #a29bfe;
}

.confetti:nth-child(12) {
    left: 60%;
    animation-delay: 3.3s;
    background-color: #fdcb6e;
}

.confetti:nth-child(13) {
    left: 70%;
    animation-delay: 3.6s;
    background-color: #e84393;
}

.confetti:nth-child(14) {
    left: 80%;
    animation-delay: 3.9s;
    background-color: #00cec9;
}

.confetti:nth-child(15) {
    left: 85%;
    animation-delay: 4.2s;
    background-color: #d63031;
}

@keyframes fallRandom {
    0% {
        top: -10px;
        transform: rotate(0deg) translateX(0px);
        opacity: 1;
    }
    10% {
        transform: rotate(36deg) translateX(5px);
    }
    20% {
        transform: rotate(72deg) translateX(-3px);
    }
    30% {
        transform: rotate(108deg) translateX(7px);
    }
    40% {
        transform: rotate(144deg) translateX(-2px);
    }
    50% {
        transform: rotate(180deg) translateX(4px);
    }
    60% {
        transform: rotate(216deg) translateX(-6px);
    }
    70% {
        transform: rotate(252deg) translateX(3px);
    }
    80% {
        transform: rotate(288deg) translateX(-1px);
    }
    90% {
        transform: rotate(324deg) translateX(2px);
        opacity: 0.8;
    }
    100% {
        top: 120px;
        transform: rotate(360deg) translateX(0px);
        opacity: 0;
    }
}
</style>
@endsection
