<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'code' => 'VEH',
                'nom' => 'Véhicules',
                'type_bien' => 'vehicule',
                'duree_vie' => 5,
                'actif' => 1,
            ],
            [
                'code' => 'IMM',
                'nom' => 'Immeubles',
                'type_bien' => 'immobilier',
                'duree_vie' => 25,
                'actif' => 1,
            ],
            [
                'code' => 'MAT',
                'nom' => 'Matériel Informatique',
                'type_bien' => 'equipement',
                'duree_vie' => 3,
                'actif' => 1,
            ],
            [
                'code' => 'MOB',
                'nom' => 'Mobilier de Bureau',
                'type_bien' => 'equipement',
                'duree_vie' => 10,
                'actif' => 1,
            ],
            [
                'code' => 'TER',
                'nom' => 'Terrains',
                'type_bien' => 'immobilier',
                'duree_vie' => 0,
                'actif' => 0,
            ],
        ];

        foreach ($categories as $categorie) {
            Categorie::create(array_merge($categorie, ['id' => (string) Str::uuid()]));
        }
    }
}
