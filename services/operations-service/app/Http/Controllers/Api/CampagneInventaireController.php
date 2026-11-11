<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CampagneInventaire;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CampagneInventaireController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = CampagneInventaire::with('lignes');

        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        $campagnes = $query->orderBy('periode_debut', 'desc')->paginate(20);
        return response()->json($campagnes);
    }

    public function show(string $id): JsonResponse
    {
        $campagne = CampagneInventaire::with('lignes')->findOrFail($id);
        return response()->json($campagne);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'service_id' => 'required|uuid',
            'periode_debut' => 'required|date',
            'periode_fin' => 'required|date|after_or_equal:periode_debut',
            'statut' => 'required|in:ouverte,cloturee',
            'inspecteur_id' => 'required|uuid',
        ]);

        $campagne = CampagneInventaire::create($validated);
        return response()->json($campagne, 201);
    }

    public function cloturer(string $id): JsonResponse
    {
        $campagne = CampagneInventaire::findOrFail($id);
        $campagne->update(['statut' => 'cloturee']);
        return response()->json($campagne);
    }
}
