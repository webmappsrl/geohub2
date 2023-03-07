<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Enums\UserRole;
use App\Models\EcTrack;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Hashing\BcryptHasher;

class GeohubImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geohub:import {--customer_email=* : comma separated emails (e.g. portable@webmapp.it,ucvs@webmapp.it)}'; // <-- allows to pass multiple values in the command line to retrieve tracks

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
        $apiUrl = 'https://geohub.webmapp.it/api/';

        //IMPORT ADMIN USERs
        $adminUsersData = json_decode(file_get_contents($apiUrl . 'export/admins'), true);
        $this->importUsers($adminUsersData, UserRole::Admin);

        //IMPORT EDITOR USERS
        $editorUsersData = json_decode(file_get_contents($apiUrl . 'export/editors'), true);
        $this->importUsers($editorUsersData, UserRole::Editor);

        //IMPORT TRACKS

        if (empty($this->option('customer_email'))) {
            $tracksData = json_decode(file_get_contents($apiUrl . 'export/tracks/'), true);
        } else {
            $this->info("Finding selected Users tracks");
            $options = $this->option('customer_email');

            $tracksData = json_decode(file_get_contents($apiUrl . 'export/tracks/' . $options[0]), true);
            if (empty($tracksData)) {
                $this->info("No tracks found for the provided customers email");
            } else {
                $count = count($tracksData);
                $this->info(
                    "Found $count tracks for the provided customers email"
                );
            }
        }
        $this->importTracks($tracksData);
    }


    private function importUsers($data, UserRole $role)
    {
        $this->info('Importing User');
        foreach ($data as $element) {
            $this->info("Creating user {$element['name']}");

            User::updateOrCreate([
                'email' => $element['email']
            ], [
                'name' => $element['name'],
                'password' => $element['geopass'],
                'role' => $role
            ]);
        }
    }

    private function importTracks($data)
    {
        $this->info("start importing " . count($data) . " tracks");
        foreach ($data as $key => $track) {
            $trackProps = json_decode(file_get_contents('https://geohub.webmapp.it/api/ec/track/' . "$key"), true);
            $geojson_content = json_encode($trackProps['geometry']);
            EcTrack::updateOrCreate([
                'geohub_id' => $trackProps['properties']['id']
            ], [
                'name' => $trackProps['properties']['name'],
                'description' => $trackProps['properties']['description'],
                'geometry' => DB::select("SELECT ST_AsText(ST_Force2D(ST_LineMerge(ST_GeomFromGeoJSON('" . $geojson_content . "')))) As wkt")[0]->wkt,
                'excerpt' => $trackProps['properties']['excerpt'],
                'user_id' => User::where('email', $trackProps['properties']['author_email'])->first()->id
            ]);
            $this->info("Track {$trackProps["properties"]["name"]["it"]} of {$trackProps["properties"]["author_email"]} imported correctly");
        } {
        }
    }
}
