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
        Schema::table('taxonomy_themeables', function (Blueprint $table) {
            $table->foreign(['taxonomy_theme_id'])->references(['id'])->on('taxonomy_themes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taxonomy_themeables', function (Blueprint $table) {
            $table->dropForeign('taxonomy_themeables_taxonomy_theme_id_foreign');
        });
    }
};
