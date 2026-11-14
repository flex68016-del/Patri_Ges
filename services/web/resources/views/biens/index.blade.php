@extends('layouts.app')

@section('title', 'Biens')

@section('content')
<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-[#1f2937]">Biens</h1>
        <a href="{{ route('biens.create') }}" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
            Nouveau bien
        </a>
    </div>

    <div class="bg-white rounded-md shadow-sm p-6">
        <p class="text-[#6b7280]">Aucun bien enregistre.</p>
    </div>
</div>
@endsection
