<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Affectation;
use Illuminate\Http\JsonResponse;

class AffectationController extends Controller
{
    public function index(): JsonResponse
    {
        $affectations = Affectation::with(['bien', 'service'])->get();

        return response()->json($affectations);
    }

    public function show(string $id): JsonResponse
    {
        $affectation = Affectation::with(['bien', 'service'])->findOrFail($id);

        return response()->json($affectation);
    }
}
