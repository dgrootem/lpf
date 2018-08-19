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
        if (is_null($datum)) $datum = Carbon::today();
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
        return view('periode.create',compact('periode','statuses','ambts'));
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
        $statuses = Status::all();
        return view('periode.edit',compact(['periode','statuses']));
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

    private function fillPeriode(Request $request,Periode $periode){
      $periode->start = Carbon::parse(request('start'));
      $periode->stop = Carbon::parse(request('stop'));
      $periode->school_id = request('school_id');
      $periode->leerkracht_id = request('leerkracht_id');
      $periode->aantal_uren_van_titularis = request('aantal_uren_van_titularis');
      $periode->status_id = request('status_id');
      $periode->opmerking = request('opmerking');
      $periode->heleDag = request('heleDag');
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
          // Log::debug("Created new periode: ");
          // Log::debug(compact('nieuwePeriode'));
        }

        //overlap met bestaande opengestelde periode -> aanpassen door deze vroeger te laten stoppen
        $newstop = clone $periodeToCheckFor->start;
        $newstop->addDays(-1);
        $periode->stop =  $newstop;
        // Log::debug("Modified stop of periode to " . $periode->stop);
        // Log::debug(compact('periode'));
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
        Log::debug("Modified start of periode to " . $periode->start);
        Log::debug(compact('periode'));
        $periode->save();
      }

      //check of de periode waarvoor we checks doen andere periodes bevat
      //deze moeten dan verwijderd worden
      $periodes = Periode::openPeriodesInRangeForLeerkracht($periodeToCheckFor->start,
                                                            $periodeToCheckFor->stop,
                                                            $periodeToCheckFor->leerkracht_id,
                                                            $periodeToCheckFor->id
                                                            )->get();
      Log::debug(compact('periodes'));

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
/*
    function deletedPeriodesInRange($start,$stop,$leerkracht_id,$mezelf){
      return Periode::periodesInRangeForLeekracht($start,$stop,$leerkracht_id,$mezelf,1)
                    ->get();
    }

    function openPeriodesInRange($start,$stop,$leerkracht_id,$mezelf){
      $periodes = Periode::periodesInRangeForLeekracht($start,$stop,$leerkracht_id,$mezelf)
                    ->where('status_id',Status::opengesteld()) //
                    ->get();
      return $periodes;
    }

    function ingenomenPeriodesInRange($start,$stop,$leerkracht_id,$mezelf){
      $periodes = Periode::periodesInRangeForLeekracht($start,$stop,$leerkracht_id,$mezelf)
                    ->where('status_id','<>',Status::opengesteld()) //
                    ->get();
      return $periodes;
    }
*/
    function periodeDieDatumBevat($date,$leerkracht_id,$mezelf){
      $periode = Periode::where('id','<>',$mezelf)  //exclude matching with myself when editing a period
                    ->where('leerkracht_id',$leerkracht_id)
                    ->where('start','<=',$date)
                    ->where('stop','>=',$date)->first();
      return $periode;
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
