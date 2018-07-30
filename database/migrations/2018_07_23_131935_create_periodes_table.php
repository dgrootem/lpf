<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodes', function (Blueprint $table) {
            $table->increments('id');
            $table->date('start');
            $table->date('stop');
            $table->unsignedInteger('school_id');
            $table->unsignedInteger('status_id');
            $table->unsignedInteger('leerkracht_id');
            $table->string('type'); //ZT, RV
            $table->smallInteger('heleDag'); //1 = HELE DAG, 0 = HALVE DAG
            $table->integer('aantal_uren_van_titularis');

            $table->string('opmerking');
            $table->unsignedInteger('user_id'); //creator van de periode

            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools');
            $table->foreign('status_id')->references('id')->on('statuses');
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
        Schema::dropIfExists('periodes');
    }
}
