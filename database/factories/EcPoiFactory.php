<?php

namespace Database\Factories;

use App\Enums\UserRole;
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
        if (User::where('role', UserRole::Editor->value)->count() == 0) {
            User::factory(10)->create(['role' => UserRole::Editor->value]);
        }


        $lat = $this->faker->randomFloat(6, 10.331693, 10.665219);
        $lng = $this->faker->randomFloat(6, 43.6516, 43.873329);

        return [
            'name' => [
                'it' => $this->faker->name(),
                'en' => $this->faker->name(),
                'de' => $this->faker->name(),
                'fr' => $this->faker->name(),
                'es' => $this->faker->name(),
            ],
            'description' => [
                'it' => $this->faker->text(),
                'en' => $this->faker->text(),
                'de' => $this->faker->text(),
                'fr' => $this->faker->text(),
                'es' => $this->faker->text(),
            ],
            'geometry' => DB::raw("ST_GeomFromText('POINT($lat $lng)')"),
            'user_id' => User::where('role', UserRole::Editor->value)->inRandomOrder()->first()->id,
        ];
    }
}
