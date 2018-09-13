<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //
    public static function zt(){
      return Status::where('omschrijving','zt')->first()->id;
    }
}
