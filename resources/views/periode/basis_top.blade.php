

{{ Form::hidden('school_id', Request::old('periode->school_id')) }}
{{ Form::hidden('leerkracht_id', Request::old('periode->leerkracht_id')) }}


{{-- <input type="hidden" name="school_id" value="{{$periode->school->id}}"> --}}
{{-- <input type="hidden" name="leerkracht_id" value="{{$periode->leerkracht->id}}"> --}}
<input type="hidden" name="heleDag" value="1">

<div class="form-row alert-primary ">
  <div class="col strong">
    {{ Form::label('', 'Leerkracht') }}
  </div>
  <div class="col">
    {{Form::label('leerkracht',$periode->leerkracht->naam) }}
    {{-- {{ Form::text('leerkracht[naam]', Request::old('naam')) }} --}}
  </div>
  {{-- <div class="col"><label >
    {{ $periode->leerkracht->naam }}
  </label></div> --}}
</div>

<div class="form-row alert-secondary">
  <div class="col strong">
    {{ Form::label('', 'School') }}
  </div>
  <div class="col">
    {{ Form::label('school', $periode->school->naam) }}
    {{-- {{ Form::text('school[naam]', Request::old('naam')) }} --}}
  </div>
  {{-- <div class="col strong"><label>School</label></div>
  <div class="col"><label>{{$periode->school->naam}}</label>  </div> --}}
</div>
