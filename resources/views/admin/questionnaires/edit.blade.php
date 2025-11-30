<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Kuisioner: {{ $questionnaire->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.questionnaires.show', $questionnaire) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                    Detail
                </a>
                <a href="{{ route('admin.questionnaires.index') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-4 md:p-6">
                    <form method="POST" action="{{ route('admin.questionnaires.update', $questionnaire) }}">
                        @csrf
                        @method('PUT')

                        <!-- Faculty Selection -->
                        <div class="mb-6">
                            <x-input-label for="faculty_id" :value="__('Fakultas')" />
                            <select id="faculty_id" name="faculty_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary" required>
                                <option value="">Pilih Fakultas</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}" {{ $questionnaire->faculty_id == $faculty->id ? 'selected' : '' }}>
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
                                    <option value="{{ $subCategory->id }}" {{ $questionnaire->sub_category_id == $subCategory->id ? 'selected' : '' }}>
                                        {{ $subCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('sub_category_id')" class="mt-2" />
                        </div>

                        <!-- Title -->
                        <div class="mb-6">
                            <x-input-label for="title" :value="__('Judul Kuisioner')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $questionnaire->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">{{ old('description', $questionnaire->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Instruksi section removed per request -->

                        <!-- Estimated Duration -->
                        <div class="mb-6">
                            <x-input-label for="estimated_duration" :value="__('Estimasi Durasi (menit)')" />
                            <x-text-input id="estimated_duration" class="block mt-1 w-full" type="number" name="estimated_duration" :value="old('estimated_duration', $questionnaire->estimated_duration)" min="1" max="60" required />
                            <x-input-error :messages="$errors->get('estimated_duration')" class="mt-2" />
                        </div>

                        <!-- Active Status -->
                        <div class="mb-6">
                            <x-input-label for="is_active" :value="__('Status Aktif')" />
                            <div class="mt-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $questionnaire->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring-primary">
                                    <span class="ml-2 text-sm text-gray-600">Kuisioner aktif dan tersedia untuk diisi</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        <!-- Start Date -->
                        <div class="mb-6">
                            <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date', $questionnaire->start_date ? \Carbon\Carbon::parse($questionnaire->start_date)->format('Y-m-d') : '')" />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <!-- End Date -->
                        <div class="mb-6">
                            <x-input-label for="end_date" :value="__('Tanggal Berakhir')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date', $questionnaire->end_date ? \Carbon\Carbon::parse($questionnaire->end_date)->format('Y-m-d') : '')" />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>

                        {{-- Sub-bab & Pertanyaan (editable) --}}
                        @php
                            $subsections = [];
                            if ($questionnaire->relationLoaded('questions')) {
                                $grouped = $questionnaire->questions->groupBy('sub_category_id');
                            } else {
                                $grouped = $questionnaire->questions()->get()->groupBy('sub_category_id');
                            }
                            foreach ($grouped as $subCatId => $qs) {
                                $subsections[] = [
                                    'sub_category_id' => $subCatId,
                                    'questions' => $qs->map(function($q){
                                        return [
                                            'question_text' => $q->question_text,
                                            'order' => $q->order,
                                            'is_required' => (bool) $q->is_required,
                                        ];
                                    })->values()->toArray(),
                                ];
                            }
                        @endphp

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-3">Sub-bab & Pertanyaan</h3>
                            <div id="subsections-container">
                                @foreach($subsections as $i => $sub)
                                <div class="subsection-item mb-6 p-4 border rounded-lg bg-gray-50" data-subsection="{{ $i }}">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h4 class="font-semibold">Sub-bab {{ $i + 1 }}</h4>
                                        </div>
                                        <button type="button" onclick="removeSubsection({{ $i }})" class="text-red-500">Hapus</button>
                                    </div>

                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700">Pilih Sub-kategori</label>
                                        <select name="subsections[{{ $i }}][sub_category_id]" class="mt-1 block w-full border-gray-300 rounded-md">
                                            <option value="">-- Pilih Sub-kategori --</option>
                                            @foreach($subCategories as $sc)
                                                <option value="{{ $sc->id }}" {{ $sc->id == $sub['sub_category_id'] ? 'selected' : '' }}>{{ $sc->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="questions-container space-y-3" data-subsection="{{ $i }}">
                                        @foreach($sub['questions'] as $qi => $q)
                                        <div class="question-item p-3 border rounded-md bg-white" data-question="{{ $qi }}">
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-3 mb-2">
                                                        <span class="text-sm font-bold">Q{{ $qi + 1 }}</span>
                                                        <label class="flex items-center gap-2">
                                                            <input type="checkbox" name="subsections[{{ $i }}][questions][{{ $qi }}][is_required]" value="1" {{ $q['is_required'] ? 'checked' : '' }}>
                                                            <span class="text-sm">Wajib diisi</span>
                                                        </label>
                                                    </div>
                                                    <textarea name="subsections[{{ $i }}][questions][{{ $qi }}][question_text]" rows="2" class="w-full border-gray-300 rounded-md">{{ $q['question_text'] }}</textarea>
                                                </div>
                                                <button type="button" onclick="removeQuestion({{ $i }}, {{ $qi }})" class="text-red-500">Hapus</button>
                                            </div>
                                            <input type="hidden" name="subsections[{{ $i }}][questions][{{ $qi }}][order]" value="{{ $q['order'] }}">
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-3">
                                        <button type="button" onclick="addQuestion({{ $i }})" class="px-3 py-2 bg-indigo-600 text-white rounded-md">Tambah Pertanyaan</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <button type="button" onclick="addSubsection()" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Tambah Sub-bab</button>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('admin.questionnaires.show', $questionnaire) }}" class="text-gray-500 hover:text-gray-700">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button type="submit">
                                {{ __('Update Kuisioner') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

<script>
    // initialize counters based on existing rendered subsections
    let subsectionIndex = {!! json_encode(count($subsections)) !!};
    let questionIndices = {};

    // populate questionIndices from existing DOM
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.subsection-item').forEach(function(el) {
            const idx = el.dataset.subsection;
            const qcount = el.querySelectorAll('.question-item').length;
            questionIndices[idx] = qcount;
        });
    });

    function addSubsection() {
        const container = document.getElementById('subsections-container');
        const i = subsectionIndex;
        const html = `
            <div class="subsection-item mb-6 p-4 border rounded-lg bg-gray-50" data-subsection="${i}">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="font-semibold">Sub-bab ${i + 1}</h4>
                    </div>
                    <button type="button" onclick="removeSubsection(${i})" class="text-red-500">Hapus</button>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700">Pilih Sub-kategori</label>
                    <select name="subsections[${i}][sub_category_id]" class="mt-1 block w-full border-gray-300 rounded-md">
                        <option value="">-- Pilih Sub-kategori --</option>
                        @foreach($subCategories as $sc)
                        <option value="{{ $sc->id }}">{{ $sc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="questions-container space-y-3" data-subsection="${i}"></div>

                <div class="mt-3">
                    <button type="button" onclick="addQuestion(${i})" class="px-3 py-2 bg-indigo-600 text-white rounded-md">Tambah Pertanyaan</button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        questionIndices[i] = 0;
        subsectionIndex++;
    }

    function addQuestion(subsectionId) {
        const container = document.querySelector(`.questions-container[data-subsection="${subsectionId}"]`);
        if (!container) return;
        if (typeof questionIndices[subsectionId] === 'undefined') questionIndices[subsectionId] = 0;
        const qidx = questionIndices[subsectionId];
        const html = `
            <div class="question-item p-3 border rounded-md bg-white" data-question="${qidx}">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-sm font-bold">Q${qidx + 1}</span>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="subsections[${subsectionId}][questions][${qidx}][is_required]" value="1">
                                <span class="text-sm">Wajib diisi</span>
                            </label>
                        </div>
                        <textarea name="subsections[${subsectionId}][questions][${qidx}][question_text]" rows="2" class="w-full border-gray-300 rounded-md"></textarea>
                    </div>
                    <button type="button" onclick="removeQuestion(${subsectionId}, ${qidx})" class="text-red-500">Hapus</button>
                </div>
                <input type="hidden" name="subsections[${subsectionId}][questions][${qidx}][order]" value="${qidx + 1}">
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        questionIndices[subsectionId]++;
    }

    function removeSubsection(subsectionId) {
        const el = document.querySelector(`.subsection-item[data-subsection="${subsectionId}"]`);
        if (el) el.remove();
    }

    function removeQuestion(subsectionId, questionId) {
        const el = document.querySelector(`.questions-container[data-subsection="${subsectionId}"] .question-item[data-question="${questionId}"]`);
        if (el) el.remove();
    }
</script>
