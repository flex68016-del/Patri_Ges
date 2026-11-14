@extends('layouts.app')

@section('title', 'Administration - Référentiels')

@section('content')
<div>
    <h1 class="text-3xl font-semibold text-[#1f2937] mb-8">Gestion des référentiels</h1>

    <div class="space-y-6">
        <!-- Services -->
        <div class="bg-white rounded-md shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-[#1f2937]">Services</h2>
                <button class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                    Nouveau service
                </button>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#e5e7eb]">
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Code</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Nom</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Type</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Parent</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-[#e5e7eb]">
                        <td class="py-3 px-4 text-[#1f2937]">MIN</td>
                        <td class="py-3 px-4 text-[#6b7280]">Ministère</td>
                        <td class="py-3 px-4 text-[#6b7280]">Ministère</td>
                        <td class="py-3 px-4 text-[#6b7280]">-</td>
                        <td class="py-3 px-4">
                            <button class="text-[#047857] hover:text-[#065f46] mr-2">Modifier</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Catégories -->
        <div class="bg-white rounded-md shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-[#1f2937]">Catégories de biens</h2>
                <button class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                    Nouvelle catégorie
                </button>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#e5e7eb]">
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Libellé</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Type</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Durée amortissement</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-[#e5e7eb]">
                        <td class="py-3 px-4 text-[#1f2937]">Véhicules</td>
                        <td class="py-3 px-4 text-[#6b7280]">Véhicule</td>
                        <td class="py-3 px-4 text-[#6b7280]">5 ans</td>
                        <td class="py-3 px-4">
                            <button class="text-[#047857] hover:text-[#065f46] mr-2">Modifier</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection