<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
