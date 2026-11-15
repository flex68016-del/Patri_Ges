<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware JWT : verifie la signature RS256 du token avec la cle
 * publique, controle l'expiration et recharge l'utilisateur en base
 * pour rejeter les comptes desactives. Utilise sur toutes les routes
 * /api/* protegees de ce service.
 */
class JwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json(['error' => 'Token manquant'], 401);
        }

        try {
            // La cle publique ne peut que verifier : elle ne signe jamais,
            // ce qui permet de la distribuer aux autres microservices.
            $publicKey = file_get_contents(storage_path('app/jwt/public.pem'));
            $decoded = JWT::decode($token, new Key($publicKey, 'RS256'));

            // Le "sub" du token désigne l'utilisateur : on le recharge pour
            // verifier qu'il existe toujours et qu'il est actif.
            $user = User::find($decoded->sub);

            if (! $user || $user->statut !== 'actif') {
                return response()->json(['error' => 'Utilisateur invalide ou inactif'], 401);
            }

            // On injecte l'utilisateur resolu dans la requete : les
            // controleurs y accèdent ensuite via $request->user().
            $request->setUserResolver(fn () => $user);

            return $next($request);
        } catch (ExpiredException $e) {
            return response()->json(['error' => 'Token expiré'], 401);
        } catch (SignatureInvalidException $e) {
            return response()->json(['error' => 'Token invalide'], 401);
        } catch (\Exception $e) {
            // Toute autre erreur de decodage (format, algorithme) est
            // ramenee a un 401 generique sans detailler la cause.
            return response()->json(['error' => 'Erreur d\'authentification'], 401);
        }
    }
}
