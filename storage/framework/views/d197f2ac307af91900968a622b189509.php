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
                Notifikasi
            </h2>
            <?php if($unreadCount > 0): ?>
                <button id="mark-all-read" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Tandai Semua Dibaca
                </button>
            <?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-start p-4 border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200 <?php echo e($notification->is_read ? 'opacity-75' : ''); ?>"
                             data-notification-id="<?php echo e($notification->id); ?>">
                        <div class="flex-shrink-0 mr-2">
                            <?php if(!$notification->is_read): ?>
                                <div class="w-3 h-3 bg-red-500 rounded-full mt-2"></div>
                            <?php else: ?>
                                <!-- No dot when read -->
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 flex items-center space-x-2">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800"><?php echo e($notification->title); ?></p>
                                        <p class="text-sm text-gray-600 mt-1"><?php echo e($notification->message); ?></p>
                                        <p class="text-xs text-gray-500 mt-2"><?php echo e($notification->created_at->diffForHumans()); ?></p>
                                    </div>
                                    <?php if($notification->is_read): ?>
                                        <span class="text-xs text-gray-400 italic">Telah dibaca</span>
                                    <?php endif; ?>
                                </div>
                                <?php if(!$notification->is_read): ?>
                                    <button class="mark-read-btn text-xs text-primary hover:text-primary-dark ml-3 flex-shrink-0 px-3 py-1 border border-primary rounded">
                                        Tandai Dibaca
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="text-center py-12">
    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4"
         xmlns="http://www.w3.org/2000/svg"
         viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="2"
         stroke-linecap="round"
         stroke-linejoin="round">
        <path d="M18 8a6 6 0 10-12 0v4l-2 3h16l-2-3V8z" />
        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
        <line x1="4" y1="4" x2="20" y2="20" />
    </svg>

    <p class="text-gray-500 text-lg">Belum ada notifikasi</p>
</div>

                    <?php endif; ?>

                    <?php if($notifications->hasPages()): ?>
                        <div class="mt-6">
                            <?php echo e($notifications->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mark single notification as read
            document.querySelectorAll('.mark-read-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const notificationId = this.closest('[data-notification-id]').dataset.notificationId;

                    fetch(`/admin/notifications/${notificationId}/read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
                });
            });

            // Mark all notifications as read
            document.getElementById('mark-all-read')?.addEventListener('click', function() {
                fetch('/admin/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
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
<?php /**PATH C:\laragon\www\skalafa-system\resources\views/admin/notifications/index.blade.php ENDPATH**/ ?>