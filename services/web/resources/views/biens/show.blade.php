@extends('layouts.app')

@section('title', 'Détail du Bien - Gestion du Patrimoine')

@section('content')
<div x-data="{
    bien: null,
    loading: true,
    activeTab: 'general'
}" x-init="loadBien()">
    <div class="mb-6">
        <a href="{{ route('biens.index') }}" class="text-primary-600 hover:text-primary-700 mb-2 inline-block">
            ← Retour à la liste
        </a>
        <h1 class="text-2xl font-bold text-gray-900" x-text="bien?.designation"></h1>
        <p class="text-gray-600" x-text="bien?.code_inventaire"></p>
    </div>

    <div x-show="loading" class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
        Chargement...
    </div>

    <div x-show="!loading && bien">
        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-4">
                    <button @click="activeTab = 'general'"
                        :class="activeTab === 'general' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                        Général
                    </button>
                    <button @click="activeTab = 'technique'"
                        :class="activeTab === 'technique' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                        Technique
                    </button>
                    <button @click="activeTab = 'affectations'"
                        :class="activeTab === 'affectations' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                        Affectations
                    </button>
                    <button @click="activeTab = 'maintenance'"
                        :class="activeTab === 'maintenance' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-4 px-1 border-b-2 font-medium text-sm">
                        Maintenance
                    </button>
                </nav>
            </div>

            <!-- Tab: Général -->
            <div x-show="activeTab === 'general'" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Code inventaire</dt>
                                <dd class="text-sm text-gray-900" x-text="bien.code_inventaire"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Désignation</dt>
                                <dd class="text-sm text-gray-900" x-text="bien.designation"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Catégorie</dt>
                                <dd class="text-sm text-gray-900" x-text="bien.categorie?.libelle"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Service actuel</dt>
                                <dd class="text-sm text-gray-900" x-text="bien.service_courant?.nom"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                <dd>
                                    <span class="px-2 py-1 text-xs rounded-full"
                                        :class="{
                                            'bg-green-100 text-green-800': bien.statut === 'actif',
                                            'bg-yellow-100 text-yellow-800': bien.statut === 'en_maintenance',
                                            'bg-red-100 text-red-800': bien.statut === 'reforme',
                                            'bg-gray-100 text-gray-800': bien.statut === 'cede'
                                        }" x-text="bien.statut"></span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Valorisation</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Valeur d'acquisition</dt>
                                <dd class="text-sm text-gray-900" x-text="formatCurrency(bien.valeur_acquisition)"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date d'acquisition</dt>
                                <dd class="text-sm text-gray-900" x-text="formatDate(bien.date_acquisition)"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Mode d'acquisition</dt>
                                <dd class="text-sm text-gray-900" x-text="bien.mode_acquisition"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Valeur net comptable</dt>
                                <dd class="text-lg font-bold text-primary-600" x-text="formatCurrency(bien.vnc)"></dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Tab: Technique -->
            <div x-show="activeTab === 'technique'" class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Caractéristiques techniques</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">État</dt>
                        <dd class="text-sm text-gray-900" x-text="bien.etat"></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Duree de vie estimée</dt>
                        <dd class="text-sm text-gray-900" x-text="bien.categorie?.duree_amortissement + ' ans'"></dd>
                    </div>
                </dl>
            </div>

            <!-- Tab: Affectations -->
            <div x-show="activeTab === 'affectations'" class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Historique des affectations</h3>
                <div class="text-sm text-gray-500">
                    <p>Aucune affectation enregistrée</p>
                </div>
            </div>

            <!-- Tab: Maintenance -->
            <div x-show="activeTab === 'maintenance'" class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Historique des maintenances</h3>
                <div class="text-sm text-gray-500">
                    <p>Aucune maintenance enregistrée</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-sm p-4 flex justify-end space-x-3">
            <a :href="'/biens/' + bien.id + '/edit'"
                class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">
                Modifier
            </a>
            <button @click="affecterBien"
                class="px-4 py-2 bg-secondary-600 text-white rounded-md hover:bg-secondary-700 transition">
                Affecter
            </button>
            <button @click="reformerBien"
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                Réformer
            </button>
        </div>
    </div>
</div>

<script>
function loadBien() {
    const token = localStorage.getItem('token');
    const bienId = '{{ $id }}';

    fetch('http://localhost:8002/api/biens/' + bienId, {
        headers: {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        this.bien = data.data || data;
        this.loading = false;
    })
    .catch(error => {
        console.error('Erreur lors du chargement du bien:', error);
        this.loading = false;
    });
}

function formatDate(date) {
    return new Date(date).toLocaleDateString('fr-FR');
}

function formatCurrency(value) {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF'
    }).format(value);
}

function affecterBien() {
    window.location.href = '/biens/{{ $id }}/affecter';
}

function reformerBien() {
    if (confirm('Êtes-vous sûr de vouloir réformer ce bien ?')) {
        const token = localStorage.getItem('token');
        fetch('http://localhost:8002/api/biens/{{ $id }}/reformer', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            alert('Bien réformé avec succès');
            location.reload();
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la réforme');
        });
    }
}
</script>
@endsection
