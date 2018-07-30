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
}
