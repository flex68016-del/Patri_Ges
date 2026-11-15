<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// Point d'entree du seeding patrimoine : referentiels de base.
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            ServiceSeeder::class,
            CategorieSeeder::class,
        ]);
    }
}
