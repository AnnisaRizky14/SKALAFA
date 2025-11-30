<?php if (isset($component)) { $__componentOriginale0f1cdd055772eb1d4a99981c240763e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0f1cdd055772eb1d4a99981c240763e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Atur Sub-bab & Pertanyaan')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="<?php echo e(route('admin.questionnaires.store')); ?>" id="questionnaire-form">
                <?php echo csrf_field(); ?>

                
                <input type="hidden" name="faculty_id" value="<?php echo e($data['faculty_id']); ?>">
                <input type="hidden" name="sub_category_id" value="<?php echo e($data['sub_category_id'] ?? ''); ?>">
                <input type="hidden" name="title" value="<?php echo e($data['title']); ?>">
                <input type="hidden" name="description" value="<?php echo e($data['description'] ?? ''); ?>">
                <input type="hidden" name="instructions" value="<?php echo e($data['instructions'] ?? ''); ?>">
                <input type="hidden" name="estimated_duration" value="<?php echo e($data['estimated_duration']); ?>">
                <input type="hidden" name="is_active" value="<?php echo e($data['is_active'] ?? 0); ?>">
                <input type="hidden" name="start_date" value="<?php echo e($data['start_date'] ?? ''); ?>">
                <input type="hidden" name="end_date" value="<?php echo e($data['end_date'] ?? ''); ?>">

                
                <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-indigo-700 shadow-2xl rounded-2xl p-8 mb-12 text-white">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h2 class="text-3xl font-bold mb-2"><?php echo e($data['title']); ?></h2>
                            <p class="text-indigo-100 text-sm">Siapkan semua sub-bab dan pertanyaan kuisioner Anda</p>
                        </div>
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl px-6 py-4">
                            <div class="text-center">
                                <p class="text-indigo-100 text-sm mb-1">Total Pertanyaan</p>
                                <p class="text-4xl font-bold" id="totalQuestionsDisplay">0</p>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
                            <p class="text-indigo-100 text-xs uppercase tracking-wide mb-1">Fakultas</p>
                            <p class="text-lg font-semibold"><?php echo e($faculties->firstWhere('id', $data['faculty_id'])->name ?? '-'); ?></p>
                        </div>
                        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
                            <p class="text-indigo-100 text-xs uppercase tracking-wide mb-1">Estimasi Durasi</p>
                            <p class="text-lg font-semibold"><?php echo e($data['estimated_duration'] ?? 'N/A'); ?> Menit</p>
                        </div>
                        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-4">
                            <p class="text-indigo-100 text-xs uppercase tracking-wide mb-1">Status</p>
                            <p class="text-lg font-semibold"><?php echo e($data['is_active'] ? 'Aktif' : 'Tidak Aktif'); ?></p>
                        </div>
                    </div>
                </div>

                
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    
                    <div class="lg:col-span-3">
                        
                        <div class="space-y-6 mb-8">
                            <div id="subsections-container">
                                <!-- Subsections will be injected here -->
                            </div>
                        </div>

                        
                        <div class="mb-8">
                            <button type="button" onclick="addSubsection()" class="w-full px-6 py-4 border-2 border-dashed border-indigo-900 bg-gradient-to-br from-indigo-50 to-indigo-100 text-indigo-900 font-bold rounded-2xl hover:from-indigo-100 hover:to-indigo-200 hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-3 group">
                                <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Tambah Sub-bab Baru</span>
                            </button>
                        </div>
                    </div>

                    
                    <div class="lg:col-span-1">
                        <div class="sticky top-20">
                            
                            <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 mb-6">
                                <h3 class="font-bold text-blue-900 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    Panduan
                                </h3>
                                <ul class="text-sm text-blue-800 space-y-2">
                                    <li class="flex gap-2">
                                        <span class="text-blue-600 font-bold">1.</span>
                                        <span>Tambah sub-bab untuk mengelompokkan pertanyaan</span>
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="text-blue-600 font-bold">2.</span>
                                        <span>Pilih sub-kategori untuk setiap sub-bab</span>
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="text-blue-600 font-bold">3.</span>
                                        <span>Tambah pertanyaan di setiap sub-bab</span>
                                    </li>
                                    <li class="flex gap-2">
                                        <span class="text-blue-600 font-bold">4.</span>
                                        <span>Klik "Buat Kuisioner" untuk menyimpan</span>
                                    </li>
                                </ul>
                            </div>

                            
                            <div class="bg-gradient-to-br from-indigo-900 to-indigo-800 text-white rounded-xl p-6">
                                <h3 class="font-bold mb-4">Statistik</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center pb-3 border-b border-indigo-700">
                                        <span class="text-indigo-100">Sub-bab</span>
                                        <span class="text-2xl font-bold" id="totalSubsections">0</span>
                                    </div>
                                    <div class="flex justify-between items-center pb-3 border-b border-indigo-700">
                                        <span class="text-indigo-100">Pertanyaan</span>
                                        <span class="text-2xl font-bold" id="totalQuestions">0</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-2">
                                        <span class="text-indigo-100">Status</span>
                                        <span class="text-sm font-semibold px-2 py-1 bg-indigo-700 rounded-full" id="statusIndicator">Siap</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="flex items-center justify-between gap-4 mt-12 pt-8 border-t border-gray-300">
                    <a href="<?php echo e(route('admin.questionnaires.create')); ?>" class="inline-flex items-center px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg transition-all duration-300 gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Kembali
                    </a>
                    <button id="submit-btn" type="submit" disabled class="px-8 py-3 bg-gradient-to-r from-indigo-900 to-indigo-800 hover:from-indigo-800 hover:to-indigo-700 text-white font-bold rounded-lg transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl">
                        Buat Kuisioner
                    </button>
                </div>
            </form>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale0f1cdd055772eb1d4a99981c240763e)): ?>
