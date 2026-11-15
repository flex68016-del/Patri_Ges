<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Affectation;
use Illuminate\Http\JsonResponse;

/**
 * Consultation des affectations : liste et detail d'une affectation
 * avec ses relations bien et service.
 */
class AffectationController extends Controller
{
    /** Liste de toutes les affectations (historiques incluses). */
    public function index(): JsonResponse
    {
        $affectations = Affectation::with(['bien', 'service'])->get();

        return response()->json($affectations);
    }

    /** Detail d'une affectation precise. */
    public function show(string $id): JsonResponse
    {
        $affectation = Affectation::with(['bien', 'service'])->findOrFail($id);

        return response()->json($affectation);
    }
}
