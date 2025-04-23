<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Wm\WmPackage\Models\Layer;

class CreateLayerablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layerables', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Layer::class);
            $table->morphs('layerable');
            $table->timestamps();

            $table->index('layer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('layerables');
    }
}
