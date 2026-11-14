@extends('layouts.app')

@section('title', 'Administration - Nouvel utilisateur')

@section('content')
<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-[#1f2937]">Nouvel utilisateur</h1>
        <a href="{{ route('admin.users') }}" class="text-[#047857] hover:text-[#065f46]">
            ← Retour
        </a>
    </div>

    <div class="bg-white rounded-md shadow-sm p-6">
        <form method="POST" action="#">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Email</label>
                    <input type="email" name="email" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Nom</label>
                    <input type="text" name="nom" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Prénom</label>
                    <input type="text" name="prenom" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Mot de passe</label>
                    <input type="password" name="password" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Rôle</label>
                    <select name="role" required
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                        <option value="">Sélectionner un rôle</option>
                        <option value="admin">Administrateur</option>
                        <option value="gestionnaire">Gestionnaire</option>
                        <option value="agent">Agent</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#1f2937] mb-2">Service</label>
                    <select name="service_id"
                        class="w-full border border-[#e5e7eb] rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#047857] focus:border-transparent">
                        <option value="">Sélectionner un service</option>
                        <option value="1">Ministère</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.users') }}" class="px-4 py-2 border border-[#e5e7eb] rounded-md text-[#1f2937] hover:bg-[#f9fafb] transition-colors duration-200">
                        Annuler
                    </a>
                    <button type="submit" class="bg-[#047857] text-white px-4 py-2 rounded-md hover:bg-[#065f46] transition-colors duration-200">
                        Créer l'utilisateur
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
