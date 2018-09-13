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



  private function datumStatus($datum,$leerkracht){
    $dag = $datum->dayOfWeek;
    switch($dag){
      case 0 : return array(
        'VM' => null,
        'NM' => null,
      );break;
      case 1 : return array(
        'VM' => $leerkracht->ma_vm,
        'NM' => $leerkracht->ma_nm,
      );break;
      case 2 : return array(
        'VM' => $leerkracht->di_vm,
        'NM' => $leerkracht->di_nm,
      );break;
      case 3 : return array(
        'VM' => $leerkracht->wo_vm,
        'NM' => null,
      );break;
      case 4 : return array(
        'VM' => $leerkracht->do_vm,
        'NM' => $leerkracht->do_nm,
      );break;
      case 5 : return array(
        'VM' => $leerkracht->vr_vm,
        'NM' => $leerkracht->vr_nm,
      );break;

    }

  }

  public function range($startDate = null){

    if (isset($startDate))
      $startOfRange = Carbon::parse($startDate);
    else {
      $startOfRange = Carbon::today();
    }
    Log::debug($startOfRange);

    setlocale(LC_TIME,'nl-BE');
    $format = '%d-%m-%Y';

    $availablePeriode = new Periode;
    $availablePeriode->status =  Status::where('omschrijving','zt')->first();
    $availablePeriode->id = -1;

    $unavailablePeriode = new Periode;
    $unavailableStatus = new Status;
    $unavailableStatus->omschrijving = '';
    $unavailableStatus->visualisatie = 'disabled';
    $unavailablePeriode->status = $unavailableStatus;
    $unavailablePeriode->id = -1;


    $leerkrachten = Leerkracht::where('actief',1)->get();

    $nbdays=env('NBDAYS_IN_OVERZICHT');

    $stopOfRange = clone $startOfRange;
    $stopOfRange->addDays($nbdays-1);


    $datumIterator = clone $startOfRange;
    $dateRange = array();
    for ($i=0; $i < $nbdays; $i++) {
      $tempArray = array('VM' => array(),'NM' => array());
      foreach ($leerkrachten as $key => $value) {
        $bla = $this->datumStatus($datumIterator,$value);

        //als aangesteld in bepaalde school, dan beschikbaar in dat dagdeel
        if (is_null($bla['VM']))
          $tempArray['VM'][$value->id] = $unavailablePeriode;
        else
          $tempArray['VM'][$value->id] = $availablePeriode;

        if (is_null($bla['NM']))
          $tempArray['NM'][$value->id] = $unavailablePeriode;
        else
          $tempArray['NM'][$value->id] = $availablePeriode;
      }
      Log::debug("TEMPARRAY=");
      Log::debug($tempArray['VM'][6]);
      //increment the $startdate by 1 in each iteration (addDays is a mutator)
      $dateRange[$datumIterator->formatLocalized($format)] = $tempArray;
      $datumIterator->addDays(1);
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
        $dateRange[$copy->formatLocalized($format)][$periode->leerkracht->id] = $periode;
      }


    }
    //return compact(['dateRange','periodesInRange','leerkrachten']);


    $scholen = CalculationController::totalsForCurrentUser();

    //return compact(['dateRange','periodesInRange','leerkrachten','scholen']);

    return view('overzicht',compact(['dateRange','periodesInRange','leerkrachten','scholen','startOfRange']));
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
