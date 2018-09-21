<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use App\Leerkracht;
use App\Status;
use App\Periode;
use App\Ambt;
use App\PeriodeWeekSchema;
use App\PeriodeDagDeel;
use App\DagDeel;
use App\DOTW;
use App\SchemaDagDeel;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use Log;
use DB;

class PeriodeController extends Controller
{
    public const UREN_PER_DAG=4.8;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    private function addDagDeel($schemaDagdeel){
      $periodeDagDeel = new PeriodeDagDeel;
      Log::debug($schemaDagdeel);
      Log::debug(Auth::user()->schools);
      if (in_array($schemaDagdeel->school_id,Auth::user()->schools->pluck('id')->toArray()))
      {
        $periodeDagDeel->status = DagDeel::AVAILABLE;
        Log::debug("AVAILABLE");
      }
      else{
        $periodeDagDeel->status = DagDeel::UNAVAILABLE;
        Log::debug("UNAVAILABLE");
      }
      //koppel PeriodeDagDeel aan SchemaDagDeel
      $periodeDagDeel->dagdeel = $schemaDagdeel;
      //$periodeDagDeel["volgorde"] = $schemaDagdeel->dag->volgorde;
      //$periodeWeekSchema->dagdelen->add($periodeDagDeel);
      return $periodeDagDeel;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $leerkracht = Leerkracht::find(request('leerkracht'))->with('aanstellingen.weekschemas.dagdelen.dag')->first();
        //return $leerkracht;
        //$leerkracht->load('aanstelling.weekschemas.dagdelen');
        $datum = request("datum");
        if (is_null($datum)) $datum = Carbon::today()->setTime(0,0,0);

        //create a new empty model to pass to the view (DONT SAVE)
        $periode = new Periode;
        $periode->school_id = 1;
        $periode->leerkracht_id = $leerkracht->id;
        $periode->start = $datum;

        $periode->stop = $datum;
        $periode->ambt = $leerkracht->ambt;

        //we put RELATIONS in arrays, so we dont need key values
        $weekschemas = array();

        //DB::beginTransaction();
        //try{
          //$periode->save();
          //$periode->weekschemas = new Collection;

          //return $leerkracht->aanstellingen->first()->weekschemas;
          //$periode->weekschemas = new \Illuminate\Database\Eloquent\Collection;
          foreach($leerkracht->aanstellingen->first()->weekschemas as $ws)
          {
            $pws = new PeriodeWeekSchema;
            $pws->volgorde = $ws->volgorde;
            $dagdelen = array();
            foreach ($ws->dagdelen as $sdagdeel) {
              $dagdelen[] = $this->addDagDeel($sdagdeel);

            }
            //return $dagdelen;

            /*
            $periodeDagDeel = new PeriodeDagDeel;
            $woensdagnm = new SchemaDagDeel;
            $woensdagnm->dag = DOTW::where('naam','wo')->first();
            //return $woensdagnm->dag;
            $woensdagnm->deel = 'NM';
            $woensdagnm->school_id = 1;

            $periodeDagDeel->status = DagDeel::UNAVAILABLE;
            $periodeDagDeel->dagdeel = $woensdagnm;
            $periodeDagDeel->volgorde = $woensdagnm->dag->volgorde;
            //return $woensdagnm;

            $dagdelen[]  =$this->addDagDeel($woensdagnm);
            */
            $pws->dagdelen = $dagdelen;
            $weekschemas[] = $pws;
            //Log::debug($pws);
          }
          //return $weekschemas;
          $periode->weekschemas = $weekschemas;

          $scholenlijst = School::alle()->pluck('naam','id');

          //TODO: in javascript de juiste ophalen
          $periode->aantal_uren_van_titularis = School::find(1)->school_type->noemer;
          $ambts = Ambt::pluck('naam','id');

          //$statuses = Status::where('choosable',1)->get();

          $scholen = CalculationController::totalsForCurrentUser();
        // }catch(\Exception $e){
        //   //DB::rollback();
        //   throw $e;
        // }
        //return $periode;
        $dagen = DOTW::orderBy('volgorde')->get();
        $user = Auth::user()->load('schools');
        ;
        //return PeriodeController::namiddagen($periode->weekschemas->first());

        return view('periode.create',compact('periode','ambts','scholen','scholenlijst','dagen'));
    }


