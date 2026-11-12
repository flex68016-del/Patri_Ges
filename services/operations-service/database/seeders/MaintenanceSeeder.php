<?php

namespace Database\Seeders;

use App\Models\Maintenance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MaintenanceSeeder extends Seeder
{
    public function run(): void
    {
        $maintenances = [
            [
                'bien_id' => (string) Str::uuid(),
                'type' => 'preventive',
                'date_planifiee' => '2026-12-15',
                'date_effective' => null,
                'cout' => 50000,
                'statut' => 'planifie',
                'technicien' => 'Auto Service Côte d\'Ivoire',
                'description' => 'Entretien annuel du véhicule',
            ],
            [
                'bien_id' => (string) Str::uuid(),
                'type' => 'corrective',
                'date_planifiee' => '2026-11-20',
                'date_effective' => '2026-11-22',
                'cout' => 275000,
                'statut' => 'termine',
                'technicien' => 'Clim Express',
                'description' => 'Réparation système de climatisation',
            ],
        ];

        foreach ($maintenances as $maintenance) {
            Maintenance::create(array_merge($maintenance, ['id' => (string) Str::uuid(), 'auteur_id' => (string) Str::uuid()]));
        }
    }
}
