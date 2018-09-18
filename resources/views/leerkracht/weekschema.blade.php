<div class="card" id="weekschema{{$weekschema->volgorde}}">
  <div class="card-header" id="heading1">
    <h5 class="mb-0">
      <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1" id="toggleButton{{$weekschema->volgorde}}">
        Weekschema week {{$weekschema->volgorde}}
      </button>
    </h5>
  </div>
  <div id="collapse1" class="collapse show" aria-labelledby="heading1" data-parent="#accordion">
    <div class="card-body">
      <table class="table table-small">
        <tbody>
          @foreach ($beschikbarescholen as $key => $school)
          <tr>
            <div class="container">
              <div class="row border align-items-center">
                <div class="col-3 ">
                  <div class="">
                    @if($school->school_type_id<3)
                    <img src="http://www.skbl.be/joomla/images/logo/logo-scholen/{{$school->logo_filename}}" width="40px" height="40px">
                    @endif
                  </div>
                  <div class="badge">
                    @if($school->school_type_id<3)
                    [{{$school->afkorting}}]
                    @endif
                  </div>
                  <div class="">
                    {{$school->naam}}
                  </div>
                  <div class="">
                    {{$school->adres}}
                  </div>
                </div>
                <div class="col-9">
                  <div class="container">
                    <div class="row ">
                      <div class="col-2">
                        @if($school->school_type_id==3)
                        <div class="p-2 bd-highlight flex-fill invisible">BB</div>
                        @endif
                        <div class="p-2 bd-highlight flex-fill">VM</div>
                        <div class="p-2 bd-highlight flex-fill">NM</div>
                      </div>
                      <div class="col">
                        @if($school->school_type_id==3)
                        <div class="d-flex flex-row bd-highlight mb-0 ">
                          @foreach($dagen as $dag)
                          <div class="p-2 bd-highlight flex-fill">{{$dag->naam}}</div>
                          @endforeach
                        </div>
                        @endif
                        <div class="dlk-radio btn-group d-flex flex-row bd-highlight mb-0">
                          @foreach($dagen as $dag)
                          <div class="p-2 bd-highlight flex-fill">
                            <label class="btn mycontainer">
                              <input type="radio" value="{{$school->id}}" name="Week{{$weekschema->volgorde}}_{{strtoupper($dag->naam)}}_VM"
                              @if(LeerkrachtController::voormiddagen($weekschema)[$dag->naam]->school_id==$school->id) checked @endif class="form-control voormiddag dagdeel" >
                              <span class="mycheckmark"></span>
                            </label>
                          </div>
                          @endforeach
                        </div>

                        <div class="dlk-radio btn-group d-flex flex-row bd-highlight mb-0">
                          @foreach($dagen as $dag)

                          <div class="p-2 bd-highlight flex-fill @if($dag->naam === 'wo') invisible @endif">
                            <label class="btn mycontainer">
                              <input type="radio" value="{{$school->id}}" name="Week{{$weekschema->volgorde}}_{{strtoupper($dag->naam)}}_NM"
                              @if(!($dag->naam === 'wo'))
                                @if(LeerkrachtController::namiddagen($weekschema)[$dag->naam]->school_id==$school->id) checked @endif
                              @endif
                              class="form-control namiddag dagdeel" >
                              <span class="mycheckmark"></span>
                            </label>
                          </div>
                          @endforeach
                        </div> <!-- dlk-radio  -->
                      </div> <!-- col   -->
                    </div><!-- row   -->
                  </div><!-- container  -->
                </div><!-- col-9 -->
              </div><!-- row   -->
            </div><!-- container  -->
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
