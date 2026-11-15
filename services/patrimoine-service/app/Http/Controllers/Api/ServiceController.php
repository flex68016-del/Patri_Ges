<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * CRUD des services (ministeres, directions) : referentiel utilise
 * pour l'affectation des biens et la responsabilite metier.
 * La suppression est logique (actif=false) pour preserver l'historique.
 */
class ServiceController extends Controller
{
    /** Liste des services actifs uniquement. */
    public function index(): JsonResponse
    {
        $services = Service::where('actif', true)->get();

        return response()->json($services);
    }

    /** Detail d'un service. */
    public function show(string $id): JsonResponse
    {
        $service = Service::findOrFail($id);

        return response()->json($service);
    }

    /** Cree un service ; le code doit etre unique. */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:services,code',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'responsable' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:255',
        ]);

        $service = Service::create($validated);

        return response()->json($service, 201);
    }

    /** Mise a jour partielle ; la regle unique exclut l'enregistrement courant. */
    public function update(Request $request, string $id): JsonResponse
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'code' => 'sometimes|string|max:50|unique:services,code,'.$id,
            'nom' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'responsable' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|nullable|email|max:255',
            'telephone' => 'sometimes|nullable|string|max:255',
            'actif' => 'sometimes|boolean',
        ]);

        $service->update($validated);

        return response()->json($service);
    }

    /** Desactive le service (suppression logique) pour garder la tracabilite. */
    public function destroy(string $id): JsonResponse
    {
        $service = Service::findOrFail($id);
        $service->update(['actif' => false]);

        return response()->json(['message' => 'Service désactivé']);
    }
}
