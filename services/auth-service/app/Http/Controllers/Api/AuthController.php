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
        $user = User::where('email', $request->email)->where('actif', true)->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            return response()->json([
                'error' => 'Identifiants invalides',
            ], 401);
        }

        $payload = [
            'iss' => config('app.url'),
            'iat' => time(),
            'exp' => time() + 8 * 60 * 60, // 8 heures
            'sub' => $user->id,
            'role' => $user->role,
            'service_id' => $user->service_id,
        ];

        $privateKey = file_get_contents(storage_path('app/jwt/private.pem'));
        $token = JWT::encode($payload, $privateKey, 'RS256');

        Connexion::create([
            'utilisateur_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'connecte_at' => now(),
        ]);

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'nom' => $user->nom,
                'role' => $user->role,
                'service_id' => $user->service_id,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $connexion = Connexion::where('utilisateur_id', $user->id)
            ->whereNull('deconnecte_at')
            ->latest()
            ->first();

        if ($connexion) {
            $connexion->update(['deconnecte_at' => now()]);
        }

        return response()->json(['message' => 'Déconnexion réussie']);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => [
                'id' => $request->user()->id,
                'email' => $request->user()->email,
                'nom' => $request->user()->nom,
                'role' => $request->user()->role,
                'service_id' => $request->user()->service_id,
            ],
        ]);
    }
}
