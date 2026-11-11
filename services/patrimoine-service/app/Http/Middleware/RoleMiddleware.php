<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $userRole = $request->attributes->get('user_role');

        if (!$userRole) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié',
            ], 401);
        }

        // Vérifier les rôles multiples (séparés par virgule)
        $allowedRoles = explode(',', $role);

        if (!in_array($userRole, $allowedRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Autorisation insuffisante',
            ], 403);
        }

        return $next($request);
    }
}
