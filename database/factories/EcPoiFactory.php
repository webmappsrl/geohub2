<?php

namespace Database\Factories;


use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

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

        // Creates some editors
        if (User::where('is_editor', true)->count() == 0) {
            User::factory(10)->create(['is_editor' => true]);
        }

        $lat = $this->faker->latitude();
        $lng = $this->faker->longitude();

        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'geometry' => DB::raw("ST_GeomFromText('POINT($lat $lng)')"),
            // 'geobox_areas' => $this->faker->json_encode(),
            'user_id' => User::where('is_editor', true)->inRandomOrder()->first()->id,
        ];
    }
}