<?php $attributes = $__attributesOriginale0f1cdd055772eb1d4a99981c240763e; ?>
<?php unset($__attributesOriginale0f1cdd055772eb1d4a99981c240763e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale0f1cdd055772eb1d4a99981c240763e)): ?>
<?php $component = $__componentOriginale0f1cdd055772eb1d4a99981c240763e; ?>
<?php unset($__componentOriginale0f1cdd055772eb1d4a99981c240763e); ?>
<?php endif; ?>

<script>
let subsectionIndex = 0;
let questionIndices = {};
let submitBtn, totalQuestionsDisplay, totalSubsections, totalQuestions, statusIndicator;

function initializeForm() {
    submitBtn = document.getElementById('submit-btn');
    totalQuestionsDisplay = document.getElementById('totalQuestionsDisplay');
    totalSubsections = document.getElementById('totalSubsections');
    totalQuestions = document.getElementById('totalQuestions');
    statusIndicator = document.getElementById('statusIndicator');
    
    // Initialize with one subsection and one question
    addSubsection();
    addQuestion(0);
    updateStatistics();
}

function updateStatistics() {
    if (!submitBtn || !totalQuestionsDisplay) return;
    
    const totalQ = document.querySelectorAll('.question-item').length;
    const totalS = document.querySelectorAll('.subsection-item').length;
    
    totalQuestionsDisplay.textContent = totalQ;
    totalSubsections.textContent = totalS;
    totalQuestions.textContent = totalQ;
    
    // Update status indicator
    if (totalQ === 0) {
        statusIndicator.textContent = 'Perlu Ditambah';
        statusIndicator.className = 'text-sm font-semibold px-2 py-1 bg-red-700 rounded-full';
    } else if (totalQ < 3) {
        statusIndicator.textContent = 'Minimal 3';
        statusIndicator.className = 'text-sm font-semibold px-2 py-1 bg-yellow-700 rounded-full';
    } else {
        statusIndicator.textContent = 'Lengkap';
        statusIndicator.className = 'text-sm font-semibold px-2 py-1 bg-green-700 rounded-full';
    }
    
    submitBtn.disabled = totalQ === 0;
}

