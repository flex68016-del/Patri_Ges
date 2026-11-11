<?php

namespace Database\Factories;

use App\Models\Maintenance;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Maintenance>
 */
class MaintenanceFactory extends Factory
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
            'bien_id' => (string) Str::uuid(),
            'type' => fake()->randomElement(['preventive', 'corrective']),
            'description' => fake()->sentence(),
            'date_planifiee' => fake()->date(),
            'date_effective' => fake()->optional()->date(),
            'cout' => fake()->optional()->randomFloat(2, 0, 10000),
            'statut' => fake()->randomElement(['planifie', 'en_cours', 'termine', 'annule']),
            'technicien' => fake()->optional()->name(),
            'auteur_id' => (string) Str::uuid(),
        ];
    }
}
