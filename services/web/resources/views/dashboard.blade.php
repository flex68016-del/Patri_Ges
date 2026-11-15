{{-- Tableau de bord : compteurs cles (biens, maintenances, campagnes, anomalies) et elements recents. --}}
@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div>
    <h1 class="text-3xl font-semibold text-[#1f2937] mb-8">Tableau de bord</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-md shadow-sm p-6">
            <div class="text-sm font-medium text-[#6b7280]">Total Biens</div>
            <div class="mt-2 text-3xl font-semibold text-[#1f2937]">{{ $nbBiens }}</div>
        </div>
        <div class="bg-white rounded-md shadow-sm p-6">
            <div class="text-sm font-medium text-[#6b7280]">Maintenances</div>
            <div class="mt-2 text-3xl font-semibold text-[#1f2937]">{{ $nbMaintenances }}</div>
        </div>
        <div class="bg-white rounded-md shadow-sm p-6">
            <div class="text-sm font-medium text-[#6b7280]">Campagnes d'inventaire</div>
            <div class="mt-2 text-3xl font-semibold text-[#1f2937]">{{ $nbCampagnes ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-md shadow-sm p-6">
            <div class="text-sm font-medium text-[#6b7280]">Anomalies</div>
            <div class="mt-2 text-3xl font-semibold text-[#1f2937]">{{ $nbAnomalies }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-md shadow-sm p-6">
            <h2 class="text-xl font-semibold text-[#1f2937] mb-4">Derniers biens</h2>
            @forelse($biens as $bien)
                <div class="flex justify-between items-center py-3 border-b border-[#e5e7eb] last:border-0">
                    <div>
                        <div class="font-medium text-[#1f2937]">{{ $bien['designation'] ?? '-' }}</div>
                        <div class="text-sm text-[#6b7280]">{{ $bien['code'] ?? '' }}</div>
                    </div>
                    <span class="text-xs px-2 py-1 rounded bg-[#ecfdf5] text-[#047857]">{{ $bien['statut'] ?? '' }}</span>
                </div>
            @empty
                <p class="text-[#6b7280]">Aucun bien enregistré.</p>
            @endforelse
        </div>

        <div class="bg-white rounded-md shadow-sm p-6">
            <h2 class="text-xl font-semibold text-[#1f2937] mb-4">Dernières anomalies</h2>
            @forelse($anomalies as $anomalie)
                <div class="flex justify-between items-center py-3 border-b border-[#e5e7eb] last:border-0">
                    <div>
                        <div class="font-medium text-[#1f2937]">{{ $anomalie['description'] ?? '-' }}</div>
                        <div class="text-sm text-[#6b7280]">{{ $anomalie['type'] ?? '' }}</div>
                    </div>
                    <span class="text-xs px-2 py-1 rounded bg-[#fef3c7] text-[#92400e]">{{ $anomalie['statut'] ?? '' }}</span>
                </div>
            @empty
                <p class="text-[#6b7280]">Aucune anomalie.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
