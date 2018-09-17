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
      return SchemaDagDeel::where('weekschema_id',$this->id)->where('deel','VM')->pluck('school_id','dag');
    }

    public function scopeNamiddagen($query){
      return SchemaDagDeel::where('weekschema_id',$this->id)->where('deel','NM')->pluck('school_id','dag')->toArray();
    }
}
