@extends('layouts.app')

@section('title', 'Maintenances')

@section('content')
@php $canWrite = in_array(session('user.role'), ['gestionnaire', 'administrateur']); @endphp
<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-[#1f2937]">Maintenances</h1>
        @if($canWrite)
            <a href="{{ route('maintenances.create') }}" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                Nouvelle maintenance
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

    <div class="bg-white rounded-md shadow-sm p-6">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#e5e7eb] text-left">
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Bien</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Type</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Planifiée le</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Effectuée le</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Coût</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]">Statut</th>
                    <th class="pb-3 text-sm font-medium text-[#6b7280]"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($maintenances as $m)
                    <tr class="border-b border-[#e5e7eb] last:border-0">
                        <td class="py-3 text-sm font-medium text-[#1f2937]">{{ $biens_labels[$m['bien_id'] ?? ''] ?? '-' }}</td>
                        <td class="py-3 text-sm text-[#6b7280]">{{ $m['type'] ?? '-' }}</td>
                        <td class="py-3 text-sm text-[#6b7280]">{{ $m['date_planifiee'] ?? '-' }}</td>
                        <td class="py-3 text-sm text-[#6b7280]">{{ $m['date_effective'] ?? '-' }}</td>
                        <td class="py-3 text-sm text-[#6b7280]">{{ isset($m['cout']) ? number_format($m['cout'], 0, ',', ' ').' FCFA' : '-' }}</td>
                        <td class="py-3"><span class="text-xs px-2 py-1 rounded bg-[#ecfdf5] text-[#047857]">{{ $m['statut'] ?? '-' }}</span></td>
                        <td class="py-3 text-right">
                            @if($canWrite && in_array($m['statut'] ?? '', ['planifie', 'en_cours']))
                                <a href="{{ route('maintenances.cloturer', $m['id']) }}" class="text-[#047857] hover:text-[#065f46] text-sm">Clôturer</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-8 text-center text-[#6b7280]">Aucune maintenance enregistrée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
