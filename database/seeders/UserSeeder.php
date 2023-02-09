<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Webmapp',
            'email' => 'team@webmapp.it',
            'password' => bcrypt('webmapp'),
            'is_admin' => true,
        ])->markEmailAsVerified();

        User::factory()->create([
            'name' => 'Editor',
            'email' => 'editor@webmapp.it',
            'password' => bcrypt('webmapp'),
            'is_editor' => true,
        ])->markEmailAsVerified();

        User::factory()->create([
            'name' => 'Contributor',
            'email' => 'contributor@webmapp.it',
            'password' => bcrypt('webmapp'),
        ])->markEmailAsVerified();

        // 100 contributor
        User::factory(100)->create(['password'=>bcrypt('webmapp')]);

    }
}
