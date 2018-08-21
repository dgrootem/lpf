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
