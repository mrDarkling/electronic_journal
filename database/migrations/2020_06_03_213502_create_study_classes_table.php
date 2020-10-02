<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudyClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('study_classes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('start_time');
            $table->string('end_time');
            $table->bigInteger('group_id')->nullable(false);
            $table->bigInteger('study_day_id')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('study_classes');
    }
}
