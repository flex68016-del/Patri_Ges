<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Connexion;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Controleur d'authentification : point d'entree unique pour le login,
 * la deconnexion, le profil courant et la gestion des comptes.
 * C'est ce service qui signe les JWT (cle privee RS256).
 */
class AuthController extends Controller
{
    /**
     * Authentifie un utilisateur : verifie le mot de passe (bcrypt),
     * signe un JWT de 8 heures et journalise la connexion.
     */
    public function login(LoginRequest $request)
    {
        // Seuls les comptes au statut "actif" peuvent se connecter :
        // les comptes suspendus ou desactives sont rejetes ici.
        $user = User::where('email', $request->email)->where('statut', 'actif')->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants invalides',
            ], 401);
        }

        $payload = [
            'iss' => config('app.url'),
            'iat' => time(),
            // Duree de vie limitee a 8 heures pour reduire la fenetre
            // d'exposition d'un token intercepte.
            'exp' => time() + 8 * 60 * 60,
            // sub = identifiant de l'utilisateur, verifie par JwtMiddleware
            // dans chaque microservice pour retrouver le role.
            'sub' => $user->id,
            'role' => $user->role,
        ];

        // En environnement de test, on utilise HS256 avec la APP_KEY pour
        // eviter de distribuer les cles RSA ; en production c'est RS256.
        if (app()->environment('testing')) {
            $secret = config('app.key');
            $token = JWT::encode($payload, $secret, 'HS256');
        } else {
            $privateKey = file_get_contents(storage_path('app/jwt/private.pem'));
            $token = JWT::encode($payload, $privateKey, 'RS256');
        }

        // Tracabilite : chaque session est enregistree avec IP et user-agent
        // dans la table connexions pour l'audit des acces.
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

    /**
     * Cloture la session : marque la derniere connexion active comme
     * inacte. Le JWT reste techniquement valide jusqu'a son expiration
     * (stateless), la revocation reelle est assuree par le statut
     * utilisateur verifie a chaque appel /api/me.
     */
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

    /** Retourne le profil de l'utilisateur authentifie (endpoint /api/me). */
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

    /** Liste de tous les comptes (admin uniquement via middleware role). */
    public function users()
    {
        $users = User::orderBy('nom')->get(['id', 'email', 'nom', 'prenom', 'role', 'statut', 'derniere_connexion_at']);

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    /** Cree un nouveau compte utilisateur avec son role metier. */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:administrateur,gestionnaire,responsable,inspecteur',
        ]);

        $user = User::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'statut' => 'actif',
        ]);

        return response()->json([
            'success' => true,
            'data' => $user->only(['id', 'email', 'nom', 'prenom', 'role', 'statut']),
        ], 201);
    }
}
