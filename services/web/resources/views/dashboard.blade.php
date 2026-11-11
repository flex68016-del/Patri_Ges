@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ stats: { total_biens: 0, valeur_totale: 0, biens_affectes: 0, maintenances_retard: 0 } }">
    <h1 class="text-2xl font-bold text-gray-900 mb-8">Tableau de bord</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total biens</p>
                    <p class="text-3xl font-bold text-gray-900" x-text="stats.total_biens">0</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Valeur totale</p>
                    <p class="text-2xl font-bold text-gray-900" x-text="formatCurrency(stats.valeur_totale)">0 FCFA</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Biens affectés</p>
                    <p class="text-3xl font-bold text-gray-900" x-text="stats.biens_affectes">0</p>
                    <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-primary-600 h-2 rounded-full" :style="'width: ' + (stats.biens_affectes / stats.total_biens * 100) + '%'"></div>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Maintenances en retard</p>
                    <p class="text-3xl font-bold text-red-600" x-text="stats.maintenances_retard">0</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions rapides</h2>
            <div class="space-y-3">
                <a href="{{ route('biens.create') }}" class="block w-full bg-primary-600 text-white py-3 rounded-lg font-medium hover:bg-primary-700 text-center">
                    Nouveau bien
                </a>
                <a href="{{ route('maintenances.create') }}" class="block w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-medium hover:bg-gray-200 text-center">
                    Nouvelle maintenance
                </a>
                <a href="{{ route('inventaires.create') }}" class="block w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-medium hover:bg-gray-200 text-center">
                    Campagne d'inventaire
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Répartition par type</h2>
            <div class="h-64">
                <canvas id="typeChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Répartition par statut</h2>
            <div class="h-64">
                <canvas id="statutChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
function formatCurrency(value) {
    return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
}

// Charger les statistiques depuis l'API
fetch('/api/dashboard/stats', {
    headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
    },
})
.then(response => response.json())
.then(data => {
    this.stats = data;
})
.catch(() => {
    console.error('Erreur lors du chargement des statistiques');
});
</script>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique de répartition par type
    const typeCtx = document.getElementById('typeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Véhicules', 'Immeubles', 'Équipements', 'Mobilier'],
            datasets: [{
                data: [35, 25, 30, 10],
                backgroundColor: [
                    '#1B5E20', // primary-800
                    '#4CAF50', // primary-500
                    '#81C784', // primary-300
                    '#C8E6C9', // primary-100
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Graphique de répartition par statut
    const statutCtx = document.getElementById('statutChart').getContext('2d');
    new Chart(statutCtx, {
        type: 'bar',
        data: {
            labels: ['Actif', 'En maintenance', 'Réformé', 'Cédé'],
            datasets: [{
                label: 'Nombre de biens',
                data: [120, 15, 8, 5],
                backgroundColor: [
                    '#4CAF50', // green
                    '#FFC107', // yellow
                    '#F44336', // red
                    '#9E9E9E', // gray
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
