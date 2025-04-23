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
        Schema::table('overlay_layers', function (Blueprint $table) {
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
        Schema::table('overlay_layers', function (Blueprint $table) {
            $table->dropForeign('overlay_layers_app_id_foreign');
        });
    }
};
