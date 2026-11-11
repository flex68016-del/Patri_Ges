<?php

namespace Database\Factories;

use App\Models\Categorie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Categorie>
 */
class CategorieFactory extends Factory
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
            'code' => fake()->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'nom' => fake()->word(),
            'type_bien' => fake()->randomElement(['immobilier', 'vehicule', 'equipement']),
            'description' => fake()->sentence(),
            'duree_vie' => fake()->numberBetween(1, 20),
            'actif' => true,
        ];
    }
}
