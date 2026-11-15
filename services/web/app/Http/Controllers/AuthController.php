<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;

/**
 * Authentification cote interface : le mot de passe n'est jamais verifie
 * ici, il est transmis a auth-service qui retourne un JWT. Le token et
 * l'utilisateur sont stockes en session pour les pages suivantes.
 */
class AuthController extends Controller
{
    public function __construct(private ApiClient $api) {}

    /** Affiche le formulaire de connexion (redirige si deja connecte). */
    public function showLogin()
    {
        if (session('user')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Delegation complete a auth-service : c'est lui qui verifie le
        // mot de passe (bcrypt) et signe le JWT RS256.
        $response = $this->api->post('auth', '/api/login', $credentials);
        $data = $response->json();

        // En cas d'echec, on renvoie l'utilisateur au formulaire avec le
        // message de l'API sans jamais reveler si l'email existe.
        if (! $response->successful() || ! ($data['success'] ?? false)) {
            return back()->withErrors([
                'email' => $data['message'] ?? 'Les identifiants fournis ne correspondent pas a nos enregistrements.',
            ])->withInput($request->only('email'));
        }

        // Le token JWT et le profil utilisateur vivent en session : ils
        // seront joints automatiquement a chaque appel ApiClient.
        session([
            'token' => $data['data']['token'],
            'user' => $data['data']['user'],
        ]);

        return redirect()->route('dashboard');
    }

    /**
     * Deconnexion : on previent auth-service pour invalider le token
     * (journalise dans la table connexions), puis on detruit la session
     * locale et on regenere le jeton CSRF.
     */
    public function logout(Request $request)
    {
        if (session('token')) {
            $this->api->post('auth', '/api/logout');
        }

        $request->session()->forget(['token', 'user']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
