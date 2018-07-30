<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
use App\Leerkracht;
use App\Status;
use App\Periode;

use Carbon\Carbon;

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
      $emptyStatus = new Status;
      $emptyStatus->omschrijving = '';
      $emptyStatus->visualisatie='';

      $leerkrachten = Leerkracht::where('actief',1)->get();

      $nbdays=21;

      $today = Carbon::today();
      $endRange = Carbon::today()->addDays($nbdays-1);
      $range = array();
      for ($i=0; $i < $nbdays; $i++) {
        $tempArray = array();
        foreach ($leerkrachten as $key => $value) {
          $tempArray[$value->id] = $emptyStatus;
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

        $days = $start->diffInDays($stop);
        for ($i=0; $i < $days; $i++) {
          $copy= clone $start;
          $copy->addDays($i);
          if (!$copy->isWeekend())
          $range[$copy->formatLocalized($format)][$periode->leerkracht->id] = $periode->status;
        }


      }
      //return compact(['range','periodesInRange','leerkrachten']);

      return view('overzicht',compact(['range','periodesInRange','leerkrachten']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Leerkracht $leerkracht)
    {
        //
        //$leerkracht = Leerkracht::find(1);


        $school = School::find(1);
        $statuses = Status::all();
        return view('periode.create',compact('leerkracht','school','statuses'));
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
         return redirect('/');
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
    public function edit($id)
    {
        //
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
        //
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
}
