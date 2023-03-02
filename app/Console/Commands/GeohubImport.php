<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Console\Command;
use Illuminate\Hashing\BcryptHasher;

class GeohubImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geohub:import {--customer_email=*}'; // <-- allows to pass multiple values in the command line to retrieve tracks

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import geohub data. If no customer email is provided, all tracks and users will be imported.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        //IMPORT USERS
        $usersData = json_decode(file_get_contents('https://geohub.webmapp.it/api/export/editors'), true);
        $this->importUsers($usersData);
        $this->info('everything imported correctly');
    }

    private function importUsers($data)
    {
        $this->info('Importing User');
        foreach ($data as $element) {
            $this->info("Creating user {$element['name']}");

            User::updateOrCreate([
                'email' => $element['email']
            ], [
                'name' => $element['name'],
                'password' => $element['geopass'],
                'role' => UserRole::Editor,

            ]);
        }
    }
}
