<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class School extends Model
{
    //
    public function school_type()
    {
        return $this->belongsTo(SchoolType::class);
    }

    public function leerkrachts(){
        $leerkrachten = DB::table('leerkrachts')
            ->join('aanstellings','aanstellings.leerkracht_id','=','leerkrachts.id')
            ->join('weekschema','weekschema.aanstelling_id','=','aanstellings.id')
            ->join('schemadagdeel','schemadagdeel.weekschema_id','=','weekschema.id')
            ->where('schemadagdeel.school_id',$this->id)
            ->select('leerkrachts.id')
            ->get()->unique()->pluck('id')->toArray();
        return Leerkracht::whereIn('id',$leerkrachten);
      //return $leerkrachts->where('school_id',$this->id);
    }

    public function users(){
      return $this->belongsToMany(User::class);
    }

    public function periodes(){
      return $this->hasMany(Periode::class);
    }

    public function scopeAlle($query){
      return $query->where('school_type_id','<',3);
    }

    public static function EmptySchool(){
      static $emptySchool;
      if (is_null($emptySchool)){
        $emptySchool = new School;
        $emptySchool->id = 1;
      }
      return $emptySchool;
    }


}
