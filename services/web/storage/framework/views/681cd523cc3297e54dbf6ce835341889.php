<?php $__env->startSection('title', 'Detail du bien'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="<?php echo e(route('biens.index')); ?>" class="text-blue-600 hover:text-blue-700">← Retour aux biens</a>
    </div>

    <h1 class="text-3xl font-bold text-gray-900 mb-8">Detail du bien #<?php echo e($id); ?></h1>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500">Details du bien a venir.</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/resources/views/biens/show.blade.php ENDPATH**/ ?>