<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodeDagDeelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periode_dag_deels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('periode_week_schema_id');
            $table->unsignedInteger('dagdeel_id');
            $table->integer('status'); //UNAVAILABLE, AVAILABLE, BOOKED
            $table->timestamps();

            $table->foreign('periode_week_schema_id')->references('id')->on('periode_week_schemas');
            $table->foreign('dagdeel_id')->references('id')->on('schemadagdeel');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periode_dag_deels');
    }
}
