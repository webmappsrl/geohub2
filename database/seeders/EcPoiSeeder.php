<?php

namespace Database\Seeders;

use App\Models\EcPoi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EcPoiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EcPoi::factory()->count(100)->create();
    }
}
