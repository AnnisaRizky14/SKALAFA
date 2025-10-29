@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navigation Header -->
    <header class="bg-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('survey.questionnaires', $questionnaire->faculty) }}" class="text-gray-600 hover:text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <img src="{{ asset('storage/unib-logo.png') }}" alt="Logo UNIB" class="h-10 w-10">
                    <div>
                        <h1 class="text-xl font-bold text-primary">Informasi Peserta</h1>
                        <p class="text-sm text-gray-600">{{ $questionnaire->title }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Langkah 3 dari 3</p>
                    <div class="w-32 progress-bar mt-2">
                        <div class="progress-fill" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12">
        <!-- Page Title -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Informasi Peserta</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Silakan lengkapi informasi Anda sebelum memulai kuisioner <strong>{{ $questionnaire->title }}</strong>
            </p>
        </div>

        <!-- Form -->
        <div class="max-w-2xl mx-auto">
            <form action="{{ route('survey.save-participant-info', $questionnaire) }}" method="POST" class="bg-white rounded-xl shadow-lg p-8">
                @csrf

                <!-- Email Field -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-gray-500">(Opsional)</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                           placeholder="contoh@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Field -->
                <div class="mb-6">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-gray-500">(Opsional)</span>
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                           placeholder="Masukkan nama lengkap Anda">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Field -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-gray-500">(Opsional)</span>
                    </label>
                    <select id="status" name="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors">
                        <option value="">Pilih status Anda</option>
                        <option value="Mahasiswa" {{ old('status') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="Dosen" {{ old('status') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="Staff" {{ old('status') == 'Staff' ? 'selected' : '' }}>Staff</option>
                        <option value="Alumni" {{ old('status') == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                        <option value="Masyarakat Umum" {{ old('status') == 'Masyarakat Umum' ? 'selected' : '' }}>Masyarakat Umum</option>
                        <option value="Lainnya" {{ old('status') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fakultas Field -->
                <div class="mb-8">
                    <label for="fakultas" class="block text-sm font-medium text-gray-700 mb-2">
                        Fakultas <span class="text-red-500">*</span>
                    </label>
                    <select id="fakultas" name="fakultas" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-colors">
                        <option value="">Pilih fakultas Anda</option>
                        @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ old('fakultas') == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('fakultas')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit"
                            class="inline-flex items-center px-8 py-4 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors duration-300 text-lg">
                        <span>Mulai Kuisioner</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Privacy Notice -->
        <div class="max-w-2xl mx-auto mt-8">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-800 mb-2">Informasi Privasi</h4>
                        <p class="text-sm text-blue-700">
                            Informasi yang Anda berikan akan digunakan untuk keperluan analisis survei dan tidak akan dibagikan kepada pihak ketiga tanpa izin Anda.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@push('styles')
<style>
    .progress-bar {
        height: 8px;
        background-color: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        border-radius: 4px;
        transition: width 0.3s ease;
    }
</style>
@endpush
@endsection
