{{ csrf_field() }}
<input type="hidden" name="school_id" value="{{$school->id}}">
<input type="hidden" name="leerkracht_id" value="{{$leerkracht->id}}">
<input type="hidden" name="heleDag" value="1">

<div class="form-row alert-primary ">
  <div class="col strong"><label >Leerkracht</label></div>
  <div class="col"><label >{{ $leerkracht->naam }}</label></div>
</div>
<div class="form-row alert-secondary">
  <div class="col strong"><label>School</label></div>
  <div class="col"><label>{{$school->naam}}</label>  </div>
</div>
