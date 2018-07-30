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
            $table->string('ambt');
            $table->string('lestijden_per_week');
            $table->unsignedInteger('vaste_school_id');
            $table->smallInteger('actief'); // ja / nee
            $table->timestamps();

            $table->foreign('vaste_school_id')->references('id')->on('schools');
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
