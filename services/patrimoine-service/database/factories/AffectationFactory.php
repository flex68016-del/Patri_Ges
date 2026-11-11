<?php

namespace Database\Factories;

use App\Models\Affectation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Affectation>
 */
class AffectationFactory extends Factory
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
            'bien_id' => \App\Models\Bien::factory(),
            'service_id' => \App\Models\Service::factory(),
            'auteur_id' => (string) \Illuminate\Support\Str::uuid(),
            'date_debut' => fake()->date(),
            'date_fin' => fake()->optional()->date(),
            'motif' => fake()->sentence(),
            'actif' => true,
        ];
    }
}
