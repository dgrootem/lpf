<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use Log;

class Periode extends Model
{
    //
    protected $guarded = ['id'];
    /*
    protected $fillable = ['start','startDagDeel'
     'stop','stopDagDeel'
     'school_id',
     'leerkracht_id',
     'ambt_id',
     'aantal_uren_van_titularis',
     'status_id',
     'opmerking',
     'heleDag'
   ];
   */

   public function getStartAttribute($date){
     return Carbon::parse($date);//->format('Y-m-d');
   }

   public function getStopAttribute($date){
     return Carbon::parse($date);//->format('Y-m-d');
   }

   public function school()
   {
       return $this->belongsTo(School::class);
   }
   public function status()
   {
       return $this->belongsTo(Status::class);
   }
   public function leerkracht()
   {
       return $this->belongsTo(Leerkracht::class);
   }

   public function ambt()
   {
       return $this->belongsTo(Ambt::class);
   }

   public function weekschemas()
   {
     return $this->hasMany(PeriodeWeekSchema::class);
   }


   public function scopePeriodesInRange($query,$begin,$einde){
     return $query->where('stop','>=',$begin)->where('start','<=',$einde)->whereHas('school',function($query){
       $query->where('school_type_id','<',3);
     });
   }

   public function scopePeriodesInRangeForLeekracht($query,$begin,$einde,$leerkracht_id,$zelf,$deleted){
     return Periode::periodesInRange($begin,$einde,$deleted)->where('id','<>',$zelf)->where('leerkracht_id',$leerkracht_id);
   }

/*
   function scopeOpenPeriodesInRangeForLeerkracht($query,$start,$stop,$leerkracht_id,$mezelf){
     return Periode::periodesInRangeForLeekracht($start,$stop,$leerkracht_id,$mezelf,0)->where('status_id',Status::opengesteld());
   }
*/
   function scopeDeletedPeriodesInRangeForLeerkracht($query,$start,$stop,$leerkracht_id,$mezelf){
     return Periode::periodesInRangeForLeekracht($start,$stop,$leerkracht_id,$mezelf,1);

   }



}
