<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'libelle' => 'Véhicules',
                'type_bien' => 'vehicule',
                'duree_amortissement' => 5,
                'amortissable' => true,
            ],
            [
                'libelle' => 'Immeubles',
                'type_bien' => 'immobilier',
                'duree_amortissement' => 25,
                'amortissable' => true,
            ],
            [
                'libelle' => 'Matériel Informatique',
                'type_bien' => 'equipement',
                'duree_amortissement' => 3,
                'amortissable' => true,
            ],
            [
                'libelle' => 'Mobilier de Bureau',
                'type_bien' => 'equipement',
                'duree_amortissement' => 10,
                'amortissable' => true,
            ],
            [
                'libelle' => 'Terrains',
                'type_bien' => 'immobilier',
                'duree_amortissement' => null,
                'amortissable' => false,
            ],
        ];

        foreach ($categories as $categorie) {
            Categorie::create($categorie);
        }
    }
}
