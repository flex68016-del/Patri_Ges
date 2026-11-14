<?php $__env->startSection('title', 'Detail du bien'); ?>

<?php $__env->startSection('content'); ?>
<div>
    <div class="mb-8">
        <a href="<?php echo e(route('biens.index')); ?>" class="text-[#047857] hover:text-[#065f46]">← Retour aux biens</a>
    </div>

    <h1 class="text-3xl font-semibold text-[#1f2937] mb-8">Detail du bien #<?php echo e($id); ?></h1>

    <div class="bg-white rounded-md shadow-sm p-6">
        <p class="text-[#6b7280]">Details du bien a venir.</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/resources/views/biens/show.blade.php ENDPATH**/ ?>