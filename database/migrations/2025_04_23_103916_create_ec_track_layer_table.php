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
        Schema::create('ec_track_layer', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('layer_id');
            $table->bigInteger('ec_track_id');
            $table->timestamps();
            $table->index(['layer_id', 'ec_track_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_track_layer');
    }
};
