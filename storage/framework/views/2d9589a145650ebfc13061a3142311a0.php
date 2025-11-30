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
                Detail Pengaduan
            </h2>
            <a href="<?php echo e(route('admin.complaints.index')); ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Complaint Header -->
                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900"><?php echo e($complaint->title); ?></h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Diterima pada <?php echo e($complaint->created_at->format('d F Y, H:i')); ?>

                                </p>
                            </div>
                            <div class="text-right">
                                <?php if($complaint->status === 'pending'): ?>
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Tertunda
                                    </span>
                                <?php elseif($complaint->status === 'in_progress'): ?>
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Dalam Proses
                                    </span>
                                <?php elseif($complaint->status === 'resolved'): ?>
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                        Telah Selesai
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Complaint Details -->
                        <div class="lg:col-span-2">
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Detail Pengaduan</h4>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Judul</label>
                                        <p class="mt-1 text-sm text-gray-900"><?php echo e($complaint->title); ?></p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                        <div class="mt-1 text-sm text-gray-900 bg-white p-3 rounded border">
                                            <?php echo e($complaint->description); ?>

                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Email</label>
                                            <p class="mt-1 text-sm text-gray-900"><?php echo e($complaint->email ?: '-'); ?></p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Status</label>
                                            <p class="mt-1 text-sm text-gray-900">
                                                <?php if($complaint->status === 'pending'): ?>
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Tertunda
                                                    </span>
                                                <?php elseif($complaint->status === 'in_progress'): ?>
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Dalam Proses
                                                    </span>
                                                <?php elseif($complaint->status === 'resolved'): ?>
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        Telah Selesai
                                                    </span>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>

                                    <?php if($complaint->admin_notes): ?>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Catatan Admin</label>
                                        <div class="mt-1 text-sm text-gray-900 bg-white p-3 rounded border">
                                            <?php echo e($complaint->admin_notes); ?>

                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Update Status Form -->
                        <div>
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Perbarui Status Pengaduan</h4>

                                <form method="POST" action="<?php echo e(route('admin.complaints.update', $complaint)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>

                                    <div class="space-y-4">
                                        <div>
                                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                            <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                                <option value="pending" <?php echo e($complaint->status === 'pending' ? 'selected' : ''); ?>>Tertunda</option>
                                                <option value="in_progress" <?php echo e($complaint->status === 'in_progress' ? 'selected' : ''); ?>>Dalam Proses</option>
                                                <option value="resolved" <?php echo e($complaint->status === 'resolved' ? 'selected' : ''); ?>>Telah Selesai</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="admin_notes" class="block text-sm font-medium text-gray-700">Catatan Admin</label>
                                            <textarea name="admin_notes" id="admin_notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" placeholder="Tambahkan catatan untuk pengaduan ini..."><?php echo e($complaint->admin_notes); ?></textarea>
                                        </div>

                                        <button type="submit" class="w-full bg-primary-600 text-white py-2 px-4 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                            Perbarui Status
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Delete Complaint -->
                            <div class="bg-red-50 border border-red-200 rounded-lg p-6 mt-6">
                                <h4 class="text-lg font-semibold text-red-900 mb-4">Bahaya</h4>
                                <p class="text-sm text-red-700 mb-4">
                                    Tindakan ini tidak dapat dibatalkan. Ini akan menghapus pengaduan secara permanen.
                                </p>
                                <form method="POST" action="<?php echo e(route('admin.complaints.destroy', $complaint)); ?>" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini secara permanen?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        Hapus Pengaduan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
<?php /**PATH C:\laragon\www\skalafa-system\resources\views/admin/complaints/show.blade.php ENDPATH**/ ?>