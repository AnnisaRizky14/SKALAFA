@extends('layouts.app')

@section('content')
<div class="min-h-screen gradient-bg">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('storage/unib-logo.png') }}" alt="Logo UNIB" class="h-20 w-auto">
                    <div>
                        <h1 class="text-2xl font-bold text-primary">SKALAFA</h1>
                        <p class="text-sm text-gray-600">Survei Kepuasan Layanan Fakultas</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Universitas Bengkulu</p>
                    <p class="text-xs text-gray-500">{{ date('d F Y') }}</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-primary mb-4">Pengaduan</h2>
                    <p class="text-gray-600">Kirimkan pengaduan atau saran Anda untuk membantu kami meningkatkan layanan.</p>
                </div>

                <div class="text-center">
                    <a href="{{ route('complaints.create') }}" class="inline-flex items-center px-8 py-4 bg-primary text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Kirim Pengaduan Baru
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
