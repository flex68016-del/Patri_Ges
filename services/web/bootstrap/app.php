<?php

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureAuthenticated;
use App\Http\Middleware\EnsureRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias des middlewares metier utilises dans routes/web.php :
        //  - authenticated : session web valide (token + utilisateur)
        //  - admin         : role administrateur uniquement
        //  - role          : liste de roles autorises passee en parametre
        $middleware->alias([
            'authenticated' => EnsureAuthenticated::class,
            'admin' => EnsureAdmin::class,
            'role' => EnsureRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
