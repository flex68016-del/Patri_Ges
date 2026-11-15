<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CampagneInventaire;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API des campagnes d'inventaire physique : planification, suivi et
 * cloture des operations de comptage sur le terrain.
 */
class CampagneInventaireController extends Controller
{
    /** Liste paginee des campagnes, filtrable par statut/service. */
    public function index(Request $request): JsonResponse
    {
        // Eager loading des lignes de constat pour eviter le N+1.
        $query = CampagneInventaire::with('lignes');

        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        $campagnes = $query->orderBy('date_debut', 'desc')->paginate(20);

        return response()->json($campagnes);
    }

    /** Detail d'une campagne avec toutes ses lignes de constat. */
    public function show(string $id): JsonResponse
    {
        $campagne = CampagneInventaire::with('lignes')->findOrFail($id);

        return response()->json($campagne);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'statut' => 'required|in:planifie,en_cours,termine,annule',
            'inspecteur_id' => 'required|uuid',
        ]);

        // L'auteur de la campagne est l'utilisateur du JWT ;
        // l'inspecteur_id designe qui realisera le comptage.
        $validated['auteur_id'] = $request->attributes->get('user_id');

        $campagne = CampagneInventaire::create($validated);

        return response()->json($campagne, 201);
    }

    /** Cloture la campagne : le statut passe a "termine" et le rapport
     *  devient definitif (les constats ne sont plus modifiables). */
    public function cloturer(string $id): JsonResponse
    {
        $campagne = CampagneInventaire::findOrFail($id);
        $campagne->update(['statut' => 'termine']);

        return response()->json($campagne);
    }
}
