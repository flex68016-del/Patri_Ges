<?php

namespace Database\Factories;

use App\Models\Bien;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'code' => fake()->unique()->regexify('[A-Z]{3}[0-9]{6}'),
            'designation' => fake()->sentence(3),
            'categorie_id' => \App\Models\Categorie::factory(),
            'service_id' => \App\Models\Service::factory(),
            'date_acquisition' => fake()->date(),
            'valeur_acquisition' => fake()->randomFloat(2, 1000, 1000000),
            'statut' => fake()->randomElement(['disponible', 'affecte', 'en_maintenance', 'reforme', 'cede']),
            'emplacement' => fake()->address(),
            'actif' => true,
        ];
    }
}
