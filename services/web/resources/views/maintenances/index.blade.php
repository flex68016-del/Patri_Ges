@extends('layouts.app')

@section('title', 'Maintenances')

@section('content')
<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-[#1f2937]">Maintenances</h1>
        <a href="{{ route('maintenances.create') }}" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
            Nouvelle maintenance
        </a>
    </div>

    <div class="bg-white rounded-md shadow-sm p-6">
        <p class="text-[#6b7280]">Aucune maintenance enregistree.</p>
    </div>
</div>
@endsection
