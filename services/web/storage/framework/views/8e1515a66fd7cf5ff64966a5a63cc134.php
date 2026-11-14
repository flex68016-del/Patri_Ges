<?php $__env->startSection('title', 'Tableau de bord'); ?>

<?php $__env->startSection('content'); ?>
<div>
    <h1 class="text-3xl font-semibold text-[#1f2937] mb-8">Tableau de bord</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-md shadow-sm p-6">
            <div class="text-sm font-medium text-[#6b7280]">Total Biens</div>
            <div class="mt-2 text-3xl font-semibold text-[#1f2937]">0</div>
        </div>
        <div class="bg-white rounded-md shadow-sm p-6">
            <div class="text-sm font-medium text-[#6b7280]">Maintenances en cours</div>
            <div class="mt-2 text-3xl font-semibold text-[#1f2937]">0</div>
        </div>
        <div class="bg-white rounded-md shadow-sm p-6">
            <div class="text-sm font-medium text-[#6b7280]">Inventaires</div>
            <div class="mt-2 text-3xl font-semibold text-[#1f2937]">0</div>
        </div>
        <div class="bg-white rounded-md shadow-sm p-6">
            <div class="text-sm font-medium text-[#6b7280]">Anomalies</div>
            <div class="mt-2 text-3xl font-semibold text-[#1f2937]">0</div>
        </div>
    </div>

    <div class="bg-white rounded-md shadow-sm p-6">
        <h2 class="text-xl font-semibold text-[#1f2937] mb-4">Activites recentes</h2>
        <p class="text-[#6b7280]">Aucune activite recente.</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /app/resources/views/dashboard.blade.php ENDPATH**/ ?>