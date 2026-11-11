@extends('layouts.app')

@section('title', 'Biens - Gestion du Patrimoine')

@section('content')
<div x-data="{
    biens: [],
    loading: true,
    filters: {
        search: '',
        categorie: '',
        service: '',
        statut: ''
    }
}" x-init="loadBiens()">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Biens</h1>
        <p class="text-gray-600">Liste des biens du patrimoine de l'État</p>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" x-model="filters.search" @input="loadBiens()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                    placeholder="Code ou désignation...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                <select x-model="filters.categorie" @change="loadBiens()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Toutes</option>
                    <option value="vehicule">Véhicules</option>
                    <option value="immobilier">Immeubles</option>
                    <option value="equipement">Équipements</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                <select x-model="filters.service" @change="loadBiens()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Tous</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select x-model="filters.statut" @change="loadBiens()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Tous</option>
                    <option value="actif">Actif</option>
                    <option value="en_maintenance">En maintenance</option>
                    <option value="reforme">Réformé</option>
                    <option value="cede">Cédé</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
            <span class="text-sm text-gray-600" x-text="biens.length + ' bien(s)'"></span>
            <a href="{{ route('biens.create') }}"
                class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">
                Nouveau bien
            </a>
        </div>

        <div x-show="loading" class="p-8 text-center text-gray-500">
            Chargement...
        </div>

        <div x-show="!loading" class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Désignation</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valeur</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="bien in biens" :key="bien.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900" x-text="bien.code_inventaire"></td>
                            <td class="px-4 py-3 text-sm text-gray-900" x-text="bien.designation"></td>
                            <td class="px-4 py-3 text-sm text-gray-600" x-text="bien.categorie?.libelle"></td>
                            <td class="px-4 py-3 text-sm text-gray-600" x-text="bien.service_courant?.nom"></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full"
                                    :class="{
                                        'bg-green-100 text-green-800': bien.statut === 'actif',
                                        'bg-yellow-100 text-yellow-800': bien.statut === 'en_maintenance',
                                        'bg-red-100 text-red-800': bien.statut === 'reforme',
                                        'bg-gray-100 text-gray-800': bien.statut === 'cede'
                                    }" x-text="bien.statut"></span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900" x-text="formatCurrency(bien.valeur_acquisition)"></td>
                            <td class="px-4 py-3 text-sm">
                                <a :href="'/biens/' + bien.id" class="text-primary-600 hover:text-primary-700 mr-2">Voir</a>
                                <a :href="'/biens/' + bien.id + '/edit'" class="text-primary-600 hover:text-primary-700">Modifier</a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div x-show="!loading && biens.length === 0" class="p-8 text-center text-gray-500">
            Aucun bien trouvé
        </div>
    </div>
</div>

<script>
function loadBiens() {
    const token = localStorage.getItem('token');
    const params = new URLSearchParams();
    if (this.filters.search) params.append('search', this.filters.search);
    if (this.filters.categorie) params.append('categorie', this.filters.categorie);
    if (this.filters.service) params.append('service', this.filters.service);
    if (this.filters.statut) params.append('statut', this.filters.statut);

    fetch('http://localhost:8002/api/biens?' + params.toString(), {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        this.biens = data.data || data;
        this.loading = false;
    })
    .catch(error => {
        console.error('Erreur lors du chargement des biens:', error);
        // Données de secours en cas d'erreur
        this.biens = [];
        this.loading = false;
    });
}

function formatCurrency(value) {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF'
    }).format(value);
}
</script>
@endsection
