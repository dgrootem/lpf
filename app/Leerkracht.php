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

    public function scopeBelongsToSchool($query,$school_id){
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

    public function ma_vm(){
      return $this->belongsTo(School::class,'MA_VM','id');
    }
    public function ma_nm(){
      return $this->belongsTo(School::class,'MA_NM','id');
    }
    public function di_vm(){
      return $this->belongsTo(School::class,'DI_VM','id');
    }
    public function di_nm(){
      return $this->belongsTo(School::class,'DI_NM','id');
    }
    public function wo_vm(){
      return $this->belongsTo(School::class,'WO_VM','id');
    }
    public function do_vm(){
      return $this->belongsTo(School::class,'DO_VM','id');
    }
    public function do_nm(){
      return $this->belongsTo(School::class,'DO_NM','id');
    }
    public function vr_vm(){
      return $this->belongsTo(School::class,'VR_VM','id');
    }
    public function vr_nm(){
      return $this->belongsTo(School::class,'VR_NM','id');
    }

    /*
    public function schools(){
      return $this->belongsTo(School::class,'MA_VM')
      ->union($this->belongsTo(School::class,'DO_VM'));
    }
    */
}
