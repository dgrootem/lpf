<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    //
    public function school_type()
    {
        return $this->belongsTo(SchoolType::class);
    }
}
