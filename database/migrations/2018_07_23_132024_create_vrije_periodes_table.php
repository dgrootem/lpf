<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVrijePeriodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vrije_periodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('naam');
            $table->date('start');
            $table->date('stop');
            $table->unsignedInteger('school_id')->nullable(); //NULL == geldt voor alle scholen
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vrije_periodes');
    }
}
