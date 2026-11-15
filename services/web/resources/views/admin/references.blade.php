{{-- Page des referentiels : liste et creation des services et categories de biens. --}}
@extends('layouts.app')

@section('title', 'Administration - Référentiels')

@section('content')
<div>
    <h1 class="text-3xl font-semibold text-[#1f2937] mb-8">Gestion des référentiels</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-[#ecfdf5] border border-[#a7f3d0] text-[#065f46] rounded-md">{{ session('success') }}</div>
    @endif

    <div class="space-y-6">
        <div class="bg-white rounded-md shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-[#1f2937]">Services</h2>
                <a href="{{ route('admin.services.create') }}" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                    Nouveau service
                </a>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#e5e7eb]">
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Code</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Nom</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Responsable</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr class="border-b border-[#e5e7eb]">
                            <td class="py-3 px-4 text-[#1f2937]">{{ $service['code'] ?? '-' }}</td>
                            <td class="py-3 px-4 text-[#6b7280]">{{ $service['nom'] ?? '-' }}</td>
                            <td class="py-3 px-4 text-[#6b7280]">{{ $service['responsable'] ?? '-' }}</td>
                            <td class="py-3 px-4"><span class="{{ ($service['actif'] ?? false) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }} px-2 py-1 rounded text-sm">{{ ($service['actif'] ?? false) ? 'Actif' : 'Inactif' }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-[#6b7280]">Aucun service.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-md shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-[#1f2937]">Catégories de biens</h2>
                <a href="{{ route('admin.categories.create') }}" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                    Nouvelle catégorie
                </a>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#e5e7eb]">
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Code</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Nom</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Type</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Durée de vie</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $categorie)
                        <tr class="border-b border-[#e5e7eb]">
                            <td class="py-3 px-4 text-[#1f2937]">{{ $categorie['code'] ?? '-' }}</td>
                            <td class="py-3 px-4 text-[#6b7280]">{{ $categorie['nom'] ?? '-' }}</td>
                            <td class="py-3 px-4 text-[#6b7280]">{{ $categorie['type_bien'] ?? '-' }}</td>
                            <td class="py-3 px-4 text-[#6b7280]">{{ $categorie['duree_vie'] ?? '-' }} ans</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-[#6b7280]">Aucune catégorie.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
