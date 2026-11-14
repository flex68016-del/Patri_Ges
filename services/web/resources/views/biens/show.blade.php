@extends('layouts.app')

@section('title', 'Detail du bien')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('biens.index') }}" class="text-blue-600 hover:text-blue-700">← Retour aux biens</a>
    </div>

    <h1 class="text-3xl font-bold text-gray-900 mb-8">Detail du bien #{{ $id }}</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500">Details du bien a venir.</p>
    </div>
</div>
@endsection
