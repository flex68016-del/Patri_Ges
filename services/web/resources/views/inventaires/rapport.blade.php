@extends('layouts.app')

@section('title', 'Rapport des écarts')

@section('content')
<div>
    <div class="mb-8">
        <a href="{{ route('inventaires.index') }}" class="text-[#047857] hover:text-[#065f46]">← Retour aux inventaires</a>
    </div>

    <h1 class="text-3xl font-semibold text-[#1f2937] mb-8">Rapport des écarts</h1>

    <div class="bg-white rounded-md shadow-sm p-6 mb-6">
        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="bg-[#f0fdf4] p-4 rounded-md">
                <div class="text-sm font-medium text-[#6b7280]">Total biens</div>
                <div class="mt-2 text-2xl font-semibold text-[#1f2937]">150</div>
            </div>
            <div class="bg-green-50 p-4 rounded-md">
                <div class="text-sm font-medium text-[#6b7280]">Présents</div>
                <div class="mt-2 text-2xl font-semibold text-green-600">142</div>
            </div>
            <div class="bg-red-50 p-4 rounded-md">
                <div class="text-sm font-medium text-[#6b7280]">Absents</div>
                <div class="mt-2 text-2xl font-semibold text-red-600">5</div>
            </div>
            <div class="bg-yellow-50 p-4 rounded-md">
                <div class="text-sm font-medium text-[#6b7280]">Dégradés</div>
                <div class="mt-2 text-2xl font-semibold text-yellow-600">3</div>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-[#374151] mb-2">Campagne</label>
            <select class="w-full px-4 py-3 border border-[#e5e7eb] rounded-md">
                <option>Campagne 2026-001</option>
            </select>
        </div>

        <button class="bg-[#047857] text-white px-6 py-3 rounded-md hover:bg-[#065f46] transition-colors duration-200">
            Générer le rapport
        </button>
    </div>

    <div class="bg-white rounded-md shadow-sm p-6">
        <h2 class="text-xl font-semibold text-[#1f2937] mb-4">Détail des écarts</h2>
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#e5e7eb]">
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Code bien</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Désignation</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Constat</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Observation</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Valeur estimée</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-[#e5e7eb]">
                    <td class="py-3 px-4 text-[#1f2937]">TG-VEH-2026-000045</td>
                    <td class="py-3 px-4 text-[#6b7280]">Toyota Hilux</td>
                    <td class="py-3 px-4"><span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">Absent</span></td>
                    <td class="py-3 px-4 text-[#6b7280]">Non retrouvé lors de l'inventaire</td>
                    <td class="py-3 px-4 text-[#1f2937]">15 000 000 FCFA</td>
                </tr>
                <tr class="border-b border-[#e5e7eb]">
                    <td class="py-3 px-4 text-[#1f2937]">TG-VEH-2026-000046</td>
                    <td class="py-3 px-4 text-[#6b7280]">Peugeot 308</td>
                    <td class="py-3 px-4"><span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">Dégradé</span></td>
                    <td class="py-3 px-4 text-[#6b7280]">Carrosserie endommagée</td>
                    <td class="py-3 px-4 text-[#1f2937]">8 500 000 FCFA</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection