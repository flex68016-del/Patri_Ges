<?php

namespace Database\Factories;

use App\Models\Bien;
use App\Models\Categorie;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Bien>
 */
class BienFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'code' => fake()->unique()->regexify('[A-Z]{3}[0-9]{6}'),
            'designation' => fake()->sentence(3),
            'categorie_id' => Categorie::factory(),
            'service_id' => Service::factory(),
            'date_acquisition' => fake()->date(),
            'valeur_acquisition' => fake()->randomFloat(2, 1000, 1000000),
            'statut' => fake()->randomElement(['disponible', 'affecte', 'en_maintenance', 'reforme', 'cede']),
            'emplacement' => fake()->address(),
            'actif' => true,
        ];
    }
}
