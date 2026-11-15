<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware JWT : verifie la signature RS256 localement puis
 * revalide l'utilisateur via GET /api/me d'auth-service. Le role et
 * l'identifiant sont exposes aux controleurs via les attributs de
 * requete (user_id, user_role, user_data).
 */
class JwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Token manquant',
            ], 401);
        }

        try {
            // Verification cryptographique locale, puis revalidation
            // distante pour rejeter les tokens de comptes desactives.
            if (app()->environment('testing')) {
                $secret = config('app.key');
                $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            } else {
                $publicKey = file_get_contents(storage_path('app/jwt/public.pem'));
                $decoded = JWT::decode($token, new Key($publicKey, 'RS256'));
            }

            $response = Http::withToken($token)
                ->get(env('AUTH_SERVICE_URL', 'http://localhost:8001').'/api/me');

            if (! $response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token invalide ou utilisateur introuvable',
                ], 401);
            }

            $userData = $response->json();

            // Propagation de l'identite pour la suite du pipeline.
            $request->attributes->set('user_id', $decoded->sub);
            $request->attributes->set('user_role', $decoded->role);
            $request->attributes->set('user_data', $userData['data']);

            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token invalide ou expiré',
            ], 401);
        }
    }
}
