<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Kuisioner: {{ $questionnaire->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.questionnaires.edit', $questionnaire) }}"
                   class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                    Edit
                </a>
                <a href="{{ route('admin.questionnaires.index') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Questionnaire Info -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Judul</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $questionnaire->title }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Fakultas</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $questionnaire->faculty->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Status</h3>
                            <p class="mt-1">
                                @if($questionnaire->is_active)
                                    @if($questionnaire->isAvailable())
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Aktif & Tersedia
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Aktif & Tidak Tersedia
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Durasi</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $questionnaire->estimated_duration }} menit</p>
                        </div>
                    </div>

                    @if($questionnaire->description)
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Deskripsi</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $questionnaire->description }}</p>
                        </div>
                    @endif

                    @if($questionnaire->instructions)
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Instruksi</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $questionnaire->instructions }}</p>
                        </div>
                    @endif

                    @if($questionnaire->start_date || $questionnaire->end_date)
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($questionnaire->start_date)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tanggal Mulai</h3>
                                    <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($questionnaire->start_date)->format('d M Y') }}</p>
                                </div>
                            @endif
                            @if($questionnaire->end_date)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tanggal Berakhir</h3>
                                    <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($questionnaire->end_date)->format('d M Y') }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Questions Section -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pertanyaan ({{ $questionnaire->questions->count() }})</h3>

                    @forelse($questionnaire->questions->groupBy('subCategory.name') as $subCategoryName => $questions)
                        @if($subCategoryName)
                            <div class="mb-6">
                                <h4 class="text-md font-medium text-gray-700 mb-3">{{ $subCategoryName }}</h4>
                            </div>
                        @endif

                        <div class="space-y-4">
                            @foreach($questions as $question)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 mb-2">{{ $question->question_text }}</p>
                                            <div class="text-xs text-gray-500">
                                                Tipe: {{ ucfirst($question->question_type) }}
                                                @if($question->is_required)
                                                    <span class="text-red-500">*</span> Wajib
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $question->order }}
                                            </span>
                                        </div>
                                    </div>

                                    @if($question->question_type === 'multiple_choice' || $question->question_type === 'checkbox')
                                        <div class="mt-3 ml-4">
                                            <ul class="text-sm text-gray-600 space-y-1">
                                                @foreach($question->options ?? [] as $option)
                                                    <li>• {{ $option }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">Belum ada pertanyaan dalam kuisioner ini.</p>
                    @endforelse
                </div>
            </div>

            <!-- Responses Summary -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Respon</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $questionnaire->responses->count() }}</div>
                            <div class="text-sm text-gray-500">Total Respon</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $questionnaire->completedResponses->count() }}</div>
                            <div class="text-sm text-gray-500">Respon Selesai</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-600">
                                @if($questionnaire->completedResponses->count() > 0)
                                    {{ number_format($questionnaire->getAverageRating(), 1) }}/5
                                @else
                                    -
                                @endif
                            </div>
                            <div class="text-sm text-gray-500">Rating Rata-rata</div>
                        </div>
                    </div>

                    @if($questionnaire->responses->count() > 0)
                        <div class="mt-6">
                            <a href="{{ route('admin.responses.index', ['questionnaire' => $questionnaire->id]) }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                Lihat Semua Respon
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
