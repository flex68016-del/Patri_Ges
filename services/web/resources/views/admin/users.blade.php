@extends('layouts.app')

@section('title', 'Administration - Utilisateurs')

@section('content')
<div>
    <h1 class="text-3xl font-semibold text-[#1f2937] mb-8">Gestion des utilisateurs</h1>

    <div class="bg-white rounded-md shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-[#1f2937]">Liste des utilisateurs</h2>
            <button class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                Nouvel utilisateur
            </button>
        </div>

        <table class="w-full">
            <thead>
                <tr class="border-b border-[#e5e7eb]">
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Email</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Role</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Service</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Statut</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-[#e5e7eb]">
                    <td class="py-3 px-4 text-[#1f2937]">admin@patrimoine.gouv</td>
                    <td class="py-3 px-4 text-[#6b7280]">Administrateur</td>
                    <td class="py-3 px-4 text-[#6b7280]">-</td>
                    <td class="py-3 px-4"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">Actif</span></td>
                    <td class="py-3 px-4">
                        <button class="text-[#047857] hover:text-[#065f46] mr-2">Modifier</button>
                        <button class="text-red-600 hover:text-red-700">Désactiver</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection