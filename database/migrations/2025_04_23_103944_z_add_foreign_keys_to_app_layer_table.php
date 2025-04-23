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
        Schema::table('app_layer', function (Blueprint $table) {
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
        Schema::table('app_layer', function (Blueprint $table) {
            $table->dropForeign('app_layer_layer_id_foreign');
        });
    }
};
