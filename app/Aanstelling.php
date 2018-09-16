<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aanstelling extends Model
{
    //
    public function weekschemas(){
      return $this->hasMany(Weekschema::class);
    }

    public function leerkracht(){
      return $this->belongsTo(Leerkracht::class);
    }
}
