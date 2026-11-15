@extends('layouts.app')

@section('title', 'Detail du bien')

@section('content')
<div>
    <div class="mb-8">
        <a href="{{ route('biens.index') }}" class="text-[#047857] hover:text-[#065f46]">← Retour aux biens</a>
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
        <div class="lg:col-span-2 bg-white rounded-md shadow-sm p-6">
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
            <h2 class="text-xl font-semibold text-[#1f2937] mb-4">Affecter à un service</h2>
            <form method="POST" action="{{ route('biens.affecter', $bien['id']) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Service *</label>
                    <select name="service_id" required class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                        <option value="">Sélectionner</option>
                        @foreach($services ?? [] as $service)
                            <option value="{{ $service['id'] }}">{{ $service['nom'] }}</option>
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
    </div>
</div>
@endsection
