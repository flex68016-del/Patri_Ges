<?php $__env->startSection('title', 'Modifier le bien'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="<?php echo e(route('biens.index')); ?>" class="text-blue-600 hover:text-blue-700">← Retour aux biens</a>
    </div>

    <h1 class="text-3xl font-bold text-gray-900 mb-8">Modifier le bien #<?php echo e($id); ?></h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Designation</label>
                <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                Mettre a jour
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/resources/views/biens/edit.blade.php ENDPATH**/ ?>