@extends('layouts.app')

@section('title', 'Nouvelle maintenance')

@section('content')
<div>
    <div class="mb-8">
        <a href="{{ route('maintenances.index') }}" class="text-[#047857] hover:text-[#065f46]">← Retour aux maintenances</a>
    </div>

    <h1 class="text-3xl font-semibold text-[#1f2937] mb-8">Nouvelle maintenance</h1>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <div class="bg-white rounded-md shadow-sm p-6">
        <form method="POST" action="{{ route('maintenances.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Bien *</label>
                    <select name="bien_id" required class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                        <option value="">Sélectionner un bien</option>
                        @foreach($biens as $bien)
                            <option value="{{ $bien['id'] }}" @selected(old('bien_id') === $bien['id'])>{{ $bien['designation'] }} ({{ $bien['code'] }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Type de maintenance *</label>
                    <select name="type" required class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                        <option value="preventive" @selected(old('type') === 'preventive')>Préventive</option>
                        <option value="corrective" @selected(old('type') === 'corrective')>Corrective</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Statut *</label>
                    <select name="statut" required class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                        @foreach(['planifie' => 'Planifiée', 'en_cours' => 'En cours', 'termine' => 'Terminée', 'annule' => 'Annulée'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('statut', 'planifie') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Date planifiée *</label>
                    <input type="date" name="date_planifiee" value="{{ old('date_planifiee') }}" required class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Date effective</label>
                    <input type="date" name="date_effective" value="{{ old('date_effective') }}" class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Coût (FCFA)</label>
                    <input type="number" name="cout" value="{{ old('cout') }}" min="0" class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Technicien</label>
                    <input type="text" name="technicien" value="{{ old('technicien') }}" class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#1f2937] mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">{{ old('description') }}</textarea>
            </div>
            <button type="submit" class="bg-[#047857] text-white px-6 py-3 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                Enregistrer
            </button>
        </form>
    </div>
</div>
@endsection
