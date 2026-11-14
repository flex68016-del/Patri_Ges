<?php $__env->startSection('title', 'Biens'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Biens</h1>
        <a href="<?php echo e(route('biens.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Nouveau bien
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500">Aucun bien enregistre.</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/resources/views/biens/index.blade.php ENDPATH**/ ?>