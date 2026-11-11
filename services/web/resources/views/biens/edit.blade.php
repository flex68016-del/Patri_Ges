@extends('layouts.app')

@section('title', 'Modifier le Bien - Gestion du Patrimoine')

@section('content')
<div x-data="{
    categories: [],
    services: [],
    bien: null,
    loading: true,
    form: {
        code_inventaire: '',
        designation: '',
        categorie_id: '',
        date_acquisition: '',
        mode_acquisition: 'achat',
        valeur_acquisition: '',
        etat: 'bon',
        service_courant_id: '',
        description: ''
    },
    errors: {}
}" x-init="loadData()">
    <div class="mb-6">
        <a href="{{ route('biens.index') }}" class="text-primary-600 hover:text-primary-700 mb-2 inline-block">
            ← Retour à la liste
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Modifier le bien</h1>
        <p class="text-gray-600" x-text="bien?.designation"></p>
    </div>

    <div x-show="loading" class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
        Chargement...
    </div>

    <div x-show="!loading && bien" class="bg-white rounded-lg shadow-sm p-6">
        <form @submit.prevent="submitForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Colonne gauche -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Code inventaire *</label>
                        <input type="text" x-model="form.code_inventaire"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                            placeholder="Ex: VH-2026-001">
                        <p x-show="errors.code_inventaire" class="text-red-600 text-sm mt-1" x-text="errors.code_inventaire"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Désignation *</label>
                        <input type="text" x-model="form.designation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                            placeholder="Ex: Toyota Land Cruiser 2023">
                        <p x-show="errors.designation" class="text-red-600 text-sm mt-1" x-text="errors.designation"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
                        <select x-model="form.categorie_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">Sélectionner...</option>
                            <template x-for="cat in categories" :key="cat.id">
                                <option :value="cat.id" x-text="cat.libelle"></option>
                            </template>
                        </select>
                        <p x-show="errors.categorie_id" class="text-red-600 text-sm mt-1" x-text="errors.categorie_id"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date d'acquisition *</label>
                        <input type="date" x-model="form.date_acquisition"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <p x-show="errors.date_acquisition" class="text-red-600 text-sm mt-1" x-text="errors.date_acquisition"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mode d'acquisition *</label>
                        <select x-model="form.mode_acquisition"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="achat">Achat</option>
                            <option value="don">Don</option>
                            <option value="legs">Legs</option>
                            <option value="transfert">Transfert</option>
                        </select>
                    </div>
                </div>

                <!-- Colonne droite -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Valeur d'acquisition (FCFA) *</label>
                        <input type="number" x-model="form.valeur_acquisition"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                            placeholder="Ex: 35000000">
                        <p x-show="errors.valeur_acquisition" class="text-red-600 text-sm mt-1" x-text="errors.valeur_acquisition"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">État *</label>
                        <select x-model="form.etat"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="neuf">Neuf</option>
                            <option value="bon">Bon</option>
                            <option value="moyen">Moyen</option>
                            <option value="mauvais">Mauvais</option>
                            <option value="hors_service">Hors service</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service actuel *</label>
                        <select x-model="form.service_courant_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">Sélectionner...</option>
                            <template x-for="service in services" :key="service.id">
                                <option :value="service.id" x-text="service.nom"></option>
                            </template>
                        </select>
                        <p x-show="errors.service_courant_id" class="text-red-600 text-sm mt-1" x-text="errors.service_courant_id"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea x-model="form.description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                            placeholder="Description détaillée du bien..."></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('biens.show', ['id' => $id]) }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
                    Annuler
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function loadData() {
    // TODO: Appeler les API patrimoine-service
    // Pour l'instant, données simulées
    this.categories = [
        { id: '1', libelle: 'Véhicules' },
        { id: '2', libelle: 'Immeubles' },
        { id: '3', libelle: 'Matériel Informatique' },
        { id: '4', libelle: 'Mobilier de Bureau' },
    ];
    this.services = [
        { id: '1', nom: 'Ministère de la Santé' },
        { id: '2', nom: 'Ministère de l\'Éducation' },
        { id: '3', nom: 'Direction des Finances' },
        { id: '4', nom: 'Direction des Ressources Humaines' },
    ];
    this.bien = {
        id: '{{ $id }}',
        code_inventaire: 'VH-2026-001',
        designation: 'Toyota Land Cruiser 2023',
        categorie_id: '1',
        date_acquisition: '2023-01-15',
        mode_acquisition: 'achat',
        valeur_acquisition: 35000000,
        etat: 'bon',
        service_courant_id: '1',
        description: 'Véhicule tout-terrain pour les déplacements de terrain'
    };
    this.form = { ...this.bien };
    this.loading = false;
}

function submitForm() {
    // TODO: Appeler l'API patrimoine-service
    // Pour l'instant, validation de base
    this.errors = {};

    if (!this.form.code_inventaire) {
        this.errors.code_inventaire = 'Ce champ est requis';
    }
    if (!this.form.designation) {
        this.errors.designation = 'Ce champ est requis';
    }
    if (!this.form.categorie_id) {
        this.errors.categorie_id = 'Ce champ est requis';
    }
    if (!this.form.date_acquisition) {
        this.errors.date_acquisition = 'Ce champ est requis';
    }
    if (!this.form.valeur_acquisition) {
        this.errors.valeur_acquisition = 'Ce champ est requis';
    }
    if (!this.form.service_courant_id) {
        this.errors.service_courant_id = 'Ce champ est requis';
    }

    if (Object.keys(this.errors).length === 0) {
        alert('Bien modifié avec succès !');
        window.location.href = '/biens/{{ $id }}';
    }
}
</script>
@endsection
