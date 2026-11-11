<?php

namespace Database\Factories;

use App\Models\Connexion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Connexion>
 */
class ConnexionFactory extends Factory
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
            'user_id' => \App\Models\User::factory(),
            'ip_adresse' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'statut' => 'actif',
            'connexion_at' => now(),
            'deconnexion_at' => null,
        ];
    }
}
