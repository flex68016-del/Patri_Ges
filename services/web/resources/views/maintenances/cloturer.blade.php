{{-- Formulaire de cloture d'une maintenance : date effective et cout final. --}}
@extends('layouts.app')

@section('title', 'Clôturer la maintenance')

@section('content')
<div>
    <div class="mb-8">
        <a href="{{ route('maintenances.index') }}" class="text-[#047857] hover:text-[#065f46]">← Retour aux maintenances</a>
    </div>

    <h1 class="text-3xl font-semibold text-[#1f2937] mb-8">Clôturer la maintenance</h1>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="bg-white rounded-md shadow-sm p-6 max-w-xl">
        <p class="text-sm text-[#6b7280] mb-6">
            Maintenance {{ $maintenance['type'] ?? '' }} — planifiée le {{ $maintenance['date_planifiee'] ?? '-' }}
        </p>
        <form method="POST" action="{{ route('maintenances.cloturer.store', $maintenance['id']) }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-[#1f2937] mb-2">Date effective *</label>
                <input type="date" name="date_effective" value="{{ old('date_effective', date('Y-m-d')) }}" required
                    class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-[#1f2937] mb-2">Coût final (FCFA) *</label>
                <input type="number" name="cout" value="{{ old('cout', $maintenance['cout'] ?? '') }}" required min="0"
                    class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
            </div>

            <div class="flex justify-end space-x-4 pt-4 border-t border-[#e5e7eb]">
                <a href="{{ route('maintenances.index') }}" class="px-4 py-2 border border-[#e5e7eb] rounded-md text-[#1f2937] hover:bg-[#f9fafb] transition-colors duration-200">
                    Annuler
                </a>
                <button type="submit" class="bg-[#047857] text-white px-6 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                    Clôturer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
