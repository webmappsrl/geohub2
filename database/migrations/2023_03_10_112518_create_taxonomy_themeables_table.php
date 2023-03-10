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
        Schema::create('taxonomy_themeables', function (Blueprint $table) {
            $table->foreignId('taxonomy_theme_id')->constrained();
            $table->morphs('themeable'); // <-- morphs() is a shortcut for themeable_id and themeable_type
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxonomy_themeables');
    }
};