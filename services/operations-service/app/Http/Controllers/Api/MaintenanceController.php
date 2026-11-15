<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API des maintenances : interventions preventives et correctives
 * sur les biens. Le cycle de vie va de "planifie" a "termine" via la
 * cloture, qui exige la date effective et le cout final.
 */
class MaintenanceController extends Controller
{
    /** Liste paginee des maintenances, filtrable par statut/type. */
    public function index(Request $request): JsonResponse
    {
        $query = Maintenance::query();

        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $maintenances = $query->orderBy('date_planifiee')->paginate(20);

        return response()->json($maintenances);
    }

    /** Detail d'une maintenance. */
    public function show(string $id): JsonResponse
    {
        $maintenance = Maintenance::findOrFail($id);

        return response()->json($maintenance);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'bien_id' => 'required|uuid',
            'type' => 'required|in:preventive,corrective',
            'statut' => 'required|in:planifie,en_cours,termine,annule',
            'date_planifiee' => 'required|date',
            'date_effective' => 'nullable|date',
            'cout' => 'nullable|numeric|min:0',
            'technicien' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Auteur = utilisateur du JWT, jamais fourni par le client.
        $validated['auteur_id'] = $request->attributes->get('user_id');

        $maintenance = Maintenance::create($validated);

        return response()->json($maintenance, 201);
    }

    /** Mise a jour partielle du suivi (statut, technicien, cout...). */
    public function update(Request $request, string $id): JsonResponse
    {
        $maintenance = Maintenance::findOrFail($id);

        $validated = $request->validate([
            'statut' => 'sometimes|in:planifie,en_cours,termine,annule',
            'date_effective' => 'sometimes|date',
            'cout' => 'sometimes|numeric|min:0',
            'technicien' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
        ]);

        $maintenance->update($validated);

        return response()->json($maintenance);
    }

    /**
     * Cloture la maintenance : impose statut "termine" et enregistre
     * la date effective reelle ainsi que le cout final de l'intervention.
     */
    public function cloturer(Request $request, string $id): JsonResponse
    {
        $maintenance = Maintenance::findOrFail($id);

        $validated = $request->validate([
            'date_effective' => 'required|date',
            'cout' => 'required|numeric|min:0',
        ]);

        $maintenance->update([
            'statut' => 'termine',
            'date_effective' => $validated['date_effective'],
            'cout' => $validated['cout'],
        ]);

        return response()->json($maintenance);
    }
}
