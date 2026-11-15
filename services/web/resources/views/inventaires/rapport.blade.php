@extends('layouts.app')

@section('title', 'Rapport des écarts')

@section('content')
<div>
    <div class="mb-8">
        <a href="{{ route('inventaires.index') }}" class="text-[#047857] hover:text-[#065f46]">← Retour aux inventaires</a>
    </div>

    <h1 class="text-3xl font-semibold text-[#1f2937] mb-2">{{ $campagne['titre'] ?? 'Rapport des écarts' }}</h1>
    <p class="text-[#6b7280] mb-8">{{ $campagne['date_debut'] ?? '' }} → {{ $campagne['date_fin'] ?? '' }}</p>

    @php
        $lignes = $campagne['lignes'] ?? [];
        $total = count($lignes);
        $presents = collect($lignes)->where('constat', 'present')->count();
        $absents = collect($lignes)->where('constat', 'absent')->count();
        $degrades = collect($lignes)->where('constat', 'degrade')->count();
    @endphp

    <div class="bg-white rounded-md shadow-sm p-6 mb-6">
        <div class="grid grid-cols-4 gap-4">
            <div class="bg-[#f0fdf4] p-4 rounded-md">
                <div class="text-sm font-medium text-[#6b7280]">Total constats</div>
                <div class="mt-2 text-2xl font-semibold text-[#1f2937]">{{ $total }}</div>
            </div>
            <div class="bg-green-50 p-4 rounded-md">
                <div class="text-sm font-medium text-[#6b7280]">Présents</div>
                <div class="mt-2 text-2xl font-semibold text-green-600">{{ $presents }}</div>
            </div>
            <div class="bg-red-50 p-4 rounded-md">
                <div class="text-sm font-medium text-[#6b7280]">Absents</div>
                <div class="mt-2 text-2xl font-semibold text-red-600">{{ $absents }}</div>
            </div>
            <div class="bg-yellow-50 p-4 rounded-md">
                <div class="text-sm font-medium text-[#6b7280]">Dégradés</div>
                <div class="mt-2 text-2xl font-semibold text-yellow-600">{{ $degrades }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-md shadow-sm p-6">
        <h2 class="text-xl font-semibold text-[#1f2937] mb-4">Détail des constats</h2>
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#e5e7eb]">
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Bien</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Constat</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Observation</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Constaté le</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lignes as $ligne)
                    <tr class="border-b border-[#e5e7eb]">
                        <td class="py-3 px-4 text-[#1f2937]">{{ $ligne['bien_libelle'] ?? '-' }}</td>
                        <td class="py-3 px-4"><span class="text-xs px-2 py-1 rounded {{ ($ligne['constat'] ?? '') === 'present' ? 'bg-green-100 text-green-800' : (($ligne['constat'] ?? '') === 'absent' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">{{ $ligne['constat'] ?? '-' }}</span></td>
                        <td class="py-3 px-4 text-[#6b7280]">{{ $ligne['observation'] ?? '-' }}</td>
                        <td class="py-3 px-4 text-[#6b7280]">{{ $ligne['constate_at'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-8 text-center text-[#6b7280]">Aucun constat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
