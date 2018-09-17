<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Leerkracht;
use App\School;
use App\DOTW;
use App\Aanstelling;
use App\Weekschema;

use Carbon\Carbon;

use Log;

class LeerkrachtController extends Controller
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
        //
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
        return view('leerkracht.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Leerkracht $leerkracht)
    {
      $beschikbarescholen = School::all();
      $leerkracht->load('aanstellingen.weekschemas.dagdelen'); //eager load all nested relationships
      //als de leerkracht nog geen aanstelling (en dus ook geen weekschemas) heeft
      //  creeren we een blanco versie
      if (!isset($leerkracht->aanstellingen) || $leerkracht->aanstellingen->isEmpty())
      {
        $aanstelling = new Aanstelling;
        $aanstelling->start = Carbon::parse(Carbon::today()->year . '-10-01');
        $leerkracht->aanstellingen()->save($aanstelling);
        $weekschema = new Weekschema;
        $aanstelling->weekschemas()->save($weekschema);

        //reload relationships
        $leerkracht = $leerkracht->load('aanstellingen.weekschemas.dagdelen');
        //return compact('leerkracht','aanstelling');
      }
      //$leerkracht = $leerkracht->fresh()->with('aanstellingen.weekschemas.dagdelen');
      //return compact('leerkracht','aanstelling');
      $dagen = DOTW::all();
      return view('leerkracht.edit',compact(['leerkracht','beschikbarescholen','dagen']));
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
        $leerkracht = Leerkracht::find($id);
        //$previous = request('myprevious');
        $data = request()->all();
        Log::debug($data);
        $leerkracht->update($data);
        return redirect(url('/overzichten'));
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
