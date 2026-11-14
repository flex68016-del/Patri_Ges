@extends('layouts.app')

@section('title', 'Biens')

@section('content')
<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Biens</h1>
        <a href="{{ route('biens.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Nouveau bien
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500">Aucun bien enregistre.</p>
    </div>
</div>
@endsection
