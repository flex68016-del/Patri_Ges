<?php

namespace Database\Factories;

use App\Models\CampagneInventaire;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CampagneInventaire>
 */
class CampagneInventaireFactory extends Factory
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
            'titre' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'date_debut' => fake()->date(),
            'date_fin' => fake()->date(),
            'statut' => fake()->randomElement(['planifie', 'en_cours', 'termine', 'annule']),
            'inspecteur_id' => (string) Str::uuid(),
            'auteur_id' => (string) Str::uuid(),
        ];
    }
}
