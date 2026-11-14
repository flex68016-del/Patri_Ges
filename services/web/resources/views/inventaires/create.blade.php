@extends('layouts.app')

@section('title', 'Nouvel inventaire')

@section('content')
<div>
    <div class="mb-8">
        <a href="{{ route('inventaires.index') }}" class="text-blue-600 hover:text-blue-700">← Retour aux inventaires</a>
    </div>

    <h1 class="text-3xl font-bold text-gray-900 mb-8">Nouvel inventaire</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reference</label>
                <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date debut</label>
                <input type="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                Enregistrer
            </button>
        </form>
    </div>
</div>
@endsection
