<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use App\Leerkracht;
use App\Status;
use App\Periode;

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
        $periode = new Periode;
        $periode->school_id = 1;
        $periode->leerkracht_id = $leerkracht->id;
        $periode->start = Carbon::today();
        $periode->stop = Carbon::today();


        $school = School::find(1);

        $periode->aantal_uren_van_titularis = $school->school_type->noemer;

        $statuses = Status::all();
        return view('periode.create',compact('periode','statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        //dd(request()->all());
/*
        $periode = new App\Periode;
        $periode -> start = request('start');
        $periode -> start = request('stop');
        $periode -> start = request('school_id');
        $periode -> start = request('leerkracht_id');
*/
        $periode = new Periode;
        $this->fillPeriode($request,$periode);
        /*
        Periode::create(request(
          ['start',
           'stop',
           'school_id',
           'leerkracht_id',
           'aantal_uren_van_titularis',
           'status_id',
           'opmerking',
           'heleDag'
         ]));
        */

        $this->checkAndFix($periode);
        $periode->save();

        return redirect('/');
    }


    private $statusOpengesteld = null;

    function checkAndFix($periodeToCheckFor){
      //check eerst of de start in een bestaande opengestelde periode valt
      if (is_null($this->statusOpengesteld))
      $this->statusOpengesteld = Status::where('omschrijving','opengesteld')->first()->id;

      $periode = $this->periodeDieDatumBevat($periodeToCheckFor->start,$periodeToCheckFor->leerkracht_id,$periodeToCheckFor->id);
      if (!is_null($periode)){
        if ($periode->status_id != $this->statusOpengesteld) throw new \Exception("Periode overlapt met bestaande niet-opengestelde periode", 1);
        //overlap met bestaande opengestelde periode -> aanpassen door deze vroeger te laten stoppen
        $periode->stop = clone $periodeToCheckFor->start;
        $periode->stop->addDays(-1);
        $periode->save();
      }

      //doe nu hetzelfde voor de stopdatum
      $periode = $this->periodeDieDatumBevat($periodeToCheckFor->stop,$periodeToCheckFor->leerkracht_id,$periodeToCheckFor->id);
      if (!is_null($periode)){
        if ($periode->status_id != $this->statusOpengesteld) throw new \Exception("Periode overlapt met bestaande niet-opengestelde periode", 1);
        //overlap met bestaande opengestelde periode -> aanpassen door deze later te laten starten
        $periode->start = clone $periodeToCheckFor->stop;
        $periode->start->addDays(1);
        $periode->save();
      }

    }

    function periodeDieDatumBevat($date,$leerkracht_id,$mezelf){

      //Log::debug(compact('periode_id','leerkracht_id','date','statusOpengesteld'));

      $periode = Periode::where('id','<>',$mezelf)  //exclude matching with myself when editing a period
                    ->where('leerkracht_id',$leerkracht_id)
                    //->where('status_id','<>',$statusOpengesteld) //
                    ->where('start','<=',$date)
                    ->where('stop','>=',$date)->first();

      return $periode;
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
        return redirect('/');
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
    }

    public function checkForConflict()
    {
      if (is_null($this->statusOpengesteld))
      $this->statusOpengesteld = Status::where('omschrijving','opengesteld')->first()->id;

      $date = request('date');
      $leerkracht_id = request('leerkracht_id');
      $periode_id = request('periode_id');
      $periode = $this->periodeDieDatumBevat($date,$leerkracht_id,$periode_id);
      Log::debug(compact('periode'));
      if ((is_null($periode)) || ($periode->status_id == $this->statusOpengesteld))
        $result =  true;
      //else
      //  if ($periode->id == $periode_id) $result=true;
      else $result = false;

      return compact('result');
    }
}
