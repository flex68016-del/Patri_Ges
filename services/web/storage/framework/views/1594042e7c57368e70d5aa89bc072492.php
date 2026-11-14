<?php $__env->startSection('title', 'Maintenances'); ?>

<?php $__env->startSection('content'); ?>
<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-[#1f2937]">Maintenances</h1>
        <a href="<?php echo e(route('maintenances.create')); ?>" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
            Nouvelle maintenance
        </a>
    </div>

    <div class="bg-white rounded-md shadow-sm p-6">
        <p class="text-[#6b7280]">Aucune maintenance enregistree.</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/resources/views/maintenances/index.blade.php ENDPATH**/ ?>