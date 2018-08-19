<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use Log;

class Periode extends Model
{
    //
    protected $fillable = ['start',
     'stop',
     'school_id',
     'leerkracht_id',
     'ambt_id',
     'aantal_uren_van_titularis',
     'status_id',
     'opmerking',
     'heleDag'
   ];


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


   public function scopePeriodesInRange($query,$begin,$einde,$deleted){
     return $query->where('deleted',$deleted)->where('stop','>=',$begin)->where('start','<=',$einde);
   }

   public function scopePeriodesInRangeForLeekracht($query,$begin,$einde,$leerkracht_id,$zelf,$deleted){
     return Periode::periodesInRange($begin,$einde,$deleted)->where('id','<>',$zelf)->where('leerkracht_id',$leerkracht_id);
   }

   function scopeOpenPeriodesInRangeForLeerkracht($query,$start,$stop,$leerkracht_id,$mezelf){
     return Periode::periodesInRangeForLeekracht($start,$stop,$leerkracht_id,$mezelf,0)->where('status_id',Status::opengesteld());
   }

   function scopeDeletedPeriodesInRangeForLeerkracht($query,$start,$stop,$leerkracht_id,$mezelf){
     return Periode::periodesInRangeForLeekracht($start,$stop,$leerkracht_id,$mezelf,1);

   }

}
