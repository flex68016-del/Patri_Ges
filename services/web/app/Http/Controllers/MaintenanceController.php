<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;

/**
 * Maintenances : planification preventive/corrective, suivi d'avancement
 * et cloture avec cout final. Toutes les ecritures passent par
 * operations-service.
 */
class MaintenanceController extends Controller
{
    public function __construct(private ApiClient $api) {}

    /** Liste des maintenances, avec libelles des biens resolus. */
    public function index()
    {
        $response = $this->api->get('operations', '/api/maintenances');
        // La liste des biens sert uniquement a traduire bien_id en libelle
        // lisible (code - designation) dans le tableau.
        $biens = $this->api->get('patrimoine', '/api/biens')->json('data', []);

        $labels = [];
        foreach ((array) $biens as $bien) {
            $labels[$bien['id']] = ($bien['code'] ?? '').' - '.($bien['designation'] ?? '');
        }

        return view('maintenances.index', [
            'maintenances' => $response->json('data', []),
            'pagination' => $response->json(),
            'biens_labels' => $labels,
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

    /** Formulaire de cloture : date effective et cout final obligatoires. */
    public function cloturerForm(string $id)
    {
        $maintenance = $this->api->get('operations', "/api/maintenances/{$id}");

        if (! $maintenance->successful()) {
            abort(404);
        }

        return view('maintenances.cloturer', ['maintenance' => $maintenance->json()]);
    }

    public function cloturer(Request $request, string $id)
    {
        $validated = $request->validate([
            'date_effective' => 'required|date',
            'cout' => 'required|numeric|min:0',
        ]);

        $response = $this->api->post('operations', "/api/maintenances/{$id}/cloturer", $validated);

        if (! $response->successful()) {
            return back()->withErrors(['cloture' => $response->json('message', 'Erreur lors de la clôture.')])->withInput();
        }

        return redirect()->route('maintenances.index')->with('success', 'Maintenance clôturée avec succès.');
    }
}
