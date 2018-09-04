<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\School;
use App\Leerkracht;
use App\Status;
use App\Periode;

use Carbon\Carbon;

use Log;



class OverzichtController extends Controller
{
  //requires $a en $b to be carbon instances
  private function min($a,$b){
    if ($a->gt($b)) {return $b;} else {return $a;}
  }

  private function max($a,$b){
    if ($a->gt($b)) {return $a;} else {return $b;}
  }

  public function range($startDate){

    $startOfRange = Carbon::parse($startDate);
    Log::debug($startOfRange);

    setlocale(LC_TIME,'nl-BE');
    $format = '%d-%m-%Y';

    $emptyPeriode = new Periode;

    $emptyStatus = new Status;
    $emptyStatus->omschrijving = '';
    $emptyStatus->visualisatie='';
    $emptyPeriode->status = $emptyStatus;
    $emptyPeriode->id = -1;

    $leerkrachten = Leerkracht::where('actief',1)->get();

    $nbdays=env('NBDAYS_IN_OVERZICHT');

    $stopOfRange = clone $startOfRange;
    $stopOfRange->addDays($nbdays-1);


    $startPunt = clone $startOfRange;
    $range = array();
    for ($i=0; $i < $nbdays; $i++) {
      $tempArray = array();
      foreach ($leerkrachten as $key => $value) {
        $tempArray[$value->id] = $emptyPeriode;
      }
      //increment the $startdate by 1 in each iteration (addDays is a mutator)
      $range[$startPunt->formatLocalized($format)] = $tempArray;
      $startPunt->addDays(1);
    }

    $periodesInRange = Periode::periodesInRange($startOfRange->format('Y-m-d'),
                                                $stopOfRange->format('Y-m-d'),
                                                0)->get();

    foreach ($periodesInRange as $key => $periode) {
      $ps=$periode->start;
      $pe=$periode->stop;
      $start = $this->max(Carbon::parse($periode->start),$startOfRange);
      $stop = $this->min(Carbon::parse($periode->stop),$stopOfRange);

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


    $scholen = CalculationController::totalsForCurrentUser();

    //return compact(['range','periodesInRange','leerkrachten','scholen']);

    return view('overzicht',compact(['range','periodesInRange','leerkrachten','scholen','startOfRange']));
  }

  public function defaultRange(){
    $today = Carbon::today();
    return $this->range($today);
  }




  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth');
  }


}
