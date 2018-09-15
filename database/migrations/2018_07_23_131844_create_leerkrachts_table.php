<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeerkrachtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leerkrachts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('naam');
            $table->unsignedInteger('ambt_id');

            //deze velden bevatten de school_id van de school waar deze leerkracht die moment werkt
            $table->unsignedInteger('MA_VM')->default(1);
            $table->unsignedInteger('MA_NM')->default(1);
            $table->unsignedInteger('DI_VM')->default(1);
            $table->unsignedInteger('DI_NM')->default(1);
            $table->unsignedInteger('WO_VM')->default(1);
            $table->unsignedInteger('DO_VM')->default(1);
            $table->unsignedInteger('DO_NM')->default(1);
            $table->unsignedInteger('VR_VM')->default(1);
            $table->unsignedInteger('VR_NM')->default(1);

            $table->string('lestijden_per_week');


            $table->smallInteger('actief'); // ja / nee
            $table->timestamps();

            //$table->foreign('vaste_school_id')->references('id')->on('schools');
            $table->foreign('ambt_id')->references('id')->on('ambts');
            $table->foreign('MA_VM')->references('id')->on('schools');
            $table->foreign('MA_NM')->references('id')->on('schools');
            $table->foreign('DI_VM')->references('id')->on('schools');
            $table->foreign('DI_NM')->references('id')->on('schools');
            $table->foreign('WO_VM')->references('id')->on('schools');
            $table->foreign('DO_VM')->references('id')->on('schools');
            $table->foreign('DO_NM')->references('id')->on('schools');
            $table->foreign('VR_VM')->references('id')->on('schools');
            $table->foreign('VR_NM')->references('id')->on('schools');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leerkrachts');
    }
}
