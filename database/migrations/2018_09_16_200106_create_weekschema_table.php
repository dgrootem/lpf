<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeekschemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekschema', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('aanstelling_id');
            $table->integer('volgorde')->default(1);  //indien wekelijks terugkerend schema, is er maar 1 weekschema, met volgorde '1'
            $table->timestamps();

            $table->foreign('aanstelling_id')->references('id')->on('aanstellings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weekschema');
    }
}
