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
        Schema::create('ugc_pois', function (Blueprint $table) {
            $table->id('id');
            $table->jsonb('properties');
            $table->text('name')->default('');
            $table->geography('geometry', 'pointz');
            $table->integer('app_id');
            $table->integer('user_id');
            $table->timestamps();

            $table->index('app_id');
            $table->index('user_id');
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
        Schema::dropIfExists('ugc_pois');
    }
};
