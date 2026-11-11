<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token manquant'], 401);
        }

        try {
            $publicKey = file_get_contents(storage_path('app/jwt/public.pem'));
            $decoded = JWT::decode($token, new Key($publicKey), 'RS256');

            $user = User::find($decoded->sub);

            if (!$user || !$user->actif) {
                return response()->json(['error' => 'Utilisateur invalide ou inactif'], 401);
            }

            $request->setUser($user);

            return $next($request);
        } catch (ExpiredException $e) {
            return response()->json(['error' => 'Token expiré'], 401);
        } catch (SignatureInvalidException $e) {
            return response()->json(['error' => 'Token invalide'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur d\'authentification'], 401);
        }
    }
}