    public static function voormiddagen($weekschema){
      Log::debug($weekschema);
      $result = array();
      foreach($weekschema->dagdelen as $dagdeel){
        Log::debug("[".$dagdeel->dagdeel->dag->naam . "_".$dagdeel->dagdeel->deel."]");
        if ($dagdeel->dagdeel->deel === "VM")
          $result[] = $dagdeel;
      }
      return $result;
          /*
      return array_filter($weekschema->dagdelen->load('dagdeel')->toArray(), function ($var){
        return ($var["dagdeel"]["deel"] === 'VM');
      });*/
    }

    public static function namiddagen($weekschema){
      Log::debug($weekschema);
      $result = array();
      foreach($weekschema->dagdelen as $dagdeel){
        Log::debug("[".$dagdeel->dagdeel->dag->naam . "_".$dagdeel->dagdeel->deel."]");
        if ($dagdeel->dagdeel->deel === "NM")
          $result[] = $dagdeel;
      }
      return $result;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $periode = new Periode;
        //return($request->all());
        $this->fillPeriode($request,$periode);

        //$this->checkAndFix($periode);
        $periode->save();

        return redirect('/overzichten');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Periode $periode)
    {
        //return compact('periode');
        $statuses = Status::where('choosable',1)->get();
        $ambts = Ambt::pluck('naam','id');
        //Log::debug('edit route');
        //Log::debug(compact('periode'));
        $scholenlijst = School::alle()->pluck('naam','id');
        $scholen = CalculationController::totalsForCurrentUser();
        return view('periode.edit',compact(['periode','statuses','ambts','scholen','scholenlijst']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //return compact('id');
        $periode = Periode::find($id);
        $this->fillPeriode($request,$periode);
        //$this->checkAndFix($periode);
        $periode->save();
        return redirect('/overzichten');
    }

    function fromRequest($name){
      $value = request($name);
      //Log::debug(compact('name','value'));
      return $value;
    }

    function fromRequestCB($name){
      $value = $this->fromRequest($name);
      if (is_null($value)) $value = 0;
      return $value;
    }

    public function fillPeriode(Request $request,Periode $periode){

      //DB::beginTransaction();
      try{
      $periode->start = Carbon::parse($this->fromRequest('start'));
      $periode->startDagDeel = $this->fromRequest('startDagDeel');
      $periode->stop = Carbon::parse($this->fromRequest('stop'));
      $periode->stopDagDeel = $this->fromRequest('stopDagDeel');
      $periode->school_id = $this->fromRequest('school_id');
      $periode->leerkracht_id = $this->fromRequest('leerkracht_id');
      $periode->aantal_uren_van_titularis = $this->fromRequest('aantal_uren_van_titularis');
      $periode->status_id = $this->fromRequest('status_id');
      $periode->opmerking = $this->fromRequest('opmerking');
      $periode->heleDag = $this->fromRequest('heleDag');
      $periode->ambt = $this->fromRequest('ambt');

      $periode->save();

      $data = request()->all();


      foreach($data as $key => $value){
        if (strpos($key,"Week")===0){ //we hebben een checkbox voor een TOEWIJZING
          $_w = intval(substr($key,4,1)); //Weeknummer
          $_d = strtolower(substr($key,6,2)); //Dag
          $_p = substr($key,9,2); //dagdeel (dayPart)
          $weekschema = $periode->weekschemas()->where('volgorde',$_w)->first();
          if (!isset($weekschema)){
            $weekschema = $this->newWeekSchema($periode,$periode->leerkracht->aanstelling()->weekschemas[$_w-1]);
          }
          Log::debug($weekschema);
          Log::debug("looking for deel ".$_p." bij dag ".$_d);
          $dagdeel = $weekschema->dagdelen->where('dagdeel.deel',$_p)->where('dagdeel.dag.naam',$_d)->first();
          $dagdeel->status = DagDeel::BOOKED;
          $dagdeel->save();
        }
      }

      $aantalDagdelen = $this->calculateAantalDagdelen($periode)['aantalDagdelen'];

      Log::debug('aantalDagdelen=');
      Log::debug($aantalDagdelen);
      $periode->aantalDagdelen = $aantalDagdelen;
      $periode->save();
    }catch(\Exception $e){
      //DB::rollback();
      throw $e;
    }
    DB::commit();
    }


    public function newWeekSchema($periode,$aanstellingsWeekschema){
      $pws = new PeriodeWeekSchema;
      $aantal = $periode->weekschemas()->count();
      $pws->volgorde=$aantal+1;
      $periode->weekschemas()->save($pws);
      foreach($aanstellingsWeekschema->dagdelen as $dagdeel){
        //Log::debug($dagdeel->dag->naam."_".$dagdeel->deel."->".$dagdeel->status);
        $pdd = new PeriodeDagDeel;
        $pdd->dagdeel_id = $dagdeel->id;
        $pdd->status = -1;
        $pws->dagdelen()->save($pdd);
      }
      $pws->load('dagdelen.dagdeel.dag');
      return $pws;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $periode = Periode::find($id);
        $periode->delete();
        return redirect('/overzichten');
    }


    // -------------------- HELPER FUNCTIONS --------------------
    private function checkDatumInPeriodeConflict($date,$startstop,$leerkracht_id,$periode_id)
    {
      $periode = $this->periodeDieDatumBevat($date,$leerkracht_id,$periode_id);
      if (!((is_null($periode))))// || ($periode->status_id == Status::opengesteld())))
        return $startstop . "in bestaande periode";
      else
        return null;
    }


    public function checkForConflict()
    {
      $datestart = request('datestart');
      $datestop = request('datestop');
      $leerkracht_id = request('leerkracht_id');
      $periode_id = request('periode_id');

      $result = $this->checkDatumInPeriodeConflict($datestart,"Start",$leerkracht_id,$periode_id);
      if (!is_null($result)) return compact('result');

      $result = $this->checkDatumInPeriodeConflict($datestop,"Stop",$leerkracht_id,$periode_id);
      if (!is_null($result)) return compact('result');

      $periodes = Periode::periodesInRange($datestart,$datestop,$leerkracht_id,$periode_id,0)->get();
      if (!$periodes->isEmpty())
        $result = "De nieuwe periode omvat 1 of meerdere niet-beschikbare periodes";
      else
        $result = null;

      return compact('result');
    }

    public static function canBeChosen($periode,$dag,$deel,$volgorde){
      $a = $periode->leerkracht->aanstelling->weekschemas[$volgorde]->dagdelen()->where('dag',$dag)->where('deel',$deel)->first();
      return (in_array($a->school_id,Auth::user()->schools()->pluck('id')->toArray()));
      //return (is_null($a[$dagDeel]) || (!in_array($a[$dagDeel],Auth::user()->schools()->pluck('id')->toArray())));
    }


/*
    function checkAndFix($periodeToCheckFor){
      $oudePeriode = Periode::find($periodeToCheckFor->id);

      $periode = $this->periodeDieDatumBevat($periodeToCheckFor->start,$periodeToCheckFor->leerkracht_id,$periodeToCheckFor->id);
      if (!is_null($periode)){
        if ($periode->status_id != Status::opengesteld()) throw new \Exception("Periode overlapt met bestaande niet-opengestelde periode", 1);
        //ALS de stop valt na de stop van de al bestaande periode -> splits de bestaande periode
        if ($periode->stop >= $periodeToCheckFor->stop)
        {
          $nieuwePeriode = $periode->replicate();
          $newstart = clone $periodeToCheckFor->stop;
          $newstart->addDays(1);
          $nieuwePeriode->start= $newstart;
          $nieuwePeriode->save();
        }

        //overlap met bestaande opengestelde periode -> aanpassen door deze vroeger te laten stoppen
        $newstop = clone $periodeToCheckFor->start;
        $newstop->addDays(-1);
        $periode->stop =  $newstop;
        $periode->save();
      }

      //doe nu hetzelfde voor de stopdatum
      $periode = $this->periodeDieDatumBevat($periodeToCheckFor->stop,$periodeToCheckFor->leerkracht_id,$periodeToCheckFor->id);
      if (!is_null($periode)){
        if ($periode->status_id != Status::opengesteld()) throw new \Exception("Periode overlapt met bestaande niet-opengestelde periode", 1);
        //overlap met bestaande opengestelde periode -> aanpassen door deze later te laten starten
        $newstart = clone $periodeToCheckFor->stop;
        $newstart->addDays(1);
        $periode->start = $newstart;
        $periode->save();
      }

      //check of de periode waarvoor we checks doen andere periodes bevat
      //deze moeten dan verwijderd worden
      $periodes = Periode::openPeriodesInRangeForLeerkracht($periodeToCheckFor->start,
                                                            $periodeToCheckFor->stop,
                                                            $periodeToCheckFor->leerkracht_id,
                                                            $periodeToCheckFor->id
                                                            )->get();
      foreach ($periodes as $key => $thePeriode) {
        $thePeriode->deleted = 1;
        $thePeriode->save();
      }

      //check of de periode gekrompen is en oude 'verwijderde' opengestelde periodes terug vrijgeeft
      if (!is_null($oudePeriode)){
        $oudestart = $oudePeriode->start;
        $oudestop = $oudePeriode->stop;
        if ($oudestart < $periodeToCheckFor->start)
        {
          $end= Carbon::parse($periodeToCheckFor->start);
          $end->addDays(-1);
          $this->resetPeriodesInRange($oudestart,$end,$periodeToCheckFor->leerkracht_id,$periodeToCheckFor->id);
        }
        if ($periodeToCheckFor->stop < $oudestop){
          $begin= Carbon::parse($periodeToCheckFor->stop);
          $begin->addDays(1);
          $this->resetPeriodesInRange($begin,$oudestop,$periodeToCheckFor->leerkracht_id,$periodeToCheckFor->id);
        }
      }
    }

    function resetPeriodesInRange($start,$stop,$leerkracht_id,$mezelf){
      $periodes = Periode::deletedPeriodesInRangeForLeerkracht($start,$stop,$leerkracht_id,$mezelf)->get();
      foreach ($periodes as $key => $thePeriode) {
        $thePeriode->deleted = 0;
        $thePeriode->save();
      }
    }

    function periodeDieDatumBevat($date,$leerkracht_id,$mezelf){
      $periode = Periode::where('id','<>',$mezelf)  //exclude matching with myself when editing a period
                    ->where('leerkracht_id',$leerkracht_id)
                    ->where('start','<=',$date)
                    ->where('stop','>=',$date)->first();
      return $periode;
    }
    */

    //TODO: herschalen wanneer LPF-leerkracht in ander systeem werkt dan aantal_uren_van_titularis:
    //  bijvoorbeeld: titularis werkt in BaO in /24, LPF in /22, dan is 19/24 te herschalen naar 17,5/22 ??
    // TODO: wat met onvolledige weken? hoe worden dan woensdagen geteld?
    //  bijv
    //    iemand die vervanging doet op maandag -> woensdag? is dat 3 dagen aan 24/24 ?
    //    iemand die vervanging doet op woensdag alleen? zou 1 dag aan 12/24 zijn...
    //    iemand die vervanging doet van donderdag tot dinsdag? 4 dagen aan 24/24 ?
    function calculateAantalDagdelen($periode)
    {
      $aantalWeken = $periode->stop->weekOfYear - $periode->start->weekOfYear +1;
      Log::debug("aantal volledige weken=".$aantalWeken);

      // voor de eerste week:
      //   bepaal weekschema $ws
      //   en overloop de dagen van de week vanaf de startdatum
      // als aantalweken >= 2
      //   voor elke week
      //    bepaal weekschema $ws
      //    als NIET laatste week
      //      aantalDagdelen += $ws -> aantal van dagdelen met status == BOOKED
      //    anders
      //      en overloop de dagen van de week tot aan de stopdatum
      $aantalWeekSchemas = $periode->weekschemas->count();
      $a_start = Carbon::parse($periode->leerkracht->aanstelling()->start);
      $a_stop = Carbon::parse($periode->leerkracht->aanstelling()->stop);

      $startWeekVanAanstelling = $a_start->weekOfYear;

      $p_start = Carbon::parse($periode->start);
      $p_stop = Carbon::parse($periode->stop);

      $datumIterator = clone $p_start;
      $aantalDagdelen = 0;
      for($i=1;$i<=$aantalWeken;$i++){
        $currentWeekVolgorde = ($datumIterator->weekOfYear - $startWeekVanAanstelling) % $aantalWeekSchemas;
        $pws = $periode->weekschemas[$currentWeekVolgorde];
        $current_dagdelen = $pws->dagdelen()->with('dagdeel.dag')->where('status',1);

        if ($i==1){ //eerste week speciaal behandelen
          $startDagVolgorde = DOTW::where('naam',OverzichtController::shortDayOfWeek($p_start->dayOfWeek))->pluck('volgorde');
          foreach($current_dagdelen as $currentDagDeel)
          {
            if ($currentDagDeel->dagdeel->dag->volgorde >= $startDagVolgorde)
              $aantalDagdelen++;
          }
        }
        else if ($i==$aantalWeken){ //laatste week speciaal behandelen
          $stopDagVolgorde = DOTW::where('naam',OverzichtController::shortDayOfWeek($p_stop->dayOfWeek))->pluck('volgorde');
          foreach($current_dagdelen as $currentDagDeel)
          {
            if ($currentDagDeel->dagdeel->dag->volgorde <= $stopDagVolgorde)
              $aantalDagdelen++;
          }

          }
        else{ //normal case
          $dagdelen += $current_dagdelen->count();
        }
        $datumIterator->addDays(7); //spring steeds een week verder
      }
      return compact('result','aantalDagdelen');

      /*
      Log::debug(request()->all());
      $datestart = Carbon::parse($this->fromRequest('start'));
      $datestop = Carbon::parse($this->fromRequest('stop'));
      $opdrachtBreuk = $this->fromRequest('aantal_uren_van_titularis');
      $leerkracht_id = $this->fromRequest('leerkracht_id');
      $school_id = $this->fromRequest('school_id');
      $status = $this->fromRequest('status_id');
      $periode_id = $this->fromRequest('periode_id');

    //   return $this->berekenUren($datestart,$datestop,$opdrachtBreuk,$leerkracht_id,$school_id,$status,$periode_id);
    // }
    //
    // function berekenUren($datestart,$datestop,$opdrachtBreuk,$leerkracht_id,$school_id,$status,$periode_id)
    // {
      // Log::debug('status=' . $status);
      /*if ($status == Status::opengesteld()){

        $result = "";
        $uren = 0;
      }
      else{
*/

        $dagen = $this->calculateNbDays($datestart,$datestop);
        // Log::debug('dagen='.$dagen);
        $leerkracht = Leerkracht::find($leerkracht_id);
        $school = School::find($school_id);

        //TODO: goed uitschrijven welke checks hier zouden kunnen achterzitten, zeer complex!!
        /*
        $begin = new Carbon;
        $begin->setISODate($datestart->year,$datestart->weekOfYear);
        $andere_periodes = Periode::periodesInRangeForLeekracht($begin,$einde,$leerkracht_id,$periode_id,0);
        */
        $result = null;
        /*if ($leerkracht->lestijden_per_week < $opdrachtBreuk)
          $result =  "OPGELET: Leerkracht heeft minder uren (" .
                      $leerkracht->lestijden_per_week .
                      ") dan gewenste opdracht (" . $opdrachtBreuk.")" ;
                      */
        // Log::debug('aantal_uren_van_titularis='.$opdrachtBreuk);
        // Log::debug('lestijden_per_week LPF='.$leerkracht->lestijden_per_week);
        // Log::debug('minimum='.(min($opdrachtBreuk,$leerkracht->lestijden_per_week)));
        $uren = $dagen * min($opdrachtBreuk,$leerkracht->lestijden_per_week) / $school->school_type->noemer * PeriodeController::UREN_PER_DAG;
      //}
      return compact('result','uren');
    }

    // TODO: uitfilteren van vrije periodes
    function calculateNbDays($d1,$d2){
      $werkdagen = $d1->diffInDaysFiltered(function(Carbon $date) {
             return !($date->isWeekend());},
             $d2);
             return $werkdagen+1;

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
