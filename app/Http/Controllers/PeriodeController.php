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
        $periode->start = request('start');
        $periode->stop = request('stop');
        $periode->aantal_uren_van_titularis = request('aantal_uren_van_titularis');
        $periode->status_id = request('status_id');
        $periode->opmerking = request('opmerking');
        $periode->save();
        return redirect('/');
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
      $date = request('date');
      $leerkracht_id = request('leerkracht_id');
      $periode_id = request('periode_id');


      $periode = Periode::where('leerkracht_id',$leerkracht_id)->where('start','<=',$date)->where('stop','>=',$date)->first();
      Log::debug(compact('periode_id'));
      if (is_null($periode))
        $result =  true;
      else
        if ($periode->id == $periode_id) $result=true;
        else $result = false;

      return compact('result');
    }
}
