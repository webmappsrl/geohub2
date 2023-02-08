<?php

namespace Database\Seeders;

use App\Models\EcTrack;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EcTrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EcTrack::factory()->count(100)->create();
    }
}
