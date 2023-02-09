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

        $lat = $this->faker->randomFloat(6, 10.331693, 10.665219);
        $lng = $this->faker->randomFloat(6, 43.6516, 43.873329);

        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'geometry' => DB::raw("ST_GeomFromText('POINT($lat $lng)')"),
            // 'geobox_areas' => $this->faker->json_encode(),
        ];
    }
}
