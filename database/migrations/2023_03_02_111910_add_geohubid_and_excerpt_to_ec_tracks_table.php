<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ec_tracks', function (Blueprint $table) {
            $table->integer('geohub_id')->unique()->nullable();
            $table->text('excerpt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ec_tracks', function (Blueprint $table) {
            $table->dropColumn('geohub_id');
            $table->dropColumn('excerpt');
        });
    }
};
