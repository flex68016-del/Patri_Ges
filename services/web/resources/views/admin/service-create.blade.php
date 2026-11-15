{{-- Formulaire de creation d'un service (ministere, direction) qui accueillera les affectations. --}}
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

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <div class="bg-white rounded-md shadow-sm p-6">
        <form method="POST" action="{{ route('admin.services.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Code *</label>
                    <input type="text" name="code" value="{{ old('code') }}" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent"
                        placeholder="Ex: MIN, DIR, SERV">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Nom *</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent"
                        placeholder="Ex: Ministère de la Santé">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Description</label>
                    <textarea name="description" rows="2" class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Responsable</label>
                    <input type="text" name="responsable" value="{{ old('responsable') }}" class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Téléphone</label>
                    <input type="text" name="telephone" value="{{ old('telephone') }}" class="w-full border border-[#e5e7eb] rounded-md px-4 py-2">
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('admin.references') }}" class="px-4 py-2 border border-[#e5e7eb] rounded-md text-[#1f2937] hover:bg-[#f9fafb] transition-colors duration-200">
                    Annuler
                </a>
                <button type="submit" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                    Créer le service
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
