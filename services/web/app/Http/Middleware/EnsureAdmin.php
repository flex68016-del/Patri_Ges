<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware d'autorisation : restreint l'acces aux administrateurs.
 * Utilise pour les routes /admin/* qui gerent utilisateurs et referentiels.
 */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ((session('user.role') ?? null) !== 'administrateur') {
            abort(403);
        }

        return $next($request);
    }
}
