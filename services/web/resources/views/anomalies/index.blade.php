{{-- Liste des anomalies avec filtres par statut et type, et action de resolution. --}}
@extends('layouts.app')

@section('title', 'Anomalies')

@section('content')
<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-[#1f2937]">Anomalies</h1>
        @if(in_array(session('user.role'), ['gestionnaire', 'administrateur', 'inspecteur']))
            <a href="{{ route('anomalies.create') }}" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                Signaler une anomalie
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-[#ecfdf5] border border-[#a7f3d0] text-[#065f46] rounded-md">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <div class="bg-white rounded-md shadow-sm p-6 mb-6">
        <form method="GET" class="flex gap-4">
            <select name="statut" class="border border-[#e5e7eb] rounded-md px-4 py-2 text-sm">
                <option value="">Tous les statuts</option>
                @foreach(['signale' => 'Signalée', 'en_cours' => 'En cours', 'resolu' => 'Résolue', 'clos' => 'Clôturée'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['statut'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="type" class="border border-[#e5e7eb] rounded-md px-4 py-2 text-sm">
                <option value="">Tous les types</option>
                @foreach(['disparition' => 'Disparition', 'degradation' => 'Dégradation', 'inventaire_ecart' => 'Écart inventaire', 'autre' => 'Autre'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['type'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-[#1f2937] text-white px-4 py-2 rounded-md text-sm hover:bg-[#374151]">Filtrer</button>
        </form>
    </div>

    <div class="bg-white rounded-md shadow-sm p-6">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#e5e7eb] text-left">
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Bien</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Type</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Gravité</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Constatée le</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Statut</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($anomalies as $anomalie)
                    <tr class="border-b border-[#e5e7eb] last:border-0">
                        <td class="py-3 text-sm font-medium text-[#1f2937]">{{ $biens_labels[$anomalie['bien_id'] ?? ''] ?? ($anomalie['bien_id'] ?? '-') }}</td>
                        <td class="py-3 text-sm text-[#6b7280]">{{ $anomalie['type'] ?? '-' }}</td>
                        <td class="py-3"><span class="text-xs px-2 py-1 rounded {{ ($anomalie['gravite'] ?? '') === 'critique' ? 'bg-red-100 text-red-800' : (($anomalie['gravite'] ?? '') === 'elevee' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }}">{{ $anomalie['gravite'] ?? '-' }}</span></td>
                        <td class="py-3 text-sm text-[#6b7280]">{{ $anomalie['date_constatation'] ?? '-' }}</td>
                        <td class="py-3"><span class="text-xs px-2 py-1 rounded bg-[#ecfdf5] text-[#047857]">{{ $anomalie['statut'] ?? '-' }}</span></td>
                        <td class="py-3 text-right">
                            @if(in_array($anomalie['statut'] ?? '', ['signale', 'en_cours']) && in_array(session('user.role'), ['gestionnaire', 'administrateur', 'inspecteur']))
                                <form method="POST" action="{{ route('anomalies.resoudre', $anomalie['id']) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-[#047857] hover:text-[#065f46] text-sm">Marquer résolue</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-[#6b7280]">Aucune anomalie enregistrée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
