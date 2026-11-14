@extends('layouts.app')

@section('title', 'Nouveau bien')

@section('content')
<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-[#1f2937]">Nouveau bien</h1>
        <a href="{{ route('biens.index') }}" class="text-[#047857] hover:text-[#065f46]">
            ← Retour
        </a>
    </div>

    <div class="bg-white rounded-md shadow-sm p-6">
        <form method="POST" action="#" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Désignation *</label>
                    <input type="text" name="designation" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent"
                        placeholder="Ex: Toyota Camry 2024">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Référence *</label>
                    <input type="text" name="reference" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent"
                        placeholder="Ex: BIEN-2024-001">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Catégorie *</label>
                    <select name="categorie_id" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                        <option value="">Sélectionner une catégorie</option>
                        <option value="1">Véhicules</option>
                        <option value="2">Mobilier</option>
                        <option value="3">Informatique</option>
                        <option value="4">Immobilier</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Service *</label>
                    <select name="service_id" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                        <option value="">Sélectionner un service</option>
                        <option value="1">Ministère</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Date d'acquisition *</label>
                    <input type="date" name="date_acquisition" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Valeur d'acquisition (FCFA) *</label>
                    <input type="number" name="valeur_acquisition" required min="0"
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent"
                        placeholder="Ex: 15000000">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">État *</label>
                    <select name="etat" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                        <option value="">Sélectionner l'état</option>
                        <option value="neuf">Neuf</option>
                        <option value="bon">Bon</option>
                        <option value="moyen">Moyen</option>
                        <option value="mauvais">Mauvais</option>
                        <option value="hors_service">Hors service</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Localisation</label>
                    <input type="text" name="localisation"
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent"
                        placeholder="Ex: Bureau 203, Bâtiment A">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#1f2937] mb-2">Description</label>
                <textarea name="description" rows="3"
                    class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent"
                    placeholder="Description détaillée du bien (optionnel)"></textarea>
            </div>

            <div class="flex justify-end space-x-4 pt-4 border-t border-[#e5e7eb]">
                <a href="{{ route('biens.index') }}" class="px-4 py-2 border border-[#e5e7eb] rounded-md text-[#1f2937] hover:bg-[#f9fafb] transition-colors duration-200">
                    Annuler
                </a>
                <button type="submit" class="bg-[#047857] text-white px-6 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                    Enregistrer le bien
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
