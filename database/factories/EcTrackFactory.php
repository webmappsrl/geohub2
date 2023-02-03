<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EcTrack>
 */
class EcTrackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'geography' => $this->createLineString(),
            'geobox_areas' => $this->faker->json_encode(),
        ];
    }

    function createLineString(): string
    {
        $lat1 = $this->faker->randomFloat(2, 11, 13);
        $lat2 = $this->faker->randomFloat(2, 11, 13);
        $lng1 = $this->faker->randomFloat(2, 42, 45);
        $lng2 = $this->faker->randomFloat(2, 42, 45);
        return "LINESTRING($lat1 $lng1 0, $lat2 $lng1 0, $lat2 $lng2 0, $lat1 $lng2 0)";
    }
}