<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Periode;

class PeriodeAddOriginatingSchool extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('periodes', function (Blueprint $table) {
        $table->unsignedInteger('originating_school_id')->default(1);
        $table->foreign('originating_school_id')->references('id')->on('schools');
      });

      $periodes = Periode::with('weekschemas.dagdelen.dagdeel')->get();
      foreach($periodes as $periode){
        $dagdelen = $periode->weekschemas()->first()->dagdelen;
        foreach($dagdelen as $dagdeel){
          if ($dagdeel->dagdeel->school_id>1){
            $periode->originating_school_id = $dagdeel->dagdeel->school_id;
            //echo "Setting originating_school_id to " .$dagdeel->dagdeel->school_id . "for periode ".$periode->id;
            $periode->save();
            break; //we found the school --> process next one
          }
        }
      }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('periodes', function (Blueprint $table) {
        $table->dropForeign('periodes_originating_school_id_foreign');
        $table->dropColumn('originating_school_id');
      });
    }
}
