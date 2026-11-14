<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Test temporaire avec identifiants en dur
        if ($credentials['email'] === 'admin@patrimoine.gouv' && $credentials['password'] === 'password123') {
            session([
                'user' => [
                    'id' => 1,
                    'email' => 'admin@patrimoine.gouv',
                    'nom' => 'Admin',
                    'prenom' => 'Système',
                    'role' => 'admin',
                ]
            ]);
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas a nos enregistrements.',
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['token', 'user']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
