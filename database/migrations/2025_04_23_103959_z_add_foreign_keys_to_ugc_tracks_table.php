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
        Schema::table('ugc_tracks', function (Blueprint $table) {
            $table->foreign(['app_id'])->references(['id'])->on('apps')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ugc_tracks', function (Blueprint $table) {
            $table->dropForeign('ugc_tracks_app_id_foreign');
        });
    }
};
