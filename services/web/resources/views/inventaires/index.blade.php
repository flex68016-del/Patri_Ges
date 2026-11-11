@extends('layouts.app')

@section('title', 'Inventaires - Gestion du Patrimoine')

@section('content')
<div x-data="{
    campagnes: [],
    loading: true
}" x-init="loadCampagnes()">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Campagnes d'inventaire</h1>
        <p class="text-gray-600">Gestion des campagnes d'inventaire du patrimoine</p>
    </div>

    <!-- Tableau -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
            <span class="text-sm text-gray-600" x-text="campagnes.length + ' campagne(s)'"></span>
            <a href="{{ route('inventaires.create') }}"
                class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">
                Nouvelle campagne
            </a>
        </div>

        <div x-show="loading" class="p-8 text-center text-gray-500">
            Chargement...
        </div>

        <div x-show="!loading" class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Période</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inspecteur</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progression</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="campagne in campagnes" :key="campagne.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900" x-text="campagne.titre"></td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                <span x-text="formatDate(campagne.date_debut)"></span> - <span x-text="formatDate(campagne.date_fin)"></span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600" x-text="campagne.inspecteur?.nom"></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full"
                                    :class="{
                                        'bg-blue-100 text-blue-800': campagne.statut === 'planifie',
                                        'bg-yellow-100 text-yellow-800': campagne.statut === 'en_cours',
                                        'bg-green-100 text-green-800': campagne.statut === 'termine',
                                        'bg-red-100 text-red-800': campagne.statut === 'annule'
                                    }" x-text="campagne.statut"></span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary-600 h-2 rounded-full" :style="'width: ' + campagne.progression + '%'"></div>
                                </div>
                                <span class="text-xs text-gray-500" x-text="campagne.progression + '%'"></span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <button @click="cloturerCampagne(campagne)" x-show="campagne.statut !== 'termine'"
                                    class="text-primary-600 hover:text-primary-700 mr-2">Clôturer</button>
                                <a :href="'/inventaires/' + campagne.id" class="text-primary-600 hover:text-primary-700">Voir</a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div x-show="!loading && campagnes.length === 0" class="p-8 text-center text-gray-500">
            Aucune campagne d'inventaire trouvée
        </div>
    </div>
</div>

<script>
function loadCampagnes() {
    // TODO: Appeler l'API operations-service
    // Pour l'instant, données simulées
    this.campagnes = [
        {
            id: '1',
            titre: 'Inventaire annuel 2026',
            date_debut: '2026-01-01',
            date_fin: '2026-12-31',
            statut: 'en_cours',
            progression: 45,
            inspecteur: { nom: 'Koné Fatou' }
        },
        {
            id: '2',
            titre: 'Inventaire véhicules 2025',
            date_debut: '2025-06-01',
            date_fin: '2025-06-30',
            statut: 'termine',
            progression: 100,
            inspecteur: { nom: 'Touré Awa' }
        }
    ];
    this.loading = false;
}

function formatDate(date) {
    return new Date(date).toLocaleDateString('fr-FR');
}

function cloturerCampagne(campagne) {
    if (confirm('Êtes-vous sûr de vouloir clôturer cette campagne ?')) {
        // TODO: Appeler l'API operations-service
        alert('Action à implémenter');
    }
}
</script>
@endsection
