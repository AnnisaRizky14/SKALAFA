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
                Kelola Kuisioner
            </h2>
            <a href="<?php echo e(route('admin.questionnaires.create')); ?>"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition-colors duration-200">
                Tambah Kuisioner
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-12">
            <!-- Search and Filter -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6">
                <div class="p-6">
                    <form method="GET" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex-1 max-w-md">
                            <div class="flex">
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                       placeholder="Cari kuisioner..."
                                       class="flex-1 border border-gray-300 rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-lg transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <select name="status" onchange="this.form.submit()"
                                    class="border border-gray-300 rounded-lg px-10 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Status</option>
                                <option value="1" <?php echo e(request('status') == '1' ? 'selected' : ''); ?>>Aktif</option>
                                <option value="0" <?php echo e(request('status') == '0' ? 'selected' : ''); ?>>Tidak Aktif</option>
                            </select>
                            <?php if(!auth()->user()->isFacultyAdmin()): ?>
                            <select name="faculty" onchange="this.form.submit()"
                                    class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Fakultas</option>
                                <?php $__currentLoopData = \App\Models\Faculty::active()->ordered()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faculty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($faculty->id); ?>" <?php echo e(request('faculty') == $faculty->id ? 'selected' : ''); ?>>
                                        <?php echo e($faculty->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Questionnaires List -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6">
                <!-- Mobile View -->
                <div class="block md:hidden space-y-4">
                    <?php $__empty_1 = true; $__currentLoopData = $questionnaires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $questionnaire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center">
                                <?php $bgColor = $questionnaire->faculty->color ?? '#003f7f'; $style = 'style="background-color: ' . $bgColor . '"'; ?>
                                <div class="flex-shrink-0 h-10 w-10 rounded-lg flex items-center justify-center text-white font-bold text-sm"
                                     <?php echo $style; ?>>
                                    <?php echo e(substr($questionnaire->title, 0, 1)); ?>

                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo e($questionnaire->title); ?>

                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?php echo e($questionnaire->questions->count()); ?> pertanyaan • <?php echo e($questionnaire->estimated_duration); ?> menit
                                    </div>
                                </div>
                            </div>
                            <span class="status-badge inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo e($questionnaire->is_active ? ($questionnaire->isAvailable() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') : 'bg-red-100 text-red-800'); ?> ml-2">
                                <?php echo e($questionnaire->is_active ? ($questionnaire->isAvailable() ? 'Aktif' : 'Tidak Tersedia') : 'Tidak Aktif'); ?>

                            </span>
                        </div>

                        <div class="space-y-2 mb-3">
                            <div class="text-xs text-gray-600">
                                <strong>Fakultas:</strong> <?php echo e($questionnaire->faculty->name); ?>

                            </div>
                            <div class="text-xs text-gray-600">
                                <strong>Respon:</strong> <?php echo e($questionnaire->responses->count()); ?>

                                <?php if($questionnaire->completedResponses->count() > 0): ?>
                                    (<?php echo e($questionnaire->completedResponses->count()); ?> selesai)
                                <?php endif; ?>
                            </div>
                            <?php if($questionnaire->completedResponses->count() > 0): ?>
                            <div class="text-xs text-gray-600">
                                <strong>Rating:</strong>
                                <div class="flex items-center mt-1">
                                    <div class="flex text-yellow-400 mr-1">
                                        <?php $rating = $questionnaire->getAverageRating() ?>
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= floor($rating)): ?>
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            <?php else: ?>
                                                <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="text-xs text-gray-500"><?php echo e(number_format($rating, 1)); ?>/5</span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex space-x-2">
                                <a href="<?php echo e(route('admin.questionnaires.preview', $questionnaire)); ?>"
                                   class="text-purple-600 hover:text-purple-900 p-1" title="Preview">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="<?php echo e(route('admin.questionnaires.show', $questionnaire)); ?>"
                                   class="text-indigo-600 hover:text-indigo-900 p-1" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </a>
                                <a href="<?php echo e(route('admin.questionnaires.edit', $questionnaire)); ?>"
                                   class="text-yellow-600 hover:text-yellow-900 p-1" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <button type="button"
                                        class="toggle-status-btn text-green-600 hover:text-green-900 p-1"
                                        data-id="<?php echo e($questionnaire->id); ?>"
                                        data-active="<?php echo e($questionnaire->is_active); ?>"
                                        title="<?php echo e($questionnaire->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>">
                                    <?php if($questionnaire->is_active): ?>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                        </svg>
                                    <?php else: ?>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    <?php endif; ?>
                                </button>
                            </div>
                            <form method="POST" action="<?php echo e(route('admin.questionnaires.destroy', $questionnaire)); ?>"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuisioner ini?')"
                                  class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-900 p-1" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kuisioner</h3>
                        <p class="text-gray-500 mb-4">Mulai dengan membuat kuisioner pertama.</p>
                        <a href="<?php echo e(route('admin.questionnaires.create')); ?>"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Kuisioner
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block">
                    <div class="overflow-x-auto min-w-full">
                        <table class="min-w-full divide-y divide-gray-200 table-auto w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-2/5 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kuisioner
                                </th>
                                <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fakultas
                                </th>
                                <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Respon
                                </th>
                                <th class="w-1/6 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rating Rata-rata
                                </th>
                                <th class="w-1/6 px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__empty_1 = true; $__currentLoopData = $questionnaires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $questionnaire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <?php $bgColor = $questionnaire->faculty->color ?? '#003f7f'; $style = 'style="background-color: ' . $bgColor . '"'; ?>
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-lg flex items-center justify-center text-white font-bold text-sm"
                                                 <?php echo $style; ?>>
                                                <?php echo e(substr($questionnaire->title, 0, 1)); ?>

                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo e($questionnaire->title); ?>

                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?php echo e($questionnaire->questions->count()); ?> pertanyaan • <?php echo e($questionnaire->estimated_duration); ?> menit
                                            </div>
                                            <?php if($questionnaire->description): ?>
                                                <div class="text-xs text-gray-400 truncate max-w-xs">
                                                    <?php echo e($questionnaire->description); ?>

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900"><?php echo e($questionnaire->faculty->name); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo e($questionnaire->is_active ? ($questionnaire->isAvailable() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') : 'bg-red-100 text-red-800'); ?>">
                                        <?php echo e($questionnaire->is_active ? ($questionnaire->isAvailable() ? 'Aktif & Tersedia' : 'Aktif & Tidak Tersedia') : 'Tidak Aktif'); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($questionnaire->responses->count()); ?>

                                    <?php if($questionnaire->completedResponses->count() > 0): ?>
                                        <span class="text-gray-500">
                                            (<?php echo e($questionnaire->completedResponses->count()); ?> selesai)
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php if($questionnaire->completedResponses->count() > 0): ?>
                                        <div class="flex items-center">
                                            <div class="flex text-yellow-400 mr-2">
                                                <?php $rating = $questionnaire->getAverageRating() ?>
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <?php if($i <= floor($rating)): ?>
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    <?php else: ?>
                                                        <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </div>
                                            <span class="text-xs text-gray-500"><?php echo e(number_format($rating, 1)); ?>/5</span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="<?php echo e(route('admin.questionnaires.preview', $questionnaire)); ?>"
                                           class="text-purple-600 hover:text-purple-900" title="Preview">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="<?php echo e(route('admin.questionnaires.show', $questionnaire)); ?>"
                                           class="text-indigo-600 hover:text-indigo-900" title="Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </a>
                                        <a href="<?php echo e(route('admin.questionnaires.edit', $questionnaire)); ?>"
                                           class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <button type="button"
                                                class="toggle-status-btn text-green-600 hover:text-green-900"
                                                data-id="<?php echo e($questionnaire->id); ?>"
                                                data-active="<?php echo e($questionnaire->is_active); ?>"
                                                title="<?php echo e($questionnaire->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>">
                                            <?php if($questionnaire->is_active): ?>
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                                </svg>
                                            <?php else: ?>
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            <?php endif; ?>
                                        </button>
                                        <form method="POST" action="<?php echo e(route('admin.questionnaires.destroy', $questionnaire)); ?>"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuisioner ini?')"
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
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kuisioner</h3>
                                    <p class="text-gray-500 mb-4">Mulai dengan membuat kuisioner pertama.</p>
                                    <a href="<?php echo e(route('admin.questionnaires.create')); ?>"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Tambah Kuisioner
                                    </a>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if($questionnaires->hasPages()): ?>
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <?php echo e($questionnaires->appends(request()->query())->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-status-btn');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const questionnaireId = this.getAttribute('data-id');
                    const isActive = this.getAttribute('data-active') === '1';

                    // Show confirmation with better warning for deactivation
                    const action = isActive ? 'menonaktifkan' : 'mengaktifkan';
                    let confirmMessage = `Apakah Anda yakin ingin ${action} kuisioner ini?`;

                    if (isActive) {
                        confirmMessage += '\n\nPeringatan: Menonaktifkan kuisioner akan membuatnya tidak tersedia untuk diisi responden. Kuisioner yang sudah dinonaktifkan tidak akan muncul di daftar survei yang tersedia.';
                    }

                    if (!confirm(confirmMessage)) {
                        return;
                    }

                    // Disable button during request
                    this.disabled = true;
                    this.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';

                    // Send AJAX request
                    fetch(`/admin/questionnaires/${questionnaireId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => {
                        // Check if response is ok
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Update status badge
                            const statusBadge = this.closest('tr').querySelector('.status-badge');
                            statusBadge.textContent = data.status_text;
                            statusBadge.className = `status-badge inline-flex px-2 py-1 text-xs font-semibold rounded-full ${data.status_class}`;

                            // Update button icon and data attributes
                            this.setAttribute('data-active', data.is_active ? '1' : '0');
                            this.setAttribute('title', data.is_active ? 'Nonaktifkan' : 'Aktifkan');

                            const iconContainer = this.querySelector('svg');
                            if (data.is_active) {
                                iconContainer.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>';
                            } else {
                                iconContainer.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                            }

                            // Show success message using toast if available
                            if (typeof showToast === 'function') {
                                showToast(data.message, 'success');
                            } else {
                                alert(data.message);
                            }

                            // Reload page after short delay to reflect changes
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            const errorMsg = data.message || 'Terjadi kesalahan saat mengubah status kuisioner.';
                            if (typeof showToast === 'function') {
                                showToast(errorMsg, 'error');
                            } else {
                                alert(errorMsg);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const errorMsg = 'Terjadi kesalahan saat mengubah status kuisioner. Silakan coba lagi.';
                        if (typeof showToast === 'function') {
                            showToast(errorMsg, 'error');
                        } else {
                            alert(errorMsg);
                        }
                    })
                    .finally(() => {
                        // Re-enable button and restore original icon
                        this.disabled = false;
                        const isActive = this.getAttribute('data-active') === '1';
                        if (isActive) {
                            this.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path></svg>';
                        } else {
                            this.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                        }
                    });
                });
            });
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
<?php /**PATH C:\laragon\www\skalafa-system\resources\views/admin/questionnaires/index.blade.php ENDPATH**/ ?>