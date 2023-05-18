<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\EcPoi;
use App\Enums\UserRole;
use App\Models\EcTrack;
use App\Models\TaxonomyTheme;
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

        //IMPORT TAXONOMY
        $taxonomyThemesData = json_decode(file_get_contents($apiUrl . 'export/taxonomy/themes'), true);
        $this->importTaxonomyThemes($taxonomyThemesData);

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

        //IMPORT ECPOIS
        if (empty($this->option('customer_email'))) {
            $ecPoisData = json_decode(file_get_contents($apiUrl . 'export/pois/'), true);
        } else {
            $this->info("Finding selected Users ecpois");
            $options = $this->option('customer_email');

            $ecPoisData = json_decode(file_get_contents($apiUrl . 'export/pois/' . $options[0]), true);
            if (empty($ecPoisData)) {
                $this->info("No ecpois found for the provided customers email");
            } else {
                $count = count($ecPoisData);
                $this->info(
                    "Found $count ecpois for the provided customers email"
                );
            }
        }
        $this->importPois($ecPoisData);
    }


    private function importUsers($data, UserRole $role)
    {
        $this->info('Importing Users');
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
            $trackJson = json_decode(file_get_contents('https://geohub.webmapp.it/api/ec/track/' . "$key"), true);
            $trackProperties = $trackJson['properties'];
            $geojson_content = json_encode($trackJson['geometry']);
            EcTrack::updateOrCreate([
                'geohub_id' => $trackProperties['id']
            ], [
                'name' => $trackProperties['name'],
                'description' => $trackProperties['description'] ?? null,
                'geometry' => DB::select("SELECT ST_AsText(ST_Force2D(ST_LineMerge(ST_GeomFromGeoJSON('" . $geojson_content . "')))) As wkt")[0]->wkt,
                'excerpt' => $trackProperties['excerpt'] ?? null,
                'user_id' => User::where('email', $trackProperties['author_email'])->first()->id,
                'color' => $trackProperties['color'] ?? null
            ]);
            // update the taxonomyThemes relationship for the track
            $themeIds = $trackProperties['taxonomy']['theme'];
            $track = EcTrack::where('geohub_id', $trackProperties['id'])->first();
            foreach ($themeIds as $themeId) {
                $theme = json_decode(file_get_contents("https://geohub.webmapp.it/api/taxonomy/theme/$themeId"), true);
                $track->taxonomyThemes()->attach(TaxonomyTheme::where('identifier', $theme['identifier'])->first()->id);
                $this->info("Track {$trackProperties["name"]["it"]} of {$trackProperties["author_email"]} imported correctly");
            }
        }
    }

    private function importPois($data)
    {
        $this->info("start importing " . count($data) . " ecpois");
        foreach ($data as $key => $ecpoi) {
            $ecpoiProps = json_decode(file_get_contents('https://geohub.webmapp.it/api/ec/poi/' . "$key"), true);
            $geometry = '{"type":"Point","coordinates":[' . $ecpoiProps['geometry']['coordinates'][0] . ',' . $ecpoiProps['geometry']['coordinates'][1] . ']}';
            $geometry_poi = DB::select("SELECT ST_AsText(ST_GeomFromGeoJSON('$geometry')) As wkt")[0]->wkt;
            EcPoi::updateOrCreate([
                'geohub_id' => $ecpoiProps['properties']['id']
            ], [
                'name' => $ecpoiProps['properties']['name'],
                'description' => $ecpoiProps['properties']['description'] ?? null,
                'geometry' => $geometry_poi,
                'excerpt' => $ecpoiProps['properties']['excerpt'] ?? null,
                'user_id' => User::where('email', $ecpoiProps['properties']['author_email'])->first()->id
            ]);

            //update the taxonomyThemes relationship for the ecpoi
            $themeIds = $ecpoiProps['properties']['taxonomy']['theme'] ?? null;
            if ($themeIds) {
                $ecpoi = EcPoi::where('geohub_id', $ecpoiProps['properties']['id'])->first();
                foreach ($themeIds as $themeId) {
                    $theme = json_decode(file_get_contents("https://geohub.webmapp.it/api/taxonomy/theme/$themeId"), true);
                    $ecpoi->taxonomyThemes()->attach(TaxonomyTheme::where('identifier', $theme['identifier'])->first()->id);
                    $this->info("Ecpoi {$ecpoiProps["properties"]["name"]["it"]} of {$ecpoiProps["properties"]["author_email"]} imported correctly");
                }
            }
        } {
        }
    }

    private function importTaxonomyThemes($data)
    {
        $this->info('Importing Taxonomy Themes');
        foreach ($data as $element) {
            $name = $element['name']['it'] ? $element['name']['it'] : $element['name']['en'];
            $this->info("Creating taxonomy theme {$name}");
            TaxonomyTheme::updateOrCreate(
                [
                    'identifier' => $element['identifier']
                ],
                [
                    'name' => $element['name'] ?? null,
                ]
            );
        }
    }
}
