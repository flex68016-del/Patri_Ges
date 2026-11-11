<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MaintenanceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Maintenance::query();

        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $maintenances = $query->orderBy('date_prevue')->paginate(20);
        return response()->json($maintenances);
    }

    public function show(string $id): JsonResponse
    {
        $maintenance = Maintenance::findOrFail($id);
        return response()->json($maintenance);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'bien_id' => 'required|uuid',
            'bien_libelle' => 'required|string|max:255',
            'type' => 'required|in:preventive,corrective',
            'statut' => 'required|in:planifiee,en_cours,cloturee,annulee',
            'date_prevue' => 'required|date',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'cout' => 'nullable|numeric|min:0',
            'prestataire' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'auteur_id' => 'required|uuid',
        ]);

        $maintenance = Maintenance::create($validated);
        return response()->json($maintenance, 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $maintenance = Maintenance::findOrFail($id);

        $validated = $request->validate([
            'statut' => 'sometimes|in:planifiee,en_cours,cloturee,annulee',
            'date_debut' => 'sometimes|date',
            'date_fin' => 'sometimes|date|after_or_equal:date_debut',
            'cout' => 'sometimes|numeric|min:0',
            'prestataire' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
        ]);

        $maintenance->update($validated);
        return response()->json($maintenance);
    }

    public function cloturer(Request $request, string $id): JsonResponse
    {
        $maintenance = Maintenance::findOrFail($id);

        $validated = $request->validate([
            'date_fin' => 'required|date',
            'cout' => 'required|numeric|min:0',
        ]);

        $maintenance->update([
            'statut' => 'cloturee',
            'date_fin' => $validated['date_fin'],
            'cout' => $validated['cout'],
        ]);

        return response()->json($maintenance);
    }
}
