@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Gestion du Patrimoine</h1>
        <p class="mt-2 text-gray-500">Connectez-vous pour acceder a la plateforme</p>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input
                type="email"
                name="email"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="vous@exemple.com"
                required
                value="{{ old('email') }}"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
            <input
                type="password"
                name="password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="••••••••"
                required
            >
        </div>

        <button
            type="submit"
            class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors"
        >
            Se connecter
        </button>
    </form>
</div>
@endsection
