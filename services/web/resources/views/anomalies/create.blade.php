{{-- Formulaire de signalement d'une anomalie sur un bien (type, gravite, description). --}}
@extends('layouts.app')

@section('title', 'Signaler une anomalie')

@section('content')
<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-[#1f2937]">Signaler une anomalie</h1>
        <a href="{{ route('anomalies.index') }}" class="text-[#047857] hover:text-[#065f46]">
            ← Retour
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="bg-white rounded-md shadow-sm p-6 max-w-2xl">
        <form method="POST" action="{{ route('anomalies.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-[#1f2937] mb-2">Bien concerné *</label>
                <select name="bien_id" required
                    class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                    <option value="">Sélectionner un bien</option>
                    @foreach($biens as $bien)
                        <option value="{{ $bien['id'] }}" @selected(old('bien_id') === $bien['id'])>{{ $bien['code'] }} - {{ $bien['designation'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Type d'anomalie *</label>
                    <select name="type" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                        @foreach(['disparition' => 'Disparition', 'degradation' => 'Dégradation', 'inventaire_ecart' => 'Écart inventaire', 'autre' => 'Autre'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Gravité *</label>
                    <select name="gravite" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                        @foreach(['faible' => 'Faible', 'moyenne' => 'Moyenne', 'elevee' => 'Élevée', 'critique' => 'Critique'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('gravite', 'faible') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Statut *</label>
                    <select name="statut" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                        @foreach(['signale' => 'Signalée', 'en_cours' => 'En cours', 'resolu' => 'Résolue', 'clos' => 'Clôturée'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('statut', 'signale') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Date de constatation *</label>
                    <input type="date" name="date_constatation" value="{{ old('date_constatation', date('Y-m-d')) }}" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#1f2937] mb-2">Description *</label>
                <textarea name="description" required rows="4" placeholder="Décrivez l'anomalie constatée..."
                    class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">{{ old('description') }}</textarea>
            </div>

            <div class="flex justify-end space-x-4 pt-4 border-t border-[#e5e7eb]">
                <a href="{{ route('anomalies.index') }}" class="px-4 py-2 border border-[#e5e7eb] rounded-md text-[#1f2937] hover:bg-[#f9fafb] transition-colors duration-200">
                    Annuler
                </a>
                <button type="submit" class="bg-[#047857] text-white px-6 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                    Signaler l'anomalie
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
