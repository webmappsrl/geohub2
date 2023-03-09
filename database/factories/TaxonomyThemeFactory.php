<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaxonomyTheme>
 */
class TaxonomyThemeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => [
                'it' => $this->faker->name(),
                'en' => $this->faker->name(),
                'de' => $this->faker->name(),
                'fr' => $this->faker->name(),
                'es' => $this->faker->name(),
            ],
            'identifier' => $this->faker->unique()->word(),
        ];
    }
}