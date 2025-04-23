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
        Schema::create('overlay_layer_layer', function (Blueprint $table) {
            $table->integer('overlay_layer_id');
            $table->bigInteger('layer_id');

            $table->index('layer_id');
            $table->index('overlay_layer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overlay_layer_layer');
    }
};
