<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchemaDagDeel extends Model
{
    //
    public function weekschema(){
      return $this->belongsTo(Weekschema::class);
    }

    public function school(){
      return $this->belongsTo(School::class);
    }

    public function dag(){
      return $this->belongsTo(DOTW::class);
    }
}
