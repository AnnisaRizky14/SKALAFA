<!-- resources/views/survey/dashboard.blade.php -->

@extends('layouts.app')

@section('content')
<div class="min-h-screen gradient-bg">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('unib-logo.png') }}" alt="Logo UNIB" class="h-20 w-auto">
                    <div>
                        <h1 class="text-2xl font-bold text-primary">SKALAFA</h1>
                        <p class="text-sm text-gray-600">Survei Kepuasan Layanan Fakultas</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600 font-medium">Universitas Bengkulu</p>
                    <p class="text-xs text-gray-500">
                        {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }}
                    </p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12">
        <!-- Welcome Section -->
        <div class="text-center text-white mb-16">
            <h2 class="text-4xl font-bold mb-4">Selamat Datang di SKALAFA</h2>
            <p class="text-xl mb-8 opacity-90">Sistem Survei Kepuasan Layanan Fakultas Universitas Bengkulu</p>
            <p class="text-lg opacity-80 max-w-3xl mx-auto mb-8">
                Berpartisipasilah dalam survei kepuasan untuk membantu meningkatkan kualitas layanan fakultas. 
                Masukan Anda sangat berharga bagi pengembangan pendidikan di Universitas Bengkulu.
            </p>
            <a href="{{ route('survey.faculties') }}" class="inline-flex items-center px-8 py-4 bg-white text-primary font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Mulai Kuisioner
            </a>
        </div>

        <!-- Faculty Cards Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-12">
            <h3 class="text-3xl font-bold text-center text-primary mb-12">Fakultas di Universitas Bengkulu</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($faculties as $faculty)
                <div class="bg-white border-2 border-gray-100 rounded-xl p-6 card-hover cursor-pointer" onclick="window.location.href='{{ route('survey.questionnaires', $faculty) }}'">
                    <div class="text-center">
                        <!-- Faculty Icon/Logo -->
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center text-white text-1xl font-bold" style="background-color: {{ $faculty->color }}">
                            {{ $faculty->short_name }}
                        </div>
                        
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $faculty->name }}</h4>
                        <p class="text-sm text-gray-600 mb-4">{{ $faculty->description }}</p>
                        
                        <!-- Statistics -->
                        @php $stats = $faculty->getSatisfactionStats() @endphp
                        <div class="flex justify-between text-xs text-gray-500 border-t pt-3">
                            <span>{{ $stats['total_responses'] }} Responden</span>
                            @if($stats['average_rating'] > 0)
                                <span class="flex items-center">
                                    <svg class="w-3 h-3 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    {{ $stats['average_rating'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Call to Action -->
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center mb-12">
            <h3 class="text-2xl font-bold text-primary mb-4">Siap untuk Berpartisipasi?</h3>
            <p class="text-gray-600 mb-6">Survei hanya membutuhkan waktu 5-10 menit. Mari bersama meningkatkan kualitas pendidikan!</p>
            <a href="{{ route('survey.faculties') }}" class="inline-flex items-center px-8 py-4 btn-primary rounded-lg font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
                Pilih Fakultas
            </a>
        </div>

        <!-- Features Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white rounded-xl p-8 text-center shadow-lg">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h4 class="text-xl font-semibold text-gray-800 mb-2">Mudah & Cepat</h4>
                <p class="text-gray-600">Proses survei yang simple dan tidak memakan waktu lama</p>
            </div>

            <div class="bg-white rounded-xl p-8 text-center shadow-lg">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h4 class="text-xl font-semibold text-gray-800 mb-2">Aman & Anonim</h4>
                <p class="text-gray-600">Data Anda dijamin aman dan identitas tetap anonim</p>
            </div>

            <div class="bg-white rounded-xl p-8 text-center shadow-lg">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h4 class="text-xl font-semibold text-gray-800 mb-2">Berdampak</h4>
                <p class="text-gray-600">Masukan Anda membantu meningkatkan kualitas layanan</p>
            </div>
        </div>

        <!-- Complaints Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            <h3 class="text-2xl font-bold text-primary mb-4">Ada Masalah atau Keluhan?</h3>
            <p class="text-gray-600 mb-6">Kirimkan pengaduan atau saran Anda untuk membantu kami meningkatkan kualitas layanan.</p>
            <a href="{{ route('complaints.index') }}" class="inline-flex items-center px-8 py-4 bg-red-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Kirim Pengaduan
            </a>
        </div>
    </main>

    <!-- Footer -->
<footer class="text-white py-12 border-t-4 border-primary-600" style="background-color: #003f7f;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-start gap-8 flex-wrap">

            <!-- Brand -->
            <div class="text-left">
                <div class="flex items-start mb-4">
                    <img class="h-14 w-auto" src="{{ asset('unib-logo.png') }}" alt="UNIB Logo">
                    <div class="ml-3">
                        <div class="text-white font-bold text-lg">SKALAFA</div>
                        <div class="text-unib-blue-200 text-sm">Universitas Bengkulu</div>
                    </div>
                </div>
                <p class="text-unib-blue-200">
                    Sistem Informasi Survei Kepuasan Layanan Fakultas Universitas Bengkulu.
                </p>
            </div>

            <!-- Kontak -->
            <div class="w-full sm:w-auto text-left sm:text-right ml-auto">
                <h3 class="text-left sm:text-right text-lg font-semibold mb-5">Kontak</h3>
                <div class="space-y-2 text-unib-blue-200 text-left sm:text-right">
                    <p><i class="fas fa-map-marker-alt mr-1"></i> Jl. WR Supratman, Bengkulu</p>
                    <p><i class="fas fa-phone mr-1"></i> (0736) 344087</p>
                    <p><i class="fas fa-envelope mr-1"></i> sisteminformasi@unib.ac.id</p>
                </div>
            </div>

        </div>

        <!-- Bottom text -->
        <div class="border-t border-unib-blue-700 mt-8 pt-8 text-center text-unib-blue-200">
            <p>&copy; {{ date('Y') }} Prodi Sistem Informasi Fakultas Teknik Universitas Bengkulu. All rights reserved.</p>
        </div>
    </div>
</footer>

@push('scripts')
<script>
    // Add smooth scrolling animation when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Animate faculty cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all cards
        document.querySelectorAll('.card-hover').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    });
</script>
@endpush
@endsection