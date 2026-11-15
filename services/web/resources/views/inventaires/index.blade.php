@extends('layouts.app')

@section('title', 'Inventaires')

@section('content')
@php $canWrite = in_array(session('user.role'), ['gestionnaire', 'administrateur']); @endphp
<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-[#1f2937]">Inventaires</h1>
        @if($canWrite)
            <a href="{{ route('inventaires.create') }}" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                Nouvelle campagne
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-[#ecfdf5] border border-[#a7f3d0] text-[#065f46] rounded-md">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-md shadow-sm p-6">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#e5e7eb] text-left">
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Titre</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Période</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Statut</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($campagnes as $campagne)
                    <tr class="border-b border-[#e5e7eb] last:border-0">
                        <td class="py-3 text-sm font-medium text-[#1f2937]">{{ $campagne['titre'] ?? '-' }}</td>
                        <td class="py-3 text-sm text-[#6b7280]">{{ $campagne['date_debut'] ?? '-' }} → {{ $campagne['date_fin'] ?? '-' }}</td>
                        <td class="py-3"><span class="text-xs px-2 py-1 rounded bg-[#ecfdf5] text-[#047857]">{{ $campagne['statut'] ?? '-' }}</span></td>
                        <td class="py-3 text-right space-x-3">
                            <a href="{{ route('inventaires.constats', $campagne['id']) }}" class="text-[#047857] hover:text-[#065f46] text-sm">Constats</a>
                            <a href="{{ route('inventaires.rapport', $campagne['id']) }}" class="text-[#047857] hover:text-[#065f46] text-sm">Rapport</a>
                            @if($canWrite && in_array($campagne['statut'] ?? '', ['planifie', 'en_cours']))
                                <form method="POST" action="{{ route('inventaires.cloturer', $campagne['id']) }}" class="inline" onsubmit="return confirm('Clôturer cette campagne ?');">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-700 text-sm">Clôturer</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-8 text-center text-[#6b7280]">Aucune campagne enregistrée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
