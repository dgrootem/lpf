<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Aanstelling extends Model
{
    //
    public function weekschemas(){
      return $this->hasMany(Weekschema::class);
    }

    public function leerkracht(){
      return $this->belongsTo(Leerkracht::class);
    }

    

    public function volgordeVoorDatum($datum){
      //als $datum < $referentiedatum -> return -1 want is FOUT
      //we kijken of $datum in zelfde jaar ligt als $referentieDatum
      //indien niet, dan verhogen we het weeknummer met 52 alvorens te gaan vergelijken
      $referentieDatum = Carbon::parse($this->start);

      $aantalWeekSchemas = $this->weekschemas->count();


      if ($datum < $referentieDatum) return -1; //ERROR
      if ($aantalWeekSchemas==0) return -1; //ERROR

      $dw = $datum->weekOfYear;
      if ($datum->year > $referentieDatum->year) $dw+=52; //want in volgend januari

      $volgorde = ($dw - $referentieDatum->weekOfYear) % $aantalWeekSchemas;
      return $volgorde;

    }
}
