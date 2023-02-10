<?php

namespace Database\Seeders;

use App\Enums\UserRole;
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
            'role' => UserRole::Admin->value,
        ])->markEmailAsVerified();

        User::factory()->create([
            'name' => 'Editor',
            'email' => 'editor@webmapp.it',
            'password' => bcrypt('webmapp'),
            'role' => UserRole::Editor->value,
        ])->markEmailAsVerified();

        User::factory()->create([
            'name' => 'Contributor',
            'email' => 'contributor@webmapp.it',
            'password' => bcrypt('webmapp'),
            'role' => UserRole::Contributor->value,
        ])->markEmailAsVerified();

        // 100 contributor
        User::factory(100)->create(['password' => bcrypt('webmapp')]);
        // 10 editor
        User::factory(10)->create(['role' => UserRole::Editor->value, 'password' => bcrypt('webmapp')]);
    }
}