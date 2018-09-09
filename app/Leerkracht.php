<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leerkracht extends Model
{
    //
    protected $guarded = ['id','naam','ambt_id','actief'];


    public function ambt()
    {
        return $this->belongsTo(Ambt::class);
    }

    public function scopeBelongsToSchool($quer,$school_id){
      return $query->where('MA_VM',$school_id)
                   ->orWhere('MA_NM',$school_id)
                   ->orWhere('DI_VM',$school_id)
                   ->orWhere('DI_NM',$school_id)
                   ->orWhere('WO_VM',$school_id)
                   ->orWhere('DO_VM',$school_id)
                   ->orWhere('DO_NM',$school_id)
                   ->orWhere('VR_VM',$school_id)
                   ->orWhere('VR_NM',$school_id);
      //return $this->belongsTo(School::class);
    }
    /*
    public function schools(){
      return $this->belongsTo(School::class,'MA_VM')
      ->union($this->belongsTo(School::class,'DO_VM'));
    }
    */
}
