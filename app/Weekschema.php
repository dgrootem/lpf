<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Weekschema extends Model
{
    //
    protected $table = 'weekschema';

    public function aanstelling(){
      return $this->belongsTo(Aanstelling::class);
    }

    public function dagdelen(){
      return $this->hasMany(SchemaDagDeel::class);
    }

    public function scopeVoormiddagen($query){
      return Weekschema::voormiddagenFull($query)->pluck('school_id','dag')->toArray();
    }

    public function scopeVoormiddagenFull($query){
      return SchemaDagDeel::where('weekschema_id',$this->id)->where('deel','VM');
    }

    public function scopeNamiddagen($query){
      return Weekschema::namiddagenFull($query)->pluck('school_id','dag')->toArray();
    }

    public function scopeNamiddagenFull($query){
      return SchemaDagDeel::where('weekschema_id',$this->id)->where('deel','NM');
    }
}
