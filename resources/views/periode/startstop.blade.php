<!-- <div class="form-row"> -->
  {{-- <div class="form-group col-md-6"> --}}
    <div class="card">
      <h5 class="card-header">Data</h5>
    <!-- <div class="col"> -->
      <div class="card-body">
        <p class="card-text">
          <div class="form-row">
            <div class="col">
              {{Form::label('start','Start')}}
            </div>
            <div class="col">
              {{Form::date('start',$periode->start,
                array('min' => '2018-07-01','max'=>'2019-06-30',
                    'class' => 'form-control')) }}
            </div>
            <div class="col">
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-light @if (strcmp($periode->startDagDeel,"VM")==0 ) active @endif">
                  <input type="radio" required name="startDagDeel" id="startDagDeel-VM" value="VM" autocomplete="off"
                    @if (strcmp($periode->startDagDeel,"VM")==0 )
                      checked
                    @endif
                    >
                  <span class="glyphicon glyphicon-ok fa fa-check"></span>VM
                </label>
                <label class="btn btn-light @if (strcmp($periode->startDagDeel,"NM")==0 ) active @endif">
                  <input type="radio" required name="startDagDeel" id="startDagDeel-NM" value="NM" autocomplete="off" @if (strcmp($periode->startDagDeel,"NM")==0 ) checked @endif>
                  <span class="glyphicon glyphicon-ok fa fa-check"></span>NM
                </label>
              </div>
            </div>
          </div>
        </p>
  <!-- </div> -->
  <!-- <div class="form-group col-md-6"> -->
        <p class="card-text">
          <div class="form-row">
            <div class="col">

              {{Form::label('stop','Stop')}}
            </div>
            <div class="col">
          {{Form::date('stop',$periode->stop,
            array('min' => '2018-07-01','max'=>'2019-06-30',
                  'class' => 'form-control')) }}
            </div>
            <div class="col">
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-light @if (strcmp($periode->stopDagDeel,"VM")==0 ) active @endif">
                  <input type="radio" required name="stopDagDeel" id="stopDagDeel-VM" value="VM" autocomplete="off" @if (strcmp($periode->stopDagDeel,"VM")==0 ) checked @endif>
                  <span class="glyphicon glyphicon-ok fa fa-check"></span>VM
                </label>
                <label class="btn btn-light @if (strcmp($periode->stopDagDeel,"NM")==0 ) active @endif">
                  <input type="radio" required name="stopDagDeel" id="stopDagDeel-NM" value="NM" autocomplete="off" @if (strcmp($periode->stopDagDeel,"NM")==0 ) checked @endif>
                  <span class="glyphicon glyphicon-ok fa fa-check"></span>NM
                </label>
              </div>
            </div>
          </div>
        </p>
        <small id="dateRangeError" class="error" aria-live="polite"></small>
        <div id="berekenUren" class="error" aria-live="polite"></div>
        <div class="bg-light">Deze periode begint in weekschema <span id="weekschemaVoorStart">{{$periode->leerkracht->aanstelling()->volgordeVoorDatum($periode->start)+1}}</span></div>
      </div>
    </div>
  {{-- </div> --}}
<!-- </div> -->
