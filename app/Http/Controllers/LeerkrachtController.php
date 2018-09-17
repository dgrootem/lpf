<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Leerkracht;
use App\School;

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
