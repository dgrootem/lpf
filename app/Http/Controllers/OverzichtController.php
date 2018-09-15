<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\School;
use App\Leerkracht;
use App\Status;
use App\Periode;

use App\DagDeel;
use App\Dag;

use Carbon\Carbon;

use Log;

use DB;



class OverzichtController extends Controller
{
  //requires $a en $b to be carbon instances
  private function min($a,$b){
    if ($a->gt($b)) {return $b;} else {return $a;}
  }

  private function max($a,$b){
    if ($a->gt($b)) {return $a;} else {return $b;}
  }

  private function shortDayOfWeek($dagIndex)
  {
    switch($dagIndex){
      case 0: return "zo";
      case 1: return "ma";
      case 2: return "di";
      case 3: return "wo";
      case 4: return "do";
      case 5: return "vr";
      case 6: return "za";
      default: return "**ERROR**";
    }
  }

  private function fillDagDeel($school,$dagdeel)
  {
    $dagdeel->school = $school;
    if ((is_null($school)) || ($school->id==1)) $dagdeel->status = DagDeel::UNAVAILABLE;
    else $dagdeel->status = DagDeel::AVAILABLE;

    return $dagdeel;
  }

  private function datumStatus($datum,$leerkracht){

    $dagnr = $datum->dayOfWeek;
    //Log::debug("dag van de week=".$dag);

    $dag = new Dag;

    $dag->vm = new DagDeel;
    $dag->vm->naam = $this->shortDayOfWeek($dagnr) . '_vm';
    $dag->nm = new DagDeel;
    $dag->nm->naam = $this->shortDayOfWeek($dagnr) . '_nm';

    switch($dagnr){
      case 0 :
        $dag->vm->status = DagDeel::UNAVAILABLE;
        $dag->nm->status = DagDeel::UNAVAILABLE;
      break;
      case 3 :
        $dag->vm = $this->fillDagDeel($leerkracht->wo_vm,$dag->vm);
        $dag->nm->status = DagDeel::UNAVAILABLE;
      break;
      case 6 :
        $dag->vm->status = DagDeel::UNAVAILABLE;
        $dag->nm->status = DagDeel::UNAVAILABLE;
      break;
      default:
        //Log::debug("default case");
        $dag->vm = $this->fillDagDeel(School::find($leerkracht->toArray()[strtoupper($this->shortDayOfWeek($dagnr)."_vm")]),$dag->vm);
        $dag->nm = $this->fillDagDeel(School::find($leerkracht->toArray()[strtoupper($this->shortDayOfWeek($dagnr)."_nm")]),$dag->nm);
        //School::find($leerkracht->toArray()[strtoupper($this->shortDayOfWeek($dag)."_vm")]),
        //             'NM' => School::find($leerkracht->toArray()[strtoupper($this->shortDayOfWeek($dag)."_nm")]));
    }

    return $dag;

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
    $leerkrachten = Leerkracht::where('actief',1)->get();
    $nbdays=env('NBDAYS_IN_OVERZICHT');

    $stopOfRange = clone $startOfRange;
    $stopOfRange->addDays($nbdays-1);

    $datumIterator = clone $startOfRange;
    $dateRange = array();
    for ($i=0; $i < $nbdays; $i++) {
      $tempArray = array('VM' => array(),'NM' => array());
      foreach ($leerkrachten as $key => $leerkracht) {
        $vm = new DagDeel;
        $nm = new DagDeel;

        /*
        $dateStatus = $this->datumStatus($datumIterator,$leerkracht);

        $vm->school = $dateStatus['VM'];
        if (is_null($vm->school)) $vm->status = DagDeel::UNAVAILABLE;
        else $vm->status = DagDeel::AVAILABLE;

        $nm->school = $dateStatus['NM'];
        if (is_null($nm->school)) $nm->status = DagDeel::UNAVAILABLE;
        else $nm->status = DagDeel::AVAILABLE;
        */

        $dag = $this->datumStatus($datumIterator,$leerkracht);

        $tempArray['VM'][$leerkracht->id] = $dag->vm;
        $tempArray['NM'][$leerkracht->id] = $dag->nm;

        Log::debug($vm->school);
        Log::debug($nm->school);

      }
      //Log::debug("TEMPARRAY=");
      //Log::debug($tempArray['VM']);
      //increment the $startdate by 1 in each iteration (addDays is a mutator)
      $dateRange[$datumIterator->formatLocalized($format)] = $tempArray;
      $datumIterator->addDays(1);
    }

    //DB::listen(function ($query) {
    //   Log::debug($query->sql);
    // });
    Log::debug($startOfRange);
    Log::debug($stopOfRange);
    $periodesInRange = Periode::periodesInRange($startOfRange->format('Y-m-d'),
                                                $stopOfRange->format('Y-m-d'))->get();
    // DB::listen(function ($query) {
    //
    // });

    foreach ($periodesInRange as $key => $periode) {
      $ps=$periode->start;
      $psd=$periode->startDagDeel;
      $pe=$periode->stop;
      $ped=$periode->stopDagDeel;
      $start = $this->max(Carbon::parse($periode->start),$startOfRange);
      $stop = $this->min(Carbon::parse($periode->stop),$stopOfRange);

      //return compact('start','stop','ps','pe','today','endRange');

      $lkrid = $periode->leerkracht->id;
      $periodeArray = $periode->toArray();

      $days = $start->diffInDays($stop)+1;
      $datumIterator = clone $start;
      for ($i=0; $i < $days; $i++) {
        //Log::debug("DAG: ".$i);
        //Log::debug($datumIterator);
        //Log::debug("Leerkracht=".$periode->leerkracht->id);
        $dagDeel = $dateRange[$datumIterator->formatLocalized($format)]['VM'][$lkrid];
        if (!(($i==0) && (strcmp($psd,'NM')==0)))
          if (($dagDeel->status==DagDeel::AVAILABLE) && ($periodeArray[strtoupper($dagDeel->naam)]==1))
          {
            //Log::debug('VM'.DagDeel::BOOKED);
            $dagDeel->status=DagDeel::BOOKED;
            $dagDeel->school=$periode->school;
            $dagDeel->visualisatie=$periode->status->visualisatie;
            $dagDeel->periode=$periode;
          }
          else {

          }
        $dagDeel = $dateRange[$datumIterator->formatLocalized($format)]['NM'][$lkrid];
        if (!(($i==$days-1) && (strcmp($ped,'VM')==0)))
          if (($dagDeel->status==DagDeel::AVAILABLE) && ($periodeArray[strtoupper($dagDeel->naam)]==1))
          {
            //Log::debug('NM'.DagDeel::BOOKED);
            $dagDeel->status=DagDeel::BOOKED;
            $dagDeel->school=$periode->school;
            $dagDeel->visualisatie=$periode->status->visualisatie;
            $dagDeel->periode=$periode;
          }

        //
        // = $periode;
        $datumIterator->addDays(1);
        //Log::debug($dateRange[$datumIterator->formatLocalized($format)]['NM'][$lkrid]->status);
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
