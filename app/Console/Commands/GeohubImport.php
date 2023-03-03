<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Enums\UserRole;
use App\Models\EcTrack;
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

        $tracksData = json_decode(file_get_contents('https://geohub.webmapp.it/api/export/tracks'), true);
        $this->importTracks($tracksData);
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

    private function importTracks($data)
    {
        //checks if the user provided an option input
        if (!empty($this->option('customer_email'))) {
            // transform input into array
            $customerEmails = explode(',', implode(',', $this->option('customer_email')));
            $this->info("Finding selected Users tracks");
            $requestedTrack;
            $totalTracks = count($data);
            $counter = 1;

            // Filter tracks based on inputs
            foreach ($data as $key => $element) {
                $trackProps = json_decode(file_get_contents('https://geohub.webmapp.it/api/ec/track/' . $key), true);
                $this->info("checked $counter / $totalTracks");
                $counter++;

                foreach ($customerEmails as $customerEmail) {
                    if ($customerEmail == $trackProps['properties']['author_email']) {
                        $requestedTrack = $trackProps;

                        EcTrack::updateOrCreate([
                            'geohub_id' => $requestedTrack['properties']['id']
                        ], [
                            'name' => $requestedTrack['properties']['name'],
                            'description' => $requestedTrack['properties']['description'],
                            'excerpt' => $requestedTrack['properties']['excerpt'],
                            'user_id' => User::where('email', $requestedTrack['properties']['author_email'])->first()->id
                        ]);
                        $this->info("Track {$requestedTrack["properties"]["name"]["it"]} of {$requestedTrack["properties"]["author_email"]} imported correctly");
                    }
                }
            }
        }

        // If no customer email is provided, all tracks will be imported
        $tracks = count($data);
        $counter = 1;
        foreach ($data as $key => $element) {
            $this->info("Importing track $counter / $tracks");
            $counter++;

            $trackProps = json_decode(file_get_contents('https://geohub.webmapp.it/api/ec/track/' . $key), true);

            EcTrack::updateOrCreate([
                'geohub_id' => $trackProps['properties']['id']
            ], [
                'name' => $trackProps['properties']['name'],
                'description' => $trackProps['properties']['description'],
                'excerpt' => $trackProps['properties']['excerpt'],
                'user_id' => User::where('email', $trackProps['properties']['author_email'])->first()->id
            ]);
        }
    }
}
