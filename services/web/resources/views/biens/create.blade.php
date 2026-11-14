@extends('layouts.app')

@section('title', 'Nouveau bien')

@section('content')
<div>
    <div class="mb-8">
        <a href="{{ route('biens.index') }}" class="text-[#047857] hover:text-[#065f46]">← Retour aux biens</a>
    </div>

    <h1 class="text-3xl font-semibold text-[#1f2937] mb-8">Nouveau bien</h1>

    <div class="bg-white rounded-md shadow-sm p-6">
        <form class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-[#374151] mb-2">Designation</label>
                <input type="text" class="w-full px-4 py-3 border border-[#e5e7eb] rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#374151] mb-2">Categorie</label>
                <select class="w-full px-4 py-3 border border-[#e5e7eb] rounded-md">
                    <option>Selectionner une categorie</option>
                </select>
            </div>
            <button type="submit" class="bg-[#047857] text-white px-6 py-3 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                Enregistrer
            </button>
        </form>
    </div>
</div>
@endsection
