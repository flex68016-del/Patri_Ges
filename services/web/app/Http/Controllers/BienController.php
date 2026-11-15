<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;

/**
 * Controleur des biens : coquille web qui valide les formulaires puis
 * delegue toutes les regles metier a l'API patrimoine-service.
 */
class BienController extends Controller
{
    public function __construct(private ApiClient $api) {}

    /** Liste paginee des biens, avec filtres recherche/categorie/statut. */
    public function index(Request $request)
    {
        $query = [];
        // On ne transmet a l'API que les filtres reellement renseignes.
        foreach (['search', 'categorie_id', 'statut'] as $param) {
            if ($request->filled($param)) {
                $query[$param] = $request->input($param);
            }
        }

        $response = $this->api->get('patrimoine', '/api/biens', $query);

        return view('biens.index', [
            'biens' => $response->json('data', []),
            'pagination' => $response->json(),
        ]);
    }

    public function create()
    {
        // Les categories et services servent a remplir les selects du formulaire.
        $categories = $this->api->get('patrimoine', '/api/categories')->json();
        $services = $this->api->get('patrimoine', '/api/services')->json();

        return view('biens.create', [
            'categories' => is_array($categories) ? $categories : [],
            'services' => is_array($services) ? $services : [],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'designation' => 'required|string|max:255',
            'categorie_id' => 'required|uuid',
            'service_id' => 'nullable|uuid',
            'date_acquisition' => 'required|date',
            'valeur_acquisition' => 'required|numeric|min:0',
            'emplacement' => 'nullable|string|max:255',
        ]);

        $response = $this->api->post('patrimoine', '/api/biens', $validated);

        if (! $response->successful()) {
            return back()->withErrors(['bien' => $response->json('message', 'Erreur lors de la création.')])->withInput();
        }

        return redirect()->route('biens.index')->with('success', 'Bien créé avec succès.');
    }

    /**
     * Fiche detail d'un bien : informations, liste des services pour
     * l'affectation et historique complet des mouvements (affectations,
     * maintenances, reformes, cessions).
     */
    public function show(string $id)
    {
        $response = $this->api->get('patrimoine', "/api/biens/{$id}");

        if (! $response->successful()) {
            abort(404);
        }

        $services = $this->api->get('patrimoine', '/api/services')->json();
        $historique = $this->api->get('patrimoine', "/api/biens/{$id}/historique")->json();

        return view('biens.show', [
            'bien' => $response->json(),
            'services' => is_array($services) ? $services : [],
            'historique' => is_array($historique) ? $historique : [],
        ]);
    }

    /** Formulaire d'edition : seuls les champs mutables sont envoyes (PUT). */
    public function edit(string $id)
    {
        $response = $this->api->get('patrimoine', "/api/biens/{$id}");

        if (! $response->successful()) {
            abort(404);
        }

        $services = $this->api->get('patrimoine', '/api/services')->json();

        return view('biens.edit', [
            'bien' => $response->json(),
            'services' => is_array($services) ? $services : [],
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'designation' => 'required|string|max:255',
            'statut' => 'required|in:disponible,affecte,en_maintenance,reforme,cede',
            'service_id' => 'nullable|uuid',
            'emplacement' => 'nullable|string|max:255',
        ]);

        $response = $this->api->put('patrimoine', "/api/biens/{$id}", $validated);

        if (! $response->successful()) {
            return back()->withErrors(['bien' => $response->json('message', 'Erreur lors de la modification.')])->withInput();
        }

        return redirect()->route('biens.show', $id)->with('success', 'Bien modifié avec succès.');
    }

    public function reformer(Request $request, string $id)
    {
        $validated = $request->validate([
            'motif' => 'required|string',
        ]);

        $response = $this->api->post('patrimoine', "/api/biens/{$id}/reformer", $validated);

        if (! $response->successful()) {
            return back()->withErrors(['reforme' => $response->json('message', 'Erreur lors de la réforme.')]);
        }

        return back()->with('success', 'Bien réformé avec succès.');
    }

    public function ceder(Request $request, string $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:vente,don,destruction',
            'date' => 'required|date',
            'valeur_cession' => 'nullable|numeric|min:0',
        ]);

        $response = $this->api->post('patrimoine', "/api/biens/{$id}/ceder", $validated);

        if (! $response->successful()) {
            return back()->withErrors(['cession' => $response->json('message', 'Erreur lors de la cession.')]);
        }

        return back()->with('success', 'Bien cédé avec succès.');
    }

    /**
     * Affecte le bien a un service : la date de debut est obligatoire car
     * elle determine la periode d'occupation dans le historique.
     */
    public function affecter(Request $request, string $id)
    {
        $validated = $request->validate([
            'service_id' => 'required|uuid',
            'date_debut' => 'required|date',
            'motif' => 'nullable|string',
        ]);

        $response = $this->api->post('patrimoine', "/api/biens/{$id}/affecter", $validated);

        if (! $response->successful()) {
            return back()->withErrors(['affectation' => $response->json('message', 'Erreur lors de l\'affectation.')]);
        }

        return back()->with('success', 'Bien affecté avec succès.');
    }
}
