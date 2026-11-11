@extends('layouts.app')

@section('title', 'Maintenances - Gestion du Patrimoine')

@section('content')
<div x-data="{
    maintenances: [],
    loading: true,
    filters: {
        statut: '',
        type: ''
    }
}" x-init="loadMaintenances()">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Maintenances</h1>
        <p class="text-gray-600">Gestion des maintenances préventives et correctives</p>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select x-model="filters.statut" @change="loadMaintenances()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Tous</option>
                    <option value="planifie">Planifié</option>
                    <option value="en_cours">En cours</option>
                    <option value="termine">Terminé</option>
                    <option value="annule">Annulé</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select x-model="filters.type" @change="loadMaintenances()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Tous</option>
                    <option value="preventive">Préventive</option>
                    <option value="corrective">Corrective</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
            <span class="text-sm text-gray-600" x-text="maintenances.length + ' maintenance(s)'"></span>
            <a href="{{ route('maintenances.create') }}"
                class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">
                Nouvelle maintenance
            </a>
        </div>

        <div x-show="loading" class="p-8 text-center text-gray-500">
            Chargement...
        </div>

        <div x-show="!loading" class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bien</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date prévue</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Coût estimé</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="maintenance in maintenances" :key="maintenance.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900" x-text="maintenance.bien?.designation"></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full"
                                    :class="{
                                        'bg-blue-100 text-blue-800': maintenance.type === 'preventive',
                                        'bg-orange-100 text-orange-800': maintenance.type === 'corrective'
                                    }" x-text="maintenance.type"></span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600" x-text="formatDate(maintenance.date_prevue)"></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full"
                                    :class="{
                                        'bg-yellow-100 text-yellow-800': maintenance.statut === 'planifie',
                                        'bg-blue-100 text-blue-800': maintenance.statut === 'en_cours',
                                        'bg-green-100 text-green-800': maintenance.statut === 'termine',
                                        'bg-red-100 text-red-800': maintenance.statut === 'annule'
                                    }" x-text="maintenance.statut"></span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900" x-text="formatCurrency(maintenance.cout_estime)"></td>
                            <td class="px-4 py-3 text-sm">
                                <button @click="cloturerMaintenance(maintenance)" x-show="maintenance.statut !== 'termine'"
                                    class="text-primary-600 hover:text-primary-700 mr-2">Clôturer</button>
                                <a :href="'/maintenances/' + maintenance.id" class="text-primary-600 hover:text-primary-700">Voir</a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div x-show="!loading && maintenances.length === 0" class="p-8 text-center text-gray-500">
            Aucune maintenance trouvée
        </div>
    </div>
</div>

<script>
function loadMaintenances() {
    const token = localStorage.getItem('token');
    const params = new URLSearchParams();
    if (this.filters.statut) params.append('statut', this.filters.statut);
    if (this.filters.type) params.append('type', this.filters.type);

    fetch('http://localhost:8003/api/maintenances?' + params.toString(), {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        this.maintenances = data.data || data;
        this.loading = false;
    })
    .catch(error => {
        console.error('Erreur lors du chargement des maintenances:', error);
        this.maintenances = [];
        this.loading = false;
    });
}

function formatDate(date) {
    return new Date(date).toLocaleDateString('fr-FR');
}

function formatCurrency(value) {
    return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
}

function cloturerMaintenance(maintenance) {
    if (confirm('Êtes-vous sûr de vouloir clôturer cette maintenance ?')) {
        const token = localStorage.getItem('token');
        fetch('http://localhost:8003/api/maintenances/' + maintenance.id + '/cloturer', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            alert('Maintenance clôturée avec succès');
            loadMaintenances();
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la clôture');
        });
    }
}
</script>
@endsection
