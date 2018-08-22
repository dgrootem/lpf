<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use App\Leerkracht;
use App\Status;
use App\Periode;
use App\Ambt;

use Carbon\Carbon;
use Log;

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



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $leerkracht = Leerkracht::find(request('leerkracht'));
        $datum = request("datum");
        if (is_null($datum)) $datum = Carbon::today()->setTime(0,0,0);
        $periode = new Periode;
        $periode->school_id = 1;
        $periode->leerkracht_id = $leerkracht->id;
        $periode->start = $datum;
        $periode->stop = $datum;
        $periode->ambt = $leerkracht->ambt;

        $school = School::find(1);

        $periode->aantal_uren_van_titularis = $school->school_type->noemer;
        $ambts = Ambt::pluck('naam','id');

        $statuses = Status::where('choosable',1)->get();

        $scholen = CalculationController::totalsForCurrentUser();
        return view('periode.create',compact('periode','statuses','ambts','scholen'));
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
        $this->fillPeriode($request,$periode);

        $this->checkAndFix($periode);
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
        $scholen = CalculationController::totalsForCurrentUser();
        return view('periode.edit',compact(['periode','statuses','ambts','scholen']));
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
        $this->checkAndFix($periode);
        $periode->save();
        return redirect('/overzichten');
    }

    function fromRequest($name){
      $value = request($name);
      //Log::debug(compact('name','value'));
      return $value;
    }

    private function fillPeriode(Request $request,Periode $periode){

      $periode->start = Carbon::parse($this->fromRequest('start'));
      $periode->stop = Carbon::parse($this->fromRequest('stop'));
      $periode->school_id = $this->fromRequest('school_id');
      $periode->leerkracht_id = $this->fromRequest('leerkracht_id');
      $periode->aantal_uren_van_titularis = $this->fromRequest('aantal_uren_van_titularis');
      $periode->status_id = $this->fromRequest('status_id');
      $periode->opmerking = $this->fromRequest('opmerking');
      $periode->heleDag = $this->fromRequest('heleDag');
      $periode->ambt = $this->fromRequest('ambt');
      $uren = $this->calculateUren()['uren'];

      Log::debug('Uren=');
      Log::debug($uren);
      $periode->berekendeUren = $uren;
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
      if (!((is_null($periode)) || ($periode->status_id == Status::opengesteld())))
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

    //TODO: herschalen wanneer LPF-leerkracht in ander systeem werkt dan aantal_uren_van_titularis:
    //  bijvoorbeeld: titularis werkt in BaO in /24, LPF in /22, dan is 19/24 te herschalen naar 17,5/22 ??
    // TODO: wat met onvolledige weken? hoe worden dan woensdagen geteld?
    //  bijv
    //    iemand die vervanging doet op maandag -> woensdag? is dat 3 dagen aan 24/24 ?
    //    iemand die vervanging doet op woensdag alleen? zou 1 dag aan 12/24 zijn...
    //    iemand die vervanging doet van donderdag tot dinsdag? 4 dagen aan 24/24 ?
    function calculateUren()
    {
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
      if ($status == Status::opengesteld()){

        $result = "";
        $uren = 0;
      }
      else{


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
      }
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
