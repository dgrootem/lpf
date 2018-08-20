<!-- <div class="form-row"> -->
  <div class="form-group col-md-6">
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
          </div>
        </p>
        <small id="dateRangeError" class="error" aria-live="polite"></small>
      </div>
    </div>
  </div>
<!-- </div> -->
