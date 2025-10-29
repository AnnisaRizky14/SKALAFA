 @extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navigation Header -->
    <header class="bg-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('survey.dashboard') }}" class="text-gray-600 hover:text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <img src="{{ asset('storage/unib-logo.png') }}" alt="Logo UNIB" class="h-10 w-10">
                    <div>
                        <h1 class="text-xl font-bold text-primary">SKALAFA</h1>
                        <p class="text-sm text-gray-600">Pilih Fakultas</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Langkah 1 dari 3</p>
                    <div class="w-32 progress-bar mt-2">
                        <div class="progress-fill" style="width: 33.33%"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12">
        <!-- Page Title -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Pilih Fakultas</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Silakan pilih fakultas yang ingin Anda berikan penilaian terhadap layanannya
            </p>
        </div>

        <!-- Faculty Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
            @foreach($faculties as $faculty)
            <div class="faculty-card bg-white rounded-xl shadow-lg overflow-hidden card-hover transform transition-all duration-300">

                <!-- Faculty Header -->
                <div class="h-32 flex items-center justify-center text-white text-3xl font-bold relative overflow-hidden"
                     style="background: linear-gradient(135deg, {{ $faculty->color }}, {{ \App\Helpers\ColorHelper::adjustColor($faculty->color, -20) }})">
                    <div class="absolute inset-0 bg-black opacity-10"></div>
                    <span class="relative z-10">{{ $faculty->short_name }}</span>

                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-{{ $faculty->id }}" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                    <circle cx="10" cy="10" r="2" fill="white" opacity="0.3"/>
                                </pattern>
                            </defs>
                            <rect width="100" height="100" fill="url(#pattern-{{ $faculty->id }})"/>
                        </svg>
                    </div>
                </div>

                <!-- Faculty Info -->
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $faculty->name }}</h3>
                    <p class="text-sm text-gray-600 mb-4 leading-relaxed">{{ Str::limit($faculty->description, 100) }}</p>

                    <!-- Statistics -->
                    @php $stats = $faculty->getSatisfactionStats() @endphp
                    <div class="flex items-center justify-between text-sm border-t pt-4">
                        <div class="flex items-center text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $stats['total_responses'] }} responden
                        </div>

                        @if($stats['average_rating'] > 0)
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 mr-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($stats['average_rating']))
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-xs text-gray-600">{{ $stats['average_rating'] }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Questionnaire Count -->
                    @php $questionnaireCount = $faculty->activeQuestionnaires()->count() @endphp
                    <div class="mt-3 text-xs text-gray-500">
                        {{ $questionnaireCount }} kuisioner tersedia
                    </div>

                    <!-- Lihat Kuisioner Button -->
                    <div class="mt-4 text-center">
                        <a href="/survey/questionnaires/{{ $faculty->id }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-300">
                            <span>Lihat Kuisioner</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- No Faculty Available Message -->
        @if($faculties->isEmpty())
        <div class="text-center py-16">
            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Fakultas Tersedia</h3>
            <p class="text-gray-500">Saat ini tidak ada fakultas yang membuka survei kepuasan.</p>
        </div>
        @endif


    </main>

    <!-- Help Section -->
    <section class="bg-white py-12 border-t">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h3 class="text-2xl font-bold text-center text-gray-800 mb-8">Butuh Bantuan?</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Cara Mengisi Survei</h4>
                        <p class="text-sm text-gray-600">Pilih fakultas, pilih kuisioner, dan isi semua pertanyaan dengan jujur</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Waktu Pengisian</h4>
                        <p class="text-sm text-gray-600">Rata-rata membutuhkan waktu 5-10 menit untuk menyelesaikan survei</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Keamanan Data</h4>
                        <p class="text-sm text-gray-600">Semua data dijamin aman dan identitas Anda tetap anonim</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
