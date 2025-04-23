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
        Schema::create('ec_pois', function (Blueprint $table) {
            $table->id('id');
            $table->jsonb('properties');
            $table->text('name');
            $table->geography('geometry', 'pointz');
            $table->integer('app_id');
            $table->bigInteger('osmid')->nullable();
            $table->timestamps();

            $table->index('osmid');
            $table->index('app_id');
            $table->spatialIndex('geometry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_pois');
    }
};
