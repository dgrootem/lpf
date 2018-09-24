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


    private function addDagDeel($schemaDagdeel,$useEloquent){
      $periodeDagDeel = new PeriodeDagDeel;
      //Log::debug($schemaDagdeel);
      //Log::debug(Auth::user()->schools);
      if (in_array($schemaDagdeel->school_id,Auth::user()->schools->pluck('id')->toArray()))
      {
        $periodeDagDeel->status = DagDeel::AVAILABLE;
        //Log::debug("AVAILABLE");
      }
      else{
        $periodeDagDeel->status = DagDeel::UNAVAILABLE;
        //Log::debug("UNAVAILABLE");
      }
      //koppel PeriodeDagDeel aan SchemaDagDeel
      if (!$useEloquent){
          Log::debug("Using plain model");
        $periodeDagDeel->dagdeel = $schemaDagdeel;
      }
      else {
        Log::debug("Using eloquent model");
        $periodeDagDeel->dagdeel_id = $schemaDagdeel->id;
      }
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
        $lkrid = request('leerkracht');

        $leerkracht = Leerkracht::with('aanstellingen.weekschemas.dagdelen.dag')->find($lkrid);
        if ($leerkracht->aanstellingen->count() == 0)
        return redirect(url('/leerkracht/'.$lkrid.'/edit'));
        //return compact('leerkracht');
        //return $leerkracht;
        //$leerkracht->load('aanstelling.weekschemas.dagdelen');
        $datum = request("datum");
        if (is_null($datum)) $datum = Carbon::today()->setTime(0,0,0);

        //create a new empty model to pass to the view (DONT SAVE)
        $periode = new Periode;
        $periode->school_id = 1;
        $periode->leerkracht_id = $leerkracht->id;
        $periode->start = $datum;
        $periode->startDagDeel = 'VM';
        $periode->stop = $datum;
        $periode->stopDagDeel = 'NM';
        $periode->ambt = $leerkracht->ambt;

        //we put RELATIONS in arrays, so we dont need key values
        $weekschemas = array();

        foreach($leerkracht->aanstellingen->first()->weekschemas as $ws)
        {
          $pws = new PeriodeWeekSchema;
          $pws->volgorde = $ws->volgorde;
          $dagdelen = array();
          foreach ($ws->dagdelen as $sdagdeel) {
            $dagdelen[] = $this->addDagDeel($sdagdeel,false);

          }

          $pws->dagdelen = $dagdelen;
          $weekschemas[] = $pws;
        }
        $periode->weekschemas = $weekschemas;

        $scholenlijst = School::alle()->pluck('afkorting','id');
        //$scholenlijst = School::select(DB::raw("CONCAT(`afkorting`,'--',`naam`) AS display_name"),'id')->get()->pluck('display_name','id');

        //TODO: in javascript de juiste ophalen
        $periode->aantal_uren_van_titularis = School::find(1)->school_type->noemer;
        $ambts = Ambt::pluck('naam','id');

        $scholen = CalculationController::totalsForCurrentUser();

        //$dagen = DOTW::orderBy('volgorde')->get();
        $user = Auth::user()->load('schools');

        //return compact('periode');

        return view('periode.create',compact('periode','ambts','scholen','scholenlijst'));
    }


    public static function voormiddagen($weekschema){
      //Log::debug($weekschema);
      $result = array();
      foreach($weekschema->dagdelen as $dagdeel){
        //Log::debug("[".$dagdeel->dagdeel->dag->naam . "_".$dagdeel->dagdeel->deel."]");
        if ($dagdeel->dagdeel->deel === "VM")
          $result[] = $dagdeel;
      }
      return $result;
    }

    public static function namiddagen($weekschema){
      //Log::debug($weekschema);
      $result = array();
      foreach($weekschema->dagdelen as $dagdeel){
        //Log::debug("[".$dagdeel->dagdeel->dag->naam . "_".$dagdeel->dagdeel->deel."]");
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
        // return($request->all());
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
      $periode->load('weekschemas.dagdelen.dagdeel');
          //return compact('periode');
        //$statuses = Status::where('choosable',1)->get();
        $ambts = Ambt::pluck('naam','id');
        //Log::debug('edit route');
        //Log::debug(compact('periode'));
        $scholenlijst = School::alle()->pluck('naam','id');
        $scholen = CalculationController::totalsForCurrentUser();
        return view('periode.edit',compact(['periode','ambts','scholen','scholenlijst']));
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
      // return($request->all());
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

    public function fillPeriode(Request $request,Periode $periode)
    {
      $leerkracht = Leerkracht::with('aanstellingen')->find($this->fromRequest('leerkracht_id'));
      $aanstelling = $leerkracht->aanstelling();



      $periode->start = max($aanstelling->start,Carbon::parse($this->fromRequest('start')));
      $periode->startDagDeel = $this->fromRequest('startDagDeel');
      $periode->stop = min($aanstelling->stop,Carbon::parse($this->fromRequest('stop')));
      $periode->stopDagDeel = $this->fromRequest('stopDagDeel');
      $periode->school_id = $this->fromRequest('school_id');
      $periode->leerkracht_id = $leerkracht->id;
      $periode->aantal_uren_van_titularis = $this->fromRequest('aantal_uren_van_titularis');
      $periode->status_id = $this->fromRequest('status_id');
      $periode->opmerking = $this->fromRequest('opmerking');
      $periode->heleDag = $this->fromRequest('heleDag');
      $periode->ambt = $this->fromRequest('ambt');

      $periode->save();

      $data = request()->all();

      //check voor mismatch tussen aantal weekschemas in aanstelling en in periode
      // (kan enkel bij creatie van nieuwe periode)
      //Log::debug($periode->weekschemas->count());
      //Log::debug($aanstelling->weekschemas->count());

      if ($periode->weekschemas->count() < $aanstelling->weekschemas->count())
      foreach($aanstelling->weekschemas as $a_w){
        $this->newWeekSchema($periode,$a_w);
      }


      //TODO: we moeten kijken hoeveel weekschemas er zijn gekoppeld aan de leerkracht

      foreach($data as $key => $value){
        if (strpos($key,"Week")===0){ //we hebben een checkbox voor een TOEWIJZING
          $_w = intval(substr($key,4,1)); //Weeknummer
          $_d = strtolower(substr($key,6,2)); //Dag
          $_p = substr($key,9,2); //dagdeel (dayPart)
          $weekschema = $periode->weekschemas()->where('volgorde',$_w)->first();
          if (!isset($weekschema)){
            //$weekschema = $this->newWeekSchema($periode,$aanstelling->weekschemas[$_w-1]);
            Log::error('Weekschema '.$_w.' bestaat niet. Aanstelling heeft maar '.$aanstelling->weekschemas->count().' schemas');
            continue;
          }
          //Log::debug($weekschema);
          Log::debug("looking for deel ".$_p." bij dag ".$_d);
          $dagdeel = $weekschema->dagdelen->where('dagdeel.deel',$_p)->where('dagdeel.dag.naam',$_d)->first();
          Log::debug("setting status to BOOKED");
          $dagdeel->status = DagDeel::BOOKED;
          $dagdeel->save();

          Log::debug($dagdeel->status);

        }
      }


      //referesh the $periode object to account for all the created stuff before calculation
      $periode->load('weekschemas.dagdelen');


      $aantalDagdelen = $this->calculateAantalDagdelen($periode)['aantalDagdelen'];

      //Log::debug('aantalDagdelen=');
      //Log::debug($aantalDagdelen);
      $periode->aantalDagdelen = $aantalDagdelen;
      $periode->save();

    }


    public function newWeekSchema($periode,$aanstellingsWeekschema){
      $pws = new PeriodeWeekSchema;
      $aantal = $periode->weekschemas()->count();
      $pws->volgorde=$aantal+1;
      $periode->weekschemas()->save($pws);
      foreach($aanstellingsWeekschema->dagdelen as $dagdeel){
        $pdd = $this->addDagDeel($dagdeel,true);
        $pdd->dagdeel_id = $dagdeel->id;
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

    public function getStartWeekschemaNr(){
      $leerkracht = Leerkracht::find(request('leerkracht_id'));
      $datestart = Carbon::parse(request('datestart'));
      $volgorde = $leerkracht->aanstelling()->volgordeVoorDatum($datestart)+1;
      return compact('volgorde');
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



    public function getConflictingDays(){



      $datestart = request('datestart');
      $datestop = request('datestop');
      $leerkracht_id = request('leerkracht_id');
      $periode_id = request('periode_id');


      $conflictdagen = $this->getConflictDays($datestart,$datestop,$leerkracht_id,$periode_id);

      //return 'stront';
      Log::debug("Conflictiing days found:");
      Log::debug($conflictdagen);
      //$conflictdagen = "blabla";
      return compact('conflictdagen');
    }

    public function getConflictDays($datestart,$datestop,$leerkracht_id,$periode_id)
    {

      //$theRangeData = OverzichtController::rangeForLeerkrachten

      $dstart = Carbon::parse($datestart);
      $dstop = Carbon::parse($datestop);

      //echo 'START=' . $datestart .' => '. $dstart;
      //echo 'START=' . $datestop .' => '. $dstop;


      //$leerkracht = Leerkracht::with('periodes.weekschemas.dagdelen.schemadagdeel.dag')->find($leerkracht_id);

      $days = DB::table('periodes')
        ->join('periode_week_schemas','periode_week_schemas.periode_id','=','periodes.id')
        ->join('periode_dag_deels','periode_dag_deels.periode_week_schema_id','=','periode_week_schemas.id')
        ->join('schemadagdeel','schemadagdeel.id','=','periode_dag_deels.dagdeel_id')
        ->join('dotws','dotws.id','=','schemadagdeel.dag_id')
        ->where('periodes.id','<>',$periode_id) //exclude self
        ->where('periodes.leerkracht_id','=',$leerkracht_id) //find only for this teacher
        ->where('periodes.start','<=',$dstop)
        ->where('periodes.stop','>=',$dstart)
        ->where('periode_dag_deels.status','=',DagDeel::BOOKED)
        ->select(['periodes.id','periode_week_schemas.volgorde','schemadagdeel.deel','dotws.naam'])->get()->unique();
        return $days;
    }

    public function getOpdrachtBreuk(){
      $date = request('startdate');
      $leerkracht_id = request('leerkracht_id');
      $school_id = request('school_id');

      $teller = Leerkracht::find($leerkracht_id)->aanstelling()->lestijden_per_week;
      $noemer = School::find($school_id)->school_type->noemer;
      return compact('teller','noemer');
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


    //TODO: herschalen wanneer LPF-leerkracht in ander systeem werkt dan aantal_uren_van_titularis:
    //  bijvoorbeeld: titularis werkt in BaO in /24, LPF in /22, dan is 19/24 te herschalen naar 17,5/22 ??
    // TODO: wat met onvolledige weken? hoe worden dan woensdagen geteld?
    //  bijv
    //    iemand die vervanging doet op maandag -> woensdag? is dat 3 dagen aan 24/24 ?
    //    iemand die vervanging doet op woensdag alleen? zou 1 dag aan 12/24 zijn...
    //    iemand die vervanging doet van donderdag tot dinsdag? 4 dagen aan 24/24 ?
    function calculateAantalDagdelen($periode)
    {
      //$aantalWeken = $periode->stop->weekOfYear - $periode->start->weekOfYear +1;
      //houd rekening met volgend kalenderjaar
      // ->verschil in dagen /7, en elke begonnen week moet meegerekend worden
      $aantalWeken = ceil(($periode->start->diffInDays($periode->stop)+1)/7);
      Log::debug("aantal weken=".$aantalWeken);

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
      $a = $periode->leerkracht->aanstelling();
      $a_start = Carbon::parse($a->start);
      $a_stop = Carbon::parse($a->stop);

      $startWeekVanAanstelling = $a_start->weekOfYear;

      $p_start = Carbon::parse($periode->start);
      $p_stop = Carbon::parse($periode->stop);

      $datumIterator = clone $p_start;
      $aantalDagdelen = 0;

      for($i=1;$i<=$aantalWeken;$i++){
        //$currentWeekVolgorde = (($datumIterator->weekOfYear - $startWeekVanAanstelling) % $aantalWeekSchemas) ;
        $currentWeekVolgorde = $a->volgordeVoorDatum($datumIterator);
        //only process weeks after the start of the aanstelling
        if ($currentWeekVolgorde>=0){
        //if ($datumIterator->weekOfYear > $startWeekVanAanstelling) { //only process weeks after the start of the aanstelling
          $pws = $periode->weekschemas[$currentWeekVolgorde];
          $current_dagdelen = $pws->dagdelen()->with('dagdeel.dag')->where('status',DagDeel::BOOKED)->get();
          Log::debug($current_dagdelen);

          if ($i==1){ //eerste week speciaal behandelen
            $startDagVolgorde = DOTW::where('naam',OverzichtController::shortDayOfWeek($p_start->dayOfWeek))->pluck('volgorde')->first();
            foreach($current_dagdelen as $currentDagDeel)
            {
              if ($currentDagDeel->dagdeel->dag->volgorde >= $startDagVolgorde)
                $aantalDagdelen++;
            }
          }
          else if ($i==$aantalWeken){ //laatste week speciaal behandelen
            $stopDagVolgorde = DOTW::where('naam',OverzichtController::shortDayOfWeek($p_stop->dayOfWeek))->pluck('volgorde')->first();
            foreach($current_dagdelen as $currentDagDeel)
            {
              if ($currentDagDeel->dagdeel->dag->volgorde <= $stopDagVolgorde)
                $aantalDagdelen++;
            }

          }
          else{ //normal case
            $aantalDagdelen += $current_dagdelen->count();
          }
        }
        $datumIterator->addDays(7); //spring steeds een week verder
      }
      Log::debug("***** Aantal dagdelen =".$aantalDagdelen. " *****");
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
