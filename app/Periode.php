<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Periode extends Model
{
    //
    protected $fillable = ['start',
     'stop',
     'school_id',
     'leerkracht_id',
     'aantal_uren_van_titularis',
     'status_id',
     'opmerking',
     'heleDag'
   ];


   public function getStartAttribute($date){
     return Carbon::parse($date)->format('Y-m-d');
   }

   public function getStopAttribute($date){
     return Carbon::parse($date)->format('Y-m-d');
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
}
