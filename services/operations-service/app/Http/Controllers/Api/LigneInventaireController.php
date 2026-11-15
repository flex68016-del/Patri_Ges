<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LigneInventaire;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LigneInventaireController extends Controller
{
    public function index(string $campagneId): JsonResponse
    {
        $lignes = LigneInventaire::where('campagne_id', $campagneId)->get();

        return response()->json([
            'success' => true,
            'data' => $lignes,
        ]);
    }

    public function store(Request $request, string $campagneId): JsonResponse
    {
        $validated = $request->validate([
            'bien_id' => 'required|uuid',
            'bien_libelle' => 'required|string|max:255',
            'constat' => 'required|in:present,absent,degrade',
            'observation' => 'nullable|string',
        ]);

        $ligne = LigneInventaire::create([
            'campagne_id' => $campagneId,
            'bien_id' => $validated['bien_id'],
            'bien_libelle' => $validated['bien_libelle'],
            'constat' => $validated['constat'],
            'observation' => $validated['observation'] ?? null,
            'constate_par_id' => $request->attributes->get('user_id'),
            'constate_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $ligne,
        ], 201);
    }
}
