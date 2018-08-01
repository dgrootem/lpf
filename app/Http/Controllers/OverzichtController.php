<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use App\Leerkracht;
use App\Status;
use App\Periode;

use Carbon\Carbon;



class OverzichtController extends Controller
{
  //requires $a en $b to be carbon instances
  private function min($a,$b){
    if ($a->gt($b)) {return $b;} else {return $a;}
  }

  private function max($a,$b){
    if ($a->gt($b)) {return $a;} else {return $b;}
  }

  public function defaultRange(){
    setlocale(LC_TIME,'nl-BE');
    $format = '%d-%m-%Y';

    $emptyPeriode = new Periode;

    $emptyStatus = new Status;
    $emptyStatus->omschrijving = '';
    $emptyStatus->visualisatie='';
    $emptyPeriode->status = $emptyStatus;
    $emptyPeriode->id = -1;

    $leerkrachten = Leerkracht::where('actief',1)->get();

    $nbdays=21;

    $today = Carbon::today();
    $endRange = Carbon::today()->addDays($nbdays-1);
    $range = array();
    for ($i=0; $i < $nbdays; $i++) {
      $tempArray = array();
      foreach ($leerkrachten as $key => $value) {
        $tempArray[$value->id] = $emptyPeriode;
      }
      $range[Carbon::today()->addDays($i)->formatLocalized($format)] = $tempArray;
    }

    //return compact('today','endRange','range');

    /*$periodesInRange = Periode::whereBetween('stop',[$today,$endRange])
                      ->orWhereBetween('start',[$today,$endRange])->get();
                      //->orWhere('start','<=',$today)->where('stop','>=',$endRange)->get();*/
    $periodesInRange = Periode::where('stop','>=',Carbon::today()->format('Y-m-d'))
    ->where('start','<=',$endRange->format('Y-m-d'))->get();



    //return compact('today','endRange','periodesInRange');

    foreach ($periodesInRange as $key => $periode) {
      $ps=$periode->start;
      $pe=$periode->stop;
      $today=Carbon::today();
      $start = $this->max(Carbon::parse($periode->start),Carbon::today());

      $stop = $this->min(Carbon::parse($periode->stop),$endRange);

      //return compact('start','stop','ps','pe','today','endRange');

      $days = $start->diffInDays($stop)+1;
      for ($i=0; $i < $days; $i++) {
        $copy= clone $start;
        $copy->addDays($i);
        if (!$copy->isWeekend())
        $range[$copy->formatLocalized($format)][$periode->leerkracht->id] = $periode;
      }


    }
    //return compact(['range','periodesInRange','leerkrachten']);

    return view('overzicht',compact(['range','periodesInRange','leerkrachten']));
  }
}
