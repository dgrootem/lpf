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
            $table->string('startDagDeel');
            $table->date('stop');
            $table->string('stopDagDeel');
            $table->unsignedInteger('school_id');
            $table->unsignedInteger('status_id');
            $table->unsignedInteger('leerkracht_id');
            //$table->string('type'); //ZT, RV  --> zit vervat in status
            $table->smallInteger('heleDag'); //1 = HELE DAG, 0 = HALVE DAG
            $table->integer('aantal_uren_van_titularis');
            $table->string('ambt');
            //opdrachtbreuk van titularis =  aantal_uren_van_titularis / schooltype->noemer

            $table->smallInteger('deleted')->default(0);

            $table->string('opmerking')->nullable();
            //$table->unsignedInteger('user_id'); //creator van de periode
            // user die periode gemaakt heeft hangt vast aan school

            $table->integer('aantalDagdelen')->default(0);
/*
            $table->smallInteger('MA_VM')->nullable();
            $table->smallInteger('MA_NM')->nullable();
            $table->smallInteger('DI_VM')->nullable();
            $table->smallInteger('DI_NM')->nullable();
            $table->smallInteger('WO_VM')->nullable();
            $table->smallInteger('DO_VM')->nullable();
            $table->smallInteger('DO_NM')->nullable();
            $table->smallInteger('VR_VM')->nullable();
            $table->smallInteger('VR_NM')->nullable();
*/
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools');
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('leerkracht_id')->references('id')->on('leerkrachts')->onDelete('cascade');
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
