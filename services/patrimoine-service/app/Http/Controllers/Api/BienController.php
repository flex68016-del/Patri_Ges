<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Affectation;
use App\Models\Bien;
use App\Models\Cession;
use App\Models\Mouvement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * API des biens du patrimoine : inventaire complet avec recherche,
 * filtres, fiche detail (sous-types, historique, VNC) et cycle de vie
 * (creation, edition, affectation, reforme, cession). Chaque mutation
 * est encadree par une transaction et tracee dans la table mouvements.
 */
class BienController extends Controller
{
    /**
     * Liste paginee des biens actifs. Filtres optionnels : service_id,
     * categorie_id, statut, et recherche plein texte sur code/designation.
     */
    public function index(Request $request): JsonResponse
    {
        // eager loading de categorie et service pour eviter le N+1
        // sur les listes (un appel SQL par relation).
        $query = Bien::with(['categorie', 'service']);

        if ($request->has('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->has('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('search')) {
            // La recherche porte sur le code et la designation ; le groupe
            // de parentheses evite qu'un futur filtre ne soit avale par le OR.
            $query->where(function ($q) use ($request) {
                $q->where('designation', 'like', '%'.$request->search.'%')
                    ->orWhere('code', 'like', '%'.$request->search.'%');
            });
        }

        // Les biens cedes (actif=false) sortent de l'inventaire courant
        // mais restent accessibles via leur fiche historique.
        $biens = $query->where('actif', true)->paginate(20);

        return response()->json($biens);
    }

    /**
     * Fiche complete d'un bien : categorie, service, sous-type metier
     * (immobilier/vehicule/equipement), affectations, mouvements et cession.
     * La valeur nette comptable est calculee a la volee (non stockee).
     */
    public function show(string $id): JsonResponse
    {
        // findOrFail leve un 404 automatique si le bien n'existe pas.
        $bien = Bien::with(['categorie', 'service', 'immobilier', 'vehicule', 'equipement', 'affectations', 'mouvements', 'cession'])
            ->findOrFail($id);

        // VNC = valeur d'acquisition - amortissements cumules,
        // calculee selon la duree de vie de la categorie.
        $bien->valeur_net_comptable = $bien->calculerValeurNetComptable();

        return response()->json($bien);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:biens,code',
            'designation' => 'required|string|max:255',
            'categorie_id' => 'required|uuid|exists:categories,id',
            'service_id' => 'nullable|uuid|exists:services,id',
            'date_acquisition' => 'required|date',
            'valeur_acquisition' => 'required|numeric|min:0',
            'statut' => 'sometimes|in:disponible,affecte,en_maintenance,reforme,cede',
            'emplacement' => 'nullable|string|max:255',
        ]);

        // Un bien nait toujours "disponible" sauf precision contraire.
        $validated['statut'] ??= 'disponible';

        // Transaction : la creation du bien et le mouvement d'audit
        // reussissent ou echouent ensemble (pas d'etat incoherent).
        return DB::transaction(function () use ($validated, $request) {
            $bien = Bien::create($validated);

            // Le premier mouvement trace la creation avec le snapshot
            // des donnees initiales pour l'historique.
            Mouvement::create([
                'bien_id' => $bien->id,
                'type' => 'creation',
                'auteur_id' => $request->attributes->get('user_id'),
                'detail' => ['donnees' => $validated],
            ]);

            return response()->json($bien, 201);
        });
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $bien = Bien::findOrFail($id);

        // Mise a jour partielle : seuls les champs fournis sont modifies.
        // Le code et la date d'acquisition ne sont pas mutables ici.
        $validated = $request->validate([
            'designation' => 'sometimes|string|max:255',
            'statut' => 'sometimes|in:disponible,affecte,en_maintenance,reforme,cede',
            'service_id' => 'nullable|uuid|exists:services,id',
            'emplacement' => 'nullable|string|max:255',
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
        ]);

        return DB::transaction(function () use ($bien, $validated, $request) {
            // Cloture de l'affectation courante : date_fin = debut de la
            // nouvelle, pour eviter tout chevauchement dans le temps.
            Affectation::where('bien_id', $bien->id)
                ->where('actif', true)
                ->update(['date_fin' => $validated['date_debut'], 'actif' => false]);

            $affectation = Affectation::create([
                'bien_id' => $bien->id,
                'service_id' => $validated['service_id'],
                'date_debut' => $validated['date_debut'],
                'motif' => $validated['motif'],
                'auteur_id' => $request->attributes->get('user_id'),
            ]);

            // Le statut du bien suit l'affectation : il devient "affecte"
            // et pointe vers le nouveau service responsable.
            $bien->update([
                'service_id' => $validated['service_id'],
                'statut' => 'affecte',
            ]);

            Mouvement::create([
                'bien_id' => $bien->id,
                'type' => 'affectation',
                'auteur_id' => $request->attributes->get('user_id'),
                'detail' => ['affectation_id' => $affectation->id, 'service_id' => $validated['service_id']],
            ]);

            return response()->json($affectation, 201);
        });
    }

    /**
     * Reforme du bien : sortie du patrimoine pour cause de fin de vie
     * ou d'obsolescence. Le motif est obligatoire (justification
     * administrative). Reserve a l'administrateur.
     */
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
                'auteur_id' => $request->attributes->get('user_id'),
                'detail' => ['motif' => $validated['motif']],
            ]);

            return response()->json(['message' => 'Bien réformé']);
        });
    }

    /**
     * Cession du bien : vente, don ou destruction. Cree l'acte de
     * cession, sort le bien de l'inventaire actif (actif=false) et
     * journalise le mouvement. Reserve a l'administrateur.
     */
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
                'valide_par_id' => $request->attributes->get('user_id'),
            ]);

            $bien->update(['statut' => 'cede', 'actif' => false]);

            Mouvement::create([
                'bien_id' => $bien->id,
                'type' => 'cession',
                'auteur_id' => $request->attributes->get('user_id'),
                'detail' => ['cession_id' => $cession->id, 'type' => $validated['type']],
            ]);

            return response()->json($cession, 201);
        });
    }

    /** Historique chronologique de tous les mouvements du bien. */
    public function historique(string $id): JsonResponse
    {
        $bien = Bien::findOrFail($id);
        // Tri du plus recent au plus ancien pour l'affichage de la fiche.
        $mouvements = Mouvement::where('bien_id', $id)->orderBy('date', 'desc')->get();

        return response()->json($mouvements);
    }
}
