<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
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
            'nom' => fake()->company(),
            'description' => fake()->sentence(),
            'responsable' => fake()->name(),
            'email' => fake()->email(),
            'telephone' => fake()->phoneNumber(),
            'actif' => true,
        ];
    }
}
