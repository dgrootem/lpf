<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAanstellingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aanstellings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('leerkracht_id');
            $table->integer('lestijden_per_week');
            $table->date('start');
            $table->date('stop')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('aanstellings');
    }
}
