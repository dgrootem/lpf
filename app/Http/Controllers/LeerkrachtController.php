<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Leerkracht;
use App\School;
use App\DOTW;
use App\Aanstelling;
use App\Weekschema;
use App\SchemaDagDeel;

use Carbon\Carbon;

use Log;
use DB;

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
      $dagen = DOTW::all();
      if (!isset($leerkracht->aanstellingen) || $leerkracht->aanstellingen->isEmpty())
      {
        $aanstelling = new Aanstelling;
        $aanstelling->start = Carbon::parse(Carbon::today()->year . '-10-01');
        $leerkracht->aanstellingen()->save($aanstelling);
        $weekschema = $this->newWeekSchema($aanstelling);
        $weekschema->load('dagdelen');
        //return $weekschema;
        //$aanstelling->weekschemas()->save($weekschema);




        //reload relationships
        $leerkracht = $leerkracht->load('aanstellingen.weekschemas.dagdelen');

        //return compact('leerkracht','aanstelling');
      }
      //$leerkracht = $leerkracht->fresh()->with('aanstellingen.weekschemas.dagdelen');
      //return compact('leerkracht','aanstelling');
      //return $leerkracht->aanstellingen->first()->weekschemas->first()->voormiddagenFull()->get();

      return view('leerkracht.edit',compact(['leerkracht','beschikbarescholen','dagen']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Leerkracht $leerkracht)
    {
        //if we get here, there is always at least 1 aanstelling + weekschema
        $leerkracht->load('aanstellingen.weekschemas.dagdelen');
        $data = request()->all();

      //  return $data;
        //we gaan voorlopig uit van 1 aanstelling per leerkracht
        $a = $leerkracht->aanstellingen()->first();
        foreach($data as $key => $value)
        {
          //Log::debug($key);
          //Log::debug(strpos($key,'Week')===0);
          if (strpos($key,'Week')===0) // key start met week, verwerken en opslaan
          {
            $_w = intval(substr($key,4,1)); //Weeknummer
            $_d = strtolower(substr($key,6,2)); //Dag
            $_p = substr($key,9,2); //dagdeel (dayPart)

            $weekschema = $a->weekschemas->where('volgorde',$_w)->first();
            //Log::debug($weekschema);
            if (!isset($weekschema)){
              $weekschema = $this->newWeekSchema($a);
              //Log::debug($weekschema);
              $a->weekschemas()->save($weekschema);
              $a->load('weekschemas.dagdelen');
            }
            foreach($weekschema->dagdelen as $td)
              if(($td->dag->naam === $_d) && ($td->deel === $_p))
              {
                Log::debug("Found dagdeel ".$_d ."_".$_p );
                $td->school_id = $value;
                $td->save();
              }

          }
        }
        $leerkracht->load('aanstellingen.weekschemas.dagdelen');
        return redirect(url('/overzichten'));
    }

//creer een weekschema waarbij alle dagdelen gaan naar 'GEEN TOEWIJZING'
    private function newWeekSchema($aanstelling){
      DB::listen(function ($query) {
        //Log::debug($query->sql);
      });

      $weekschema = new Weekschema;

      $aantal = $aanstelling->weekschemas()->count();
      $weekschema->volgorde=$aantal+1;
      $aanstelling->weekschemas()->save($weekschema);

      $dagen = DOTW::orderBy('volgorde')->get();
      foreach(['VM','NM'] as $deel){
        Log::debug("Creating ".$deel);
        foreach ($dagen as $key => $dag) {
          if (($dag->naam === 'wo') && ($deel === 'NM')) continue;
          Log::debug("Create ".$dag.'_'.$deel);
          $d = new SchemaDagDeel;
          $d->school_id = 1;
          $d->dag_id = $dag->id;
          $d->deel = $deel;
          $weekschema->dagdelen()->save($d);
        }
      }
      DB::listen(function ($query) {

      });
      return $weekschema;

    }

    public static function voormiddagen($weekschema){
      $result = array();
      foreach($weekschema->voormiddagenFull()->get() as $dag)
        $result[$dag->dag->naam] = $dag;
      return $result;
    }

    public static function namiddagen($weekschema){
      $result = array();
      foreach($weekschema->namiddagenFull()->get() as $dag)
        $result[$dag->dag->naam] = $dag;
      return $result;
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
