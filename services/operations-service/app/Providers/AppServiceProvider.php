<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Fournisseur de services : aucun binding specifique n'est requis,
// l'injection de dependances de Laravel suffit pour ce service.
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
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
