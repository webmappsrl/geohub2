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

        $lat = $this->faker->latitude();
        $lng = $this->faker->longitude();

        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'geometry' => DB::raw("ST_GeomFromText('POINT($lat $lng)')"),
            // 'geobox_areas' => $this->faker->json_encode(),
        ];
    }
}
