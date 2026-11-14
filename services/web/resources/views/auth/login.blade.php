@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="w-full max-w-md bg-white rounded-md shadow-sm p-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-semibold text-[#1f2937]">Gestion du Patrimoine</h1>
        <p class="mt-2 text-[#6b7280]">Connectez-vous pour acceder a la plateforme</p>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-medium text-[#374151] mb-2">Email</label>
            <input
                type="email"
                name="email"
                class="w-full px-4 py-3 border border-[#e5e7eb] rounded-md focus:ring-2 focus:ring-[#047857] focus:border-[#047857]"
                placeholder="vous@exemple.com"
                required
                value="{{ old('email') }}"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-[#374151] mb-2">Mot de passe</label>
            <input
                type="password"
                name="password"
                class="w-full px-4 py-3 border border-[#e5e7eb] rounded-md focus:ring-2 focus:ring-[#047857] focus:border-[#047857]"
                placeholder="••••••••"
                required
            >
        </div>

        <button
            type="submit"
            class="w-full bg-[#047857] text-white py-3 rounded-md font-medium hover:bg-[#065f46] transition-colors duration-200"
        >
            Se connecter
        </button>
    </form>
</div>
@endsection
