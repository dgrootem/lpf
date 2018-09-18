<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeriodeDagDeel extends Model
{
  public function weekschema(){
    return $this->belongsTo(PeriodeWeekSchema::class);
  }

  public function dagdeel(){
    return $this->belongsTo(SchemaDagDeel::class);
  }
    //
}
