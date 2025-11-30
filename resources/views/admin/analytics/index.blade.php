<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Analitik Survei
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.responses.index') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                    Kembali ke Respon
                </a>
                <button onclick="exportAnalytics()"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                    Export Laporan
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-12">
            <!-- Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Respon</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalResponses) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Fakultas</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalFaculties) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Kuisioner Aktif</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalQuestionnaires) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Rating Rata-rata</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($averageRating, 2) }}/5</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Ratings Chart (Conditional based on user role) -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6">
                    @if(auth()->user()->isSuperAdmin())
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Rating per Fakultas</h3>
                        <div class="space-y-4">
                            @forelse($facultyRatings as $faculty)
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $faculty['name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $faculty['responses'] }} respon</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($faculty['rating']))
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 ml-2">{{ number_format($faculty['rating'], 1) }}</span>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">Belum ada data rating per fakultas</p>
                            @endforelse
                        </div>
                    @else
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Rating per Kuisioner</h3>
                        <div class="space-y-4">
                            @forelse($questionnaireRatings as $questionnaire)
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $questionnaire['title'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $questionnaire['responses'] }} respon</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($questionnaire['rating']))
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 ml-2">{{ number_format($questionnaire['rating'], 1) }}</span>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">Belum ada data rating per kuisioner</p>
                            @endforelse
                        </div>
                    @endif
                </div>

                <!-- Satisfaction Distribution -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Kepuasan</h3>
                    <div class="space-y-3">
                        @foreach($satisfactionLevels as $level => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">{{ $level }}</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $totalResponses > 0 ? ($count / $totalResponses) * 100 : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900 w-8 text-right">{{ $count }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Monthly Trends and Top Questionnaires -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Monthly Trends -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Respon Bulanan</h3>
                    <div class="space-y-3">
                        @foreach($monthlyTrends as $trend)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">{{ $trend['month'] }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $trend['count'] }} respon</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Top Questionnaires -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Kuisioner Terpopuler</h3>
                    <div class="space-y-3">
                        @forelse($topQuestionnaires as $questionnaire)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $questionnaire->title }}</p>
                                <p class="text-xs text-gray-500">{{ $questionnaire->faculty->name }}</p>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $questionnaire->responses_count }} respon</span>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">Belum ada data kuisioner</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportAnalytics() {
            const url = '{{ route("admin.analytics.export") }}';
            window.open(url, '_blank');
        }
    </script>
</x-admin-layout>
