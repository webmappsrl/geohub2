<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        //
    }
}
