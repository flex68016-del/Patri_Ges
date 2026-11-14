@extends('layouts.app')

@section('title', 'Detail du bien')

@section('content')
<div>
    <div class="mb-8">
        <a href="{{ route('biens.index') }}" class="text-[#047857] hover:text-[#065f46]">← Retour aux biens</a>
    </div>

    <h1 class="text-3xl font-semibold text-[#1f2937] mb-8">Detail du bien #{{ $id }}</h1>

    <div class="bg-white rounded-md shadow-sm p-6">
        <p class="text-[#6b7280]">Details du bien a venir.</p>
    </div>
</div>
@endsection
