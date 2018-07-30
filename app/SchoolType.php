<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolType extends Model
{
    //
    public function schools()
    {
        return $this->hasMany(School::class);
    }
}
