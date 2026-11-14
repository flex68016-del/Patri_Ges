<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Gestion du Patrimoine'); ?></title>
    <style>
        body { font-family: sans-serif; }
        .container { max-width: 400px; margin: 100px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; }
        button { width: 100%; padding: 10px; background: #2563eb; color: white; border: none; }
    </style>
</head>
<body>
    <?php if(session()->has('user')): ?>
        <nav style="padding: 20px; background: #f3f4f6;">
            <a href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
            <a href="<?php echo e(route('logout')); ?>">Deconnexion</a>
        </nav>
    <?php endif; ?>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
</body>
</html>
<?php /**PATH /app/resources/views/layouts/app.blade.php ENDPATH**/ ?>