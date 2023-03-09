<?php

namespace Database\Seeders;

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
        TaxonomyTheme::factory()->count(100)->create();
    }
}