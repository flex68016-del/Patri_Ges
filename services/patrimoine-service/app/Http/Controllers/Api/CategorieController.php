<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * CRUD des categories de biens : regroupent les biens par nature
 * (immobilier, vehicule, equipement) avec une duree de vie moyenne
 * utilisee pour le calcul d'amortissement et de la VNC.
 */
class CategorieController extends Controller
{
    /** Liste de toutes les categories. */
    public function index(): JsonResponse
    {
        $categories = Categorie::all();

        return response()->json($categories);
    }

    /** Detail d'une categorie. */
    public function show(string $id): JsonResponse
    {
        $categorie = Categorie::findOrFail($id);

        return response()->json($categorie);
    }

    /** Cree une categorie ; duree_vie obligatoire (annees) pour l'amortissement. */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:categories,code',
            'nom' => 'required|string|max:255',
            'type_bien' => 'required|in:immobilier,vehicule,equipement',
            'duree_vie' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $categorie = Categorie::create($validated);

        return response()->json($categorie, 201);
    }

    /** Mise a jour partielle de la categorie. */
    public function update(Request $request, string $id): JsonResponse
    {
        $categorie = Categorie::findOrFail($id);

        $validated = $request->validate([
            'code' => 'sometimes|string|max:50|unique:categories,code,'.$id,
            'nom' => 'sometimes|string|max:255',
            'type_bien' => 'sometimes|in:immobilier,vehicule,equipement',
            'duree_vie' => 'sometimes|integer|min:1',
            'description' => 'sometimes|nullable|string',
            'actif' => 'sometimes|boolean',
        ]);

        $categorie->update($validated);

        return response()->json($categorie);
    }

    /** Supprime la categorie (a n'utiliser que si aucun bien n'y est rattache). */
    public function destroy(string $id): JsonResponse
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->delete();

        return response()->json(['message' => 'Catégorie supprimée']);
    }
}
