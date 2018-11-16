<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Status;
use App\Periode;
use App\Aanstelling;
use App\School;
use App\Leerkracht;
use App\DagDeel;
use Log;

class CalculationController extends Controller
{
    //
    public const AANTAL_WEKEN=35;

    public static function maxForAanstellingInSchool(Aanstelling $aanstelling,School $school){
      $aantal = array();
      $totaal = 0;
      $aanstelling->load('weekschemas.dagdelen.dag');
      $aantalWeekSchemas = $aanstelling->weekschemas->count();
      $aantalWeken = (int)(floor(CalculationController::AANTAL_WEKEN / $aantalWeekSchemas));
      Log::debug('Aantal schemas='.$aantalWeekSchemas);
      Log::debug('Aantal weken (std)='.$aantalWeken);
      foreach($aanstelling->weekschemas as $weekschema){
        Log::debug('processing schema '.$weekschema->volgorde);
        $aantalDagdelenInSchool = $weekschema->dagdelen()->where('school_id',$school->id)->count();

        $aantal[$weekschema->volgorde] =  $aantalWeken * $aantalDagdelenInSchool;
        Log::debug('Calculating '.$aantalWeken.' * '.$aantalDagdelenInSchool.' = '.$aantal[$weekschema->volgorde]);
        //als het aantal weken niet deelbaar is door aantal schemas, de resterende $aantalWeekSchemas
        // overlopen tot het einde van de aanstelling (dus alle schemas met volgordenr <= rest )
        if (CalculationController::AANTAL_WEKEN % $aantalWeekSchemas > 0){
          Log::debug('AANTAL WEKEN is niet deelbaar door aantalSchemas');
          if ($weekschema->volgorde <= ((CalculationController::AANTAL_WEKEN % $aantalWeekSchemas))){
            Log::debug('Dagdelen voor schema '.$weekschema->volgorde.' voegen we nogmaals toe');
            $aantal[$weekschema->volgorde]+=$aantalDagdelenInSchool;
          }
        }
        Log::debug('schema['.$weekschema->volgorde.'] heeft '.$aantalDagdelenInSchool.' dagdelen in school met id '.$school->id);
        Log::debug('totaal voor aanstelling in dit schema='.$aantal[$weekschema->volgorde]);
        $totaal += $aantal[$weekschema->volgorde];

      }
      Log::debug('Algemeen totaal voor deze aanstelling ='.$totaal);
      return $totaal;
    }

    public static function totalForLeerkrachtInSchool(Leerkracht $leerkracht,School $school){
      $totaal = Periode::where('leerkracht_id',$leerkracht->id)
                ->where('status_id',2)
                ->where('originating_school_id',$school->id)
                ->where('deleted',0)->sum('aantalDagdelen');
      return $totaal;
    }


    public static function totalsForCurrentUser(){
      $scholenraw = Auth::user()->schools;
      $scholen = array();
      foreach ($scholenraw as $key => $school) {
        $scholen[$school->id]['naam'] = $school->naam;
        $scholen[$school->id]['afkorting'] = $school->afkorting;
        Log::debug('school='.$school->naam);
        //Log::debug('lestijden_per_week='.$school->lestijden_per_week);

        //$leerkrachtIds = $school->leerkrachts()->pluck('id')->toArray();
        //todo: rekening houden met in welke school een leerkracht stond
        // voor inplanning
        //todo: voor elke leerkracht vermenigvuldigen met aantal uren/school voltijdse uren
        // en dan pas vermenigvuldigen met aantal weken (per leerkracht) om aan max inzetbaarheid te komen per jaar
        // daarna alles optellen voor totaal per user

        //BETER: tel het aantal dagdelen in de standaard inplanning op school X voor een leerkracht
        // foreach user->school
        //  foreach school->leerkrachten:
        //    foreach leerkracht->aanstelling->weekschemas : $aantal[volgorde] = count(dagdelen d where d.school_id == school->id)
        // if (AANTAL_WEKEN % aantal weekschemas == 0) som($aantal) * AANTAL_WEKEN / aantal weekschemas;
        // else
        //  som($aantal) * floor(AANTAL_WEKEN / aantal_weekschemas)
        //  for(i=0;i< (AANTAL_WEKEN % aantal_weekschemas); i++) som+=$aantal[i]
        //deze gegevens kunnen in een aparte tabel op voorhand eenmalig berekend worden


        /*
          $dagdelen = Periode::whereIn('leerkracht_id',$leerkrachtIds)
                    ->where('status_id',2)
                    ->where('deleted',0)
                    ->sum('aantalDagdelen');
          Log::debug('uren='.$dagdelen);
          $scholen[$school->id]['RV'] = $dagdelen;
          */
          $max = 0;
          $used = 0;
          Log::debug("Processing ".$school->naam);
          foreach($school->leerkrachts()->get() as $leerkracht){
            Log::debug("Processing ".$leerkracht->naam);
            $max+=CalculationController::maxForAanstellingInSchool($leerkracht->aanstellingen()->first(),$school);
            $used+=CalculationController::totalForLeerkrachtInSchool($leerkracht,$school);
          }
          $scholen[$school->id]['RV'] = $used;
          Log::debug('used = '.$used .'/'.$max.' ('.$used/$max.'%)');
          $scholen[$school->id]['unused'] = $max - $used;
      }
      //Log::debug(CalculationController::AANTAL_WEKEN);
      //Log::debug($scholen);

      return $scholen;
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
