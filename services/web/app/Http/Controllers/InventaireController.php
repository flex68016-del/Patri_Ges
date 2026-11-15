<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;

class InventaireController extends Controller
{
    public function __construct(private ApiClient $api) {}

    public function index()
    {
        $response = $this->api->get('operations', '/api/campagnes-inventaire');

        return view('inventaires.index', [
            'campagnes' => $response->json('data', []),
            'pagination' => $response->json(),
        ]);
    }

    public function create()
    {
        $users = $this->api->get('auth', '/api/users')->json('data', []);

        return view('inventaires.create', ['users' => is_array($users) ? $users : []]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'inspecteur_id' => 'required|uuid',
        ]);

        $validated['statut'] = 'planifie';

        $response = $this->api->post('operations', '/api/campagnes-inventaire', $validated);

        if (! $response->successful()) {
            return back()->withErrors(['campagne' => $response->json('message', 'Erreur lors de la création.')])->withInput();
        }

        return redirect()->route('inventaires.index')->with('success', 'Campagne créée avec succès.');
    }

    public function constats(string $id)
    {
        $campagne = $this->api->get('operations', "/api/campagnes-inventaire/{$id}");

        if (! $campagne->successful()) {
            abort(404);
        }

        $biens = $this->api->get('patrimoine', '/api/biens')->json('data', []);
        $lignes = $this->api->get('operations', "/api/campagnes-inventaire/{$id}/lignes")->json('data', []);

        return view('inventaires.constats', [
            'campagne' => $campagne->json(),
            'biens' => is_array($biens) ? $biens : [],
            'lignes' => is_array($lignes) ? $lignes : [],
        ]);
    }

    public function storeLigne(Request $request, string $id)
    {
        $validated = $request->validate([
            'bien_id' => 'required|uuid',
            'bien_libelle' => 'nullable|string|max:255',
            'constat' => 'required|in:present,absent,degrade',
            'observation' => 'nullable|string',
        ]);

        if (empty($validated['bien_libelle'])) {
            $bien = $this->api->get('patrimoine', "/api/biens/{$validated['bien_id']}")->json();
            $validated['bien_libelle'] = ($bien['designation'] ?? 'Bien').' ('.($bien['code'] ?? '').')';
        }

        $response = $this->api->post('operations', "/api/campagnes-inventaire/{$id}/lignes", $validated);

        if (! $response->successful()) {
            return back()->withErrors(['ligne' => $response->json('message', 'Erreur lors de l\'enregistrement du constat.')])->withInput();
        }

        return back()->with('success', 'Constat enregistré.');
    }

    public function rapport(string $id)
    {
        $campagne = $this->api->get('operations', "/api/campagnes-inventaire/{$id}");

        if (! $campagne->successful()) {
            abort(404);
        }

        return view('inventaires.rapport', ['campagne' => $campagne->json()]);
    }

    public function cloturer(string $id)
    {
        $response = $this->api->post('operations', "/api/campagnes-inventaire/{$id}/cloturer");

        if (! $response->successful()) {
            return back()->withErrors(['campagne' => $response->json('message', 'Erreur lors de la clôture.')]);
        }

        return redirect()->route('inventaires.index')->with('success', 'Campagne clôturée avec succès.');
    }
}
