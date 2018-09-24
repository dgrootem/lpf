<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Status;
use App\Periode;
use Log;

class CalculationController extends Controller
{
    //
    public const AANTAL_WEKEN=35;

    public static function totalsForCurrentUser(){
      $scholenraw = Auth::user()->schools;
      $scholen = array();
      foreach ($scholenraw as $key => $school) {
        $scholen[$school->id]['naam'] = $school->naam;
        Log::debug('school='.$school->naam);
        //Log::debug('lestijden_per_week='.$school->lestijden_per_week);

        $leerkrachtIds = $school->leerkrachts()->pluck('id')->toArray();
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



          $dagdelen = Periode::whereIn('leerkracht_id',$leerkrachtIds)
                    ->where('status_id',2)
                    ->where('deleted',0)
                    ->sum('aantalDagdelen');
          Log::debug('uren='.$dagdelen);
          $scholen[$school->id]['RV'] = $dagdelen;

        $scholen[$school->id]['unused'] =
            9 * CalculationController::AANTAL_WEKEN -
            ($scholen[$school->id]['RV']);// + $scholen[$school->id]['ZT']);
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