function addSubsection() {
    const container = document.getElementById('subsections-container');
    const currentSubsectionIndex = subsectionIndex;
    questionIndices[currentSubsectionIndex] = 0;

    const subsectionHtml = `
        <div class="subsection-item bg-white border-2 border-gray-200 rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300" data-subsection="${currentSubsectionIndex}">
            <div class="bg-gradient-to-r from-indigo-100 via-blue-50 to-indigo-50 px-6 py-5 border-b-2 border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-900 to-indigo-800 text-white rounded-full flex items-center justify-center font-bold text-lg shadow-md">
                        ${currentSubsectionIndex + 1}
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-800">Sub-bab ${currentSubsectionIndex + 1}</h4>
                        <p class="text-xs text-gray-500">Kelompokkan pertanyaan serupa di sini</p>
                    </div>
                </div>
                <button type="button" onclick="removeSubsection(${currentSubsectionIndex})" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all duration-300 hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>

            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <label class="block text-sm font-bold text-gray-700 mb-3">📂 Pilih Sub-kategori</label>
                <select name="subsections[${currentSubsectionIndex}][sub_category_id]" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-900 focus:border-transparent transition-all font-medium" required>
                    <option value="">-- Pilih Sub-kategori --</option>
                    <?php $__currentLoopData = $subCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($subCategory->id); ?>"><?php echo e($subCategory->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="questions-container space-y-3 p-6 bg-white min-h-[150px]" data-subsection="${currentSubsectionIndex}">
            </div>

            <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-t border-gray-200 flex gap-3">
                <button type="button" onclick="addQuestion(${currentSubsectionIndex})" class="flex-1 px-4 py-3 bg-gradient-to-r from-indigo-900 to-indigo-800 hover:from-indigo-800 hover:to-indigo-700 text-white font-semibold rounded-lg transition-all duration-300 hover:shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Pertanyaan
                </button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', subsectionHtml);
    subsectionIndex++;
    updateStatistics();
}

function addQuestion(subsectionId) {
    const container = document.querySelector(`.questions-container[data-subsection="${subsectionId}"]`);
    if (!container) return;
    
    if (!questionIndices[subsectionId]) {
        questionIndices[subsectionId] = 0;
    }
    
    const questionId = questionIndices[subsectionId];

    const questionHtml = `
        <div class="question-item bg-gradient-to-r from-gray-50 to-gray-100 border-2 border-gray-200 rounded-lg p-5 hover:shadow-lg transition-all duration-300" data-question="${questionId}">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-sm font-bold text-white bg-gradient-to-r from-indigo-900 to-indigo-800 px-3 py-1 rounded-full">Q${questionId + 1}</span>
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-indigo-50 px-3 py-1 rounded-lg transition-colors">
                            <input type="checkbox" name="subsections[${subsectionId}][questions][${questionId}][is_required]" value="1" class="w-5 h-5 text-indigo-900 rounded focus:ring-indigo-900 cursor-pointer">
                            <span class="text-sm font-medium text-gray-700">Wajib diisi</span>
                        </label>
                    </div>
                    <textarea name="subsections[${subsectionId}][questions][${questionId}][question_text]" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-900 focus:border-transparent transition-all resize-none font-medium placeholder-gray-400" placeholder="Ketik pertanyaan di sini..." required></textarea>
                </div>
                <button type="button" onclick="removeQuestion(${subsectionId}, ${questionId})" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all duration-300 hover:scale-110 flex-shrink-0 h-fit mt-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <input type="hidden" name="subsections[${subsectionId}][questions][${questionId}][order]" value="${questionId + 1}">
        </div>
    `;

    container.insertAdjacentHTML('beforeend', questionHtml);
    questionIndices[subsectionId]++;
    updateStatistics();
}

function removeSubsection(subsectionId) {
    const elem = document.querySelector(`.subsection-item[data-subsection="${subsectionId}"]`);
    if (elem) {
        elem.style.opacity = '0';
        elem.style.transform = 'translateY(-10px)';
        setTimeout(() => elem.remove(), 300);
        delete questionIndices[subsectionId];
        updateStatistics();
    }
}

function removeQuestion(subsectionId, questionId) {
    const elem = document.querySelector(`.questions-container[data-subsection="${subsectionId}"] .question-item[data-question="${questionId}"]`);
    if (elem) {
        elem.style.opacity = '0';
        elem.style.transform = 'translateX(-10px)';
        setTimeout(() => elem.remove(), 300);
        if (questionIndices[subsectionId] > 0) {
            questionIndices[subsectionId]--;
        }
        updateStatistics();
    }
}

document.addEventListener('DOMContentLoaded', initializeForm);

document.getElementById('questionnaire-form').addEventListener('submit', function(e) {
    const totalQ = document.querySelectorAll('.question-item').length;
    if (totalQ === 0) {
        e.preventDefault();
        alert('Silakan tambahkan minimal 1 pertanyaan sebelum membuat kuisioner.');
        return false;
    }
});
</script>

<style>
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slideIn {
    animation: slideIn 0.3s ease-out;
}
</style>
<?php /**PATH C:\laragon\www\skalafa-system\resources\views/admin/questionnaires/setup.blade.php ENDPATH**/ ?>