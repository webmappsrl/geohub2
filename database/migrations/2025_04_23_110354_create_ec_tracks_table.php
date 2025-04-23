<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_tracks', function (Blueprint $table) {
            $table->id('id');
            $table->jsonb('properties');
            $table->text('name');
            $table->integer('app_id');
            $table->geography('geometry', 'multiLineStringz');
            $table->bigInteger('osmid')->nullable();
            $table->timestamps();

            $table->index('osmid');
            $table->index('app_id');
            $table->spatialIndex('geometry')->comment('for geography queries');
        });

        // Used on pbf generations
        DB::statement('CREATE INDEX ec_tracks_geometry_3857_index ON ec_tracks USING GIST (ST_Transform(geometry::geometry, 3857));');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_tracks');
    }
};
