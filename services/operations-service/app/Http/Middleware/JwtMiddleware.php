<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

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
            // Pour les tests, utiliser HS256
            if (app()->environment('testing')) {
                $secret = config('app.key');
                $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            } else {
                $publicKey = file_get_contents(storage_path('app/jwt/public.pem'));
                $decoded = JWT::decode($token, new Key($publicKey, 'RS256'));
            }

            // Valider le token avec auth-service
            $response = Http::withToken($token)
                ->get(env('AUTH_SERVICE_URL', 'http://localhost:8001').'/api/me');

            if (! $response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token invalide ou utilisateur introuvable',
                ], 401);
            }

            $userData = $response->json();

            // Ajouter les données utilisateur à la requête
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
