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
        Schema::create('hoqu_caller_jobs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('job_id');
            $table->string('class');
            $table->morphs('feature');
            $table->string('field_to_update');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hoqu_caller_jobs');
    }
};
