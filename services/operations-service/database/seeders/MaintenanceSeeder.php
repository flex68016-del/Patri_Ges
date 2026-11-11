<?php

namespace Database\Seeders;

use App\Models\Maintenance;
use Illuminate\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    public function run(): void
    {
        $maintenances = [
            [
                'bien_id' => '00000000-0000-0000-0000-000000000001',
                'type' => 'preventive',
                'date_prevue' => '2026-12-15',
                'date_realisee' => null,
                'statut' => 'planifie',
                'cout_estime' => 50000,
                'cout_reel' => null,
                'description' => 'Entretien annuel du véhicule',
                'prestataire' => 'Auto Service Côte d\'Ivoire',
            ],
            [
                'bien_id' => '00000000-0000-0000-0000-000000000002',
                'type' => 'corrective',
                'date_prevue' => '2026-11-20',
                'date_realisee' => '2026-11-22',
                'statut' => 'termine',
                'cout_estime' => 250000,
                'cout_reel' => 275000,
                'description' => 'Réparation système de climatisation',
                'prestataire' => 'Clim Express',
            ],
        ];

        foreach ($maintenances as $maintenance) {
            Maintenance::create($maintenance);
        }
    }
}
