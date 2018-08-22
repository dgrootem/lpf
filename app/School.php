<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    //
    public function school_type()
    {
        return $this->belongsTo(SchoolType::class);
    }

    public function leerkrachts(){
      return $this->belongsToMany(Leerkracht::class);
    }

    public function users(){
      return $this->belongsToMany(User::class);
    }

    public function periodes(){
      return $this->hasMany(Periode::class);
    }

    
}
