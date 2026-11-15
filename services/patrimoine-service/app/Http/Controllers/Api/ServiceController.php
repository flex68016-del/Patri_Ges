<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        $services = Service::where('actif', true)->get();

        return response()->json($services);
    }

    public function show(string $id): JsonResponse
    {
        $service = Service::findOrFail($id);

        return response()->json($service);
    }

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

    public function destroy(string $id): JsonResponse
    {
        $service = Service::findOrFail($id);
        $service->update(['actif' => false]);

        return response()->json(['message' => 'Service désactivé']);
    }
}
