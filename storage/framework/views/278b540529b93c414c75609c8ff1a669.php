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
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Respon
            </h2>
            <div class="flex items-center space-x-4">
                <a href="<?php echo e(route('admin.analytics.index')); ?>"
                   class="bg-purple-600 hover:bg-purple-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                    Lihat Analitik
                </a>
                <div class="relative">
                    <button onclick="toggleExportDropdown()"
                            class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                        Export Data
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="exportDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 hidden">
                        <div class="py-1">
                            <button onclick="exportResponses('excel')"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Export Excel
                                </div>
                            </button>
                            <button onclick="exportResponses('pdf')"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Export PDF
                                </div>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-12">
            <!-- Search and Filter -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6">
                <div class="p-6">
                    <form method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <?php if(auth()->user()->isSuperAdmin()): ?>
                            <!-- Faculty Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
                                <select name="faculty_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Semua Fakultas</option>
                                    <?php $__currentLoopData = $faculties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faculty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($faculty->id); ?>" <?php echo e(request('faculty_id') == $faculty->id ? 'selected' : ''); ?>>
                                            <?php echo e($faculty->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <?php endif; ?>

                            <!-- Questionnaire Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kuisioner</label>
                                <select name="questionnaire_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Semua Kuisioner</option>
                                    <?php $__currentLoopData = $questionnaires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $questionnaire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($questionnaire->id); ?>" <?php echo e(request('questionnaire_id') == $questionnaire->id ? 'selected' : ''); ?>>
                                            <?php echo e($questionnaire->title); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Date Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                                <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Rating Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rating Minimum</label>
                                <select name="min_rating" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Tidak ada</option>
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <option value="<?php echo e($i); ?>" <?php echo e(request('min_rating') == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rating Maksimum</label>
                                <select name="max_rating" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Tidak ada</option>
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <option value="<?php echo e($i); ?>" <?php echo e(request('max_rating') == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <!-- Search -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                       placeholder="Cari respon..."
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                Terapkan Filter
                            </button>
                            <a href="<?php echo e(route('admin.responses.index')); ?>"
                               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Responses List -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6">
                <div class="overflow-x-auto min-w-full">
                    <table class="min-w-full divide-y divide-gray-200 table-auto w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-2/5 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Responden
                                </th>
                                <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kuisioner
                                </th>
                                <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fakultas
                                </th>
                                <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rating Rata-rata
                                </th>
                                <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tingkat Kepuasan
                                </th>
                                <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Durasi
                                </th>
                                <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Selesai
                                </th>
                                <th class="w-1/6 px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__empty_1 = true; $__currentLoopData = $responses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $response): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                Responden #<?php echo e($response->id); ?>

                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?php echo e($response->answers->count()); ?> jawaban
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo e($response->questionnaire->title); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e($response->questionnaire->faculty->short_name); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900"><?php echo e($response->faculty->name); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php $avgRating = $response->getAverageRating() ?>
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400 mr-2">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php if($i <= floor($avgRating)): ?>
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                <?php else: ?>
                                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        </div>
                                        <span class="text-sm text-gray-900"><?php echo e(number_format($avgRating, 1)); ?>/5</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php $satisfaction = $response->getSatisfactionLevel() ?>
                                    <?php if($satisfaction == 'Sangat Puas'): ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <?php echo e($satisfaction); ?>

                                        </span>
                                    <?php elseif($satisfaction == 'Puas'): ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <?php echo e($satisfaction); ?>

                                        </span>
                                    <?php elseif($satisfaction == 'Cukup Puas'): ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <?php echo e($satisfaction); ?>

                                        </span>
                                    <?php elseif($satisfaction == 'Tidak Puas'): ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                            <?php echo e($satisfaction); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            <?php echo e($satisfaction); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($response->getDurationInMinutes()); ?> menit
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($response->completed_at->format('d/m/Y H:i')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="<?php echo e(route('admin.responses.show', $response)); ?>"
                                           class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <form method="POST" action="<?php echo e(route('admin.responses.destroy', $response)); ?>"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus respon ini?')"
                                              class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada respon</h3>
                                    <p class="text-gray-500 mb-4">Belum ada respon survei yang tersimpan.</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if($responses->hasPages()): ?>
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <?php echo e($responses->appends(request()->query())->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function toggleExportDropdown() {
            const dropdown = document.getElementById('exportDropdown');
            dropdown.classList.toggle('hidden');
        }

        function exportResponses(format) {
            const url = '<?php echo e(route("admin.responses.export")); ?>' + '?format=' + format + window.location.search.replace('?', '&');
            window.open(url, '_blank');
            // Hide dropdown after selection
            document.getElementById('exportDropdown').classList.add('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('exportDropdown');
            const button = event.target.closest('button');
            if (!button || !button.onclick || !button.onclick.toString().includes('toggleExportDropdown')) {
                if (!dropdown.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            }
        });
    </script>
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
<?php /**PATH C:\laragon\www\skalafa-system\resources\views/admin/responses/index.blade.php ENDPATH**/ ?>