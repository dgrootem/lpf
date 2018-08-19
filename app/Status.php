<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //
    public static function opengesteld(){
      return Status::where('omschrijving','opengesteld')->first()->id;
    }
}
