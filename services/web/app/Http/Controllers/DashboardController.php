<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;

/**
 * Tableau de bord : agrege les compteurs et les dernieres donnees des trois
 * microservices (patrimoine, operations) pour presenter une vue d'ensemble.
 */
class DashboardController extends Controller
{
    public function __construct(private ApiClient $api) {}

    /**
     * Affiche le dashboard avec le nombre total de biens, maintenances,
     * campagnes d'inventaire et anomalies, plus les elements recents.
     */
    public function index()
    {
        // Quatre appels paralleles logiques vers les API : les compteurs
        // viennent du champ "total" de chaque liste paginee.
        $biens = $this->api->get('patrimoine', '/api/biens');
        $maintenances = $this->api->get('operations', '/api/maintenances');
        $anomalies = $this->api->get('operations', '/api/anomalies');
        $campagnes = $this->api->get('operations', '/api/campagnes-inventaire');

        return view('dashboard', [
            'nbBiens' => $biens->json('total', 0),
            'nbMaintenances' => $maintenances->json('total', 0),
            'nbCampagnes' => $campagnes->json('total', 0),
            'nbAnomalies' => $anomalies->json('total', 0),
            'biens' => array_slice($biens->json('data', []), 0, 5),
            'maintenances' => $maintenances->json('data', []),
            'anomalies' => array_slice($anomalies->json('data', []), 0, 5),
        ]);
    }
}
