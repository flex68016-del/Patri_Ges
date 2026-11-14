<?php $__env->startSection('title', 'Nouvelle maintenance'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="<?php echo e(route('maintenances.index')); ?>" class="text-blue-600 hover:text-blue-700">← Retour aux maintenances</a>
    </div>

    <h1 class="text-3xl font-bold text-gray-900 mb-8">Nouvelle maintenance</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bien</label>
                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    <option>Selectionner un bien</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de maintenance</label>
                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    <option>Preventive</option>
                    <option>Corrective</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                Enregistrer
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/resources/views/maintenances/create.blade.php ENDPATH**/ ?>