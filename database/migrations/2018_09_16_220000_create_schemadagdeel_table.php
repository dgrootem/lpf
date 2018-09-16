<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchemadagdeelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schemadagdeel', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('weekschema_id');
            $table->string('dag');
            $table->string('deel'); //VM of NM
            $table->unsignedInteger('school_id');
            $table->timestamps();

            $table->foreign('weekschema_id')->references('id')->on('weekschema');
            $table->foreign('school_id')->references('id')->on('schools');
            $table->foreign('dag')->references('naam')->on('dotws');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schemadagdeel');
    }
}
