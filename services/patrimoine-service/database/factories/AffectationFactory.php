<?php

namespace Database\Factories;

use App\Models\Affectation;
use App\Models\Bien;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'id' => (string) Str::uuid(),
            'bien_id' => Bien::factory(),
            'service_id' => Service::factory(),
            'auteur_id' => (string) Str::uuid(),
            'date_debut' => fake()->date(),
            'date_fin' => fake()->optional()->date(),
            'motif' => fake()->sentence(),
            'actif' => true,
        ];
    }
}
