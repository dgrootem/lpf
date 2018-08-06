<div class="form-row">
  <div class="form-group col-md-6">

    {{Form::label('start','Start')}}
    {{Form::date('start',NULL,/*Request::old('start')*///$periode->start,
      array('min' => '2018-07-01','max'=>'2019-06-30',
            // 'data-date-format' => 'dddd DD MM YYYY',
            'class' => 'form-control')) }}
            {{-- {{Form::text('start',Request::old('start'))}} --}}







    {{-- <label for"start">Start</label>
    <input type="date" name="start" max="2019-06-30"
       min="2018-08-01" value="{{ $periode->start }}"
       id="start"
       data-date-format="dddd DD MM YYYY"
       class="form-control" required> --}}
  </div>
  <div class="form-group col-md-6">
    {{-- <label for"stop">Einde</label> --}}
    {{Form::label('stop','Stop')}}
    {{Form::date('stop',NULL,/*Request::old('start')*///$periode->start,
      array('min' => '2018-07-01','max'=>'2019-06-30',
            // 'data-date-format' => 'dddd DD MM YYYY',
            'class' => 'form-control')) }}
            {{-- {{Form::text('start',Request::old('start'))}} --}}
    {{-- <input type="date" name="stop" min="2018-08-01"
        id="einde"
        max="2019-06-30" value="{{ $periode->stop }}"
        data-date-format="dddd DD MM YYYY"
        class="form-control" required> --}}
  </div>
</div>
<small id="dateRangeError" class="error" aria-live="polite"></small>
