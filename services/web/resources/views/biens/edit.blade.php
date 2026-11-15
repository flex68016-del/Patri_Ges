{{-- Formulaire de modification des champs mutables d'un bien (designation, statut, service, emplacement). --}}
@extends('layouts.app')

@section('title', 'Modifier le bien')

@section('content')
<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-[#1f2937]">Modifier le bien</h1>
        <a href="{{ route('biens.show', $bien['id']) }}" class="text-[#047857] hover:text-[#065f46]">
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

    <div class="bg-white rounded-md shadow-sm p-6">
        <form method="POST" action="{{ route('biens.update', $bien['id']) }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Code</label>
                    <input type="text" value="{{ $bien['code'] ?? '' }}" disabled
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 bg-[#f9fafb] text-[#6b7280]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Désignation *</label>
                    <input type="text" name="designation" value="{{ old('designation', $bien['designation'] ?? '') }}" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Statut *</label>
                    <select name="statut" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                        @foreach(['disponible' => 'Disponible', 'affecte' => 'Affecté', 'en_maintenance' => 'En maintenance', 'reforme' => 'Réformé', 'cede' => 'Cédé'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('statut', $bien['statut'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Service</label>
                    <select name="service_id"
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                        <option value="">Aucun service</option>
                        @foreach($services as $service)
                            <option value="{{ $service['id'] }}" @selected(old('service_id', $bien['service_id'] ?? '') === $service['id'])>{{ $service['nom'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Localisation</label>
                    <input type="text" name="emplacement" value="{{ old('emplacement', $bien['emplacement'] ?? '') }}"
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent"
                        placeholder="Ex: Bureau 203, Bâtiment A">
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-4 border-t border-[#e5e7eb]">
                <a href="{{ route('biens.show', $bien['id']) }}" class="px-4 py-2 border border-[#e5e7eb] rounded-md text-[#1f2937] hover:bg-[#f9fafb] transition-colors duration-200">
                    Annuler
                </a>
                <button type="submit" class="bg-[#047857] text-white px-6 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
