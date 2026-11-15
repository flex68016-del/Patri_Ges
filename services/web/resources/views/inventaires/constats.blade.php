{{-- Saisie des constats d'inventaire : chaque bien est marque present, absent ou degrade. --}}
@extends('layouts.app')

@section('title', 'Constats d\'inventaire')

@section('content')
<div>
    <div class="mb-8">
        <a href="{{ route('inventaires.index') }}" class="text-[#047857] hover:text-[#065f46]">← Retour aux inventaires</a>
    </div>

    <h1 class="text-3xl font-semibold text-[#1f2937] mb-2">{{ $campagne['titre'] ?? 'Campagne' }}</h1>
    <p class="text-[#6b7280] mb-8">{{ $campagne['date_debut'] ?? '' }} → {{ $campagne['date_fin'] ?? '' }} — Statut : {{ $campagne['statut'] ?? '' }}</p>

    @if(session('success'))
        <div class="mb-4 p-4 bg-[#ecfdf5] border border-[#a7f3d0] text-[#065f46] rounded-md">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-md shadow-sm p-6">
            <h2 class="text-xl font-semibold text-[#1f2937] mb-4">Constats enregistrés ({{ count($lignes) }})</h2>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#e5e7eb] text-left">
                        <th class="pb-3 text-sm font-medium text-[#6b7280]">Bien</th>
                        <th class="pb-3 text-sm font-medium text-[#6b7280]">Constat</th>
                        <th class="pb-3 text-sm font-medium text-[#6b7280]">Observation</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lignes as $ligne)
                        <tr class="border-b border-[#e5e7eb] last:border-0">
                            <td class="py-3 text-sm font-medium text-[#1f2937]">{{ $ligne['bien_libelle'] ?? '-' }}</td>
                            <td class="py-3"><span class="text-xs px-2 py-1 rounded {{ ($ligne['constat'] ?? '') === 'present' ? 'bg-[#ecfdf5] text-[#047857]' : 'bg-[#fef3c7] text-[#92400e]' }}">{{ $ligne['constat'] ?? '-' }}</span></td>
                            <td class="py-3 text-sm text-[#6b7280]">{{ $ligne['observation'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-8 text-center text-[#6b7280]">Aucun constat enregistré.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(in_array(session('user.role'), ['gestionnaire', 'administrateur', 'inspecteur']))
        <div class="bg-white rounded-md shadow-sm p-6">
            <h2 class="text-xl font-semibold text-[#1f2937] mb-4">Nouveau constat</h2>
            <form method="POST" action="{{ route('inventaires.lignes.store', $campagne['id']) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Bien *</label>
                    <select name="bien_id" id="bien_id" required class="w-full border border-[#e5e7eb] rounded-md px-4 py-2" onchange="document.getElementById('bien_libelle').value = this.options[this.selectedIndex].dataset.label || ''">
                        <option value="">Sélectionner un bien</option>
                        @foreach($biens as $bien)
                            <option value="{{ $bien['id'] }}" data-label="{{ $bien['designation'] }} ({{ $bien['code'] ?? '' }})">{{ $bien['designation'] }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="bien_libelle" id="bien_libelle" value="{{ old('bien_libelle') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Constat *</label>
                    <select name="constat" required class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                        <option value="present">Présent</option>
                        <option value="absent">Absent</option>
                        <option value="degrade">Dégradé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Observation</label>
                    <textarea name="observation" rows="2" class="w-full border border-[#e5e7eb] rounded-md px-4 py-2"></textarea>
                </div>
                <button type="submit" class="w-full bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46]">Enregistrer le constat</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
