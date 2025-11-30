<?php $__env->startSection('content'); ?>
<div class="min-h-screen gradient-bg">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="<?php echo e(asset('unib-logo.png')); ?>" alt="Logo UNIB" class="h-20 w-auto">
                    <div>
                        <h1 class="text-2xl font-bold text-primary">SKALAFA</h1>
                        <p class="text-sm text-gray-600">Survei Kepuasan Layanan Fakultas</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Universitas Bengkulu</p>
                    <p class="text-xs text-gray-500"><?php echo e(date('d F Y')); ?></p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8 md:py-12">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
                <div class="mb-6 md:mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-primary mb-4">Kirim Pengaduan</h2>
                    <p class="text-gray-600 text-sm md:text-base">Berikan masukan atau keluhan Anda untuk membantu kami meningkatkan kualitas layanan.</p>
                </div>

                <form action="<?php echo e(route('complaints.store')); ?>" method="POST" class="space-y-4 md:space-y-6">
                    <?php echo csrf_field(); ?>

                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo e(old('nama_lengkap')); ?>" required
                               class="w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm md:text-base">
                        <?php $__errorArgs = ['nama_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required
                               class="w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm md:text-base">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori Pengaduan</label>
                        <select id="category" name="category" required
                                class="w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm md:text-base">
                            <option value="">Pilih Kategori</option>
                            <option value="Infrastruktur" <?php echo e(old('category') == 'Infrastruktur' ? 'selected' : ''); ?>>Infrastruktur</option>
                            <option value="Layanan" <?php echo e(old('category') == 'Layanan' ? 'selected' : ''); ?>>Layanan</option>
                            <option value="Akademik" <?php echo e(old('category') == 'Akademik' ? 'selected' : ''); ?>>Akademik</option>
                            <option value="Fasilitas" <?php echo e(old('category') == 'Fasilitas' ? 'selected' : ''); ?>>Fasilitas</option>
                            <option value="Sumber Daya Manusia" <?php echo e(old('category') == 'Sumber Daya Manusia' ? 'selected' : ''); ?>>Sumber Daya Manusia</option>
                        </select>
                        <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Pengaduan</label>
                        <textarea id="description" name="description" rows="6" required
                                  class="w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent resize-vertical text-sm md:text-base"
                                  placeholder="Jelaskan pengaduan atau saran Anda secara detail..."><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 justify-between">
                        <a href="<?php echo e(route('complaints.index')); ?>" class="px-4 md:px-6 py-2 md:py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-center text-sm md:text-base">
                            Batal
                        </a>
                        <button type="submit" class="px-6 md:px-8 py-2 md:py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-semibold text-sm md:text-base">
                            Kirim Pengaduan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\skalafa-system\resources\views/complaints/create.blade.php ENDPATH**/ ?>