<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leerkracht extends Model
{
    //
    //protected $guarded = [];
    public function ambt()
    {
        return $this->belongsTo(Ambt::class);
    }
}
