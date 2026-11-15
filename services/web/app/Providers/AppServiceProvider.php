<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    // register() : point d'extension pour lier des services au conteneur.
    // Non utilise actuellement : ApiClient est resolu automatiquement par
    // l'injection de dependances de Laravel.
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
