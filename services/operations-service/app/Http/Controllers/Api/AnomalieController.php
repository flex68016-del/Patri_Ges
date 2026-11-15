<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anomalie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnomalieController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Anomalie::query();

        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $anomalies = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($anomalies);
    }

    public function show(string $id): JsonResponse
    {
        $anomalie = Anomalie::findOrFail($id);

        return response()->json($anomalie);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'bien_id' => 'required|uuid',
            'type' => 'required|in:disparition,degradation,inventaire_ecart,autre',
            'description' => 'required|string',
            'gravite' => 'required|in:faible,moyenne,elevee,critique',
            'statut' => 'required|in:signale,en_cours,resolu,clos',
            'date_constatation' => 'required|date',
        ]);

        $validated['declare_par_id'] = $request->attributes->get('user_id');

        $anomalie = Anomalie::create($validated);

        return response()->json($anomalie, 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $anomalie = Anomalie::findOrFail($id);

        $validated = $request->validate([
            'statut' => 'sometimes|in:signale,en_cours,resolu,clos',
            'description' => 'sometimes|string',
            'date_resolution' => 'sometimes|date',
        ]);

        $anomalie->update($validated);

        return response()->json($anomalie);
    }

    public function resoudre(string $id): JsonResponse
    {
        $anomalie = Anomalie::findOrFail($id);
        $anomalie->update(['statut' => 'resolu', 'date_resolution' => now()->toDateString()]);

        return response()->json($anomalie);
    }
}
