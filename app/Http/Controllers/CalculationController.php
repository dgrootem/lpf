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
        Log::debug('lestijden_per_week='.$school->lestijden_per_week);

        $leerkrachtIds = $school->leerkrachts->pluck('id')->toArray();

        foreach (Status::all() as $key => $status) {
          $uren = Periode::whereIn('leerkracht_id',$leerkrachtIds)
                    ->where('status_id',$status->id)
                    ->where('deleted',0)
                    ->sum('berekendeUren');
          Log::debug('uren='.$uren);
          $scholen[$school->id][$status->omschrijving] = $uren;
        }
        $scholen[$school->id]['unused'] =
            $school->lestijden_per_week * CalculationController::AANTAL_WEKEN -
            ($scholen[$school->id]['RV'] + $scholen[$school->id]['ZT']);
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
