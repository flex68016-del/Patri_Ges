<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bien;
use App\Models\Affectation;
use App\Models\Mouvement;
use App\Models\Cession;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BienController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Bien::with(['categorie', 'serviceCourant', 'specialisation']);

        if ($request->has('service_id')) {
            $query->where('service_courant_id', $request->service_id);
        }

        if ($request->has('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('search')) {
            $query->where('designation', 'like', '%' . $request->search . '%')
                  ->orWhere('code_inventaire', 'like', '%' . $request->search . '%');
        }

        $biens = $query->where('actif', true)->paginate(20);
        return response()->json($biens);
    }

    public function show(string $id): JsonResponse
    {
        $bien = Bien::with(['categorie', 'serviceCourant', 'specialisation', 'affectations', 'mouvements', 'cession'])
                    ->findOrFail($id);

        $bien->valeur_net_comptable = $bien->calculerValeurNetComptable();

        return response()->json($bien);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code_inventaire' => 'required|string|max:50|unique:biens',
            'designation' => 'required|string|max:255',
            'categorie_id' => 'required|uuid|exists:categories,id',
            'date_acquisition' => 'required|date',
            'mode_acquisition' => 'required|in:achat,don,transfert,construction',
            'valeur_acquisition' => 'required|numeric|min:0',
            'etat' => 'required|in:neuf,bon,moyen,degrade,hors_service',
            'statut' => 'required|in:en_stock,affecte,en_maintenance,reforme,cede',
            'service_courant_id' => 'nullable|uuid|exists:services,id',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $bien = Bien::create($validated);

            Mouvement::create([
                'bien_id' => $bien->id,
                'type' => 'creation',
                'auteur_id' => $request->header('X-User-Id'),
                'detail' => ['donnees' => $validated],
            ]);

            return response()->json($bien, 201);
        });
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $bien = Bien::findOrFail($id);

        $validated = $request->validate([
            'designation' => 'sometimes|string|max:255',
            'etat' => 'sometimes|in:neuf,bon,moyen,degrade,hors_service',
            'statut' => 'sometimes|in:en_stock,affecte,en_maintenance,reforme,cede',
            'service_courant_id' => 'nullable|uuid|exists:services,id',
        ]);

        $bien->update($validated);
        return response()->json($bien);
    }

    public function affecter(Request $request, string $id): JsonResponse
    {
        $bien = Bien::findOrFail($id);

        $validated = $request->validate([
            'service_id' => 'required|uuid|exists:services,id',
            'date_debut' => 'required|date',
            'motif' => 'nullable|string',
            'reference_acte' => 'nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($bien, $validated, $request) {
            $affectation = Affectation::create([
                'bien_id' => $bien->id,
                'service_id' => $validated['service_id'],
                'date_debut' => $validated['date_debut'],
                'motif' => $validated['motif'],
                'reference_acte' => $validated['reference_acte'],
                'auteur_id' => $request->header('X-User-Id'),
            ]);

            $bien->update([
                'service_courant_id' => $validated['service_id'],
                'statut' => 'affecte',
            ]);

            Mouvement::create([
                'bien_id' => $bien->id,
                'type' => 'affectation',
                'auteur_id' => $request->header('X-User-Id'),
                'detail' => ['affectation_id' => $affectation->id, 'service_id' => $validated['service_id']],
            ]);

            return response()->json($affectation, 201);
        });
    }

    public function reformer(Request $request, string $id): JsonResponse
    {
        $bien = Bien::findOrFail($id);

        $validated = $request->validate([
            'motif' => 'required|string',
        ]);

        return DB::transaction(function () use ($bien, $validated, $request) {
            $bien->update(['statut' => 'reforme']);

            Mouvement::create([
                'bien_id' => $bien->id,
                'type' => 'reforme',
                'auteur_id' => $request->header('X-User-Id'),
                'detail' => ['motif' => $validated['motif']],
            ]);

            return response()->json(['message' => 'Bien réformé']);
        });
    }

    public function ceder(Request $request, string $id): JsonResponse
    {
        $bien = Bien::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|in:vente,don,destruction',
            'date' => 'required|date',
            'valeur_cession' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($bien, $validated, $request) {
            $cession = Cession::create([
                'bien_id' => $bien->id,
                'type' => $validated['type'],
                'date' => $validated['date'],
                'valeur_cession' => $validated['valeur_cession'],
                'valide_par_id' => $request->header('X-User-Id'),
            ]);

            $bien->update(['statut' => 'cede', 'actif' => false]);

            Mouvement::create([
                'bien_id' => $bien->id,
                'type' => 'cession',
                'auteur_id' => $request->header('X-User-Id'),
                'detail' => ['cession_id' => $cession->id, 'type' => $validated['type']],
            ]);

            return response()->json($cession, 201);
        });
    }

    public function historique(string $id): JsonResponse
    {
        $bien = Bien::findOrFail($id);
        $mouvements = Mouvement::where('bien_id', $id)->orderBy('date', 'desc')->get();
        return response()->json($mouvements);
    }
}
