<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeerkrachtSchoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leerkracht_school', function (Blueprint $table) {
          $table->unsignedInteger('school_id');
          $table->unsignedInteger('leerkracht_id');
          $table->smallInteger('MA_V');
          $table->smallInteger('MA_N');
          $table->smallInteger('DI_V');
          $table->smallInteger('DI_N');
          $table->smallInteger('WO_V');
          //$table->smallInteger('WO_N');
          $table->smallInteger('DO_V');
          $table->smallInteger('DO_N');
          $table->smallInteger('VR_V');
          $table->smallInteger('VR_N');

          $table->string('lestijden_per_week');

          $table->foreign('school_id')->references('id')->on('schools');
          $table->foreign('leerkracht_id')->references('id')->on('leerkrachts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leerkracht_school');
    }
}
