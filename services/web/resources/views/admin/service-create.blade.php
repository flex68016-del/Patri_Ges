@extends('layouts.app')

@section('title', 'Administration - Nouveau service')

@section('content')
<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-[#1f2937]">Nouveau service</h1>
        <a href="{{ route('admin.references') }}" class="text-[#047857] hover:text-[#065f46]">
            ← Retour
        </a>
    </div>

    <div class="bg-white rounded-md shadow-sm p-6">
        <form method="POST" action="#">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Code</label>
                    <input type="text" name="code" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent"
                        placeholder="Ex: MIN, DIR, SERV">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Nom</label>
                    <input type="text" name="nom" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent"
                        placeholder="Ex: Ministère, Direction, Service">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Type</label>
                    <select name="type" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                        <option value="">Sélectionner un type</option>
                        <option value="ministere">Ministère</option>
                        <option value="direction">Direction</option>
                        <option value="service">Service</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Service parent</label>
                    <select name="parent_id"
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                        <option value="">Aucun (racine)</option>
                        <option value="1">Ministère</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.references') }}" class="px-4 py-2 border border-[#e5e7eb] rounded-md text-[#1f2937] hover:bg-[#f9fafb] transition-colors duration-200">
                        Annuler
                    </a>
                    <button type="submit" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                        Créer le service
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
