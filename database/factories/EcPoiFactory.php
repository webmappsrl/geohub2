<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EcPoi>
 */
class EcPoiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $lat1 = $this->faker->randomFloat(2, 11, 13);
        $lat2 = $this->faker->randomFloat(2, 11, 13);
        $lng1 = $this->faker->randomFloat(2, 42, 45);
        $lng2 = $this->faker->randomFloat(2, 42, 45);

        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'geometry' => DB::raw("(ST_GeomFromText('LINESTRING($lat1 $lng1, $lat2 $lng1, $lat2 $lng2, $lat1 $lng2)'))"),
            'geobox_areas' => $this->faker->json_encode(),

        ];
    }
}
