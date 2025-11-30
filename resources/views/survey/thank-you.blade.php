@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-purple-50 overflow-hidden flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-center">
                <img src="{{ asset('unib-logo.png') }}" alt="Logo UNIB" class="h-10 w-10 mr-3">
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
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Terima Kasih!</h2>
                <p class="text-xl text-gray-600 mb-6">
                    Survei Anda telah berhasil dikirim dan akan sangat membantu kami dalam meningkatkan kualitas layanan.
                </p>

                <!-- Summary Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600">Waktu Pengisian</p>
                        <p class="text-lg font-semibold text-gray-800">~5 Menit</p>
                    </div>

                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600">Status</p>
                        <p class="text-lg font-semibold text-gray-800">Berhasil</p>
                    </div>

                    <div class="bg-purple-50 rounded-lg p-4">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600">Keamanan</p>
                        <p class="text-lg font-semibold text-gray-800">Terjamin</p>
                    </div>
                </div>

                <!-- Impact Message -->
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-6 mb-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Dampak Kontribusi Anda</h3>
                    <p class="text-gray-600 mb-4">
                        Masukan yang Anda berikan akan membantu universitas untuk:
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Meningkatkan kualitas layanan
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Mengidentifikasi area perbaikan
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Meningkatkan fasilitas kampus
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Mengembangkan kurikulum
                        </div>
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

                    <button onclick="shareExperience()"
                            class="inline-flex items-center px-6 py-3 bg-secondary text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        Bagikan
                    </button>
                </div>

                <p class="text-sm text-gray-500">
                    Ingin mengisi survei untuk fakultas lain?
                    <a href="{{ route('survey.faculties') }}" class="text-primary hover:underline font-medium">
                        Pilih fakultas lainnya
                    </a>
                </p>
            </div>
        </div>

        <!-- Additional Info Section -->
        <div class="max-w-4xl mx-auto mt-16">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-center text-gray-800 mb-8">Tentang SKALAFA</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Analisis Mendalam</h4>
                        <p class="text-gray-600 text-sm">
                            Setiap respons dianalisis secara komprehensif untuk menghasilkan insight yang berharga
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Tindakan Cepat</h4>
                        <p class="text-gray-600 text-sm">
                            Hasil survei langsung ditindaklanjuti untuk perbaikan layanan yang berkelanjutan
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.196-2.121M7 20v-2c0-.656.126-1.283.356-1.857M24 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Partisipasi Aktif</h4>
                        <p class="text-gray-600 text-sm">
                            Mahasiswa berperan aktif dalam proses peningkatan kualitas pendidikan di UNIB
                        </p>
                    </div>
                </div>
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
                Terima kasih telah berpartisipasi dalam SKALAFA
            </p>
            <p class="text-xs opacity-50">
                © {{ date('Y') }} Universitas Bengkulu. Semua hak dilindungi.
            </p>
        </div>
    </footer>
</div>

<!-- Share Modal -->
<div id="share-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 mx-4">
        <!-- Header -->
        <div class="text-center mb-6">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Bagikan SKALAFA</h3>
            <p class="text-gray-600">Ajak teman-teman lain untuk berpartisipasi</p>
        </div>

        <!-- Buttons -->
        <div class="space-y-3 mb-6">
            <!-- WhatsApp -->
            <button onclick="shareToWhatsApp()"
                    class="w-full flex items-center justify-center px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12.004 0C5.373 0 0 5.373 0 12c0 2.117.553 4.09 1.608
                             5.877L.057 24l6.316-1.657A11.93 11.93 0 0 0
                             12.004 24c6.627 0 12-5.373 12-12S18.631 0
                             12.004 0zm0 21.81c-1.925 0-3.796-.507-5.438-1.465l-.39-.229-3.75
                             .982.998-3.64-.256-.374A9.75 9.75 0 0 1
                             2.25 12c0-5.386 4.368-9.75 9.754-9.75
                             2.606 0 5.058 1.014 6.9 2.857a9.72 9.72 0 0 1
                             2.85 6.893c0 5.386-4.368 9.75-9.75 9.75zm5.326-7.306c-.291-.145-1.725-.852-1.993-.95-.268-.099-.463-.145-.657.145s-.752.95-.922
                             1.145c-.17.198-.34.223-.63.074-.291-.145-1.229-.452-2.338-1.44-.864-.768-1.448-1.716-1.618-2.01-.17-.294-.018-.453.128-.598.132-.132.294-.34.442-.51.145-.17.193-.291.291-.485.099-.198.05-.371-.025-.516-.074-.145-.657-1.587-.9-2.178-.237-.57-.478-.494-.657-.502l-.562-.01c-.198
                             0-.517.074-.79.371s-1.037 1.012-1.037 2.465 1.062 2.86
                             1.21 3.061c.149.198 2.091 3.188 5.068 4.472.709.306
                             1.262.489 1.693.626.712.226 1.36.194 1.871.118.571-.085
                             1.725-.705 1.967-1.387.242-.683.242-1.267.17-1.387-.074-.124-.268-.198-.56-.344z"/>
                </svg>
                WhatsApp
            </button>

            <!-- Copy Link -->
            <button onclick="copyLink()"
                    class="w-full flex items-center justify-center px-4 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10.59 13.41a1.996 1.996 0 0 1 0-2.82l2-2a1.996
                             1.996 0 0 1 2.82 2.82l-2 2a1.996 1.996 0
                             0 1-2.82 0z"/>
                    <path d="M4 12c0-1.1.9-2 2-2h3v2H6v6h6v-3h2v3c0
                             1.1-.9 2-2 2H6c-1.1 0-2-.9-2-2v-6zm14-8h-6c-1.1
                             0-2 .9-2 2v3h2V6h6v6h-3v2h3c1.1 0 2-.9
                             2-2V6c0-1.1-.9-2-2-2z"/>
                </svg>
                Salin Link
            </button>
        </div>

        <!-- Close -->
        <button onclick="closeShareModal()"
                class="w-full text-gray-600 hover:text-gray-800 font-medium">
            Tutup
        </button>
    </div>
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

<script>
function shareExperience() {
    document.getElementById('share-modal').classList.remove('hidden');
}

function shareToWhatsApp() {
    const url = "{{ route('survey.dashboard') }}";
    const text = encodeURIComponent("Yuk ikutan Survei Kepuasan Layanan Fakultas (SKALAFA)! " + url);
    window.open("https://wa.me/?text=" + text, "_blank");
}

function copyLink() {
    const url = "{{ route('survey.dashboard') }}";
    navigator.clipboard.writeText(url).then(() => {
        alert("Link berhasil disalin!");
    });
}

function closeShareModal() {
    document.getElementById('share-modal').classList.add('hidden');
}
</script>
@endsection
