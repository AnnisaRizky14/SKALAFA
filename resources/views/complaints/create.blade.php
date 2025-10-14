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
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-primary mb-4">Kirim Pengaduan</h2>
                    <p class="text-gray-600">Berikan masukan atau keluhan Anda untuk membantu kami meningkatkan kualitas layanan.</p>
                </div>

                <form action="{{ route('complaints.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Pengaduan</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email (Opsional)</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Pengaduan</label>
                        <textarea id="description" name="description" rows="6" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent resize-vertical"
                                  placeholder="Jelaskan pengaduan atau saran Anda secara detail...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('complaints.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-8 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-semibold">
                            Kirim Pengaduan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
