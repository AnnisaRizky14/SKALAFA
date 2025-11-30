@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navigation Header -->
    <header class="bg-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('survey.faculties') }}" class="text-gray-600 hover:text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <img src="{{ asset('unib-logo.png') }}" alt="Logo UNIB" class="h-10 w-10">
                    <div>
                        <h1 class="text-xl font-bold text-primary">Pilih Kuisioner</h1>
                        <p class="text-sm text-gray-600">{{ $faculty->name }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Langkah 2 dari 3</p>
                    <div class="w-32 progress-bar mt-2">
                        <div class="progress-fill" style="width: 66.67%"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12">

        <!-- Page Title -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Pilih Kuisioner</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Silakan pilih kuisioner yang ingin Anda isi untuk fakultas <strong>{{ $faculty->name }}</strong>
            </p>
        </div>

<!-- Search Form -->
<div class="mb-8">
    <form method="GET" action="{{ route('survey.questionnaires', $faculty) }}" class="max-w-lg mx-auto">
        <div class="flex items-center shadow-sm">
            <div class="relative flex-1">
                <!-- Input -->
                <input type="text"
                       name="search"
                       value="{{ $search ?? '' }}"
                       placeholder="Cari kuisioner..."
                       class="block w-full pl-14 pr-4 py-3 text-sm border border-gray-300 rounded-l-lg bg-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
            </div>
            <!-- Button -->
            <button type="submit"
                    class="px-3 py-3 bg-blue-600 text-white font-semibold rounded-r-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-all duration-200">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>
    </form>
</div>

        <!-- Questionnaire Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach($questionnaires as $questionnaire)
            <div class="questionnaire-card bg-white rounded-xl shadow-lg overflow-hidden card-hover transform transition-all duration-300">

                <!-- Questionnaire Header -->
                <div class="h-32 flex items-center justify-center text-white text-2xl font-bold relative overflow-hidden faculty-bg">
                    <div class="absolute inset-0 bg-black opacity-10"></div>
                    <span class="relative z-10">{{ $faculty->short_name }}</span>

                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-{{ $questionnaire->id }}" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                    <circle cx="10" cy="10" r="2" fill="white" opacity="0.3"/>
                                </pattern>
                            </defs>
                            <rect width="100" height="100" fill="url(#pattern-{{ $questionnaire->id }})"/>
                        </svg>
                    </div>
                </div>

                <!-- Questionnaire Info -->
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $questionnaire->title }}</h3>
                    @if($questionnaire->description)
                    <p class="text-sm text-gray-600 mb-4 leading-relaxed">{{ Str::limit($questionnaire->description, 120) }}</p>
                    @endif

                    <!-- Statistics -->
                    <div class="flex items-center justify-between text-sm border-t pt-4">
                        <div class="flex items-center text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.196-2.121M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            {{ $questionnaire->responses()->where('is_completed', true)->count() }} responden
                        </div>

                        @php $avgRating = $questionnaire->getAverageRating() @endphp
                        @if($avgRating > 0)
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 mr-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($avgRating))
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
                            <span class="text-xs text-gray-600">{{ number_format($avgRating, 1) }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Question Count -->
                    <div class="mt-3 text-xs text-gray-500">
                        {{ $questionnaire->questions()->count() }} pertanyaan • {{ $questionnaire->estimated_duration }} menit
                    </div>

                    <!-- Subcategories Preview -->
                    @php $subcategories = $questionnaire->questions()->with('subCategory')->get()->pluck('subCategory')->unique() @endphp
                    @if($subcategories->isNotEmpty())
                    <div class="mt-3">
                        <p class="text-xs text-gray-500 mb-2">Sub-bab:</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($subcategories->take(3) as $subcategory)
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                {{ $subcategory->name }}
                            </span>
                            @endforeach
                            @if($subcategories->count() > 3)
                            <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">
                                +{{ $subcategories->count() - 3 }} lainnya
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Mulai Kuisioner Button -->
                    <div class="mt-4 text-center">
                        <a href="{{ route('survey.participant-info', $questionnaire) }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors duration-300">
                            <span>Mulai Kuisioner</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Selection Indicator -->
                <div class="absolute top-4 right-4 w-6 h-6 rounded-full border-2 border-white hidden selection-indicator">
                    <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- No Questionnaire Available Message -->
        @if($questionnaires->isEmpty())
        <div class="text-center py-16">
            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Kuisioner Tersedia</h3>
            <p class="text-gray-500 mb-6">Saat ini tidak ada kuisioner yang aktif untuk fakultas ini.</p>
            <a href="{{ route('survey.faculties') }}" class="inline-flex items-center px-6 py-3 btn-primary rounded-lg font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Pilih Fakultas
            </a>
        </div>
        @endif


    </main>

    <!-- Help Section -->
    <section class="bg-white py-12 border-t">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h3 class="text-2xl font-bold text-center text-gray-800 mb-8">Informasi Kuisioner</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Skala Likert 1-5</h4>
                        <p class="text-sm text-gray-600">Gunakan skala penilaian dari 1 (Sangat Tidak Puas) hingga 5 (Sangat Puas)</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Pertanyaan Terstruktur</h4>
                        <p class="text-sm text-gray-600">Pertanyaan dikelompokkan berdasarkan sub-bab untuk kemudahan navigasi</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Waktu Fleksibel</h4>
                        <p class="text-sm text-gray-600">Anda dapat menyimpan progress dan melanjutkan nanti</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('styles')
<style>
    .faculty-bg {
        background-color: {{ $faculty->color }};
    }
</style>
@endpush

@push('scripts')
<script>
// Animation on scroll
document.addEventListener('DOMContentLoaded', function() {
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

    // Animate cards with stagger effect
    document.querySelectorAll('.questionnaire-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
});
</script>
@endpush



@endsection
