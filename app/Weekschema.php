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
}
