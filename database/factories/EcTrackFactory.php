<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

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

        // Creates some editors
        if(User::where('is_editor',true)->count()==0) {
            User::factory(10)->create(['is_editor'=>true]);
        }

        $lat1 = $this->faker->randomFloat(2, 11, 13);
        $lat2 = $this->faker->randomFloat(2, 11, 13);
        $lng1 = $this->faker->randomFloat(2, 42, 45);
        $lng2 = $this->faker->randomFloat(2, 42, 45);

        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'geometry' => DB::raw("(ST_GeomFromText('LINESTRING($lat1 $lng1, $lat2 $lng1, $lat2 $lng2, $lat1 $lng2)'))"),
            'user_id' => User::where('is_editor',true)->inRandomOrder()->first()->id,
        ];
    }

}