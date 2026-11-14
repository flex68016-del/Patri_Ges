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

    <div class="bg-white rounded-md shadow-sm p-6">
        <form method="POST" action="#">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Libellé</label>
                    <input type="text" name="libelle" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent"
                        placeholder="Ex: Véhicules, Mobilier, Informatique">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Type de bien</label>
                    <select name="type" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                        <option value="">Sélectionner un type</option>
                        <option value="vehicule">Véhicule</option>
                        <option value="mobilier">Mobilier</option>
                        <option value="informatique">Informatique</option>
                        <option value="imobilier">Immobilier</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Durée d'amortissement (années)</label>
                    <input type="number" name="duree_amortissement" required min="1"
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent"
                        placeholder="Ex: 5">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent"
                        placeholder="Description optionnelle de la catégorie"></textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.references') }}" class="px-4 py-2 border border-[#e5e7eb] rounded-md text-[#1f2937] hover:bg-[#f9fafb] transition-colors duration-200">
                        Annuler
                    </a>
                    <button type="submit" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                        Créer la catégorie
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
