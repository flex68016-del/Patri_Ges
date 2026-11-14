@extends('layouts.app')

@section('title', 'Inventaire - Constats')

@section('content')
<div>
    <div class="mb-8">
        <a href="{{ route('inventaires.index') }}" class="text-[#047857] hover:text-[#065f46]">← Retour aux inventaires</a>
    </div>

    <h1 class="text-3xl font-semibold text-[#1f2937] mb-8">Saisie des constats</h1>

    <div class="bg-white rounded-md shadow-sm p-6 mb-6">
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-[#374151] mb-2">Campagne</label>
                <select class="w-full px-4 py-3 border border-[#e5e7eb] rounded-md">
                    <option>Campagne 2026-001</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#374151] mb-2">Service</label>
                <select class="w-full px-4 py-3 border border-[#e5e7eb] rounded-md">
                    <option>Ministère</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#374151] mb-2">Bien</label>
                <select class="w-full px-4 py-3 border border-[#e5e7eb] rounded-md">
                    <option>TG-VEH-2026-000001</option>
                </select>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-[#374151] mb-2">Constat</label>
            <div class="flex gap-4">
                <label class="flex items-center">
                    <input type="radio" name="constat" value="present" class="mr-2" checked>
                    <span class="text-[#1f2937]">Présent</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="constat" value="absent" class="mr-2">
                    <span class="text-[#1f2937]">Absent</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="constat" value="degrade" class="mr-2">
                    <span class="text-[#1f2937]">Dégradé</span>
                </label>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-[#374151] mb-2">Observation</label>
            <textarea class="w-full px-4 py-3 border border-[#e5e7eb] rounded-md" rows="3"></textarea>
        </div>

        <button class="bg-[#047857] text-white px-6 py-3 rounded-md hover:bg-[#065f46] transition-colors duration-200">
            Enregistrer le constat
        </button>
    </div>

    <div class="bg-white rounded-md shadow-sm p-6">
        <h2 class="text-xl font-semibold text-[#1f2937] mb-4">Constats enregistrés</h2>
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#e5e7eb]">
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Bien</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Constat</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Observation</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-[#e5e7eb]">
                    <td class="py-3 px-4 text-[#1f2937]">TG-VEH-2026-000001</td>
                    <td class="py-3 px-4"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">Présent</span></td>
                    <td class="py-3 px-4 text-[#6b7280]">-</td>
                    <td class="py-3 px-4">
                        <button class="text-[#047857] hover:text-[#065f46]">Modifier</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection