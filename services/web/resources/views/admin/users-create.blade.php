@extends('layouts.app')

@section('title', 'Administration - Nouvel utilisateur')

@section('content')
<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-[#1f2937]">Nouvel utilisateur</h1>
        <a href="{{ route('admin.users') }}" class="text-[#047857] hover:text-[#065f46]">
            ← Retour
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <div class="bg-white rounded-md shadow-sm p-6">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Rôle *</label>
                    <select name="role" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                        <option value="">Sélectionner un rôle</option>
                        @foreach(['administrateur' => 'Administrateur', 'gestionnaire' => 'Gestionnaire', 'responsable' => 'Responsable', 'inspecteur' => 'Inspecteur'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('role') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Nom *</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Prénom *</label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Mot de passe * (min. 8 caractères)</label>
                    <input type="password" name="password" required minlength="8"
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('admin.users') }}" class="px-4 py-2 border border-[#e5e7eb] rounded-md text-[#1f2937] hover:bg-[#f9fafb] transition-colors duration-200">
                    Annuler
                </a>
                <button type="submit" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                    Créer l'utilisateur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
