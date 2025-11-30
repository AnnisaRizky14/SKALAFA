<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Kuisioner Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.questionnaires.store') }}">
                        @csrf

                        <!-- Faculty Selection -->
                        <div class="mb-6">
                            <x-input-label for="faculty_id" :value="__('Fakultas')" />
                            <select id="faculty_id" name="faculty_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary" required>
                                <option value="">Pilih Fakultas</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">
                                        {{ $faculty->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('faculty_id')" class="mt-2" />
                        </div>

                        <!-- Sub Category Selection -->
                        <div class="mb-6">
                            <x-input-label for="sub_category_id" :value="__('Kategori Kuisioner')" />
                            <select id="sub_category_id" name="sub_category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">Pilih Kategori (Opsional)</option>
                                @foreach($subCategories as $subCategory)
                                    <option value="{{ $subCategory->id }}">
                                        {{ $subCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('sub_category_id')" class="mt-2" />
                        </div>

                        <!-- Title -->
                        <div class="mb-6">
                            <x-input-label for="title" :value="__('Judul Kuisioner')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Sub-sections and Questions -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <x-input-label :value="__('Sub-bab dan Pertanyaan')" />
                                <button type="button" id="add-subsection" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah Sub-bab
                                </button>
                            </div>

                            <div id="subsections-container" class="space-y-6">
                                <!-- Sub-sections will be added here dynamically -->
                            </div>

                            <x-input-error :messages="$errors->get('subsections')" class="mt-2" />
                        </div>

                        <!-- Estimated Duration -->
                        <div class="mb-6">
                            <x-input-label for="estimated_duration" :value="__('Estimasi Durasi (menit)')" />
                            <x-text-input id="estimated_duration" class="block mt-1 w-full" type="number" name="estimated_duration" :value="old('estimated_duration', 10)" min="1" max="60" required />
                            <x-input-error :messages="$errors->get('estimated_duration')" class="mt-2" />
                        </div>

                        <!-- Active Status -->
                        <div class="mb-6">
                            <x-input-label for="is_active" :value="__('Status Aktif')" />
                            <div class="mt-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring-primary">
                                    <span class="ml-2 text-sm text-gray-600">Kuisioner aktif dan tersedia untuk diisi</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        <!-- Start Date -->
                        <div class="mb-6">
                            <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date')" />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <!-- End Date -->
                        <div class="mb-6">
                            <x-input-label for="end_date" :value="__('Tanggal Berakhir')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date')" />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('admin.questionnaires.index') }}" class="text-gray-500 hover:text-gray-700">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button type="submit">
                                {{ __('Buat Kuisioner') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let subsectionIndex = 0;
    let questionIndices = {};

    // Add subsection button
    document.getElementById('add-subsection').addEventListener('click', function() {
        addSubsection();
    });

    function addSubsection() {
        const container = document.getElementById('subsections-container');
        const subsectionId = subsectionIndex;
        questionIndices[subsectionId] = 0;

        const subsectionHtml = `
            <div class="subsection-item bg-gray-50 border border-gray-200 rounded-lg p-6" data-subsection="${subsectionId}">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-800">Sub-bab ${subsectionId + 1}</h4>
                    <button type="button" class="remove-subsection text-red-500 hover:text-red-700" data-subsection="${subsectionId}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Sub-kategori</label>
                    <select name="subsections[${subsectionId}][sub_category_id]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary" required>
                        <option value="">Pilih Sub-kategori</option>
                        @foreach($subCategories as $subCategory)
                        <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="questions-container space-y-4" data-subsection="${subsectionId}">
                    <!-- Questions will be added here -->
                </div>

                <div class="mt-4">
                    <button type="button" class="add-question px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm" data-subsection="${subsectionId}">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Pertanyaan
                    </button>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', subsectionHtml);
        subsectionIndex++;

        // Add event listeners for the new subsection
        attachSubsectionEventListeners();
    }

    function addQuestion(subsectionId) {
        const container = document.querySelector(`.questions-container[data-subsection="${subsectionId}"]`);
        const questionId = questionIndices[subsectionId];

        const questionHtml = `
            <div class="question-item bg-white border border-gray-200 rounded-lg p-4" data-question="${questionId}">
                <div class="flex items-center justify-between mb-3">
                    <h5 class="text-md font-medium text-gray-700">Pertanyaan ${questionId + 1}</h5>
                    <button type="button" class="remove-question text-red-500 hover:text-red-700" data-subsection="${subsectionId}" data-question="${questionId}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                        <input type="number" name="subsections[${subsectionId}][questions][${questionId}][order]" value="${questionId + 1}" min="1" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary" required>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center">
                            <input type="checkbox" name="subsections[${subsectionId}][questions][${questionId}][is_required]" value="1" class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring-primary">
                            <span class="ml-2 text-sm text-gray-600">Wajib diisi</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Teks Pertanyaan</label>
                    <textarea name="subsections[${subsectionId}][questions][${questionId}][question_text]" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary" placeholder="Masukkan teks pertanyaan..." required></textarea>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', questionHtml);
        questionIndices[subsectionId]++;

        // Add event listeners for the new question
        attachQuestionEventListeners();
    }

    function attachSubsectionEventListeners() {
        // Remove subsection
        document.querySelectorAll('.remove-subsection').forEach(button => {
            button.addEventListener('click', function() {
                const subsectionId = this.dataset.subsection;
                document.querySelector(`.subsection-item[data-subsection="${subsectionId}"]`).remove();
                delete questionIndices[subsectionId];
                updateSubsectionNumbers();
            });
        });

        // Add question
        document.querySelectorAll('.add-question').forEach(button => {
            button.addEventListener('click', function() {
                const subsectionId = this.dataset.subsection;
                addQuestion(subsectionId);
            });
        });
    }

    function attachQuestionEventListeners() {
        // Remove question
        document.querySelectorAll('.remove-question').forEach(button => {
            button.addEventListener('click', function() {
                const subsectionId = this.dataset.subsection;
                const questionId = this.dataset.question;
                document.querySelector(`.questions-container[data-subsection="${subsectionId}"] .question-item[data-question="${questionId}"]`).remove();
                questionIndices[subsectionId]--;
                updateQuestionNumbers(subsectionId);
            });
        });
    }

    function updateSubsectionNumbers() {
        const subsections = document.querySelectorAll('.subsection-item');
        subsections.forEach((subsection, index) => {
            subsection.querySelector('h4').textContent = `Sub-bab ${index + 1}`;
        });
    }

    function updateQuestionNumbers(subsectionId) {
        const questions = document.querySelectorAll(`.questions-container[data-subsection="${subsectionId}"] .question-item`);
        questions.forEach((question, index) => {
            question.querySelector('h5').textContent = `Pertanyaan ${index + 1}`;
        });
    }

    // Initialize with one subsection and one question
    addSubsection();
    addQuestion(0);
});
</script>
@endpush
