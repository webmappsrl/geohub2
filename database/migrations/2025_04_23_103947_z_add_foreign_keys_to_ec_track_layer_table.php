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
        Schema::table('ec_track_layer', function (Blueprint $table) {
            $table->foreign(['ec_track_id'])->references(['id'])->on('ec_tracks')->onDelete('CASCADE');
            $table->foreign(['layer_id'])->references(['id'])->on('layers')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ec_track_layer', function (Blueprint $table) {
            $table->dropForeign('ec_track_layer_ec_track_id_foreign');
            $table->dropForeign('ec_track_layer_layer_id_foreign');
        });
    }
};
