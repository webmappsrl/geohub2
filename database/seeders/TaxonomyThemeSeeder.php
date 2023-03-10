<?php

namespace Database\Seeders;

use App\Models\EcPoi;
use App\Models\EcTrack;
use App\Models\TaxonomyTheme;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxonomyThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (EcTrack::count() === 0) {
            EcTrack::factory(50)->create();
        }
        if (EcPoi::count() === 0) {
            EcPoi::factory(50)->create();
        }

        TaxonomyTheme::factory(100)->create();

        foreach (EcTrack::all() as $track) {
            $track->taxonomyThemes()->attach(TaxonomyTheme::inRandomOrder()->limit(rand(1, 3))->get());
        }

        foreach (EcPoi::all() as $poi) {
            $poi->taxonomyThemes()->attach(TaxonomyTheme::inRandomOrder()->limit(rand(1, 3))->get());
        }
    }
}
