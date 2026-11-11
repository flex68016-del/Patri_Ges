@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8" x-data="{ email: '', password: '', loading: false, error: '' }">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Gestion du Patrimoine de l'État</h1>
        <p class="mt-2 text-gray-500">Connectez-vous pour accéder à la plateforme</p>
    </div>

    <form @submit.prevent="login" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input
                type="email"
                x-model="email"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                placeholder="vous@exemple.com"
                required
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
            <input
                type="password"
                x-model="password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                placeholder="••••••••"
                required
            >
        </div>

        <div x-show="error" class="text-red-600 text-sm" x-text="error"></div>

        <button
            type="submit"
            :disabled="loading"
            class="w-full bg-primary-600 text-white py-3 rounded-lg font-medium hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
            <span x-show="!loading">Se connecter</span>
            <span x-show="loading">Connexion...</span>
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="#" class="text-sm text-primary-600 hover:text-primary-700">Mot de passe oublié ?</a>
    </div>
</div>

<script>
function login() {
    this.loading = true;
    this.error = '';

    fetch('http://localhost:8001/api/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            email: this.email,
            password: this.password,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.token) {
            localStorage.setItem('token', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));
            window.location.href = '/dashboard';
        } else {
            this.error = data.error || 'Erreur de connexion';
        }
    })
    .catch(() => {
        this.error = 'Erreur de connexion au serveur';
    })
    .finally(() => {
        this.loading = false;
    });
}
</script>
@endsection
