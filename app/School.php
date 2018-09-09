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
      return $this->hasMany(Leerkracht::class,'MA_VM')
      ->union($this->hasMany(Leerkracht::class,'MA_NM'))
      ->union($this->hasMany(Leerkracht::class,'DI_VM'))
      ->union($this->hasMany(Leerkracht::class,'DI_NM'))
      ->union($this->hasMany(Leerkracht::class,'WO_VM'))
      ->union($this->hasMany(Leerkracht::class,'DO_VM'))
      ->union($this->hasMany(Leerkracht::class,'DO_NM'))
      ->union($this->hasMany(Leerkracht::class,'VR_VM'))
      ->union($this->hasMany(Leerkracht::class,'VR_NM'));
    }

    public function users(){
      return $this->belongsToMany(User::class);
    }

    public function periodes(){
      return $this->hasMany(Periode::class);
    }


}
