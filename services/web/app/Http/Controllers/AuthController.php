<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private ApiClient $api) {}

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

        $response = $this->api->post('auth', '/api/login', $credentials);
        $data = $response->json();

        if (! $response->successful() || ! ($data['success'] ?? false)) {
            return back()->withErrors([
                'email' => $data['message'] ?? 'Les identifiants fournis ne correspondent pas a nos enregistrements.',
            ])->withInput($request->only('email'));
        }

        session([
            'token' => $data['data']['token'],
            'user' => $data['data']['user'],
        ]);

        return redirect()->route('dashboard');
    }

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
