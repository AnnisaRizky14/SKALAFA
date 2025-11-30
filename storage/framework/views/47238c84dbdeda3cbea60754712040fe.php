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
                Dashboard Admin SKALAFA
            </h2>
            <div class="text-sm text-gray-600">
                <span id="current-date-time"><?php echo e(date('d F Y, H:i')); ?> WIB</span>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-12">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Responses Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-600">Total Respon</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo e(number_format($stats['total_responses'])); ?></p>
                                <p class="text-xs text-gray-500 mt-1">Survei yang telah selesai</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Responses Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-600">Respon Bulan Ini</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo e(number_format($stats['responses_this_month'])); ?></p>
                                <?php
                                    $previousMonth = \App\Models\Response::completed()
                                        ->whereMonth('completed_at', now()->subMonth()->month)
                                        ->whereYear('completed_at', now()->subMonth()->year)
                                        ->count();
                                    $growth = $previousMonth > 0 ? (($stats['responses_this_month'] - $previousMonth) / $previousMonth) * 100 : 0;
                                ?>
                                <p class="text-xs mt-1 flex items-center">
                                    <?php if($growth > 0): ?>
                                        <svg class="w-3 h-3 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                                        </svg>
                                        <span class="text-green-600">+<?php echo e(number_format($growth, 1)); ?>%</span>
                                    <?php elseif($growth < 0): ?>
                                        <svg class="w-3 h-3 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10"></path>
                                        </svg>
                                        <span class="text-red-600"><?php echo e(number_format($growth, 1)); ?>%</span>
                                    <?php else: ?>
                                        <span class="text-gray-500">0%</span>
                                    <?php endif; ?>
                                    <span class="text-gray-500 ml-1">dari bulan lalu</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Questionnaires Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-600">Kuisioner Aktif</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo e($stats['active_questionnaires']); ?></p>
                                <p class="text-xs text-gray-500 mt-1">Tersedia untuk diisi</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Average Satisfaction Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-600">Rata-rata Kepuasan</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo e(number_format($stats['average_satisfaction'], 2)); ?></p>
                                <div class="flex items-center mt-1">
                                    <div class="flex text-yellow-400 mr-2">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= floor($stats['average_satisfaction'])): ?>
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
                                    <span class="text-xs text-gray-500">dari 5</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Quick Actions -->
            <div class="mt-8 bg-white shadow-lg rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Aksi Cepat</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="<?php echo e(route('admin.questionnaires.create')); ?>"
                       class="flex items-center p-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="font-medium">Buat Kuisioner</span>
                    </a>

                    <a href="<?php echo e(route('admin.faculties.index')); ?>"
                       class="flex items-center p-4 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="font-medium">Kelola Fakultas</span>
                    </a>

                    <a href="<?php echo e(route('admin.responses.index')); ?>"
                    class="flex items-center p-4 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 <?php echo e(request()->routeIs('admin.responses.*') ? 'ring-2 ring-offset-2 ring-green-500' : ''); ?>">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="font-medium">Lihat Respon</span>
                    </a>

                    <a href="<?php echo e(route('admin.questionnaires.index')); ?>"
                       class="flex items-center p-4 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span class="font-medium">Lihat Survey</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Faculty Satisfaction Chart -->
                <div class="bg-white shadow-lg rounded-xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Kepuasan per Fakultas</h3>
                        <div class="text-sm text-gray-500">
                            <select id="faculty-chart-period" class="border border-gray-300 rounded px-2 py-1 text-sm">
                                <option value="all">Semua Waktu</option>
                                <option value="month">Bulan Ini</option>
                                <option value="quarter">3 Bulan Terakhir</option>
                            </select>
                        </div>
                    </div>
                    <div class="relative h-80">
                        <canvas id="faculty-satisfaction-chart"></canvas>
                    </div>
                </div>

                <!-- Monthly Responses Chart -->
                <div class="bg-white shadow-lg rounded-xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Trend Respon Bulanan</h3>
                        <span class="text-sm text-gray-500">12 Bulan Terakhir</span>
                    </div>
                    <div class="relative h-80">
                        <canvas id="monthly-responses-chart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Bottom Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Satisfaction Distribution -->
                <div class="bg-white shadow-lg rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Distribusi Kepuasan</h3>
                    <div class="relative h-64">
                        <canvas id="satisfaction-distribution-chart"></canvas>
                    </div>
                    <div class="mt-4 space-y-2">
                        <?php
                            $ratingColors = ['#da1717ff', '#f8aa24ff', '#5a5d61ff', '#2777f8ff', '#2dcf43ff'];
                            $ratingLabels = ['Sangat Tidak Puas', 'Tidak Puas', 'Cukup Puas', 'Puas', 'Sangat Puas'];
                        ?>
                        <?php $__currentLoopData = $ratingLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-2" style="background-color: <?php echo e($ratingColors[$index]); ?>"></div>
                                <span><?php echo e($label); ?></span>
                            </div>
                            <span class="font-medium"><?php echo e($chartsData['satisfaction_distribution'][$index + 1] ?? 0); ?></span>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Subcategory Ratings -->
                <div class="bg-white shadow-lg rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Rating per Kategori</h3>
                    <div class="relative h-64">
                        <canvas id="subcategory-ratings-chart"></canvas>
                    </div>
                    <div class="mt-4">
                        <?php $__currentLoopData = $chartsData['subcategory_ratings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between text-sm py-1">
                            <span class="text-gray-600"><?php echo e($subcategory['name']); ?></span>
                            <span class="font-medium text-gray-800"><?php echo e(number_format($subcategory['average_rating'], 1)); ?>/5</span>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Recent Responses -->
                <div class="bg-white shadow-lg rounded-xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Respon Terbaru</h3>
                        <a href="<?php echo e(route('admin.responses.index')); ?>" class="text-sm text-primary hover:underline">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="space-y-4 max-h-64 overflow-y-auto">
                        <?php $__empty_1 = true; $__currentLoopData = $recentResponses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $response): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">
                                    <?php echo e($response->questionnaire->title); ?>

                                </p>
                                <p class="text-xs text-gray-600">
                                    <?php echo e($response->faculty->short_name); ?> •
                                    <?php echo e($response->completed_at->diffForHumans()); ?>

                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="flex text-yellow-400">
                                    <?php $rating = $response->getAverageRating() ?>
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
                                <span class="text-xs text-gray-600"><?php echo e(number_format($rating, 1)); ?></span>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                            <p class="text-gray-500 text-sm">Belum ada respon</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/admin/dashboard.css', 'resources/js/admin/dashboard.js']); ?>
    <script id="charts-data-json" type="application/json"><?php echo json_encode($chartsData); ?></script>
    <script>
        // Debug: Log charts data to console
        try {
            const chartsDataDebug = JSON.parse(document.getElementById('charts-data-json').textContent);
            console.log('Charts Data:', chartsDataDebug);
        } catch (e) {
            console.error('Error parsing charts data:', e);
        }
    </script>
    <script>
        function updateTime() {
            const now = new Date().toLocaleString('id-ID', { 
                timeZone: 'Asia/Jakarta', 
                day: '2-digit', 
                month: 'long', 
                year: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit', 
                hour12: false 
            });
            document.getElementById('current-date-time').textContent = now + ' WIB';
        }
        setInterval(updateTime, 1000);
        updateTime(); // Initial call
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
<?php /**PATH C:\laragon\www\skalafa-system\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>