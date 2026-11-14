@extends('layouts.app')

@section('title', 'Inventaires')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Inventaires</h1>
        <a href="{{ route('inventaires.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Nouvel inventaire
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500">Aucun inventaire enregistre.</p>
    </div>
</div>
@endsection
