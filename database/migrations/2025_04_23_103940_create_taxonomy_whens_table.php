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
        Schema::create('taxonomy_whens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('app_id');
            $table->text('name');
            $table->text('description')->nullable();
            $table->string('excerpt')->nullable();
            $table->string('import_method')->nullable();
            $table->string('source_id')->nullable();
            $table->text('source')->nullable();
            $table->text('identifier')->nullable()->unique();
            $table->text('icon')->nullable();
            $table->string('color')->nullable();
            $table->integer('zindex')->nullable();
            $table->integer('feature_image')->nullable();
            $table->float('stroke_width', 0, 0)->nullable();
            $table->float('stroke_opacity', 0, 0)->nullable();
            $table->text('line_dash')->nullable();
            $table->float('min_visible_zoom', 0, 0)->nullable();
            $table->float('min_size_zoom', 0, 0)->nullable();
            $table->float('min_size', 0, 0)->nullable();
            $table->float('max_size', 0, 0)->nullable();
            $table->float('icon_zoom', 0, 0)->nullable();
            $table->float('icon_size', 0, 0)->nullable();

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
        Schema::dropIfExists('taxonomy_whens');
    }
};
