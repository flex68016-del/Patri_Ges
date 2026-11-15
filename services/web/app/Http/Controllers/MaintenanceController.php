<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function __construct(private ApiClient $api) {}

    public function index()
    {
        $response = $this->api->get('operations', '/api/maintenances');

        return view('maintenances.index', [
            'maintenances' => $response->json('data', []),
            'pagination' => $response->json(),
        ]);
    }

    public function create()
    {
        $biens = $this->api->get('patrimoine', '/api/biens')->json('data', []);

        return view('maintenances.create', ['biens' => is_array($biens) ? $biens : []]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bien_id' => 'required|uuid',
            'type' => 'required|in:preventive,corrective',
            'statut' => 'required|in:planifie,en_cours,termine,annule',
            'date_planifiee' => 'required|date',
            'date_effective' => 'nullable|date',
            'cout' => 'nullable|numeric|min:0',
            'technicien' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $response = $this->api->post('operations', '/api/maintenances', $validated);

        if (! $response->successful()) {
            return back()->withErrors(['maintenance' => $response->json('message', 'Erreur lors de la création.')])->withInput();
        }

        return redirect()->route('maintenances.index')->with('success', 'Maintenance créée avec succès.');
    }
}
