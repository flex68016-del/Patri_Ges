<?php

namespace Database\Factories;

use App\Models\Anomalie;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Anomalie>
 */
class AnomalieFactory extends Factory
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
            'type' => fake()->randomElement(['disparition', 'degradation', 'inventaire_ecart', 'autre']),
            'description' => fake()->optional()->sentence(),
            'gravite' => fake()->randomElement(['faible', 'moyenne', 'elevee', 'critique']),
            'statut' => fake()->randomElement(['signale', 'en_cours', 'resolu', 'clos']),
            'date_constatation' => fake()->date(),
            'date_resolution' => fake()->optional()->date(),
            'auteur_id' => (string) Str::uuid(),
        ];
    }
}
