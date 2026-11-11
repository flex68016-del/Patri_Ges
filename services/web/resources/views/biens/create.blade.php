@extends('layouts.app')

@section('title', 'Nouveau Bien - Gestion du Patrimoine')

@section('content')
<div x-data="{
    categories: [],
    services: [],
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
        <h1 class="text-2xl font-bold text-gray-900">Nouveau bien</h1>
        <p class="text-gray-600">Créer un nouveau bien dans le patrimoine de l'État</p>
    </div>

    <div x-show="loading" class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
        Chargement...
    </div>

    <div x-show="!loading" class="bg-white rounded-lg shadow-sm p-6">
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
                <a href="{{ route('biens.index') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
                    Annuler
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">
                    Créer le bien
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function loadData() {
    const token = localStorage.getItem('token');

    // Charger les catégories
    fetch('http://localhost:8002/api/categories', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        this.categories = data.data || data;
    })
    .catch(error => console.error('Erreur catégories:', error));

    // Charger les services
    fetch('http://localhost:8002/api/services', {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        this.services = data.data || data;
    })
    .catch(error => console.error('Erreur services:', error));

    this.loading = false;
}

function submitForm() {
    const token = localStorage.getItem('token');

    fetch('http://localhost:8002/api/biens', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.form)
    })
    .then(response => response.json())
    .then(data => {
        alert('Bien créé avec succès !');
        window.location.href = '/biens';
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la création');
    });
}
</script>
@endsection
