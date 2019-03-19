<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeriodeWeekSchema extends Model
{
  public function periode(){
    return $this->belongsTo(Periode::class);
  }

  public function dagdelen(){
    return $this->hasMany(PeriodeDagDeel::class);
  }

  public function scopeVoormiddagen($query){
    return PeriodeWeekSchema::voormiddagenFull($query)->pluck('status','dag')->toArray();
  }

  public function scopeVoormiddagenFull($query){
    return PeriodeDagDeel::where('weekschema_id',$this->id)->where('deel','VM')->orderBy('dag.volgorde');
  }

  public function scopeNamiddagen($query){
    return PeriodeWeekSchema::namiddagenFull($query)->pluck('status','dag')->toArray();
  }

  public function scopeNamiddagenFull($query){
    return PeriodeDagDeel::where('weekschema_id',$this->id)->where('deel','NM')->orderBy('dag.volgorde');
  }

  public function scopeBezetteDagDelen($query){
    return $this->hasMany(PeriodeDagDeel::class)->where('status',DagDeel::BOOKED);
  }
    //
}
