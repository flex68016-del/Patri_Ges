<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Categorie::all();

        return response()->json($categories);
    }

    public function show(string $id): JsonResponse
    {
        $categorie = Categorie::findOrFail($id);

        return response()->json($categorie);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255|unique:categories',
            'type_bien' => 'required|in:immobilier,vehicule,equipement',
            'duree_amortissement' => 'required|integer|min:1',
            'amortissable' => 'required|boolean',
        ]);

        $categorie = Categorie::create($validated);

        return response()->json($categorie, 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $categorie = Categorie::findOrFail($id);

        $validated = $request->validate([
            'libelle' => 'sometimes|string|max:255|unique:categories,libelle,'.$id,
            'type_bien' => 'sometimes|in:immobilier,vehicule,equipement',
            'duree_amortissement' => 'sometimes|integer|min:1',
            'amortissable' => 'sometimes|boolean',
        ]);

        $categorie->update($validated);

        return response()->json($categorie);
    }

    public function destroy(string $id): JsonResponse
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->delete();

        return response()->json(['message' => 'Catégorie supprimée']);
    }
}
