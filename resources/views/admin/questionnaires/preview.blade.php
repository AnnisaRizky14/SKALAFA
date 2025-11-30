<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Preview Kuisioner: {{ $questionnaire->title }}
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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Questionnaire Header (as seen by users) -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold">{{ $questionnaire->title }}</h1>
                            @if($questionnaire->description)
                                <p class="mt-2 text-blue-100">{{ $questionnaire->description }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="text-blue-100 text-sm">Fakultas</div>
                            <div class="font-semibold">{{ $questionnaire->faculty->name }}</div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-b">
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center space-x-4">
                            <span>Estimasi: {{ $questionnaire->estimated_duration }} menit</span>
                            <span>•</span>
                            <span>{{ $questionnaire->questions->count() }} pertanyaan</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($questionnaire->is_active)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Tidak Aktif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($questionnaire->instructions)
                    <div class="px-6 py-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Instruksi</h3>
                        <div class="text-gray-700 bg-blue-50 p-4 rounded-lg">
                            {!! nl2br(e($questionnaire->instructions)) !!}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Questions Preview -->
            @forelse($questionsGrouped as $subCategoryId => $questions)
                @php $subCategory = $subcategories->find($subCategoryId); @endphp
                @if($subCategory)
                    <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6">
                        <div class="bg-gray-100 px-6 py-4 border-b">
                            <h2 class="text-xl font-semibold text-gray-800">{{ $subCategory->name }}</h2>
                        </div>
                    </div>
                @endif

                @foreach($questions as $question)
                    <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6">
                        <div class="p-6">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-semibold text-sm">
                                        {{ $question->order }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-start justify-between">
                                        <h3 class="text-lg font-medium text-gray-900 mb-3">
                                            {{ $question->question_text }}
                                            @if($question->is_required)
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </h3>
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                            {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                                        </span>
                                    </div>

                                    <!-- Question Input Preview -->
                                    @if($question->question_type === 'text')
                                        <div class="mt-3">
                                            <input type="text" placeholder="Jawaban Anda..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" disabled>
                                        </div>
                                    @elseif($question->question_type === 'textarea')
                                        <div class="mt-3">
                                            <textarea rows="4" placeholder="Jawaban Anda..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" disabled></textarea>
                                        </div>
                                    @elseif($question->question_type === 'multiple_choice')
                                        <div class="mt-3 space-y-2">
                                            @foreach($question->options ?? [] as $index => $option)
                                                <label class="flex items-center">
                                                    <input type="radio" name="question_{{ $question->id }}" value="{{ $index }}" class="text-blue-600 focus:ring-blue-500" disabled>
                                                    <span class="ml-2 text-gray-700">{{ $option }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @elseif($question->question_type === 'checkbox')
                                        <div class="mt-3 space-y-2">
                                            @foreach($question->options ?? [] as $index => $option)
                                                <label class="flex items-center">
                                                    <input type="checkbox" name="question_{{ $question->id }}[]" value="{{ $index }}" class="text-blue-600 focus:ring-blue-500" disabled>
                                                    <span class="ml-2 text-gray-700">{{ $option }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @elseif($question->question_type === 'rating')
                                        <div class="mt-3">
                                            <div class="flex space-x-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <button type="button" class="w-8 h-8 text-gray-300 hover:text-yellow-400 focus:text-yellow-400" disabled>
                                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    </button>
                                                @endfor
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @empty
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pertanyaan</h3>
                        <p class="text-gray-500">Kuisioner ini belum memiliki pertanyaan.</p>
                    </div>
                </div>
            @endforelse

            <!-- Preview Footer -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 text-center">
                    <div class="text-sm text-gray-500 mb-4">
                        Ini adalah preview kuisioner. Jawaban tidak akan disimpan.
                    </div>
                    <div class="flex justify-center space-x-4">
                        <button type="button" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg cursor-not-allowed" disabled>
                            Sebelumnya
                        </button>
                        <button type="button" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 cursor-not-allowed" disabled>
                            Selanjutnya
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
