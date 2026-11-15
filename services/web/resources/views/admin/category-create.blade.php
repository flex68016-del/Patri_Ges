@extends('layouts.app')

@section('title', 'Administration - Nouvelle catégorie')

@section('content')
<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-[#1f2937]">Nouvelle catégorie de bien</h1>
        <a href="{{ route('admin.references') }}" class="text-[#047857] hover:text-[#065f46]">
            ← Retour
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <div class="bg-white rounded-md shadow-sm p-6">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Code *</label>
                    <input type="text" name="code" value="{{ old('code') }}" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent"
                        placeholder="Ex: VEH, IMM, EQU">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Nom *</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent"
                        placeholder="Ex: Véhicules">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Type de bien *</label>
                    <select name="type_bien" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                        <option value="">Sélectionner un type</option>
                        <option value="vehicule" @selected(old('type_bien') === 'vehicule')>Véhicule</option>
                        <option value="equipement" @selected(old('type_bien') === 'equipement')>Équipement</option>
                        <option value="immobilier" @selected(old('type_bien') === 'immobilier')>Immobilier</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Durée de vie (années) *</label>
                    <input type="number" name="duree_vie" value="{{ old('duree_vie') }}" required min="1"
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent"
                        placeholder="Ex: 5">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('admin.references') }}" class="px-4 py-2 border border-[#e5e7eb] rounded-md text-[#1f2937] hover:bg-[#f9fafb] transition-colors duration-200">
                    Annuler
                </a>
                <button type="submit" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                    Créer la catégorie
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
