<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class DagDeel  //extends Model
{

    public const UNAVAILABLE = 1;
    public const AVAILABLE = 2;
    public const BOOKED = 3;

    public $periode; //Periode indien er op deze moment iemand ingeboekt is
    public $status; // "available", "unavailable", "booked";
    public $naam;
    //de school waar ofwel
    // a) deze persoon normaal werkt op deze dag (wanneer niet ingeboekt)
    // b) deze persoon ingeboekt is
    public $school;

    public $visualisatie = 'bg-light';


}
