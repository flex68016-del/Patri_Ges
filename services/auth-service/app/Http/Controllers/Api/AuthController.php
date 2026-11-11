<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\Connexion;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->where('statut', 'actif')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants invalides',
            ], 401);
        }

        $payload = [
            'iss' => config('app.url'),
            'iat' => time(),
            'exp' => time() + 8 * 60 * 60, // 8 heures
            'sub' => $user->id,
            'role' => $user->role,
        ];

        // Pour les tests, utiliser HS256 au lieu de RS256
        if (app()->environment('testing')) {
            $secret = config('app.key');
            $token = JWT::encode($payload, $secret, 'HS256');
        } else {
            $privateKey = file_get_contents(storage_path('app/jwt/private.pem'));
            $token = JWT::encode($payload, $privateKey, 'RS256');
        }

        Connexion::create([
            'user_id' => $user->id,
            'ip_adresse' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'statut' => 'actif',
            'connexion_at' => now(),
        ]);

        $user->update(['derniere_connexion_at' => now()]);

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'nom' => $user->nom,
                    'prenom' => $user->prenom,
                    'role' => $user->role,
                ],
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $connexion = Connexion::where('user_id', $user->id)
            ->where('statut', 'actif')
            ->latest()
            ->first();

        if ($connexion) {
            $connexion->update([
                'statut' => 'inactif',
                'deconnexion_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $request->user()->id,
                'email' => $request->user()->email,
                'nom' => $request->user()->nom,
                'prenom' => $request->user()->prenom,
                'role' => $request->user()->role,
                'statut' => $request->user()->statut,
            ],
        ]);
    }
}
