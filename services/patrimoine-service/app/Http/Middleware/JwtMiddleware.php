<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware JWT : verifie la signature RS256 du token, puis
 * revalide l'utilisateur en appelant GET /api/me d'auth-service.
 * Ce double controle garantit qu'un token vole devient inutilisable
 * des qu'un compte est desactive. Le role et l'id sont exposes aux
 * controleurs via les attributs de requete.
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
            // Verification cryptographique locale : rapide, sans reseau.
            // En test, HS256 avec APP_KEY evite de manipuler les cles RSA.
            if (app()->environment('testing')) {
                $secret = config('app.key');
                $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            } else {
                $publicKey = file_get_contents(storage_path('app/jwt/public.pem'));
                $decoded = JWT::decode($token, new Key($publicKey, 'RS256'));
            }

            // Revalidation distante : auth-service confirme que le compte
            // existe toujours et est actif (revocation quasi immediate).
            $response = Http::withToken($token)
                ->get(env('AUTH_SERVICE_URL', 'http://localhost:8001').'/api/me');

            if (! $response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token invalide ou utilisateur introuvable',
                ], 401);
            }

            $userData = $response->json();

            // On propage l'identite a la suite de la requete : le
            // RoleMiddleware lira user_role, les controleurs user_id
            // pour tracer l'auteur des mouvements.
            $request->attributes->set('user_id', $decoded->sub);
            $request->attributes->set('user_role', $decoded->role);
            $request->attributes->set('user_data', $userData['data']);

            return $next($request);
        } catch (\Exception $e) {
            // Signature invalide, expiration ou reponse auth illisible :
            // tout echec d'authentification retourne un 401 uniforme.
            return response()->json([
                'success' => false,
                'message' => 'Token invalide ou expiré',
            ], 401);
        }
    }
}
