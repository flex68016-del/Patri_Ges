<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware d'authentification : verifie qu'une session valide existe
 * (token JWT + utilisateur). Applique a toutes les routes protegees.
 */
class EnsureAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        // Sans session complete, on renvoie vers le login au lieu d'un 401
        // car il s'agit d'une interface web (navigation navigateur).
        if (! session('user') || ! session('token')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
