<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;

class AnomalieController extends Controller
{
    public function __construct(private ApiClient $api) {}

    public function index(Request $request)
    {
        $query = [];
        foreach (['statut', 'type'] as $param) {
            if ($request->filled($param)) {
                $query[$param] = $request->input($param);
            }
        }

        $response = $this->api->get('operations', '/api/anomalies', $query);
        $biens = $this->api->get('patrimoine', '/api/biens')->json('data', []);

        $labels = [];
        foreach ((array) $biens as $bien) {
            $labels[$bien['id']] = ($bien['code'] ?? '').' - '.($bien['designation'] ?? '');
        }

        return view('anomalies.index', [
            'anomalies' => $response->json('data', []),
            'pagination' => $response->json(),
            'biens_labels' => $labels,
            'filters' => $query,
        ]);
    }

    public function create()
    {
        $biens = $this->api->get('patrimoine', '/api/biens')->json('data', []);

        return view('anomalies.create', ['biens' => is_array($biens) ? $biens : []]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bien_id' => 'required|uuid',
            'type' => 'required|in:disparition,degradation,inventaire_ecart,autre',
            'description' => 'required|string',
            'gravite' => 'required|in:faible,moyenne,elevee,critique',
            'statut' => 'required|in:signale,en_cours,resolu,clos',
            'date_constatation' => 'required|date',
        ]);

        $response = $this->api->post('operations', '/api/anomalies', $validated);

        if (! $response->successful()) {
            return back()->withErrors(['anomalie' => $response->json('message', 'Erreur lors de la création.')])->withInput();
        }

        return redirect()->route('anomalies.index')->with('success', 'Anomalie signalée avec succès.');
    }

    public function resoudre(string $id)
    {
        $response = $this->api->post('operations', "/api/anomalies/{$id}/resoudre");

        if (! $response->successful()) {
            return back()->withErrors(['anomalie' => $response->json('message', 'Erreur lors de la résolution.')]);
        }

        return back()->with('success', 'Anomalie marquée comme résolue.');
    }
}
