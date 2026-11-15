@extends('layouts.app')

@section('title', 'Administration - Utilisateurs')

@section('content')
<div>
    <h1 class="text-3xl font-semibold text-[#1f2937] mb-8">Gestion des utilisateurs</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-[#ecfdf5] border border-[#a7f3d0] text-[#065f46] rounded-md">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-md shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-[#1f2937]">Liste des utilisateurs</h2>
            <a href="{{ route('admin.users.create') }}" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                Nouvel utilisateur
            </a>
        </div>

        <table class="w-full">
            <thead>
                <tr class="border-b border-[#e5e7eb]">
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Nom</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Email</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Rôle</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-[#6b7280]">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-b border-[#e5e7eb]">
                        <td class="py-3 px-4 text-[#1f2937]">{{ $user['prenom'] ?? '' }} {{ $user['nom'] ?? '' }}</td>
                        <td class="py-3 px-4 text-[#6b7280]">{{ $user['email'] ?? '-' }}</td>
                        <td class="py-3 px-4 text-[#6b7280]">{{ ucfirst($user['role'] ?? '-') }}</td>
                        <td class="py-3 px-4">
                            <span class="{{ ($user['statut'] ?? '') === 'actif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }} px-2 py-1 rounded text-sm">
                                {{ ($user['statut'] ?? '') === 'actif' ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-8 text-center text-[#6b7280]">Aucun utilisateur.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
