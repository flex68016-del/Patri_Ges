{{-- Fiche detail d'un bien : caracteristiques, VNC, actions (affectation, edition, reforme, cession) et historique. --}}
@extends('layouts.app')

@section('title', 'Detail du bien')

@section('content')
@php $role = session('user.role'); @endphp
<div>
    <div class="mb-8 flex justify-between items-center">
        <a href="{{ route('biens.index') }}" class="text-[#047857] hover:text-[#065f46]">← Retour aux biens</a>
        @if(in_array($role, ['gestionnaire', 'administrateur']))
            <a href="{{ route('biens.edit', $bien['id']) }}" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200 text-sm">
                Modifier
            </a>
        @endif
    </div>

    <h1 class="text-3xl font-semibold text-[#1f2937] mb-8">{{ $bien['designation'] ?? 'Bien' }}</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-[#ecfdf5] border border-[#a7f3d0] text-[#065f46] rounded-md">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-md shadow-sm p-6">
                <h2 class="text-xl font-semibold text-[#1f2937] mb-4">Informations</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-[#6b7280]">Code</dt><dd class="font-medium text-[#1f2937]">{{ $bien['code'] ?? '-' }}</dd></div>
                    <div><dt class="text-[#6b7280]">Catégorie</dt><dd class="font-medium text-[#1f2937]">{{ $bien['categorie']['nom'] ?? '-' }}</dd></div>
                    <div><dt class="text-[#6b7280]">Service</dt><dd class="font-medium text-[#1f2937]">{{ $bien['service']['nom'] ?? 'Non affecté' }}</dd></div>
                    <div><dt class="text-[#6b7280]">Date d'acquisition</dt><dd class="font-medium text-[#1f2937]">{{ $bien['date_acquisition'] ?? '-' }}</dd></div>
                    <div><dt class="text-[#6b7280]">Valeur d'acquisition</dt><dd class="font-medium text-[#1f2937]">{{ number_format($bien['valeur_acquisition'] ?? 0, 0, ',', ' ') }} FCFA</dd></div>
                    <div><dt class="text-[#6b7280]">Valeur nette comptable</dt><dd class="font-medium text-[#047857]">{{ number_format($bien['valeur_net_comptable'] ?? $bien['valeur_acquisition'] ?? 0, 0, ',', ' ') }} FCFA</dd></div>
                    <div><dt class="text-[#6b7280]">Statut</dt><dd><span class="text-xs px-2 py-1 rounded bg-[#ecfdf5] text-[#047857]">{{ $bien['statut'] ?? '-' }}</span></dd></div>
                    <div><dt class="text-[#6b7280]">Localisation</dt><dd class="font-medium text-[#1f2937]">{{ $bien['emplacement'] ?? '-' }}</dd></div>
                </dl>
            </div>

            <div class="bg-white rounded-md shadow-sm p-6">
                <h2 class="text-xl font-semibold text-[#1f2937] mb-4">Historique des mouvements</h2>
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#e5e7eb] text-left">
                            <th class="pb-3 text-sm font-medium text-[#6b7280]">Date</th>
                            <th class="pb-3 text-sm font-medium text-[#6b7280]">Type</th>
                            <th class="pb-3 text-sm font-medium text-[#6b7280]">Détail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historique ?? [] as $mouvement)
                            <tr class="border-b border-[#e5e7eb] last:border-0">
                                <td class="py-3 text-sm text-[#6b7280]">{{ $mouvement['date'] ?? $mouvement['created_at'] ?? '-' }}</td>
                                <td class="py-3 text-sm text-[#1f2937]">{{ $mouvement['type'] ?? '-' }}</td>
                                <td class="py-3 text-sm text-[#6b7280]">{{ is_array($mouvement['detail'] ?? null) ? json_encode($mouvement['detail'], JSON_UNESCAPED_UNICODE) : ($mouvement['detail'] ?? '-') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-6 text-center text-[#6b7280] text-sm">Aucun mouvement enregistré.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            @if(in_array($role, ['gestionnaire', 'administrateur']))
            <div class="bg-white rounded-md shadow-sm p-6">
                <h2 class="text-xl font-semibold text-[#1f2937] mb-4">Affecter à un service</h2>
                <form method="POST" action="{{ route('biens.affecter', $bien['id']) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-[#1f2937] mb-2">Service *</label>
                        <select name="service_id" required class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                            <option value="">Sélectionner</option>
                            @foreach($services ?? [] as $service)
                                <option value="{{ $service['id'] }}" @selected(($bien['service_id'] ?? null) === $service['id'])>{{ $service['nom'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#1f2937] mb-2">Date de début *</label>
                        <input type="date" name="date_debut" value="{{ date('Y-m-d') }}" required class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#1f2937] mb-2">Motif</label>
                        <input type="text" name="motif" class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                    </div>
                    <button type="submit" class="w-full bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46]">Affecter</button>
                </form>
            </div>
            @endif

            @if($role === 'administrateur' && in_array($bien['statut'] ?? '', ['disponible', 'affecte', 'en_maintenance']))
            <div class="bg-white rounded-md shadow-sm p-6">
                <h2 class="text-xl font-semibold text-[#1f2937] mb-4">Réformer le bien</h2>
                <form method="POST" action="{{ route('biens.reformer', $bien['id']) }}" class="space-y-4" onsubmit="return confirm('Confirmer la réforme de ce bien ? Cette action est définitive.');">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-[#1f2937] mb-2">Motif *</label>
                        <textarea name="motif" required rows="2" class="w-full border border-[#e5e7eb] rounded-md px-4 py-2"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Réformer</button>
                </form>
            </div>

            <div class="bg-white rounded-md shadow-sm p-6">
                <h2 class="text-xl font-semibold text-[#1f2937] mb-4">Céder le bien</h2>
                <form method="POST" action="{{ route('biens.ceder', $bien['id']) }}" class="space-y-4" onsubmit="return confirm('Confirmer la cession de ce bien ? Cette action est définitive.');">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-[#1f2937] mb-2">Type *</label>
                        <select name="type" required class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                            <option value="vente">Vente</option>
                            <option value="don">Don</option>
                            <option value="destruction">Destruction</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#1f2937] mb-2">Date *</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#1f2937] mb-2">Valeur de cession (FCFA)</label>
                        <input type="number" name="valeur_cession" min="0" class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                    </div>
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Céder</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
