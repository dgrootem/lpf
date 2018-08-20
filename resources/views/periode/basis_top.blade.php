

{{ Form::hidden('school_id', Request::old('periode->school_id')) }}
{{ Form::hidden('leerkracht_id', Request::old('periode->leerkracht_id')) }}
<input type="hidden" name="heleDag" value="1">
<div class="form-row">
  <div class="form-group col">
    <div class="card">
      <h5 class="card-header">Opdracht</h5>
      <div class="card-body">
        <p class="card-text">
          <div class="form-row alert-primary ">
            <div class="col strong">
              {{ Form::label('', 'Leerkracht') }}
            </div>
            <div class="col">
              {{Form::label('leerkracht',$periode->leerkracht->naam) }}
            </div>
          </div>

          <div class="form-row alert-secondary">
            <div class="col strong">
              {{ Form::label('', 'School') }}
            </div>
            <div class="col">
              {{ Form::label('school', $periode->school->naam) }}
            </div>
          </div>
        </div>
      </p>
    </div>
  </div>
</div>
