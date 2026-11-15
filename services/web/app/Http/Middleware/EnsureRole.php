<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de controle par role : accepte une liste de roles autorises
 * en parametre de route (ex : role:administrateur,gestionnaire).
 * Reflete cote web les permissions definies dans les API.
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Le role vient de la session (pose au login depuis auth-service) ;
        // l'autorisation reelle reste verifiee par les API a chaque appel.
        $userRole = session('user.role') ?? null;

        if (! in_array($userRole, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
