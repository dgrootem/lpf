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
use App\DOTW;

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

  public static function shortDayOfWeek($dagIndex)
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

  public function fillDagDeel($school,$dagdeel,$dagnaam)
  {
    $dagdeel->school = $school;
    $dagdeel->naam = $dagnaam;
    if ((is_null($school)) || ($school->id==1))
      $dagdeel->status = DagDeel::UNAVAILABLE;
    else {
      $dagdeel->status = DagDeel::AVAILABLE;
    }
    //Log::debug("Filled dagdeel (".$dagdeel->naam.")= [School=".$dagdeel->school->afkorting.",Status=".$dagdeel->status."]");

    //Log::debug(compact('dagdeel'));
    //return $dagdeel;

    //return $dagdeel;
  }

  public function range($startDate = null){
    $leerkrachten = Leerkracht::where('actief',1)->with('aanstellingen.weekschemas.dagdelen.school')->get();


    if (isset($startDate))
      $startOfRange = Carbon::parse($startDate)->startOfWeek(); //start altijd op maandag
    else {
      $startOfRange = Carbon::today()->startOfWeek();//start altijd op maandag
    }
    //Log::debug($startOfRange);
    $nbdays=env('NBDAYS_IN_OVERZICHT');

    $stopOfRange = clone $startOfRange;
    $stopOfRange->addDays($nbdays-1);

    $theRangeData = $this->rangeForLeerkrachten($leerkrachten,$startOfRange,$stopOfRange,$nbdays);
    $dateRange = $theRangeData['dateRange'];
    $periodesInRange = $theRangeData['periodesInRange'];


    $scholen = CalculationController::totalsForCurrentUser();

    return view('overzicht',compact(['dateRange','periodesInRange','leerkrachten','scholen','startOfRange']));

  }

  public function conflictRangeForLeerkracht(){
    $datestart = request('datestart');
    $datestop = request('datestop');
    $leerkracht_id = request('leerkracht_id');
    $leerkrachten = Leerkracht::where('id',$leerkracht_id)->get();
    $nbdays = $datestart->diffInDays($datestop) +1;

    return $this->rangeForLeerkrachten($leerkrachten,$datestart,$datestop,$nbdays);
  }

  public function rangeForLeerkrachten($leerkrachten,$startOfRange,$stopOfRange,$nbdays)
  {
    setlocale(LC_TIME,'nl-BE');
    $format = '%d-%m-%Y';

    $firstweek = Carbon::parse("first monday of september")->weekOfYear;

    $datumIterator = clone $startOfRange;
    $dateRange = array();
    for ($i=0; $i < $nbdays; $i++) {
      $tempArray = array('VM' => array(),'NM' => array());
      foreach ($leerkrachten as $key => $leerkracht) {
        $vm = new DagDeel;
        $nm = new DagDeel;
        $tempArray['VM'][$leerkracht->id] = $vm;
        $tempArray['NM'][$leerkracht->id] = $nm;
        $dateRange[$datumIterator->formatLocalized($format)] = $tempArray;
      }
      $datumIterator->addDays(1);
    }

    //kan zeker geoptimaliseerd worden door bijvoorbeeld altijd een periode te starten op een maandag?
    foreach($leerkrachten as $key => $leerkracht){
      $aanstelling = $leerkracht->aanstelling();
      $aantalAanstellingen = $leerkracht->aanstellingen->count();
      if ($aantalAanstellingen!=0){
        $aantalWeekSchemas = $leerkracht->aanstelling()->weekschemas->count();
        Log::debug($leerkracht->naam . " -> aantal aanstellingen=".$aantalAanstellingen) ;
        Log::debug("aantal weekschemas=".$aantalWeekSchemas);
      }
      else {
        $aantalWeekSchemas = 0;
      }


      $currentWeekTeller = -1;
      $datumIterator = clone $startOfRange;
      $huidigeWeek = null;
      $weekschema = null;
      $voormiddagen = null;
      $namiddagen = null;
      for ($i=0; $i < $nbdays; $i++)
      {

        $formattedDate = $datumIterator->formatLocalized($format);
        //
        //trivial case
        if ((($aantalAanstellingen == 0) || ($aantalWeekSchemas==0)) || ($datumIterator<$aanstelling->start)){
          //Log::debug("Geen aanstelling voor ".$leerkracht->naam."..skip");
          $dateRange[$formattedDate]['VM'][$leerkracht->id]->status = DagDeel::UNAVAILABLE;
          $dateRange[$formattedDate]['NM'][$leerkracht->id]->status = DagDeel::UNAVAILABLE;
          $datumIterator->addDays(1);
          continue;
        }

        $dagnr=$datumIterator->dayOfWeek;
        //skip zaterdag en zondag
        if (($dagnr==0) || ($dagnr==6))
        {
          $dateRange[$formattedDate]['VM'][$leerkracht->id]->status = DagDeel::UNAVAILABLE;
          $dateRange[$formattedDate]['NM'][$leerkracht->id]->status = DagDeel::UNAVAILABLE;
          $datumIterator->addDays(1);
          continue;
        }

        Log::debug($formattedDate);
        //bepaal het weekschema - we halen dit enkel op wanneer we van week veranderen
        $weekTeller = $datumIterator->weekOfYear - $firstweek;
        //FIX voor periode vanaf 1 januari
        if ($weekTeller<0) $weekTeller = 52-$firstweek + $datumIterator->weekOfYear;
        if ((($currentWeekTeller != $weekTeller) && ($aantalWeekSchemas>1))|| (!isset($weekschema)))
        {
          $currentWeekTeller = $weekTeller;
          $huidigeWeek = $weekTeller % $aantalWeekSchemas +1; //we tellen Week1 , Week2, ... terwijl mod functie begint bij 0

          $weekschema = $leerkracht->aanstellingen->first()->weekschemas->where('volgorde',$huidigeWeek)->first();
          //Log::debug("NIEUWE WEEK");
          //Log::debug($weekschema);
          $voormiddagen = LeerkrachtController::voormiddagen($weekschema);//->voormiddagenFull()->get();

          $namiddagen = LeerkrachtController::namiddagen($weekschema);//->namiddagenFull()->get();

        }
        $dagnaam = $this->shortDayOfWeek($dagnr);

        //vul de voormiddag
        $this->fillDagDeel($voormiddagen[$dagnaam]->school,$dateRange[$formattedDate]['VM'][$leerkracht->id],$dagnaam);
        //$debugdagdeel = $dateRange[$formattedDate]['VM'][$leerkracht->id];
        //Log::debug("dateRange[".$formattedDate."]['VM'][".$leerkracht->naam."]");
        //Log::debug(compact('debugdagdeel'));

        //vul de namiddag
        $school = School::find(1);
        if ($dagnr!=3)
          $school = $namiddagen[$dagnaam]->school;
        $this->fillDagDeel($school,$dateRange[$formattedDate]['NM'][$leerkracht->id],$dagnaam);
        //forceer woensdagnamiddag op unavailable
        if ($dagnr==3)
          $dateRange[$formattedDate]['NM'][$leerkracht->id]->status = DagDeel::UNAVAILABLE;

        // $debugdagdeel = $dateRange[$formattedDate]['NM'][$leerkracht->id];
        // Log::debug("dateRange[".$formattedDate."]['VM'][".$leerkracht->naam."]");
        // Log::debug(compact('debugdagdeel'));

        $datumIterator->addDays(1);
      }

    }
    //return $dateRange;

    //return compact('dateRange');
    //Log::debug("TEMPARRAY=");
    //Log::debug($tempArray['VM']);
    //increment the $startdate by 1 in each iteration (addDays is a mutator)

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

      $lkrid = $periode->leerkracht->id;
      $periodeArray = $periode->toArray();

      $days = $start->diffInDays($stop)+1;
      Log::debug("Periode duurt ".$days." dagen");

      $stopweek = $stop->weekOfYear;
      $startweek = $start->weekOfYear;
      if (($stop->year > $start->year)  || (($stopweek < $startweek) && ($stop > $start))) $stopweek+=52;

      $aantalWeken = $stopweek - $startweek +1; //bereken het aantal weken te overlopen
      Log::debug("Deze liggen in ".$aantalWeken." weken");
      Log::debug(compact('startweek'));
      Log::debug(compact('stopweek'));
      $datumIterator = clone $start->startOfWeek();

      $a = $periode->leerkracht->aanstelling();

      $a_start = Carbon::parse($a->start);
      $a_stop = Carbon::parse($a->stop);

      $startWeekVanAanstelling = $a_start->weekOfYear;
      //Log::debug("Startweek aanstelling".$startWeekVanAanstelling);


      $aantalWeekSchemas = $periode->weekschemas->count();
      //Log::debug("Aantal weekschemas voor deze periode ".$aantalWeekSchemas);


      for($i=1;$i<=$aantalWeken;$i++){
        Log::debug("Huidige week=".$datumIterator->weekOfYear);
        //$currentWeekVolgorde = (($datumIterator->weekOfYear - $startWeekVanAanstelling) % $aantalWeekSchemas);
        $currentWeekVolgorde = $a->volgordeVoorDatum($datumIterator);

        //only process weeks after the start of the aanstelling
        if ($currentWeekVolgorde>=0){
        //if ($datumIterator->weekOfYear >= $startWeekVanAanstelling) {
          Log::debug("[".$periode->leerkracht->naam."] Huidig weekschema ".$currentWeekVolgorde);
          $pws = $periode->weekschemas[$currentWeekVolgorde];

          $voormiddagen=PeriodeController::voormiddagen($pws);
          $namiddagen=PeriodeController::namiddagen($pws);

          for($j=1;$j<=7;$j++)
          {
            //skip ZA & ZO + skip dagen die voor de startdag vallen
            if (($j<=5) && ($datumIterator>=$ps) && ($datumIterator<=$pe))
            {
              $dagDeel = $dateRange[$datumIterator->formatLocalized($format)]['VM'][$lkrid];
              if (!(($datumIterator == $ps) && (strcmp($psd,'NM')==0)))
              {
                $this->processDagDeel($voormiddagen[$j-1],$dagDeel,'VM',$periode);
              }
              $dagDeel = $dateRange[$datumIterator->formatLocalized($format)]['NM'][$lkrid];
              if (!(($datumIterator == $pe) && (strcmp($ped,'VM')==0)))
                $this->processDagDeel($namiddagen[$j-1],$dagDeel,'NM',$periode);
            }
            $datumIterator->addDays(1);
          }
        } else $datumIterator->addDays(7); //skip a week
      }
    }
    return array('dateRange' => $dateRange,'periodesInRange' => $periodesInRange);
  }

  private function processDagDeel($periodeDagDeel,$visualisatieDagdeel,$part,$periode){
    //Log::debug("trying $part....");
    if (
      //($visualisatieDagdeel->status==DagDeel::AVAILABLE) &&
      ($periodeDagDeel->status==DagDeel::BOOKED)
      )
    {
      //Log::debug($part.'=BOOKED');
      $visualisatieDagdeel->status=DagDeel::BOOKED;
      $visualisatieDagdeel->school=$periode->school;
      $visualisatieDagdeel->visualisatie=$periode->status->visualisatie;
      $visualisatieDagdeel->periode=$periode;
    }
    else {
      //Log::debug($part."=NOT booked");
    }
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
