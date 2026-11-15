{{-- Inventaire des biens : tableau pagine avec recherche et filtres par categorie et statut. --}}
@extends('layouts.app')

@section('title', 'Biens')

@section('content')
@php $canWrite = in_array(session('user.role'), ['gestionnaire', 'administrateur']); @endphp
<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-[#1f2937]">Biens</h1>
        @if($canWrite)
            <a href="{{ route('biens.create') }}" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                Nouveau bien
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-[#ecfdf5] border border-[#a7f3d0] text-[#065f46] rounded-md">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-md shadow-sm p-6">
        <form method="GET" action="{{ route('biens.index') }}" class="flex gap-4 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher (désignation, code)..."
                class="flex-1 border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857]">
            <button type="submit" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46]">Filtrer</button>
        </form>

        <table class="w-full">
            <thead>
                <tr class="border-b border-[#e5e7eb] text-left">
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Code</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Désignation</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Catégorie</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Service</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Valeur</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Statut</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($biens as $bien)
                    <tr class="border-b border-[#e5e7eb] last:border-0">
                        <td class="py-3 text-sm text-[#1f2937]">{{ $bien['code'] ?? '-' }}</td>
                        <td class="py-3 text-sm font-medium text-[#1f2937]">{{ $bien['designation'] ?? '-' }}</td>
                        <td class="py-3 text-sm text-[#6b7280]">{{ $bien['categorie']['nom'] ?? '-' }}</td>
                        <td class="py-3 text-sm text-[#6b7280]">{{ $bien['service']['nom'] ?? '-' }}</td>
                        <td class="py-3 text-sm text-[#6b7280]">{{ number_format($bien['valeur_acquisition'] ?? 0, 0, ',', ' ') }} FCFA</td>
                        <td class="py-3">
                            <span class="text-xs px-2 py-1 rounded {{ ($bien['statut'] ?? '') === 'affecte' ? 'bg-[#dbeafe] text-[#1e40af]' : 'bg-[#ecfdf5] text-[#047857]' }}">
                                {{ $bien['statut'] ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 text-right">
                            <a href="{{ route('biens.show', $bien['id']) }}" class="text-[#047857] hover:text-[#065f46] text-sm">Détail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-[#6b7280]">Aucun bien enregistré.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
