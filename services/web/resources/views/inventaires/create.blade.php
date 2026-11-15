@extends('layouts.app')

@section('title', 'Nouvelle campagne d\'inventaire')

@section('content')
<div>
    <div class="mb-8">
        <a href="{{ route('inventaires.index') }}" class="text-[#047857] hover:text-[#065f46]">← Retour aux inventaires</a>
    </div>

    <h1 class="text-3xl font-semibold text-[#1f2937] mb-8">Nouvelle campagne d'inventaire</h1>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <div class="bg-white rounded-md shadow-sm p-6">
        <form method="POST" action="{{ route('inventaires.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Titre *</label>
                    <input type="text" name="titre" value="{{ old('titre') }}" required class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Description</label>
                    <textarea name="description" rows="2" class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Date de début *</label>
                    <input type="date" name="date_debut" value="{{ old('date_debut') }}" required class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Date de fin *</label>
                    <input type="date" name="date_fin" value="{{ old('date_fin') }}" required class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Inspecteur *</label>
                    <select name="inspecteur_id" required class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                        <option value="">Sélectionner un utilisateur</option>
                        @foreach($users as $user)
                            <option value="{{ $user['id'] }}" @selected(old('inspecteur_id') === $user['id'])>{{ $user['prenom'] }} {{ $user['nom'] }} ({{ $user['email'] }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-[#047857] text-white px-6 py-3 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                Enregistrer
            </button>
        </form>
    </div>
</div>
@endsection
