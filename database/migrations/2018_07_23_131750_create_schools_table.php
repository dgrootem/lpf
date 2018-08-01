<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->increments('id');
            $table->string('naam');
            $table->string('adres');
            $table->integer('postcode');
            $table->string('gemeente');
            $table->integer('lestijden_per_week');
            $table->unsignedInteger('school_type_id'); //BaO ofwel BuO
            $table->timestamps();

            $table->foreign('school_type_id')->references('id')->on('school_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schools');
    }
}
