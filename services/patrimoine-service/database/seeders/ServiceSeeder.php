<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'code' => 'MIN001',
                'nom' => 'Ministère de la Santé',
                'description' => 'Ministère chargé de la santé publique',
                'responsable' => 'Dr. Kouassi Yao',
                'email' => 'sante@patrimoine.gouv',
                'telephone' => '+225 01 02 03 04',
                'actif' => true,
            ],
            [
                'code' => 'MIN002',
                'nom' => 'Ministère de l\'Éducation',
                'description' => 'Ministère chargé de l\'éducation nationale',
                'responsable' => 'Pr. Touré Awa',
                'email' => 'education@patrimoine.gouv',
                'telephone' => '+225 01 02 05 06',
                'actif' => true,
            ],
            [
                'code' => 'DIR001',
                'nom' => 'Direction des Finances',
                'description' => 'Direction des finances et de la comptabilité',
                'responsable' => 'M. Diop Moussa',
                'email' => 'finances@patrimoine.gouv',
                'telephone' => '+225 01 02 07 08',
                'actif' => true,
            ],
            [
                'code' => 'DIR002',
                'nom' => 'Direction des Ressources Humaines',
                'description' => 'Direction de la gestion du personnel',
                'responsable' => 'Mme Koné Fatou',
                'email' => 'rh@patrimoine.gouv',
                'telephone' => '+225 01 02 09 10',
                'actif' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create(array_merge($service, ['id' => (string) Str::uuid()]));
        }
    }
}
