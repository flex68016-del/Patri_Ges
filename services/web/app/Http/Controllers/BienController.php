<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;

class BienController extends Controller
{
    public function __construct(private ApiClient $api) {}

    public function index(Request $request)
    {
        $query = [];
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

    public function show(string $id)
    {
        $response = $this->api->get('patrimoine', "/api/biens/{$id}");

        if (! $response->successful()) {
            abort(404);
        }

        $services = $this->api->get('patrimoine', '/api/services')->json();

        return view('biens.show', [
            'bien' => $response->json(),
            'services' => is_array($services) ? $services : [],
        ]);
    }

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
