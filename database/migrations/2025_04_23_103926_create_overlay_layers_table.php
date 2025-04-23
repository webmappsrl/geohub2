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
        Schema::create('overlay_layers', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->integer('app_id');
            $table->jsonb('properties');
            $table->jsonb('configuration')->nullable();
            $table->timestamps();

            $table->index('app_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overlay_layers');
    }
};
