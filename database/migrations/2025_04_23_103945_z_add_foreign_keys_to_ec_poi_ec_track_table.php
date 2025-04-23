<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ec_poi_ec_track', function (Blueprint $table) {
            $table->foreign(['ec_poi_id'])->references(['id'])->on('ec_pois')->onDelete('CASCADE');
            $table->foreign(['ec_track_id'])->references(['id'])->on('ec_tracks')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ec_poi_ec_track', function (Blueprint $table) {
            $table->dropForeign('ec_poi_ec_track_ec_poi_id_foreign');
            $table->dropForeign('ec_poi_ec_track_ec_track_id_foreign');
        });
    }
};
